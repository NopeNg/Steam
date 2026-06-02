<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách game kèm theo phiên bản để hiển thị giá tiền ngoài trang chủ
        // Chỉ lấy game có API provider (gameMappings tồn tại)
        $games = Game::with('versions')
            ->where('status', 'Active')
            ->whereHas('gameMappings')  // Chỉ game có API provider
            ->orderBy('release_date', 'desc')
            ->take(6)
            ->get();
        $categories = Category::all();

        return view('Players.home', compact('games', 'categories'));
    }
}