<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $players = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('Admins.players.index', compact('players'));
    }

    public function toggleStatus($id)
    {
        $player = Player::findOrFail($id);
        $newStatus = $player->status === 'Active' ? 'Banned' : 'Active';
        $player->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái tài khoản thành công!');
    }
}