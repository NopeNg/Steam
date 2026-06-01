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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function getCartId() {
        return Cart::firstOrCreate(['player_id' => Auth::guard('player')->id()])->id;
    }

    private function generateCustomSteamKey() {
        $segments = [];
        for ($i = 0; $i < 4; $i++) {
            $segments[] = strtoupper(substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 5)), 0, 4));
        }
        return implode('-', $segments);
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

        $order = DB::transaction(function () use ($request, $cartItems, $total, $cartId, $playerId) {
            // Tạo Order
            $newOrder = Order::create([
                'player_id' => $playerId,
                'total_amount' => $total,
                'order_type' => $request->order_type, 
                'status' => 'Completed',
                'payment_method' => $request->payment_method ?? 'VNPAY'
            ]);

            foreach ($cartItems as $item) {
                // Kiểm tra game đã sở hữu chưa
                $gameId = $item->version->game_id;
                $alreadyOwned = Library::where('player_id', $playerId)
                    ->whereHas('gameKey.orderItem.version', function($query) use ($gameId) {
                        $query->where('game_id', $gameId);
                    })->exists();

                // Logic: Nếu chọn Personal mà đã sở hữu -> Tự đổi sang Other để user giữ Key
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

                $newKeyId = DB::table('game_keys')->insertGetId([
                    'order_item_id' => $orderItem->id, 
                    'key_code' => $this->generateCustomSteamKey(),
                    'status' => $isPersonal ? 'Activated' : 'Delivered',
                    'fetched_at' => now(),
                    'supplier_transaction_id' => 'SIM-' . uniqid() 
                ]);

                // Đưa vào Library để hiển thị trong kho (dù là Activated hay Delivered)
                Library::create([
                    'player_id' => $playerId,
                    'game_key_id' => $newKeyId 
                ]);
            }
            
            CartItem::where('cart_id', $cartId)->delete();
            return $newOrder;
        });

        return redirect()->route('orders.waiting', ['order_id' => $order->id]);
    }

    public function waiting($order_id) {
        $order = Order::with(['items.version.game'])
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
        $order = Order::with(['items.version.game'])
                      ->where('id', $id)
                      ->where('player_id', Auth::guard('player')->id())
                      ->firstOrFail();

        return view('Players.orders.detail', compact('order'));
    }
}