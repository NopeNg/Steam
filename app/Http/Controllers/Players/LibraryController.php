<?php

namespace App\Http\Controllers\Players;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\GameKey;
use App\Models\GameVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function index() 
    {
        $playerId = Auth::guard('player')->id();

        // Lấy tất cả bản ghi cùng mối quan hệ
        $allLibraries = Library::with([
            'gameKey.orderItem.version.game',
            'gameKey.orderItem.order',
            'game',
            'gameVersion'
        ])
        ->where('player_id', $playerId)
        ->get();

        // Chỉ giữ lại các item có gameKey tồn tại
        $allLibraries = $allLibraries->filter(fn($lib) => $lib->gameKey !== null);

        // Phân loại
        $activeGames   = $allLibraries->filter(fn($lib) => $lib->gameKey->status === 'Activated');
        $revokedGames  = $allLibraries->filter(fn($lib) => $lib->gameKey->status === 'Revoked');
        $inactiveGames = $allLibraries->filter(fn($lib) => 
            $lib->gameKey->status !== 'Activated' && 
            $lib->gameKey->status !== 'Revoked'
        );

        // Lấy danh sách ID game đã kích hoạt
        $activatedGameIds = $activeGames->pluck('gameKey.orderItem.version.game_id')
            ->filter()
            ->unique()
            ->toArray();

        return view('Players.library.index', compact(
            'activeGames', 
            'inactiveGames', 
            'revokedGames', 
            'activatedGameIds'
        ));
    }

    public function redeemView() {
        return view('Players.library.redeem');
    }

    public function activate(Request $request) {
        $gameKey = GameKey::where('key_code', $request->key_code)
                          ->whereIn('status', ['Pending', 'Giveaway'])
                          ->first();

        if (!$gameKey) {
            return back()->with('error', 'Mã không hợp lệ hoặc đã được kích hoạt trước đó.');
        }

        // Nếu là key Giveaway
        if ($gameKey->status === 'Giveaway') {
            $playerId = Auth::guard('player')->id();

            // Lấy thông tin game version
            $version = GameVersion::with('game')->find($gameKey->game_version_id);
            $gameId = $version ? $version->game_id : null;
            $versionId = $version ? $version->id : null;

            $libraryExists = \App\Models\Library::where('player_id', $playerId)
                ->where('game_key_id', $gameKey->id)
                ->exists();

            if (!$libraryExists) {
                \App\Models\Library::create([
                    'player_id' => $playerId,
                    'game_key_id' => $gameKey->id,
                    'game_id' => $gameId,
                    'version_id' => $versionId,
                    'key_code' => $gameKey->key_code,
                    'purchased_at' => now(),
                ]);
            }

            DB::transaction(function () use ($gameKey) {
                $gameKey->update(['status' => 'Activated']);
            });

            return redirect()->route('library.index')->with('success', 'Kích hoạt key Giveaway thành công!');
        }

        $playerId = Auth::guard('player')->id();
        $targetGameId = $gameKey->orderItem->version->game_id;

        // Kiểm tra đã từng kích hoạt game này chưa
        $hasEverActivatedGame = Library::where('player_id', $playerId)
            ->whereHas('gameKey.orderItem.version', function($query) use ($targetGameId) {
                $query->where('game_id', $targetGameId);
            })
            ->whereHas('gameKey', function($query) {
                $query->where('status', 'Activated');
            })
            ->exists();

        if ($hasEverActivatedGame) {
            return back()->with('error', 'Bạn đã từng kích hoạt một phiên bản khác của trò chơi này rồi!');
        }

        DB::transaction(function () use ($gameKey) {
            $gameKey->update(['status' => 'Activated']);
        });

        return redirect()->route('library.index')->with('success', 'Kích hoạt thành công!');
    }
}