<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Gift;
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

    private function getCartId()
    {
        return Cart::firstOrCreate(['player_id' => Auth::guard('player')->id()])->id;
    }

    public function checkout()
    {
        $cartItems = CartItem::with('version.game')->where('cart_id', $this->getCartId())->get();
        $friends = Player::where('id', '!=', Auth::guard('player')->id())->get();
        return view('Players.orders.checkout', compact('cartItems', 'friends'));
    }

    // Bước 1: Khởi tạo đơn hàng nháp (Pending), kiểm tra sở hữu, xóa giỏ hàng
    public function process(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:Personal,Gift,Other',
            'friend_id' => 'nullable|exists:players,id',
            'payment_method' => 'nullable|string|max:50',
        ], [
            'order_type.required' => 'Vui lòng chọn loại đơn hàng.',
            'order_type.in' => 'Loại đơn hàng không hợp lệ.',
            'friend_id.exists' => 'Bạn bè không hợp lệ.',
        ]);

        $cartId = $this->getCartId();
        $cartItems = CartItem::with('version.game')->where('cart_id', $cartId)->get();

        if ($cartItems->isEmpty())
            return redirect()->route('home');

        $total = $cartItems->sum(fn($item) => ($item->version->discount_price ?? $item->version->price) * $item->quantity);
        $playerId = Auth::guard('player')->id();

        $order = DB::transaction(function () use ($request, $cartItems, $total, $cartId, $playerId) {

            // Nếu Gift: kiểm tra đã là bạn bè chưa
            if ($request->order_type === 'Gift' && !$request->friend_id) {
                throw new \Exception('Vui lòng chọn bạn bè để tặng quà.');
            }

            $newOrder = Order::create([
                'player_id' => $playerId,
                'total_amount' => $total,
                'order_type' => $request->order_type,
                'status' => 'Pending',
                'payment_method' => $request->payment_method ?? 'VNPAY',
                'friend_id' => $request->order_type === 'Gift' ? $request->friend_id : null,
            ]);

            $hasAnyPersonalGame = false;

            foreach ($cartItems as $item) {
                $gameId = $item->version->game_id;

                $alreadyOwned = Library::where('player_id', $playerId)
                    ->whereHas('gameKey.orderItem.version', function ($query) use ($gameId) {
                        $query->where('game_id', $gameId);
                    })->exists();

                $isPersonal = ($request->order_type === 'Personal' && !$alreadyOwned);

                // Nếu đặt là Personal nhưng game đã sở hữu → chuyển order sang Other
                if ($request->order_type === 'Personal' && $alreadyOwned) {
                    $newOrder->update(['order_type' => 'Other']);
                } else if ($isPersonal) {
                    $hasAnyPersonalGame = true;
                }

                $orderItem = OrderItem::create([
                    'order_id' => $newOrder->id,
                    'game_version_id' => $item->game_version_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->version->discount_price ?? $item->version->price
                ]);

                // Nếu là Gift → tạo Gift record ngay đợi key về sẽ gán
                if ($request->order_type === 'Gift') {
                    // Gift sẽ được tạo sau khi có key ở executeOrder
                }
            }

            CartItem::where('cart_id', $cartId)->delete();
            return $newOrder;
        });

        return redirect()->route('orders.waiting', ['order_id' => $order->id]);
    }

    public function waiting($order_id)
    {
        $order = Order::with(['orderItems.version.game'])
            ->where('id', $order_id)
            ->where('player_id', Auth::guard('player')->id())
            ->firstOrFail();
        return view('Players.orders.waiting', compact('order'));
    }


    // Bước 2: Chạy ngầm xử lý gọi nhà cung cấp lấy key, kích hoạt bằng AJAX sau khi đếm ngược 30s
    public function executeOrder(Request $request, $order_id)
    {
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

        try {
            return DB::transaction(function () use ($order, $playerId, $playerEmail) {
                $hasApiError = false;
                $lastError = null;
                $createdGameKeyIds = [];
                $createdLibraryIds = [];

                foreach ($order->orderItems as $item) {
                    $gameId = $item->version->game_id;
                    $quantity = $item->quantity;

                    // Gọi Supplier lấy số lượng key tương ứng
                    $result = $this->supplierManager->purchaseKeys($gameId, $quantity, $playerEmail);

                    if ($result['success']) {
                        $lastSupplierCode = $result['supplier_code'] ?? 'UNKNOWN';
                        $supplierName = $result['supplier_name'] ?? 'Unknown';
                        $usedSupplierNames[] = $supplierName;
                        $itemFallbackChain = $result['fallback_chain'] ?? [];
                        if (!empty($itemFallbackChain)) {
                            foreach ($itemFallbackChain as $fb) {
                                $allFallbackChains[] = $fb;
                            }
                        }

                        // Vòng lặp duyệt qua TỪNG KEY nhận được từ API nhà cung cấp
                        foreach ($result['keys'] as $keyCode) {

                            // 1. Lưu thông tin key vào bảng GameKey
                            $gameKey = GameKey::create([
                                'order_item_id' => $item->id,
                                'game_version_id' => $item->game_version_id,
                                'key_code' => $keyCode,
                                'status' => 'Delivered',
                                'fetched_at' => now(),
                                'supplier_transaction_id' => $result['transaction_id'],
                                'supplier_code' => $lastSupplierCode,
                            ]);
                            $gameKeyIds[] = $gameKey->id;
                            $createdGameKeyIds[] = $gameKey->id;
                        }

                        // Nếu là Gift order → tạo Gift record (không tạo Library cho người mua)
                        if ($order->order_type === 'Gift' && $order->friend_id && !empty($gameKeyIds)) {
                            $gift = \App\Models\Gift::create([
                                'sender_id' => $playerId,
                                'receiver_id' => $order->friend_id,
                                'game_key_id' => $gameKeyIds[0],
                                'status' => 'Sent'
                            ]);
                        }
                        // Nếu là Personal/Other → tạo Library cho người mua
                        elseif (!empty($gameKeyIds)) {
                            $lib = Library::create([
                                'player_id' => $playerId,
                                'game_key_id' => $gameKeyIds[0],
                                'game_id' => $gameId,
                                'key_code' => $result['keys'][0] ?? '',
                                'version_id' => $item->game_version_id,
                                'order_item_id' => $item->id,
                            ]);
                            $createdLibraryIds[] = $lib->id;
                        }
                    } else {
                        $hasApiError = true;
                        $lastError = $result['error'];
                        Log::error('[Order] All suppliers failed for order #' . $order->id, [
                            'game_id' => $gameId,
                            'error' => $result['error'],
                            'error_code' => $result['error_code'] ?? '',
                        ]);
                        
                        // 🔥 Nếu có lỗi, xóa toàn bộ GameKey và Library đã tạo trước đó để rollback
                        if (!empty($createdGameKeyIds)) {
                            GameKey::whereIn('id', $createdGameKeyIds)->delete();
                        }
                        if (!empty($createdLibraryIds)) {
                            Library::whereIn('id', $createdLibraryIds)->delete();
                        }
                        
                        // Throw exception để rollback toàn bộ transaction
                        throw new \Exception('Supplier failed: ' . ($lastError ?? 'Unknown error'));
                    }
                }

                // Tất cả mặt hàng và số lượng key đều xử lý thành công
                $order->update(['status' => 'Completed']);
                
                session()->flash('success', 'Thanh toán thành công! Trò chơi đã được thêm vào thư viện.');
                return response()->json(['redirect_url' => route('library.index')]);
            });
        } catch (\Exception $e) {
            Log::error('[Order] executeOrder failed for order #' . $order->id . ': ' . $e->getMessage());

            // Đánh dấu đơn hàng thất bại
            $order->update(['status' => 'API_Error']);

            session()->flash('error', 'Có lỗi xảy ra trong quá trình lấy Key từ nhà cung cấp. Vui lòng liên hệ Admin!');
            return response()->json(['redirect_url' => route('orders.waiting', ['order_id' => $order->id])]);
        }
    }

    public function history()
    {
        $orders = Order::where('player_id', Auth::guard('player')->id())
            ->orderBy('created_at', 'desc')->get();
        return view('Players.orders.history', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with(['orderItems.version.game'])
            ->where('id', $id)
            ->where('player_id', Auth::guard('player')->id())
            ->firstOrFail();
        return view('Players.orders.detail', compact('order'));
    }
}