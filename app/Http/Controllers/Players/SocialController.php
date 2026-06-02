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
    // Lấy dữ liệu tổng quan cho trang Social Dashboard
    public function index() {
        $myId = Auth::guard('player')->id();

        $friends = Friendship::with(['sender', 'receiver'])
            ->where('status', 'Accepted')
            ->where(function ($query) use ($myId) {
                $query->where('sender_id', $myId)->orWhere('receiver_id', $myId);
            })->get();

        $gifts = Gift::with(['sender', 'game'])
            ->where('receiver_id', $myId)
            ->where('status', 'Sent')
            ->get();

        return view('Players.social.index', compact('gifts', 'friends', 'myId'));
    }

    // Trang Bạn bè: Kết hợp danh sách bạn, kết quả tìm kiếm và lời mời chờ duyệt
    public function friendsIndex(Request $request) {
        $myId = Auth::guard('player')->id();
        $searchTerm = $request->input('search');
        $searchResults = null;

        // 1. Tìm kiếm người chơi
        if ($searchTerm) {
            $searchResults = Player::where('username', 'like', "%$searchTerm%")
                                   ->where('id', '!=', $myId)
                                   ->get();
        }

        // 2. Lấy danh sách bạn bè (Đã chấp nhận)
        $friends = Friendship::with(['sender', 'receiver'])
            ->where('status', 'Accepted')
            ->where(function ($q) use ($myId) {
                $q->where('sender_id', $myId)->orWhere('receiver_id', $myId);
            })->get();

        // 3. Lấy lời mời kết bạn đang chờ (Mình là người nhận)
        $incomingRequests = Friendship::with('sender')
            ->where('receiver_id', $myId)
            ->where('status', 'Pending')
            ->get();

        return view('Players.social.friends', compact('friends', 'incomingRequests', 'searchResults', 'myId'));
    }

    // Route xử lý tìm kiếm
    public function searchFriend(Request $request) {
        return $this->friendsIndex($request);
    }

    // Gửi lời mời kết bạn
    public function sendRequest($id) {
        $myId = Auth::guard('player')->id();
        if ($id == $myId) return back()->with('error', 'Không thể tự kết bạn.');

        $exists = Friendship::where(function($q) use ($id, $myId) {
            $q->where('sender_id', $myId)->where('receiver_id', $id);
        })->orWhere(function($q) use ($id, $myId) {
            $q->where('sender_id', $id)->where('receiver_id', $myId);
        })->exists();

        if ($exists) return back()->with('error', 'Đã tồn tại mối quan hệ.');

        Friendship::create(['sender_id' => $myId, 'receiver_id' => $id, 'status' => 'Pending']);
        return back()->with('success', 'Đã gửi lời mời kết bạn!');
    }

    // Chấp nhận lời mời
    public function acceptRequest($id) {
        $myId = Auth::guard('player')->id();
        Friendship::where('sender_id', $id)
            ->where('receiver_id', $myId)
            ->where('status', 'Pending')
            ->update(['status' => 'Accepted']);
            
        return back()->with('success', 'Đã chấp nhận lời mời.');
    }

    // Hủy lời mời hoặc xóa bạn
    public function removeFriend($id) {
        $myId = Auth::guard('player')->id();
        Friendship::where(function($q) use ($id, $myId) {
            $q->where('sender_id', $myId)->where('receiver_id', $id);
        })->orWhere(function($q) use ($id, $myId) {
            $q->where('sender_id', $id)->where('receiver_id', $myId);
        })->delete();

        return back()->with('success', 'Đã xóa mối quan hệ.');
    }
}