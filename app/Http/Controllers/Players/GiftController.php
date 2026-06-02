<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Gift, GameKey, Friendship, Player};

class GiftController extends Controller
{
    // Hiển thị form gửi quà
    public function showSendForm($friendId) {
        $friend = Player::findOrFail($friendId);
        $myId = Auth::guard('player')->id();

        $myGames = GameKey::join('order_items', 'game_keys.order_item_id', '=', 'order_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
            ->join('games', 'game_versions.game_id', '=', 'games.id')
            ->where('orders.player_id', $myId)
            ->where('game_keys.status', 'Delivered')
            ->select('game_keys.id as key_id', 'games.name as game_name', 'game_versions.version_name')
            ->get();
            
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

        // 3. Tạo bản ghi
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
            ->select('gifts.*', 'games.name as game_name', 'senders.username as sender_name')
            ->get();

        return view('Players.social.gifts', compact('gifts'));
    }

    // BỔ SUNG: Phương thức accept
public function accept($id) {
    $myId = Auth::guard('player')->id();
    $gift = Gift::where('id', $id)->where('receiver_id', $myId)->firstOrFail();
    
    // 1. Cập nhật trạng thái quà tặng
    $gift->update(['status' => 'Accepted']);

    // 2. Chuyển quyền sở hữu GameKey cho người nhận
    $gameKey = \App\Models\GameKey::find($gift->game_key_id);
    if ($gameKey) {
        $gameKey->update([
            'player_id' => $myId,
            'status' => 'Activated' // Đổi sang Activated để filter của bạn hoạt động
        ]);

        // 3. QUAN TRỌNG: Tạo bản ghi vào bảng 'libraries' để hiển thị trong thư viện
        \App\Models\Library::create([
            'player_id' => $myId,
            'game_key_id' => $gameKey->id,
            // Thêm các cột bắt buộc khác nếu có (ví dụ: created_at)
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

    // 3. HOÀN LẠI QUÀ: Cập nhật GameKey để người gửi có thể sử dụng/tặng lại
    // Tìm game key tương ứng
    $gameKey = GameKey::find($gift->game_key_id);
    if ($gameKey) {
        // Cập nhật lại status của key thành 'Delivered' (hoặc trạng thái bạn dùng cho key khả dụng)
        $gameKey->update(['status' => 'Delivered']);
    }

    return back()->with('success', 'Món quà đã bị từ chối và được hoàn trả cho người gửi.');
}
}