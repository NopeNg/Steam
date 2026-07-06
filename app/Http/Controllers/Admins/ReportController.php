<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Player;
use App\Models\GameKey;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\OrderItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Trang báo cáo tổng hợp
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(29)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $tab = $request->input('tab', 'revenue');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        $dateRange = CarbonPeriod::create($startDate, $endDate);

        // ==================== 1. BÁO CÁO DOANH THU & ĐƠN HÀNG ====================
        
        // Doanh thu theo ngày
        $revenueByDay = Order::where('status', 'Completed')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn($d) => Carbon::parse($d->created_at)->format('d/m'));

        $chartRevenueLabels = [];
        $chartRevenueData = [];
        $chartOrderCountData = [];
        foreach ($dateRange as $date) {
            $key = $date->format('d/m');
            $chartRevenueLabels[] = $key;
            $dayOrders = $revenueByDay[$key] ?? collect();
            $chartRevenueData[] = $dayOrders->sum('total_amount');
            $chartOrderCountData[] = $dayOrders->count();
        }

        // Tổng quan doanh thu
        $totalRevenue = Order::where('status', 'Completed')->whereBetween('created_at', [$start, $end])->sum('total_amount');
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $completedOrders = Order::where('status', 'Completed')->whereBetween('created_at', [$start, $end])->count();
        $cancelledOrders = Order::where('status', 'Failed')->whereBetween('created_at', [$start, $end])->count();
        $pendingOrders = Order::where('status', 'Pending')->whereBetween('created_at', [$start, $end])->count();
        $apiErrorOrders = Order::where('status', 'API_Error')->whereBetween('created_at', [$start, $end])->count();

        // AOV (Giá trị đơn hàng trung bình)
        $avgOrderValue = $completedOrders > 0 
            ? Order::where('status', 'Completed')->whereBetween('created_at', [$start, $end])->avg('total_amount') 
            : 0;

        // Trạng thái đơn hàng (Donut chart)
        $orderStatusData = [
            $completedOrders, $pendingOrders, $apiErrorOrders, $cancelledOrders
        ];
        $orderStatusLabels = ['Hoàn thành', 'Chờ xử lý', 'lỗi API', 'Thất bại'];

        // Phương thức thanh toán
        $paymentMethods = Order::select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as total_revenue'))
            ->where('status', 'Completed')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('payment_method')
            ->get();
        $paymentLabels = $paymentMethods->pluck('payment_method')->map(fn($v) => $v ?? 'Không rõ')->toArray();
        $paymentCounts = $paymentMethods->pluck('total')->toArray();
        $paymentRevenues = $paymentMethods->pluck('total_revenue')->toArray();

        // Doanh thu theo sản phẩm (Top game bán chạy) - dựa trên đơn hàng đã hoàn thành
        $topGamesRevenue = OrderItem::select(
                'game_versions.game_id',
                'games.cover_image',
                DB::raw('COUNT(order_items.id) as total_sold'),
                DB::raw('SUM(order_items.price_at_purchase * order_items.quantity) as total_revenue')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
            ->join('games', 'game_versions.game_id', '=', 'games.id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('game_versions.game_id', 'games.cover_image')
            ->orderByDesc('total_sold')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $game = Game::find($item->game_id);
                $item->game_name = $game ? $game->name : 'Không xác định';
                $item->game_image = $item->cover_image;
                return $item;
            });

        // ==================== 2. BÁO CÁO KHÁCH HÀNG ====================
        
        // Khách hàng mới
        $newUsersCount = Player::whereBetween('created_at', [$start, $end])->count();
        
        // Khách hàng mới theo ngày
        $newUsersByDay = Player::whereBetween('created_at', [$start, $end])->get()
            ->groupBy(fn($d) => Carbon::parse($d->created_at)->format('d/m'));
        $chartNewUsersLabels = [];
        $chartNewUsersData = [];
        foreach ($dateRange as $date) {
            $key = $date->format('d/m');
            $chartNewUsersLabels[] = $key;
            $chartNewUsersData[] = ($newUsersByDay[$key] ?? collect())->count();
        }

        // Top khách hàng VIP (tổng chi tiêu cao nhất)
        $topCustomers = Player::select('players.id', 'players.username', 'players.email',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_amount) as total_spent')
            )
            ->join('orders', 'players.id', '=', 'orders.player_id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('players.id', 'players.username', 'players.email')
            ->orderByDesc('total_spent')
            ->take(15)
            ->get();

        // Khách hàng mới vs cũ (có đơn hàng trong khoảng vs có đơn hàng trước đó)
        $newCustomerOrders = Order::where('status', 'Completed')
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('player_id', function($q) use ($start) {
                $q->select('player_id')->from('orders')
                    ->where('status', 'Completed')
                    ->where('created_at', '<', $start)
                    ->distinct();
            })
            ->count();

        $returningCustomerOrders = $completedOrders - $newCustomerOrders;
        $newCustomerCount = $completedOrders - $newCustomerOrders;

        // ==================== 3. BÁO CÁO GIỎ HÀNG ====================
        $totalCartItems = \App\Models\CartItem::count();

        // Top game được thêm vào giỏ hàng nhiều nhất
        $topCartGames = \App\Models\CartItem::select(
                'game_versions.game_id',
                DB::raw('COUNT(cart_items.id) as total_added'),
                DB::raw('SUM(cart_items.quantity) as total_quantity')
            )
            ->join('game_versions', 'cart_items.game_version_id', '=', 'game_versions.id')
            ->whereBetween('cart_items.added_at', [$start, $end])
            ->groupBy('game_versions.game_id')
            ->orderByDesc('total_added')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $game = \App\Models\Game::find($item->game_id);
                $item->game_name = $game ? $game->name : 'Không xác định';
                return $item;
            });

        // Thể loại game được quan tâm trong giỏ hàng
        $topCartCategories = \App\Models\CartItem::select(
                'categories.id',
                'categories.category_name',
                DB::raw('COUNT(cart_items.id) as total_added'),
                DB::raw('SUM(cart_items.quantity) as total_quantity')
            )
            ->join('game_versions', 'cart_items.game_version_id', '=', 'game_versions.id')
            ->join('game_categories', 'game_versions.game_id', '=', 'game_categories.game_id')
            ->join('categories', 'game_categories.category_id', '=', 'categories.id')
            ->whereBetween('cart_items.added_at', [$start, $end])
            ->groupBy('categories.id', 'categories.category_name')
            ->orderByDesc('total_added')
            ->take(10)
            ->get();

        // ==================== 4. BÁO CÁO KHO HÀNG (KEY) ====================
        $totalKeys = GameKey::count();
        $soldKeys = GameKey::where('status', 'Sold')->count();
        $errorKeys = GameKey::where('status', '!=', 'Sold')
            ->where('status', '!=', 'Giveaway')
            ->where('status', '!=', 'Available')
            ->count();

        $linkedGames = Game::has('gameMappings')->get();
        $unlinkedGames = Game::doesntHave('gameMappings')->get();

        return view('Admins.reports.index', compact(
            'startDate', 'endDate', 'tab',
            'chartRevenueLabels', 'chartRevenueData', 'chartOrderCountData',
            'totalRevenue', 'totalOrders', 'completedOrders', 'cancelledOrders',
            'pendingOrders', 'apiErrorOrders', 'avgOrderValue',
            'orderStatusData', 'orderStatusLabels',
            'paymentLabels', 'paymentCounts', 'paymentRevenues',
            'topGamesRevenue',
            'newUsersCount', 'chartNewUsersLabels', 'chartNewUsersData',
            'topCustomers',
            'newCustomerOrders', 'returningCustomerOrders', 'newCustomerCount',
            'totalCartItems',
            'topCartGames', 'topCartCategories',
            'totalKeys', 'soldKeys', 'errorKeys',
            'linkedGames', 'unlinkedGames'
        ));
    }

    /**
     * Xuất báo cáo ra CSV
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'revenue');
        $startDate = $request->input('start_date', Carbon::now()->subDays(29)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $filename = "report_{$type}_{$startDate}_{$endDate}.csv";

        $content = $this->generateCsvContent($type, $start, $end);

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function generateCsvContent($type, $start, $end)
    {
        $output = fopen('php://temp', 'r+');

        switch ($type) {
            case 'revenue':
                fputcsv($output, ['Ngày', 'Doanh thu', 'Số đơn hàng']);
                $data = Order::where('status', 'Completed')
                    ->whereBetween('created_at', [$start, $end])
                    ->get()
                    ->groupBy(fn($d) => Carbon::parse($d->created_at)->format('d/m/Y'));
                foreach ($data as $day => $orders) {
                    fputcsv($output, [$day, $orders->sum('total_amount'), $orders->count()]);
                }
                break;

            case 'orders':
                fputcsv($output, ['Mã đơn', 'Khách hàng', 'Ngày tạo', 'Tổng tiền', 'Phương thức', 'Trạng thái']);
                $orders = Order::with('player')->whereBetween('created_at', [$start, $end])->orderByDesc('created_at')->get();
                foreach ($orders as $o) {
                    fputcsv($output, [
                        '#ORD-'.$o->id,
                        $o->player->username ?? 'N/A',
                        $o->created_at->format('d/m/Y H:i'),
                        $o->total_amount,
                        $o->payment_method ?? 'N/A',
                        $o->status
                    ]);
                }
                break;

            case 'customers':
                fputcsv($output, ['Username', 'Email', 'Số đơn hàng', 'Tổng chi tiêu']);
                $players = Player::select('players.id', 'players.username', 'players.email', DB::raw('COUNT(orders.id) as order_count'), DB::raw('COALESCE(SUM(orders.total_amount), 0) as total_spent'))
                    ->leftJoin('orders', function($q) use ($start, $end) {
                        $q->on('players.id', '=', 'orders.player_id')
                          ->where('orders.status', '=', 'Completed')
                          ->whereBetween('orders.created_at', [$start, $end]);
                    })
                    ->groupBy('players.id', 'players.username', 'players.email')
                    ->orderByDesc('total_spent')
                    ->get();
                foreach ($players as $p) {
                    fputcsv($output, [$p->username, $p->email, $p->order_count, $p->total_spent]);
                }
                break;

            case 'top_games':
                fputcsv($output, ['Game', 'Số lượng đã bán', 'Doanh thu']);
                $games = OrderItem::select('game_versions.game_id', DB::raw('COUNT(order_items.id) as total_sold'), DB::raw('SUM(order_items.price_at_purchase * order_items.quantity) as total_revenue'))
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
                    ->where('orders.status', 'Completed')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->groupBy('game_versions.game_id')
                    ->orderByDesc('total_sold')
                    ->orderByDesc('total_revenue')
                    ->take(5)
                    ->get();
                foreach ($games as $g) {
                    $game = Game::find($g->game_id);
                    fputcsv($output, [$game->name ?? 'N/A', $g->total_sold, $g->total_revenue]);
                }
                break;

            case 'inventory':
                fputcsv($output, ['Game', 'Phiên bản', 'Trạng thái', 'Số lượng']);
                $keys = GameKey::select('game_versions.game_id', 'game_versions.version_name', 'game_keys.status', DB::raw('count(*) as count'))
                    ->join('order_items', 'game_keys.order_item_id', '=', 'order_items.id')
                    ->join('game_versions', 'order_items.game_version_id', '=', 'game_versions.id')
                    ->groupBy('game_versions.game_id', 'game_versions.version_name', 'game_keys.status')
                    ->get();
                foreach ($keys as $k) {
                    $game = Game::find($k->game_id);
                    fputcsv($output, [$game->name ?? 'N/A', $k->version_name, $k->status, $k->count]);
                }
                break;

            case 'vip_customers':
                fputcsv($output, ['Username', 'Email', 'Số đơn', 'Tổng chi tiêu']);
                $vipPlayers = Player::select('players.id', 'players.username', 'players.email', DB::raw('COUNT(orders.id) as total_orders'), DB::raw('SUM(orders.total_amount) as total_spent'))
                    ->join('orders', 'players.id', '=', 'orders.player_id')
                    ->where('orders.status', 'Completed')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->groupBy('players.id', 'players.username', 'players.email')
                    ->orderByDesc('total_spent')
                    ->get();
                foreach ($vipPlayers as $p) {
                    fputcsv($output, [$p->username, $p->email, $p->total_orders, $p->total_spent]);
                }
                break;
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return "\xEF\xBB\xBF" . $csv;
    }
}