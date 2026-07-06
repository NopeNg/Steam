<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\GameKey;
use App\Models\Game;
use App\Models\Library;
use Illuminate\Support\Facades\DB;

class KeyController extends Controller
{
    private ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    public function index(Request $request)
    {
        $query = GameKey::with('orderItem.gameVersion.game');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('key_code', 'like', '%' . $search . '%')
                ->orWhereHas('orderItem.gameVersion.game', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $keys = $query->orderBy('id', 'desc')->paginate(15);

        return view('Admins.keys.index', compact('keys'));
    }

    public function create()
    {
        $games = Game::with('versions')->orderBy('name')->get();
        return view('Admins.keys.create', compact('games'));
    }

    public function storeCustom(Request $request)
    {
        $request->validate([
            'key_code' => 'required|unique:game_keys,key_code',
            'game_version_id' => 'required|exists:game_versions,id',
            'note' => 'nullable',
        ], [
            'key_code.required' => 'Mã key không được để trống.',
            'key_code.unique' => 'Mã key này đã tồn tại trong hệ thống.',
            'game_version_id.required' => 'Vui lòng chọn game / phiên bản cho key này.',
            'game_version_id.exists' => 'Phiên bản game không hợp lệ.',
        ]);

        $gameKey = GameKey::create([
            'key_code' => trim($request->key_code),
            'game_version_id' => $request->game_version_id,
            'status' => 'Giveaway',
            'supplier_transaction_id' => $request->note,
            'fetched_at' => now(),
        ]);

        $this->activityLog->log('Tạo key giveaway', 'Đã tạo key giveaway: ' . trim($request->key_code) . ' cho game version ID: ' . $request->game_version_id);

        return redirect()->route('admin.keys.index')->with('success', 'Đã tạo mã Key Giveaway thành công!');
    }

    /**
     * Thu hồi key của người chơi
     * - Set GameKey.status = 'Revoked'
     * - Không xóa Library record, chỉ đổi status
     */
    public function revoke(Request $request, $id)
    {
        $request->validate([
            'revoke_reason' => 'required|string|max:1000',
        ], [
            'revoke_reason.required' => 'Vui lòng nhập lý do thu hồi key.',
        ]);

        $gameKey = GameKey::with('orderItem.version.game')->findOrFail($id);

        if ($gameKey->status === 'Revoked') {
            return back()->withErrors(['error' => 'Key này đã được thu hồi trước đó.']);
        }

        if ($gameKey->status !== 'Pending' && $gameKey->status !== 'Activated') {
            return back()->withErrors(['error' => 'Chỉ có thể thu hồi key đã được giao (Pending) hoặc đã kích hoạt (Activated).']);
        }

        $gameName = $gameKey->orderItem->version->game->name ?? 'N/A';
        $orderItem = $gameKey->orderItem;
        $order = $orderItem->order ?? null;

        DB::transaction(function () use ($gameKey, $request) {
            // Đánh dấu key là Revoked và lưu lý do vào supplier_transaction_id
            $gameKey->update([
                'status' => 'Revoked',
                'supplier_transaction_id' => 'REVOKE: ' . $request->revoke_reason,
            ]);
        });

        $this->activityLog->log('Thu hồi key', 'Đã thu hồi key (ID: ' . $gameKey->id . ') của game "' . $gameName . '", lý do: ' . $request->revoke_reason);

        return back()->with('success', 'Đã thu hồi key của game "' . $gameName . '" thành công!');
    }

    /**
     * Hoàn tiền cho 1 key cụ thể
     */
    public function refundKey($id)
    {
        $gameKey = GameKey::with('orderItem.order.player')->findOrFail($id);

        if ($gameKey->status === 'Revoked') {
            return back()->withErrors(['error' => 'Key này đã bị thu hồi. Không thể hoàn tiền.']);
        }

        // Kiểm tra key đã được đổi trước đó chưa
        if (str_starts_with($gameKey->supplier_transaction_id ?? '', 'REPLACED:')) {
            return back()->withErrors(['error' => 'Key này đã được đổi. Không thể hoàn tiền sau khi đã đổi key.']);
        }

        // Kiểm tra key đã được hoàn tiền trước đó chưa (dựa vào supplier_transaction_id)
        if (str_starts_with($gameKey->supplier_transaction_id ?? '', 'REFUNDED:')) {
            return back()->withErrors(['error' => 'Key này đã được hoàn tiền trước đó.']);
        }

        $orderItem = $gameKey->orderItem;
        $order = $orderItem->order ?? null;
        $player = $order->player ?? null;

        if (!$order || !$player) {
            return back()->withErrors(['error' => 'Không tìm thấy thông tin đơn hàng hoặc người chơi.']);
        }

        // Tính tiền hoàn: price của 1 key 
        $refundAmount = $orderItem->price_at_purchase ;

        DB::transaction(function () use ($gameKey, $player, $refundAmount) {
            // Cập nhật status key thành Revoked và lưu dấu hiệu đã hoàn tiền
            $gameKey->update([
                'status' => 'Revoked',
                'supplier_transaction_id' => 'REFUNDED: ' . $gameKey->key_code,
            ]);

            // Hoàn tiền vào ví
            $player->increment('balance', $refundAmount);
        });

        $this->activityLog->log('Hoàn tiền key', 'Đã hoàn ' . number_format($refundAmount, 0, ',', '.') . ' VNĐ cho key ID: ' . $gameKey->id . ' (game: ' . ($gameKey->orderItem->version->game->name ?? 'N/A') . ', đơn hàng #' . $order->id . ')');

        return back()->with('success', 'Đã hoàn tiền ' . number_format($refundAmount, 0, ',', '.') . ' VNĐ vào ví người chơi cho key bị lỗi.');
    }

    /**
     * Đổi key mới cho 1 key cụ thể (replace)
     */
    public function replaceKey(Request $request, $id)
    {
        $request->validate([
            'new_key_code' => 'required|string|max:255',
        ], [
            'new_key_code.required' => 'Vui lòng nhập mã key mới.',
        ]);

        $gameKey = GameKey::with('orderItem.order.player')->findOrFail($id);

        if ($gameKey->status === 'Revoked') {
            return back()->withErrors(['error' => 'Key này đã bị thu hồi. Không thể đổi key mới.']);
        }

        if ($gameKey->status !== 'Pending' && $gameKey->status !== 'Activated') {
            return back()->withErrors(['error' => 'Chỉ có thể đổi key cho key đã được giao (Pending/Activated).']);
        }

        $orderItem = $gameKey->orderItem;
        $order = $orderItem->order ?? null;
        $player = $order->player ?? null;

        $oldKeyCode = $gameKey->key_code;

        DB::transaction(function () use ($gameKey, $request, $player, $oldKeyCode) {
            // Lưu key cũ vào supplier_transaction_id, cập nhật key_code mới
            $gameKey->update([
                'key_code' => trim($request->new_key_code),
                'supplier_transaction_id' => 'REPLACED: ' . $oldKeyCode,
            ]);

            // Cập nhật library với key mới
            \App\Models\Library::where('game_key_id', $gameKey->id)->update([
                'key_code' => trim($request->new_key_code),
            ]);
        });

        $this->activityLog->log('Đổi key', 'Đã đổi key cho game "' . ($gameKey->orderItem->version->game->name ?? 'N/A' ) . '": ' . $oldKeyCode . ' → ' . trim($request->new_key_code));

        return back()->with('success', 'Đã đổi key mới thành công! Key cũ đã bị vô hiệu hóa.');
    }
}
