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
     * - Xóa Library record của key đó để người chơi không thấy key nữa
     * - Cho phép người chơi mua game đó lại
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

        if ($gameKey->status !== 'Delivered' && $gameKey->status !== 'Activated') {
            return back()->withErrors(['error' => 'Chỉ có thể thu hồi key đã được giao (Delivered) hoặc đã kích hoạt (Activated).']);
        }

        $gameName = $gameKey->orderItem->version->game->name ?? 'N/A';

        DB::transaction(function () use ($gameKey, $request) {
            // Đánh dấu key là Revoked và lưu lý do vào supplier_transaction_id
            $gameKey->update([
                'status' => 'Revoked',
                'supplier_transaction_id' => 'REVOKE: ' . $request->revoke_reason,
            ]);
        });

        $this->activityLog->log('Thu hồi key', 'Đã thu hồi key (ID: ' . $gameKey->id . ') của game "' . $gameName . '", lý do: ' . $request->revoke_reason);

        return back()->with('success', 'Đã thu hồi key của game "' . $gameName . '" thành công! Người chơi sẽ không còn thấy key này trong thư viện và có thể mua lại.');
    }
}