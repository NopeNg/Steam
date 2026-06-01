<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Player;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout() {
        $cartItems = CartItem::with('version.game')->where('cart_id', 1)->get();
        $friends = Player::where('id', '!=', 1)->get(); 
        return view('Players.orders.checkout', compact('cartItems', 'friends'));
    }

    // BƯỚC 1: Tạo đơn hàng CHỜ
    public function process(Request $request) {
        $cartItems = CartItem::with('version')->where('cart_id', 1)->get();
        if($cartItems->isEmpty()) return redirect()->route('home');

        $total = $cartItems->sum(fn($item) => ($item->version->discount_price ?? $item->version->price) * $item->quantity);

        $order = DB::transaction(function () use ($request, $cartItems, $total) {
            $newOrder = Order::create([
                'player_id' => 1,
                'total_amount' => $total,
                'order_type' => $request->order_type, 
                'status' => 'Pending',
                'payment_method' => $request->payment_method ?? 'VNPay'
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'game_version_id' => $item->game_version_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->version->discount_price ?? $item->version->price
                ]);
            }
            CartItem::where('cart_id', 1)->delete();
            return $newOrder;
        });

        return redirect()->route('orders.waiting', ['order_id' => $order->id]);
    }

    // BƯỚC 2: Xử lý sau khi chờ 15s (Hoặc VNPay callback)
    public function vnpayReturn(Request $request) {
        $orderId = $request->get('order_id');
        $order = Order::with('items.version')->find($orderId);

        if ($order && $order->status === 'Pending') {
            $this->activateOrderAndGrantKeys($order);
            return redirect()->route('library.index')->with('success', 'Thanh toán thành công! Game đã được thêm vào kho.');
        }

        return redirect()->route('home')->with('error', 'Đơn hàng không hợp lệ hoặc đã xử lý trước đó.');
    }

    // HÀM CẤP KEY (Tự động tạo Key thật trong DB để tránh lỗi khóa ngoại)
    private function activateOrderAndGrantKeys($order) {
        DB::transaction(function () use ($order) {
            $order->status = 'Completed';
            $order->save();

            foreach ($order->items as $item) {
                // Tạo Key "Cha" trước
                $newKeyId = DB::table('game_keys')->insertGetId([
                    'order_item_id'           => $item->id, 
                    'key_code'                => 'KEY-' . strtoupper(bin2hex(random_bytes(4))),
                    'status'                  => 'Delivered',
                    'fetched_at'              => now(),
                    'supplier_transaction_id' => 'TRX-' . uniqid() 
                ]);

                // Tạo Library "Con" sau
                Library::create([
                    'player_id'   => $order->player_id,
                    'game_key_id' => $newKeyId 
                ]);
            }
        });
    }

    // View chờ xử lý đơn hàng (giả lập VNPay)
    public function waitingView(Request $request) {
        $orderId = $request->get('order_id');
        return view('Players.orders.waiting', compact('orderId'));
    }

    public function history() {
        $orders = Order::where('player_id', 1)->orderBy('created_at', 'desc')->get();
        return view('Players.orders.history', compact('orders'));
    }

    public function detail($id) {
    // Lấy đơn hàng theo ID và nạp trước dữ liệu liên quan
    $order = Order::with(['items.version.game'])->findOrFail($id);

    // Kiểm tra để đảm bảo người chơi chỉ xem được đơn hàng của chính họ
    if ($order->player_id !== 1) {
        return redirect()->route('home')->with('error', 'Bạn không có quyền xem đơn này.');
    }

    return view('Players.orders.detail', compact('order'));
}
}