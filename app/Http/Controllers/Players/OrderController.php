<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Player;
use App\Models\Library;
use App\Models\GameKey;
use App\Services\SupplierManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.player'),
            new Middleware('check.banned'),
        ];
    }
    
    private SupplierManagerService $supplierManager;

    public function __construct(SupplierManagerService $supplierManager)
    {
        $this->supplierManager = $supplierManager;
    }

    private function getCartId() {
        return Cart::firstOrCreate(['player_id' => Auth::guard('player')->id()])->id;
    }

    public function checkout() {
        $cartItems = CartItem::with('version.game')->where('cart_id', $this->getCartId())->get();
        $friends = Player::where('id', '!=', Auth::guard('player')->id())->get(); 
        return view('Players.orders.checkout', compact('cartItems', 'friends'));
    }

    // Bước 1: Khởi tạo đơn hàng nháp (Pending), kiểm tra sở hữu, xóa giỏ hàng
    public function process(Request $request) {
        $cartId = $this->getCartId();
        $cartItems = CartItem::with('version.game')->where('cart_id', $cartId)->get();
        
        if($cartItems->isEmpty()) return redirect()->route('home');

        $total = $cartItems->sum(fn($item) => ($item->version->discount_price ?? $item->version->price) * $item->quantity);
        $playerId = Auth::guard('player')->id();

        $order = DB::transaction(function () use ($request, $cartItems, $total, $cartId, $playerId) {
            $newOrder = Order::create([
                'player_id' => $playerId,
                'total_amount' => $total,
                'order_type' => $request->order_type,
                'status' => 'Pending',
                'payment_method' => $request->payment_method ?? 'VNPAY'
            ]);

            $hasAnyPersonalGame = false;
            
            foreach ($cartItems as $item) {
                $gameId = $item->version->game_id;

                $alreadyOwned = Library::where('player_id', $playerId)
                    ->whereHas('gameKey.orderItem.version', function($query) use ($gameId) {
                        $query->where('game_id', $gameId);
                    })->exists();

                $isPersonal = ($request->order_type === 'Personal' && !$alreadyOwned);
                
                // Nếu đặt là Personal nhưng game đã sở hữu → chuyển order sang Other
                if ($request->order_type === 'Personal' && $alreadyOwned) {
                    $newOrder->update(['order_type' => 'Other']);
                } else if ($isPersonal) {
                    $hasAnyPersonalGame = true;
                }

                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'game_version_id' => $item->game_version_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->version->discount_price ?? $item->version->price
                ]);
            }
            
            CartItem::where('cart_id', $cartId)->delete();
            return $newOrder;
        });

        return redirect()->route('orders.waiting', ['order_id' => $order->id]);
    }

    public function waiting($order_id) {
        $order = Order::with(['orderItems.version.game'])
                      ->where('id', $order_id)
                      ->where('player_id', Auth::guard('player')->id())
                      ->firstOrFail();
        return view('Players.orders.waiting', compact('order'));
    }

    // Bước 2: Chạy ngầm xử lý gọi nhà cung cấp lấy key, kích hoạt bằng AJAX sau khi đếm ngược 30s
    public function executeOrder(Request $request, $order_id) {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $playerId = Auth::guard('player')->id();
        $playerEmail = Auth::guard('player')->user()->email;

        // Lấy các mục hàng của order đã tạo ở bước 1
        $order = Order::with(['orderItems.version.game'])
                      ->where('id', $order_id)
                      ->where('player_id', $playerId)
                      ->firstOrFail();

        // Tránh gọi Supplier nhiều lần nếu đơn hàng đã được xử lý xong
        if ($order->status === 'Completed') {
            return response()->json(['redirect_url' => route('library.index')]);
        }
        if ($order->status === 'API_Error') {
            return response()->json(['redirect_url' => route('cart.index')]);
        }

        $hasApiError = false;
        $lastSupplierName = null;
        $lastSupplierCode = null;

        foreach ($order->orderItems as $item) {
            $gameId = $item->version->game_id;
            $quantity = $item->quantity;
            
            // Gọi Supplier lấy key
            $result = $this->supplierManager->purchaseKeys($gameId, $quantity, $playerEmail);

            if ($result['success']) {
                $lastSupplierName = $result['supplier_name'] ?? 'Unknown';
                $lastSupplierCode = $result['supplier_code'] ?? 'UNKNOWN';

                $gameKeyIds = [];
                foreach ($result['keys'] as $keyCode) {
                    $gameKey = GameKey::create([
                        'order_item_id' => $item->id,
                        'key_code' => $keyCode,
                        'status' => 'Pending',
                        'fetched_at' => now(),
                        'supplier_transaction_id' => $result['transaction_id'],
                        'supplier_code' => $lastSupplierCode,
                    ]);
                    $gameKeyIds[] = $gameKey->id;
                }

                // Tạo Library chỉ với key thực (không null)
                if (!empty($gameKeyIds)) {
                    Library::create([
                        'player_id' => $playerId,
                        'game_key_id' => $gameKeyIds[0],
                        'game_id' => $gameId,
                        'key_code' => $result['keys'][0] ?? '',
                        'version_id' => $item->game_version_id,
                        'order_item_id' => $item->id,
                    ]);
                }
            } else {
                $hasApiError = true;
                Log::error('[Order] All suppliers failed for order #' . $order->id, [
                    'game_id' => $gameId,
                    'error' => $result['error'],
                    'error_code' => $result['error_code'] ?? '',
                ]);
            }
        }

        $order->update(['status' => $hasApiError ? 'API_Error' : 'Completed']);
        
        if ($order->status === 'Completed') {
            session()->flash('success', 'Thanh toán thành công! Trò chơi đã được thêm vào thư viện.');
            return response()->json(['redirect_url' => route('library.index')]);
        } else {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình lấy Key từ nhà cung cấp. Vui lòng liên hệ Admin!');
            return response()->json(['redirect_url' => route('cart.index')]);
        }
    }

    public function history() {
        $orders = Order::where('player_id', Auth::guard('player')->id())
                       ->orderBy('created_at', 'desc')->get();
        return view('Players.orders.history', compact('orders'));
    }

    public function detail($id) {
        $order = Order::with(['orderItems.version.game'])
                      ->where('id', $id)
                      ->where('player_id', Auth::guard('player')->id())
                      ->firstOrFail();
        return view('Players.orders.detail', compact('order'));
    }
}