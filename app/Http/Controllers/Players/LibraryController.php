<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\GameKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LibraryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.player'),
            new Middleware('check.banned'),
        ];
    }

    /**
     * Hiển thị danh sách game trong thư viện người chơi.
     */
    public function index() 
    {
        $playerId = Auth::guard('player')->id();

        // 1. Lấy danh sách game trong Library
        $allLibraries = Library::with(['gameKey.orderItem.version.game', 'game'])
                               ->where('player_id', $playerId)
                               ->get();

        // 2. Lọc các game đang hoạt động bình thường từ Library
        $activeGames = $allLibraries->filter(fn($lib) => $lib->gameKey && $lib->gameKey->status === 'Activated');

        // 3. LẤY TRỰC TIẾP TỪ BẢNG GAME_KEY (Bỏ qua bảng Library)
        $revokedGames = GameKey::whereHas('orderItem.order', function($query) use ($playerId) {
            $query->where('player_id', $playerId);
        })
        ->where('status', 'Revoked')
        ->with('orderItem.version.game')
        ->get();

        // 4. Các game khác (ví dụ: Pending, Delivered) vẫn lấy từ Library
        // 4. Các game khác (ví dụ: Pending, Delivered) vẫn lấy từ Library
        $inactiveGames = $allLibraries->filter(fn($lib) => 
            $lib->gameKey && !in_array($lib->gameKey->status, ['Activated', 'Revoked'])
        );

        $activatedGameIds = $activeGames->map(function($lib) {
            // Ưu tiên lấy game_id từ quan hệ gameKey -> orderItem -> version
            // Nếu không có (trường hợp Giveaway, Redeem), fallback về game_id của Library
            return $lib->gameKey && $lib->gameKey->orderItem && $lib->gameKey->orderItem->version
                ? $lib->gameKey->orderItem->version->game_id
                : $lib->game_id;
        })->filter()->unique()->toArray();

        return view('Players.library.index', compact('activeGames', 'inactiveGames', 'revokedGames', 'activatedGameIds'));
    }
}