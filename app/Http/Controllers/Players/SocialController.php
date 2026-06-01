<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Friendship;
use App\Models\Library;
use App\Models\Player;

class SocialController extends Controller
{
    // Trang Dashboard Social tổng hợp
    public function index() {
        $myId = auth()->id() ?? 1;

        $friends = Friendship::where('status', 'Accepted')
            ->where(function ($query) use ($myId) {
                $query->where('sender_id', $myId)
                      ->orWhere('receiver_id', $myId);
            })->get();

        $gifts = Gift::with(['sender', 'game'])
                    ->where('receiver_id', $myId)
                    ->where('status', 'Sent')
                    ->get();

        return view('Players.social.index', compact('gifts', 'friends', 'myId'));
    }

    // Trang Danh sách bạn bè & Tìm kiếm
public function friendsIndex(Request $request) {
    $myId = auth()->id() ?? 1;
    $searchTerm = $request->input('search');
    
    $searchResults = null;
    $sentRequests = []; // Danh sách các ID bạn đã gửi lời mời
    $acceptedFriends = []; // Danh sách các ID đã là bạn

    if ($searchTerm) {
        $searchResults = Player::where('username', 'like', "%$searchTerm%")
                               ->where('id', '!=', $myId)
                               ->get();
        
        // Lấy tất cả các quan hệ giữa mình và những người tìm được
        $friendships = Friendship::where('sender_id', $myId)
            ->orWhere('receiver_id', $myId)
            ->get();
            
        foreach ($friendships as $f) {
            $otherId = ($f->sender_id == $myId) ? $f->receiver_id : $f->sender_id;
            if ($f->status == 'Accepted') $acceptedFriends[] = $otherId;
            if ($f->status == 'Pending') $sentRequests[] = $otherId;
        }
    }

    $friends = Friendship::where('status', 'Accepted')
        ->where(function ($q) use ($myId) {
            $q->where('sender_id', $myId)->orWhere('receiver_id', $myId);
        })->with(['sender', 'receiver'])->get();

    return view('Players.social.friends', compact('friends', 'searchResults', 'myId', 'sentRequests', 'acceptedFriends'));
}

    // [MỚI] Hàm xử lý tìm kiếm bạn bè
    public function searchFriend(Request $request) {
        return $this->friendsIndex($request);
    }

    // // Trang Danh sách quà tặng
    // public function giftsIndex() {
    //     $myId = auth()->id() ?? 1;
    //     $gifts = Gift::with(['sender', 'game'])
    //                 ->where('receiver_id', $myId)
    //                 ->where('status', 'Sent')
    //                 ->get();

    //     return view('Players.social.gifts', compact('gifts'));
    // }

    // Xử lý Gửi lời mời kết bạn
    public function sendRequest($id) {
        Friendship::create([
            'sender_id' => auth()->id() ?? 1,
            'receiver_id' => $id,
            'status' => 'Pending'
        ]);
        return back()->with('success', 'Đã gửi lời mời kết bạn!');
    }

    // // Xử lý Nhận quà
    // public function acceptGift($giftId) {
    //     $gift = Gift::findOrFail($giftId);
    //     $gift->update(['status' => 'Claimed']);

    //     Library::create([
    //         'player_id' => $gift->receiver_id,
    //         'game_key_id' => $gift->game_key_id,
    //         'purchased_at' => now()
    //     ]);

    //     return redirect()->back()->with('success', 'Đã nhận quà thành công!');
    // }
}