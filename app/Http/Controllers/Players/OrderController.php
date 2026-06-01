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

class OrderController extends Controller
{
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

    public function process(Request $request) {
        $cartId = $this->getCartId();
        $cartItems = CartItem::with('version.game')->where('cart_id', $cartId)->get();
        
        if($cartItems->isEmpty()) return redirect()->route('home');

        $total = $cartItems->sum(fn($item) => ($item->version->discount_price ?? $item->version->price) * $item->quantity);
        $playerId = Auth::guard('player')->id();
        $playerEmail = Auth::guard('player')->user()->email;

        $order = DB::transaction(function () use ($request, $cartItems, $total, $cartId, $playerId, $playerEmail) {
            $newOrder = Order::create([
                'player_id' => $playerId,
                'total_amount' => $total,
                'order_type' => $request->order_type,
                'status' => 'Pending',
                'payment_method' => $request->payment_method ?? 'VNPAY'
            ]);

            $hasApiError = false;
            $lastSupplierName = null;
            $lastSupplierCode = null;

            foreach ($cartItems as $item) {
                $gameId = $item->version->game_id;

                $alreadyOwned = Library::where('player_id', $playerId)
                    ->whereHas('gameKey.orderItem.version', function($query) use ($gameId) {
                        $query->where('game_id', $gameId);
                    })->exists();

                $isPersonal = ($request->order_type === 'Personal' && !$alreadyOwned);
                
                if ($request->order_type === 'Personal' && $alreadyOwned) {
                    $newOrder->update(['order_type' => 'Other']);
                }

                $orderItem = OrderItem::create([
                    'order_id' => $newOrder->id,
                    'game_version_id' => $item->game_version_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->version->discount_price ?? $item->version->price
                ]);

                // === GỌI SUPPLIER MANAGER (tự động chọn supplier phù hợp) ===
                $quantity = $item->quantity;
                $result = $this->supplierManager->purchaseKeys($gameId, $quantity, $playerEmail);

                if ($result['success']) {
                    $lastSupplierName = $result['supplier_name'] ?? 'Unknown';
                    $lastSupplierCode = $result['supplier_code'] ?? 'UNKNOWN';

                    foreach ($result['keys'] as $keyCode) {
                        GameKey::create([
                            'order_item_id' => $orderItem->id,
                            'key_code' => $keyCode,
                            'status' => $isPersonal ? 'Activated' : 'Delivered',
                            'fetched_at' => now(),
                            'supplier_transaction_id' => $result['transaction_id'],
                            'supplier_code' => $lastSupplierCode,
                        ]);
                    }

                    Library::create([
                        'player_id' => $playerId,
                        'game_key_id' => null,
                        'game_id' => $gameId,
                        'key_code' => $result['keys'][0] ?? '',
                        'version_id' => $item->game_version_id,
                        'order_item_id' => $orderItem->id,
                    ]);

                    $lastKey = GameKey::where('order_item_id', $orderItem->id)->latest()->first();
                    if ($lastKey) {
                        Library::where('player_id', $playerId)
                            ->where('order_item_id', $orderItem->id)
                            ->update(['game_key_id' => $lastKey->id]);
                    }
                } else {
                    $hasApiError = true;
                    Log::error('[Order] All suppliers failed for order #' . $newOrder->id, [
                        'game_id' => $gameId,
                        'error' => $result['error'],
                        'error_code' => $result['error_code'] ?? '',
                    ]);

                    // Fake fallback key
                    $fakeKey = 'FALLBACK-' . strtoupper(substr(uniqid(), -8)) . '-' . $gameId;
                    GameKey::create([
                        'order_item_id' => $orderItem->id,
                        'key_code' => $fakeKey,
                        'status' => 'Delivered',
                        'fetched_at' => now(),
                        'supplier_transaction_id' => 'FALLBACK-' . uniqid(),
                    ]);

                    Library::create([
                        'player_id' => $playerId,
                        'game_key_id' => null,
                        'game_id' => $gameId,
                        'key_code' => $fakeKey,
                        'version_id' => $item->game_version_id,
                        'order_item_id' => $orderItem->id,
                    ]);
                }
            }
            
            $newOrder->update(['status' => $hasApiError ? 'API_Error' : 'Completed']);
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