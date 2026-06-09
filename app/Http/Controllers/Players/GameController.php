<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GameController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('check.banned'),
        ];
    }

    // Trang 2: Danh sách trò chơi lọc chính xác theo trạng thái (Mặc định: Active)
    public function index(Request $request)
    {
        $query = Game::with('versions')->whereHas('gameMappings');  

        // 1. Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 2. Lọc theo danh mục thể loại
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // 3. Lọc theo trạng thái game (Mặc định là 'Active')
        $currentStatus = $request->input('status', 'Active');
        
        if (in_array($currentStatus, ['Active', 'Inactive', 'ComingSoon', 'Archived'])) {
            $query->where('status', $currentStatus);
        } else {
            $currentStatus = 'Active';
            $query->where('status', 'Active');
        }

        $games = $query->paginate(9);
        $categories = Category::all();

        return view('Players.games.index', compact('games', 'categories', 'currentStatus'));
    }

    // Trang 3: Chi tiết trò chơi
    public function show($id)
    {
        $game = Game::with(['images', 'versions.promotion'])
            ->whereHas('gameMappings')  
            ->findOrFail($id);
        
        return view('Players.games.show', compact('game'));
    }

    // ================= API CẬP NHẬT NGẦM QUA AJAX =================

    // API phục vụ trang danh sách game (Trang 2)
    public function checkListUpdates(Request $request)
    {
        $query = Game::with('versions')->whereHas('gameMappings');  

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $currentStatus = $request->input('status', 'Active');
        if (in_array($currentStatus, ['Active', 'Inactive', 'ComingSoon', 'Archived'])) {
            $query->where('status', $currentStatus);
        } else {
            $query->where('status', 'Active');
        }

        $games = $query->paginate(9);

        // Giả sử bạn tách phần danh sách games hiển thị ra 1 file partial: resources/views/Players/games/partials/game-grid.blade.php
        return response()->json([
            'games_grid_html' => view('Players.partials.game-grid', compact('games'))->render()
        ]);
    }

    // API phục vụ trang chi tiết game (Trang 3)
    public function checkDetailUpdates($id)
    {
        $game = Game::with(['images', 'versions.promotion'])
            ->whereHas('gameMappings')  
            ->findOrFail($id);
        
        // Giả sử bạn tách phần thông tin chi tiết (nút mua/trạng thái) ra 1 file partial: resources/views/Players/games/partials/detail-box.blade.php
        return response()->json([
            'game_detail_html' => view('Players.partials.detail-box', compact('game'))->render()
        ]);
    }
}