<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // Trang 2: Danh sách trò chơi có bộ lọc search & category
    public function index(Request $request)
    {
        $query = Game::with('versions')->where('status', 'Active');

        // Lọc theo từ khóa tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Lọc theo danh mục thể loại
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $games = $query->paginate(9);
        $categories = Category::all();

        return view('Players.games.index', compact('games', 'categories'));
    }

    // Trang 3: Chi tiết trò chơi
    public function show($id)
    {
        // Lấy thông tin game cùng với hình ảnh screenshot và các gói phiên bản (Standard, Deluxe...)
        $game = Game::with(['images', 'versions.promotion'])->findOrFail($id);
        
        return view('Players.games.show', compact('game'));
    }
}