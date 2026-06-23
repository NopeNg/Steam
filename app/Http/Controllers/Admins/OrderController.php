<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    private ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

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
        $oldStatus = $order->getOriginal('status');
        $order->update(['status' => $request->status]);

        $this->activityLog->log('Cập nhật trạng thái đơn hàng', 'Đơn hàng #' . $order->id . ': ' . $oldStatus . ' → ' . $request->status);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công!');
    }

    public function refund($id)
    {
        $order = \App\Models\Order::with(['player', 'orderItems'])->findOrFail($id);

        if ($order->status !== 'API_Error') {
            return back()->withErrors(['error' => 'Chỉ có thể hoàn tiền cho đơn hàng bị lỗi API.']);
        }

        // Kiểm tra đã hoàn tiền trước đó chưa (chống double refund)
        $alreadyRefunded = \App\Models\WalletTransaction::where('transaction_code', 'REFUND_ORD_' . $order->id)->exists();
        if ($alreadyRefunded) {
            return back()->withErrors(['error' => 'Đơn hàng này đã được hoàn tiền trước đó. Không thể hoàn tiền lần nữa.']);
        }

        // Kiểm tra đã cấp key thủ công chưa (không tính fallback key do API lỗi)
        $orderItemIds = $order->orderItems()->pluck('id')->toArray();
        $hasManualKey = \App\Models\GameKey::whereIn('order_item_id', $orderItemIds)
            ->where('supplier_transaction_id', 'not like', 'FALLBACK-%')
            ->exists();
        if ($hasManualKey) {
            return back()->withErrors(['error' => 'Đơn hàng này đã được cấp key. Không thể hoàn tiền sau khi đã cấp key.']);
        }

        $oldStatus = $order->status;

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

        $this->activityLog->log('Hoàn tiền đơn hàng', 'Đã hoàn ' . number_format($order->total_amount) . ' VNĐ cho đơn hàng #' . $order->id . ' (người chơi ID: ' . $order->player_id . ')');

        return back()->with('success', 'Đã hoàn tiền thành công vào ví người chơi và hủy đơn hàng.');
    }
    public function manualKey(Request $request, $id)
    {
        $request->validate([
            'key_code' => 'required|string|max:255',
        ]);

        $order = \App\Models\Order::with('orderItems.version.game')->findOrFail($id);

        if ($order->status !== 'API_Error') {
            return back()->withErrors(['error' => 'Chỉ có thể cấp Key thủ công cho đơn lỗi API.']);
        }

        // Kiểm tra đã hoàn tiền chưa
        $alreadyRefunded = \App\Models\WalletTransaction::where('transaction_code', 'REFUND_ORD_' . $order->id)->exists();
        if ($alreadyRefunded) {
            return back()->withErrors(['error' => 'Đơn hàng này đã được hoàn tiền. Không thể cấp key sau khi hoàn tiền.']);
        }

        // Kiểm tra đã có key chưa
        $orderItemIds = $order->orderItems()->pluck('id')->toArray();
        $hasKey = \App\Models\GameKey::whereIn('order_item_id', $orderItemIds)->exists();
        if ($hasKey) {
            return back()->withErrors(['error' => 'Đơn hàng này đã được cấp key trước đó. Không thể cấp thêm.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $request) {
            // Lấy order_item đầu tiên của đơn hàng
            $orderItem = $order->orderItems()->first();

            if ($orderItem) {
                $gameId = $orderItem->version->game_id ?? null;
                $versionId = $orderItem->game_version_id ?? null;

                // Tạo key trong game_keys
                $gameKey = \App\Models\GameKey::create([
                    'order_item_id' => $orderItem->id,
                    'key_code' => $request->key_code,
                    'status' => 'Delivered',
                    'fetched_at' => now(),
                    'supplier_transaction_id' => 'MANUAL_' . uniqid(),
                ]);

                // Tạo bản ghi library
                \App\Models\Library::create([
                    'player_id' => $order->player_id,
                    'game_key_id' => $gameKey->id,
                    'game_id' => $gameId,
                    'key_code' => $request->key_code,
                    'version_id' => $versionId,
                    'order_item_id' => $orderItem->id,
                ]);
            }

            $order->update(['status' => 'Completed']);
        });

        $this->activityLog->log('Cấp key thủ công', 'Đã cấp key thủ công cho đơn hàng #' . $order->id . ' (người chơi ID: ' . $order->player_id . ')');

        return back()->with('success', 'Đã cấp Key thủ công và hoàn tất đơn hàng.');
    }
}