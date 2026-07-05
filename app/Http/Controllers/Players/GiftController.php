<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Gift, GameKey, Friendship, Player};
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class GiftController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.player'),
            new Middleware('check.banned'),
        ];
    }

    // Hiển thị form gửi quà - lấy key từ thư viện của người dùng
    public function showSendForm($friendId) {
        $friend = Player::findOrFail($friendId);
        $myId = Auth::guard('player')->id();

        $myGames = \App\Models\Library::with(['gameKey.orderItem.version.game'])
            ->where('player_id', $myId)
            ->whereHas('gameKey', function($q) {
                $q->whereIn('status', ['Pending', 'Giveaway', 'Delivered']);
            })
            ->get()
            ->map(function($lib) {
                $version = $lib->gameKey->orderItem->version ?? $lib->gameVersion;
                $game = $version ? $version->game : $lib->game;
                return (object)[
                    'key_id' => $lib->gameKey->id,
                    'game_name' => $game ? $game->name : 'Game không xác định',
                    'version_name' => $version ? $version->version_name : 'N/A',
                ];
            });

        return view('Players.social.send_gift', compact('friend', 'myGames'));
    }

    // Xử lý gửi quà
    public function send(Request $request) {
        $request->validate(['friend_id' => 'required', 'key_id' => 'required']);
        
        $myId = Auth::guard('player')->id();
        
        // 1. Kiểm tra bạn bè
        $isFriend = Friendship::where('status', 'Accepted')
            ->where(function($q) use ($myId, $request) {
                $q->where(function($sub) use ($myId, $request) {
                    $sub->where('sender_id', $myId)->where('receiver_id', $request->friend_id);
                })->orWhere(function($sub) use ($myId, $request) {
                    $sub->where('sender_id', $request->friend_id)->where('receiver_id', $myId);
                });
            })->exists();

        if (!$isFriend) return back()->with('error', 'Chỉ tặng được cho bạn bè.');

        // 2. NGĂN CHẶN LỖI UNIQUE: Kiểm tra xem key này đã được gửi chưa
        $existingGift = Gift::where('game_key_id', $request->key_id)->first();
        if ($existingGift) {
            return back()->with('error', 'Mã quà tặng này đã được gửi đi trước đó.');
        }

        // 3. Tạo bản ghi quà tặng (giữ game trong thư viện người gửi, chỉ gửi key sang hộp quà người nhận)
        Gift::create([
            'sender_id' => $myId,
            'receiver_id' => $request->friend_id,
            'game_key_id' => $request->key_id,
            'status' => 'Sent'
        ]);

        return redirect()->route('gifts.index')->with('success', 'Gửi quà thành công!');
    }

    // Hiển thị danh sách quà nhận được
    public function index() {
        $gifts = Gift::where('receiver_id', Auth::guard('player')->id())
            ->join('game_keys', 'gifts.game_key_id', '=', 'game_keys.id')
            ->join('order_items', 'game_keys.order_item_id', '=', 'order_items.id')
            ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
            ->join('games', 'game_versions.game_id', '=', 'games.id')
            ->join('players as senders', 'gifts.sender_id', '=', 'senders.id')
            ->select('gifts.*', 'games.name as game_name', 'games.cover_image', 'senders.username as sender_name')
            ->get();

        return view('Players.social.gifts', compact('gifts'));
    }

    // BỔ SUNG: Phương thức accept - nhận quà vào thư viện
    public function accept($id) {
        $myId = Auth::guard('player')->id();
        $gift = Gift::where('id', $id)->where('receiver_id', $myId)->firstOrFail();
        
        // 1. Cập nhật trạng thái quà tặng
        $gift->update(['status' => 'Accepted']);

        // 2. Chuyển quyền sở hữu GameKey cho người nhận
        $gameKey = \App\Models\GameKey::find($gift->game_key_id);
        if ($gameKey) {
            $gameKey->update(['status' => 'Activated']);

            // 3. Tạo Library cho người nhận với đầy đủ thông tin
            $orderItem = $gameKey->orderItem;
            $version = $orderItem ? $orderItem->version : null;
            $gameId = $version ? $version->game_id : null;
            $versionId = $version ? $version->id : null;

            \App\Models\Library::create([
                'player_id' => $myId,
                'game_key_id' => $gameKey->id,
                'game_id' => $gameId,
                'version_id' => $versionId,
                'key_code' => $gameKey->key_code,
                'purchased_at' => now(),
            ]);
        }

        return back()->with('success', 'Bạn đã nhận quà! Trò chơi đã vào thư viện.');
    }

    // BỔ SUNG: Phương thức reject
   public function reject($id) {
    // 1. Tìm món quà và đảm bảo người nhận là người đang đăng nhập
    $gift = Gift::where('id', $id)->where('receiver_id', Auth::guard('player')->id())->firstOrFail();
    
    // 2. Cập nhật trạng thái món quà là Rejected
    $gift->update(['status' => 'Rejected']);

    // 3. HOÀN LẠI QUÀ: Key về thư viện của người gửi
    $gameKey = GameKey::find($gift->game_key_id);
    if ($gameKey) {
        $gameKey->update(['status' => 'Delivered']);

        // Tạo Library cho người gửi nếu chưa có
        $senderId = $gift->sender_id;
        $exists = \App\Models\Library::where('player_id', $senderId)
            ->where('game_key_id', $gameKey->id)
            ->exists();

        if (!$exists) {
            $orderItem = $gameKey->orderItem;
            $version = $orderItem ? $orderItem->version : null;
            $gameId = $version ? $version->game_id : null;
            $versionId = $version ? $version->id : null;

            \App\Models\Library::create([
                'player_id' => $senderId,
                'game_key_id' => $gameKey->id,
                'game_id' => $gameId,
                'version_id' => $versionId,
                'key_code' => $gameKey->key_code,
                'purchased_at' => now(),
            ]);
        }
    }

    return back()->with('success', 'Món quà đã bị từ chối và được hoàn trả cho người gửi.');
}
}