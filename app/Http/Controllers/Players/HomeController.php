<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::with(['versions', 'categories'])
            ->where('status', 'Active')
            ->orderBy('release_date', 'desc')
            ->take(6)
            ->get();
            
        $categories = Category::all();

        return view('Players.home', compact('games', 'categories'));
    }
}