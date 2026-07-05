<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\GameKey;
use App\Models\Game;
use App\Models\GameImage;

class PlayerController extends Controller
{
    private ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    public function index(Request $request)
    {
        $query = Player::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $players = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('Admins.players.index', compact('players'));
    }

    public function toggleStatus($id)
    {
        $player = Player::findOrFail($id);
        $oldStatus = $player->status;
        $newStatus = $player->status === 'Active' ? 'Banned' : 'Active';
        $player->update(['status' => $newStatus]);

        $this->activityLog->log('Thay đổi trạng thái người dùng', 'Người dùng "' . $player->username . '" (ID: ' . $player->id . '): ' . $oldStatus . ' → ' . $newStatus);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái tài khoản thành công!');
    }

    /**
     * Hiển thị danh sách game đã bị thu hồi của người chơi
     */
    public function revokedGames($id)
    {
        $player = Player::findOrFail($id);

        // Lấy các game keys có status = 'Revoked' thuộc về player này
        $revokedKeys = GameKey::where('game_keys.status', 'Revoked')
            ->join('order_items', 'game_keys.order_item_id', '=', 'order_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
            ->join('games', 'game_versions.game_id', '=', 'games.id')
            ->where('orders.player_id', $player->id)
            ->select(
                'game_keys.*',
                'games.id as game_id',
                'games.name as game_name',
                'games.cover_image',
                'game_versions.version_name'
            )
            ->orderBy('game_keys.fetched_at', 'desc')
            ->get()
            ->map(function ($key) {
                // Trích xuất lý do thu hồi từ supplier_transaction_id
                $revokeReason = '';
                if ($key->supplier_transaction_id && str_starts_with($key->supplier_transaction_id, 'REVOKE: ')) {
                    $revokeReason = substr($key->supplier_transaction_id, 8);
                }
                $key->revoke_reason = $revokeReason;
                
                return $key;
            });

        return view('Admins.players.revoked-games', compact('player', 'revokedKeys'));
    }
}
