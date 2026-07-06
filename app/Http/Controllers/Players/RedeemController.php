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

class RedeemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.player'),
            new Middleware('check.banned'),
        ];
    }

    /**
     * Hiển thị giao diện nhập Key.
     */
    public function redeemView() {
        return view('Players.library.redeem');
    }

    /**
     * Xử lý kích hoạt Key từ chuỗi text nhập vào form.
     */
    public function activate(Request $request) 
    {
        $request->validate(['key_code' => 'required|string']);

        $gameKey = GameKey::where('key_code', $request->key_code)
                          ->whereIn('status', ['Pending', 'Delivered', 'Giveaway'])
                          ->first();

        if (!$gameKey) {
            return back()->with('error', 'Mã không hợp lệ hoặc đã được kích hoạt.');
        }

        return ($gameKey->status === 'Giveaway') 
            ? $this->processGiveaway($gameKey) 
            : $this->processPurchase($gameKey);
    }

    /**
     * Logic riêng cho Key dạng Giveaway (Thêm mới GameKey và tạo record vào Library).
     */
/**
     * Logic riêng cho Key dạng Giveaway (Chỉ cập nhật mã có sẵn và tạo record vào Library).
     */
    private function processGiveaway(GameKey $gameKey) 
    {
        $playerId = Auth::guard('player')->id();
        
        // Vì Admin đã điền sẵn game_version_id lúc tạo Key, ta lấy trực tiếp từ $gameKey luôn
        $version = GameVersion::with('game')->find($gameKey->game_version_id);

        if (!$version) {
            return back()->with('error', 'Phiên bản game đính kèm mã này không tồn tại.');
        }

        // 1. CHẶN TRÙNG: Kiểm tra xem người chơi đã sở hữu game này trong thư viện chưa
        $alreadyOwned = Library::where('player_id', $playerId)
                               ->where('game_id', $version->game_id)
                               ->exists();

        if ($alreadyOwned) {
            return back()->with('error', 'Bạn đã sở hữu trò chơi "' . $version->game->name . '" này rồi, không thể kích hoạt thêm mã Giveaway.');
        }

        // 2. TIẾN HÀNH ĐỒNG BỘ CẢ 2 BẢNG TRONG TRANSACTION
        DB::transaction(function () use ($gameKey, $playerId, $version) {
            
            // 👉 Tác động bảng GAME_KEYS: CHỈ CẬP NHẬT mã có sẵn chứ KHÔNG TẠO MỚI nữa
            // Trạng thái chuyển sang 'Activated', giữ nguyên game_version_id ban đầu của Admin
            $gameKey->update([
                'status'                  => 'Activated',
                'fetched_at'              => now(),
                'supplier_transaction_id' => 'GIVEAWAY-PLAYER-' . $playerId,
            ]);
            
            // 👉 Tác động bảng LIBRARIES: Tạo mới bản ghi chứng nhận sở hữu game cho người chơi
            Library::create([
                'player_id'   => $playerId,
                'game_key_id' => $gameKey->id, // Dùng ngay ID của mã Key có sẵn
                'game_id'     => $version->game_id,
                'version_id'  => $version->id,
                'key_code'    => $gameKey->key_code,
            ]);
        });

        return redirect()->route('library.index')->with('success', 'Kích hoạt mã Giveaway thành công!');
    }

    /**
     * Logic cho Key mua hàng (hỗ trợ thay thế nếu game cũ bị Revoked).
     */
    private function processPurchase(GameKey $gameKey) 
    {
        $playerId = Auth::guard('player')->id();
        $targetGameId = $gameKey->orderItem->version->game_id;

        DB::transaction(function () use ($gameKey, $playerId, $targetGameId) {
            
            // 1. DỌN DẸP tránh xung đột UNIQUE khi update.
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

                // Thực hiện thay thế bằng key mới
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