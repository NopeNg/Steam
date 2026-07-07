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
        $order = \App\Models\Order::with([
            'player',
            'orderItems.gameVersion.game',
            'orderItems.gameKeys'
        ])->findOrFail($id);

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
        $order = \App\Models\Order::with(['player', 'orderItems.gameKeys'])->findOrFail($id);

        // Kiểm tra đã hoàn tiền trước đó chưa (chống double refund) - dựa vào status Cancelled
        if ($order->status === 'Cancelled') {
            return back()->withErrors(['error' => 'Đơn hàng này đã được hoàn tiền trước đó. Không thể hoàn tiền lần nữa.']);
        }

        if ($order->status !== 'API_Error') {
            return back()->withErrors(['error' => 'Chỉ có thể hoàn tiền cho đơn hàng bị lỗi API.']);
        }

        // Lấy tất cả key trong đơn hàng
        $allKeys = $order->orderItems->flatMap(function ($item) {
            return $item->gameKeys;
        });

        // Kiểm tra xem có key nào đã được refund riêng lẻ trước đó không
        $hasRefundedKey = $allKeys->contains(function ($key) {
            return str_starts_with($key->supplier_transaction_id ?? '', 'REFUNDED:');
        });

        if ($hasRefundedKey) {
            return back()->withErrors(['error' => 'Đã có key trong đơn hàng này được hoàn tiền riêng lẻ trước đó. Chỉ có thể hoàn tiền 1 lần duy nhất cho toàn bộ đơn hàng. Vui lòng hoàn tiền từng key riêng lẻ cho các key còn lại.']);
        }

        // Kiểm tra đã cấp key thủ công chưa (không tính fallback key do API lỗi)
        $orderItemIds = $order->orderItems()->pluck('id')->toArray();
        $hasManualKey = \App\Models\GameKey::whereIn('order_item_id', $orderItemIds)
            ->where('supplier_transaction_id', 'not like', 'FALLBACK-%')
            ->exists();
        if ($hasManualKey) {
            return back()->withErrors(['error' => 'Đơn hàng này đã được cấp key. Không thể hoàn tiền sau khi đã cấp key.']);
        }

        // Tính số tiền cần hoàn
        $refundAmount = $order->total_amount;

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $allKeys, $refundAmount) {
            // Hoàn tiền vào ví
            $order->player->increment('balance', $refundAmount);

            // Đánh dấu tất cả key là đã refund
            foreach ($allKeys as $key) {
                $key->update([
                    'status' => 'Revoked',
                    'supplier_transaction_id' => 'REFUNDED: ' . $key->key_code,
                ]);
            }

            // Hủy đơn hàng
            $order->update(['status' => 'Cancelled']);
        });

        $this->activityLog->log('Hoàn tiền đơn hàng', 'Đã hoàn ' . number_format($refundAmount) . ' VNĐ cho đơn hàng #' . $order->id . ' (người chơi ID: ' . $order->player_id . ')');

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

        // Kiểm tra đã hoàn tiền chưa (dựa vào status Cancelled)
        if ($order->status === 'Cancelled') {
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