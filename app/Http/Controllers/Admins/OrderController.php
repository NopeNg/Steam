<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('player');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('player', function ($q2) use ($search) {
                        $q2->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('Admins.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = \App\Models\Order::with(['player', 'orderItems.gameVersion.game'])->findOrFail($id);

        return view('Admins.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Completed,Pending,API_Error,Failed',
        ], [
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công!');
    }

    public function refund($id)
    {
        $order = \App\Models\Order::with('player')->findOrFail($id);

        if ($order->status !== 'API_Error') {
            return back()->withErrors(['error' => 'Chỉ có thể hoàn tiền cho đơn hàng bị lỗi API.']);
        }

        // Kiểm tra đã hoàn tiền trước đó chưa (chống double refund)
        $alreadyRefunded = \App\Models\WalletTransaction::where('transaction_code', 'REFUND_ORD_' . $order->id)->exists();
        if ($alreadyRefunded) {
            return back()->withErrors(['error' => 'Đơn hàng này đã được hoàn tiền trước đó. Không thể hoàn tiền lần nữa.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            $order->player->increment('balance', $order->total_amount);

            $order->update(['status' => 'Cancelled']);

            \App\Models\WalletTransaction::create([
                'player_id' => $order->player_id,
                'amount' => $order->total_amount,
                'transaction_code' => 'REFUND_ORD_' . $order->id,
                'status' => 'success'
            ]);
        });

        return back()->with('success', 'Đã hoàn tiền thành công vào ví người chơi và hủy đơn hàng.');
    }
    public function manualKey(Request $request, $id)
    {
        $request->validate([
            'key_code' => 'required|string|max:255',
        ]);

        $order = \App\Models\Order::findOrFail($id);

        if ($order->status !== 'API_Error') {
            return back()->withErrors(['error' => 'Chỉ có thể cấp Key thủ công cho đơn lỗi API.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $request) {
            \App\Models\GameKey::create([
                'game_id' => $order->game_id,
                'key_code' => $request->key_code,
                'status' => 'used'
            ]);

            \App\Models\Library::create([
                'player_id' => $order->player_id,
                'game_id' => $order->game_id,
                'key_code' => $request->key_code,
                'version_id' => $order->version_id ?? null
            ]);

            $order->update(['status' => 'Completed']);
        });

        return back()->with('success', 'Đã cấp Key thủ công và hoàn tất đơn hàng.');
    }
}