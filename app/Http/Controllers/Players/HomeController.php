<?php
namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class HomeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('check.banned'),
        ];
    }
    public function index()
    {
        // Lấy riêng biệt từng loại để không bị thiếu game
        $activeGames = $this->getActiveGames(5);
        $comingSoonGames = $this->getComingSoonGames(5);
        $topSellingGames = $this->getTopSellingGames(10);
        
        $categories = Category::all();

        return view('Players.home', compact('activeGames', 'comingSoonGames', 'topSellingGames', 'categories'));
    }

    /**
     * Endpoint AJAX để lấy dữ liệu mới ngầm dưới nền
     */
    public function checkUpdates(Request $request)
    {
        // Lấy dữ liệu mới nhất từ database
        $activeGames = $this->getActiveGames(5);
        $comingSoonGames = $this->getComingSoonGames(5);
        $topSellingGames = $this->getTopSellingGames(10);

        // CRITICAL: Để render ra HTML trả về cho AJAX, bạn nên tách các khối giao diện 
        // trong home.blade.php ra các file Blade partials (xem bước 2 bên dưới).
        return response()->json([
            'coming_soon_html' => view('Players.partials.coming-soon', compact('comingSoonGames'))->render(),
            'active_games_html' => view('Players.partials.active-games', compact('activeGames'))->render(),
            'top_selling_html' => view('Players.partials.top-selling', compact('topSellingGames'))->render(),
        ]);
    }

    /**
     * Lấy 5 game đang Active
     */
    private function getActiveGames($limit = 5)
    {
        return Game::with(['versions', 'categories'])
            ->where('status', 'Active')
            ->whereHas('gameMappings')
            ->orderBy('release_date', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Lấy 5 game đang ComingSoon
     */
    private function getComingSoonGames($limit = 5)
    {
        return Game::with('versions')
            ->where('status', 'ComingSoon')
            ->whereHas('gameMappings')
            ->orderBy('release_date', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Hàm lấy top bán chạy 10 game
     */
    private function getTopSellingGames($limit = 10)
    {
        return DB::table('games as game')
            ->join('game_versions as version', 'game.id', '=', 'version.game_id')
            ->join('order_items as item', 'version.id', '=', 'item.game_version_id')
            ->join('orders as order', 'item.order_id', '=', 'order.id')
            ->where('order.status', 'Completed')
            ->select(
                'game.id', 
                'game.name', 
                'game.cover_image', 
                DB::raw('SUM(item.quantity) as total_sold_quantity')
            )
            ->groupBy('game.id', 'game.name', 'game.cover_image')
            ->orderBy('total_sold_quantity', 'desc')
            ->take($limit)
            ->get();
    }
}