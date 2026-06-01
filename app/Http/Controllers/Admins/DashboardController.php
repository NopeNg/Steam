<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Player;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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

        $revenueToday = Order::where('status', 'Completed')->whereDate('created_at', $today)->sum('total_amount');
        $revenueWeek = Order::where('status', 'Completed')->where('created_at', '>=', $startOfWeek)->sum('total_amount');
        $revenueMonth = Order::where('status', 'Completed')->where('created_at', '>=', $startOfMonth)->sum('total_amount');
        $revenueYear = Order::where('status', 'Completed')->where('created_at', '>=', $startOfYear)->sum('total_amount');

        $orders24h = Order::where('created_at', '>=', $sub24h)->count();
        $orders24hSuccess = Order::where('status', 'Completed')->where('created_at', '>=', $sub24h)->count();
        $orders24hPending = Order::where('status', 'Pending')->where('created_at', '>=', $sub24h)->count();

        $newUsers = Player::where('created_at', '>=', $sub24h)->count();
        $totalUsers = Player::count();

        $apiErrors = Order::where('status', 'API_Error')->count();

        $recentOrders = Order::with('player')->orderBy('created_at', 'desc')->take(5)->get();

        $startDate = $request->input('start_date', Carbon::now()->subDays(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $chartOrders = Order::where('status', 'Completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d/m');
            });

        $chartDates = [];
        $chartRevenues = [];

        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateString = $date->format('d/m');
            $chartDates[] = $dateString;
            $chartRevenues[] = isset($chartOrders[$dateString]) ? $chartOrders[$dateString]->sum('total_amount') : 0;
        }

        $orders = Order::with('player')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('Admins.dashboard', compact(
            'revenueToday',
            'revenueWeek',
            'revenueMonth',
            'revenueYear',
            'orders24h',
            'orders24hSuccess',
            'orders24hPending',
            'newUsers',
            'totalUsers',
            'apiErrors',
            'recentOrders',
            'startDate',
            'endDate',
            'chartDates',
            'chartRevenues',
            'orders'
        ));
    }
}