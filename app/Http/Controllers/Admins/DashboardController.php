<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Player;
use App\Models\GameKey;
use App\Models\Game;
use App\Models\OrderItem;
use App\Models\GameVersion;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();
        $sub24h = Carbon::now()->subDay();
        $sub7d = Carbon::now()->subDays(7);
        $sub30d = Carbon::now()->subDays(30);

        // ============ DATE RANGE FOR CHARTS & STATS ============
        $startDate = $request->input('start_date', Carbon::now()->subDays(13)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // ============ THỐNG KÊ NHANH (theo khoảng thời gian) ============
        $totalRevenue = Order::where('status', 'Completed')->whereBetween('created_at', [$start, $end])->sum('total_amount');
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $totalUsers = Player::whereBetween('created_at', [$start, $end])->count();
        $errorKeys = GameKey::where('status', '!=', 'Sold')
            ->where('status', '!=', 'Giveaway')
            ->where('status', '!=', 'Available')
            ->count();
        $revenueWeek = Order::where('status', 'Completed')->where('created_at', '>=', $startOfWeek)->sum('total_amount');
        $revenueMonth = Order::where('status', 'Completed')->where('created_at', '>=', $startOfMonth)->sum('total_amount');

        $newUsers = Player::where('created_at', '>=', $sub24h)->count();
        $recentOrders = Order::with('player')->orderBy('created_at', 'desc')->take(5)->get();

        // ============ TOP GAME BÁN CHẠY ============
        $topGames = GameKey::select(
                'game_versions.game_id',
                'game_versions.version_name',
                DB::raw('count(*) as total_sold')
            )
            ->join('order_items', 'game_keys.order_item_id', '=', 'order_items.id')
            ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
            ->where('game_keys.status', 'Sold')
            ->where('game_keys.fetched_at', '>=', $startOfWeek)
            ->groupBy('game_versions.game_id', 'game_versions.version_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->game = Game::find($item->game_id);
                return $item;
            });

        // ============ DATE RANGE FOR CHARTS ============
        $dateRange = CarbonPeriod::create($startDate, $endDate);

        // ------------------- CHART 1: DOANH THU -------------------
        $revenueByDay = Order::where('status', 'Completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get()
            ->groupBy(fn($d) => Carbon::parse($d->created_at)->format('d/m'));

        $chart1Labels = [];
        $chart1Revenue = [];  // Line data
        $chart1Orders = [];   // Bar data (số đơn hoàn thành)
        foreach ($dateRange as $date) {
            $key = $date->format('d/m');
            $chart1Labels[] = $key;
            $dayOrders = isset($revenueByDay[$key]) ? $revenueByDay[$key] : collect();
            $chart1Revenue[] = $dayOrders->sum('total_amount');
            $chart1Orders[] = $dayOrders->count();
        }

        // ------------------- CHART 2: NGƯỜI DÙNG MỚI -------------------
        $usersByDay = Player::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get()
            ->groupBy(fn($d) => Carbon::parse($d->created_at)->format('d/m'));

        $chart2Labels = [];
        $chart2NewUsers = [];
        $chart2Cumulative = [];
        $cumulative = Player::whereDate('created_at', '<', $startDate)->count();
        foreach ($dateRange as $date) {
            $key = $date->format('d/m');
            $chart2Labels[] = $key;
            $count = isset($usersByDay[$key]) ? $usersByDay[$key]->count() : 0;
            $chart2NewUsers[] = $count;
            $cumulative += $count;
            $chart2Cumulative[] = $cumulative;
        }

        // ------------------- CHART 3: ĐƠN HÀNG THEO TRẠNG THÁI -------------------
        $ordersByDay = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get()
            ->groupBy(fn($d) => Carbon::parse($d->created_at)->format('d/m'));

        $chart3Labels = [];
        $chart3Completed = [];
        $chart3Pending = [];
        $chart3ApiError = [];
        $chart3Failed = [];
        foreach ($dateRange as $date) {
            $key = $date->format('d/m');
            $chart3Labels[] = $key;
            $dayOrders = isset($ordersByDay[$key]) ? $ordersByDay[$key] : collect();
            $chart3Completed[] = $dayOrders->where('status', 'Completed')->count();
            $chart3Pending[] = $dayOrders->where('status', 'Pending')->count();
            $chart3ApiError[] = $dayOrders->where('status', 'API_Error')->count();
            $chart3Failed[] = $dayOrders->where('status', 'Failed')->count();
        }

        // ------------------- CHART 4: PHÂN BỔ PHƯƠNG THỨC THANH TOÁN -------------------
        $paymentMethods = Order::select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as total_revenue'))
            ->where('status', 'Completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('payment_method')
            ->get();

        $chart4Labels = $paymentMethods->pluck('payment_method')->map(fn($v) => $v ?? 'Không rõ')->toArray();
        $chart4Counts = $paymentMethods->pluck('total')->toArray();
        $chart4Revenues = $paymentMethods->pluck('total_revenue')->toArray();

        // ------------------- CHART 5: TOP GAMES (BAR) -------------------
        $topGamesAll = GameKey::select(
                'game_versions.game_id',
                DB::raw('count(*) as total_sold'),
                DB::raw('sum(order_items.price_at_purchase) as total_revenue')
            )
            ->join('order_items', 'game_keys.order_item_id', '=', 'order_items.id')
            ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
            ->where('game_keys.status', 'Sold')
            ->whereDate('game_keys.fetched_at', '>=', $startDate)
            ->whereDate('game_keys.fetched_at', '<=', $endDate)
            ->groupBy('game_versions.game_id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $game = Game::find($item->game_id);
                $item->game_name = $game ? $game->name : 'Không xác định';
                return $item;
            });

        $chart5Labels = $topGamesAll->pluck('game_name')->toArray();
        $chart5Sold = $topGamesAll->pluck('total_sold')->toArray();
        $chart5Revenue = $topGamesAll->pluck('total_revenue')->toArray();

        // ============ ORDERS TABLE ============
        $orders = Order::with('player')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('Admins.dashboard', compact(
            'revenueWeek', 'revenueMonth',
            'newUsers', 'totalUsers',
            'errorKeys',
            'recentOrders',
            'totalOrders', 'totalRevenue',
            'startDate', 'endDate',
            'topGames',
            'chart1Labels', 'chart1Revenue', 'chart1Orders',
            'chart2Labels', 'chart2NewUsers', 'chart2Cumulative',
            'chart3Labels', 'chart3Completed', 'chart3Pending', 'chart3ApiError', 'chart3Failed',
            'chart4Labels', 'chart4Counts', 'chart4Revenues',
            'chart5Labels', 'chart5Sold', 'chart5Revenue',
            'orders'
        ));
    }
}