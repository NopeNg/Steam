<?php

namespace App\Http\Controllers\Players;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\GameKey;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\DB;   // Import DB

class LibraryController extends Controller
{
// public function index() {
//     $playerId = Auth::guard('player')->id();

//     // Lấy tất cả bản ghi trong thư viện
//     $allLibraries = Library::with(['gameKey.orderItem.version.game', 'gameKey.orderItem.order'])
//         ->where('player_id', $playerId)
//         ->get();

//     // Lấy danh sách ID các game mà người chơi đã KÍCH HOẠT thành công
//     // Cách này giúp kiểm tra cực nhanh ở Blade xem game này đã có bản quyền chưa
//     $activatedGameIds = Library::where('player_id', $playerId)
//         ->whereHas('gameKey', fn($q) => $q->where('status', 'Activated'))
//         ->with('gameKey.orderItem.version')
//         ->get()
//         ->pluck('gameKey.orderItem.version.game_id')
//         ->unique()
//         ->toArray();

//     $activeGames = $allLibraries->filter(fn($lib) => optional($lib->gameKey)->status === 'Activated');
//     $inactiveGames = $allLibraries->filter(fn($lib) => optional($lib->gameKey)->status !== 'Activated');

//     return view('Players.library.index', compact('activeGames', 'inactiveGames', 'activatedGameIds'));
// }

public function index() 
{
    $playerId = Auth::guard('player')->id();

    // 1. Lấy tất cả bản ghi cùng mối quan hệ cần thiết từ database
    $allLibraries = Library::with(['gameKey.orderItem.version.game', 'gameKey.orderItem.order'])
        ->where('player_id', $playerId)
        ->get();

    // 2. Phân loại danh sách bằng collection filter
    // Active: Trạng thái Activated
    // Revoked: Trạng thái bị thu hồi
    // Inactive: Các trạng thái còn lại (ví dụ: Delivered, Pending,...) trừ Revoked
    $activeGames   = $allLibraries->filter(fn($lib) => optional($lib->gameKey)->status === 'Activated');
    $revokedGames  = $allLibraries->filter(fn($lib) => optional($lib->gameKey)->status === 'Revoked');
    $inactiveGames = $allLibraries->filter(fn($lib) => 
        optional($lib->gameKey)->status !== 'Activated' && 
        optional($lib->gameKey)->status !== 'Revoked'
    );

    // 3. Lấy danh sách ID game đã kích hoạt thành công (dùng cho các logic kiểm tra bản quyền ở View)
    $activatedGameIds = $activeGames->pluck('gameKey.orderItem.version.game_id')->unique()->toArray();

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
    // 1. Kiểm tra Key tồn tại và đang ở trạng thái 'Pending' (Chưa kích hoạt)
    $gameKey = GameKey::where('key_code', $request->key_code)
                      ->where('status', 'Pending')
                      ->first();

    if (!$gameKey) {
        return back()->with('error', 'Mã không hợp lệ hoặc đã được kích hoạt trước đó.');
    }

    $playerId = Auth::guard('player')->id();
    $targetGameId = $gameKey->orderItem->version->game_id;

    // 2. Kiểm tra xem người dùng đã từng kích hoạt BẤT KỲ phiên bản nào của game này chưa
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

    // 3. Nếu thỏa mãn cả 2, thực hiện kích hoạt
    DB::transaction(function () use ($gameKey) {
        // Chỉ cập nhật trạng thái Key thành Activated
        // Library record đã được tạo sẵn bởi OrderController
        $gameKey->update(['status' => 'Activated']);
    });

    return redirect()->route('library.index')->with('success', 'Kích hoạt thành công!');
}
}