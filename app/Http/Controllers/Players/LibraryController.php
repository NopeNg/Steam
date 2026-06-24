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

/**
 * Class LibraryController
 * Quản lý thư viện game và quy trình kích hoạt (redeem) key của người chơi.
 */
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
     * Hiển thị danh sách game trong thư viện người chơi, được phân loại theo trạng thái Key.
     */
/**
     * Hiển thị danh sách game trong thư viện người chơi.
     * Sử dụng trạng thái từ GameKey để xác định trạng thái hiển thị (Active/Revoked/Inactive).
     */
/**
     * Hiển thị danh sách game trong thư viện người chơi.
     * Logic: Trạng thái hiển thị (Active/Revoked/Inactive) được lấy 100% từ bảng GameKey.
     */
public function index() 
{
    $playerId = Auth::guard('player')->id();

    // 1. Lấy danh sách game trong Library (chỉ dùng để hiển thị các game sở hữu hợp lệ)
    $allLibraries = Library::with(['gameKey.orderItem.version.game', 'game'])
                           ->where('player_id', $playerId)
                           ->get();

    // 2. Lọc các game đang hoạt động bình thường từ Library
    $activeGames = $allLibraries->filter(fn($lib) => $lib->gameKey && $lib->gameKey->status === 'Activated');

    // 3. LẤY TRỰC TIẾP TỪ BẢNG GAME_KEY (Bỏ qua bảng Library)
    // Lấy tất cả các Key của người chơi này (thông qua order) có trạng thái Revoked
    $revokedGames = GameKey::whereHas('orderItem.order', function($query) use ($playerId) {
        $query->where('player_id', $playerId);
    })
    ->where('status', 'Revoked')
    ->with('orderItem.version.game')
    ->get();

    // Các game khác (ví dụ: Pending) vẫn lấy từ Library
    $inactiveGames = $allLibraries->filter(fn($lib) => 
        $lib->gameKey && !in_array($lib->gameKey->status, ['Activated', 'Revoked'])
    );

    $activatedGameIds = $activeGames->pluck('gameKey.orderItem.version.game_id')->unique()->toArray();

    return view('Players.library.index', compact('activeGames', 'inactiveGames', 'revokedGames', 'activatedGameIds'));
}

    /**
     * Hiển thị giao diện nhập Key.
     */
    public function redeemView() {
        return view('Players.library.redeem');
    }

    /**
     * Xử lý kích hoạt Key. 
     * Hỗ trợ kích hoạt mới hoặc thay thế Key cũ nếu Key cũ bị Revoked.
     */
    public function activate(Request $request) 
    {
        $request->validate(['key_code' => 'required|string']);

        $gameKey = GameKey::where('key_code', $request->key_code)
                          ->whereIn('status', ['Pending', 'Giveaway'])
                          ->first();

        if (!$gameKey) {
            return back()->with('error', 'Mã không hợp lệ hoặc đã được kích hoạt.');
        }

        return ($gameKey->status === 'Giveaway') 
            ? $this->processGiveaway($gameKey) 
            : $this->processPurchase($gameKey);
    }

    /**
     * Logic riêng cho Key dạng Giveaway (tạo mới record vào Library).
     */
    private function processGiveaway(GameKey $gameKey) 
    {
        $playerId = Auth::guard('player')->id();
        $version = GameVersion::with('game')->find($gameKey->game_version_id);

        DB::transaction(function () use ($gameKey, $playerId, $version) {
            Library::updateOrCreate(
                ['player_id' => $playerId, 'game_key_id' => $gameKey->id],
                [
                    'game_id'    => $version->game_id,
                    'version_id' => $version->id,
                    'key_code'   => $gameKey->key_code,
                ]
            );
            $gameKey->update(['status' => 'Activated']);
        });

        return redirect()->route('library.index')->with('success', 'Kích hoạt Giveaway thành công!');
    }

    /**
     * Logic cho Key mua hàng (hỗ trợ thay thế nếu game cũ bị Revoked).
     */
    // private function processPurchase(GameKey $gameKey) 
    // {
    //     $playerId = Auth::guard('player')->id();
    //     $targetGameId = $gameKey->orderItem->version->game_id;

    //     // Tìm bản ghi Library cũ
    //     $existingEntry = Library::where('player_id', $playerId)
    //                             ->whereHas('game', fn($q) => $q->where('id', $targetGameId))
    //                             ->first();

    //     DB::transaction(function () use ($gameKey, $playerId, $existingEntry, $targetGameId) {
    //         if ($existingEntry) {
    //             // Kiểm tra Key cũ trong thư viện
    //             $oldKey = GameKey::find($existingEntry->game_key_id);

    //             if ($oldKey && $oldKey->status === 'Activated') {
    //                 throw new \Exception('Bạn đã sở hữu game này và nó đang hoạt động.');
    //             }

    //             // Thay thế bằng Key mới
    //             $existingEntry->update(['game_key_id' => $gameKey->id]);
    //         } else {
    //             // Tạo mới nếu chưa từng sở hữu
    //             Library::create([
    //                 'player_id'   => $playerId,
    //                 'game_key_id' => $gameKey->id,
    //                 'game_id'     => $targetGameId,
    //                 'version_id'  => $gameKey->orderItem->version_id,
    //             ]);
    //         }

    //         $gameKey->update(['status' => 'Activated']);
    //     });

    //     return redirect()->route('library.index')->with('success', 'Kích hoạt thành công!');
    // }

    private function processPurchase(GameKey $gameKey) 
{
    $playerId = Auth::guard('player')->id();
    $targetGameId = $gameKey->orderItem->version->game_id;

    DB::transaction(function () use ($gameKey, $playerId, $targetGameId) {
        
        // 1. DỌN DẸP: Nếu cái Key mới này đã lỡ nằm ở đâu đó trong Library của người chơi
        // thì xóa nó đi để tránh xung đột UNIQUE khi update.
        Library::where('player_id', $playerId)
               ->where('game_key_id', $gameKey->id)
               ->delete();

        // 2. TÌM BẢN GHI CŨ để thay thế
        $existingEntry = Library::where('player_id', $playerId)
                                ->where('game_id', $targetGameId)
                                ->first();

        if ($existingEntry) {
            // Kiểm tra Key cũ
            $oldKey = GameKey::find($existingEntry->game_key_id);
            
            if ($oldKey && $oldKey->status === 'Activated') {
                throw new \Exception('Bạn đã sở hữu và game này vẫn đang hoạt động.');
            }

            // Thực hiện thay thế
            $existingEntry->update(['game_key_id' => $gameKey->id]);
        } else {
            // Tạo mới nếu chưa từng sở hữu
            Library::create([
                'player_id'   => $playerId,
                'game_key_id' => $gameKey->id,
                'game_id'     => $targetGameId,
                'version_id'  => $gameKey->orderItem->version_id,
            ]);
        }

        // 3. Kích hoạt Key mới
        $gameKey->update(['status' => 'Activated']);
    });

    return redirect()->route('library.index')->with('success', 'Kích hoạt thành công!');
}
}