<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Friendship;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    // Không cần middleware ở đây nếu bạn đã bọc route trong web.php
    
    // Trang Dashboard Social tổng hợp
    public function index() {
        $myId = Auth::guard('player')->id();

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
        $myId = Auth::guard('player')->id();
        $searchTerm = $request->input('search');
        
        $searchResults = null;
        $sentRequests = []; 
        $acceptedFriends = []; 

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

    public function searchFriend(Request $request) {
        return $this->friendsIndex($request);
    }

    // Xử lý Gửi lời mời kết bạn
    public function sendRequest($id) {
        // Kiểm tra xem đã tồn tại lời mời hoặc đã là bạn chưa để tránh trùng lặp
        $exists = Friendship::where(function($q) use ($id) {
            $q->where('sender_id', Auth::guard('player')->id())->where('receiver_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('sender_id', $id)->where('receiver_id', Auth::guard('player')->id());
        })->exists();

        if (!$exists) {
            Friendship::create([
                'sender_id' => Auth::guard('player')->id(),
                'receiver_id' => $id,
                'status' => 'Pending'
            ]);
            return back()->with('success', 'Đã gửi lời mời kết bạn!');
        }
        
        return back()->with('error', 'Đã tồn tại lời mời hoặc đã là bạn bè.');
    }
}