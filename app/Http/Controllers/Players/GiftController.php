<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Library;
use Illuminate\Support\Facades\Auth; // Thêm Facade Auth

class GiftController extends Controller
{
    // Trang Danh sách quà tặng
    public function index() {
        // Lấy ID từ guard 'player'
        $myId = Auth::guard('player')->id();
        
        $gifts = Gift::with(['sender', 'game'])
                    ->where('receiver_id', $myId)
                    ->where('status', 'Sent')
                    ->get();

        return view('Players.social.gifts', compact('gifts'));
    }

    // Xử lý Nhận quà
    public function accept($giftId) {
        // Kiểm tra gift có tồn tại VÀ đúng là của người đang đăng nhập không
        $gift = Gift::where('id', $giftId)
                    ->where('receiver_id', Auth::guard('player')->id())
                    ->where('status', 'Sent')
                    ->firstOrFail();

        // Cập nhật trạng thái
        $gift->update(['status' => 'Claimed']);

        // Thêm vào thư viện
        Library::create([
            'player_id' => $gift->receiver_id,
            'game_key_id' => $gift->game_key_id,
            'purchased_at' => now()
        ]);

        return redirect()->back()->with('success', 'Đã nhận quà thành công!');
    }
}