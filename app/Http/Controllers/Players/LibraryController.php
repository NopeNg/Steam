<?php

namespace App\Http\Controllers\Players;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Library;

class LibraryController extends Controller
{
    // public function index() {
    //     // Giả lập lấy dữ liệu từ bảng library join sang game_keys và games
    //     $libraryItems = Library::with('gameKey.orderItem.version.game')->where('player_id', 1)->get();
    //     return view('Players.library.index', compact('libraryItems'));
    // }

//     public function index() {
//     // Lấy tất cả game trong thư viện của user hiện tại (ví dụ user id 1)
//     $libraries = \App\Models\Library::with(['gameKey.orderItem.version.game'])
//         ->where('player_id', 1)
//         ->get();

//     return view('Players.library.index', compact('libraries'));
// }

public function index() {
    $allLibraries = \App\Models\Library::with(['gameKey.orderItem.version.game'])
        ->where('player_id', 1)
        ->get();

    // Phân loại dựa trên trạng thái của game_key
    $activeGames = $allLibraries->filter(fn($lib) => $lib->gameKey->status === 'Activated');
    $inactiveGames = $allLibraries->filter(fn($lib) => $lib->gameKey->status !== 'Activated');

    return view('Players.library.index', compact('activeGames', 'inactiveGames'));
}

public function redeemView() {
    return view('Players.library.redeem');
}

public function activate(Request $request) {
    $request->validate(['key_code' => 'required']);

    $gameKey = \App\Models\GameKey::where('key_code', $request->key_code)
                                  ->where('status', 'Delivered')
                                  ->first();

    if (!$gameKey) {
        return back()->with('error', 'Mã không tồn tại hoặc đã được sử dụng.');
    }

    \DB::transaction(function () use ($gameKey) {
        $gameKey->update(['status' => 'Activated']);
        \App\Models\Library::create([
            'player_id' => 1,
            'game_key_id' => $gameKey->id
        ]);
    });

    return redirect()->route('library.index')->with('success', 'Kích hoạt thành công!');
}
}