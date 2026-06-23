<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\GameKey;
use App\Models\Game;

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
}