<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Library;

class GiftController extends Controller
{
    // Trang Danh sách quà tặng
    public function index() {
        $myId = auth()->id() ?? 1;
        $gifts = Gift::with(['sender', 'game'])
                    ->where('receiver_id', $myId)
                    ->where('status', 'Sent')
                    ->get();

        return view('Players.social.gifts', compact('gifts'));
    }

    // Xử lý Nhận quà
    public function accept($giftId) {
        $gift = Gift::findOrFail($giftId);
        $gift->update(['status' => 'Claimed']);

        Library::create([
            'player_id' => $gift->receiver_id,
            'game_key_id' => $gift->game_key_id,
            'purchased_at' => now()
        ]);

        return redirect()->back()->with('success', 'Đã nhận quà thành công!');
    }
}