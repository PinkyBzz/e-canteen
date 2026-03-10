<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistory;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMenus      = Menu::count();
        $availableMenus  = Menu::where('status', 'available')->count();
        $totalUsers      = User::where('role', 'user')->count();
        $todayOrders     = Order::whereDate('created_at', today())->count();
        $todayRevenue    = Order::whereDate('created_at', today())
                                ->whereIn('status', ['ready', 'completed'])
                                ->sum('total_price');
        $pendingOrders   = Order::whereIn('status', ['pending', 'preparing'])->count();

        // Top-up stats
        $totalTopup      = BalanceHistory::where('type', 'credit')->sum('amount');
        $todayTopup      = BalanceHistory::where('type', 'credit')->whereDate('created_at', today())->sum('amount');
        $totalTopupCount = BalanceHistory::where('type', 'credit')->count();

        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(5)
                             ->get();

        // Revenue last 7 days
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueChart[] = [
                'date'    => $date->format('d/m'),
                'revenue' => Order::whereDate('created_at', $date)
                                   ->whereIn('status', ['ready', 'completed'])
                                   ->sum('total_price'),
            ];
        }

        // Topup last 7 days
        $topupChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $topupChart[] = [
                'date'   => $date->format('d/m'),
                'amount' => BalanceHistory::where('type', 'credit')->whereDate('created_at', $date)->sum('amount'),
                'count'  => BalanceHistory::where('type', 'credit')->whereDate('created_at', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalMenus', 'availableMenus', 'totalUsers',
            'todayOrders', 'todayRevenue', 'pendingOrders',
            'totalTopup', 'todayTopup', 'totalTopupCount',
            'recentOrders', 'revenueChart', 'topupChart'
        ));
    }
}
