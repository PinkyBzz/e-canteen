<?php

namespace App\Http\Controllers\Warung;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $warung = auth()->user()->warung;

        $menuIds = $warung->menus()->pluck('id');

        $totalMenus    = $warung->menus()->count();
        $availableMenus = $warung->menus()->where('status', 'available')->count();

        // Today's orders (orders with at least one of this warung's items)
        $todayOrders = \App\Models\Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->whereDate('created_at', today())
            ->count();

        $pendingOrders = \App\Models\Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->whereIn('status', ['pending', 'preparing'])
            ->whereDate('created_at', today())
            ->count();

        // Revenue today & this month (only completed orders)
        $revenueToday = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->where('status', 'completed')->whereDate('created_at', today()))
            ->sum(DB::raw('price * quantity'));

        $revenueMonth = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->where('status', 'completed')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
            ->sum(DB::raw('price * quantity'));

        // Recent orders (last 8)
        $recentOrders = \App\Models\Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->with(['user', 'items.menu'])
            ->latest()
            ->take(8)
            ->get();

        // Top 5 selling menus this month
        $topMenus = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->where('status', 'completed')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
            ->select('menu_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(price*quantity) as total_rev'))
            ->groupBy('menu_id')
            ->with('menu')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('warung.dashboard', compact(
            'warung', 'totalMenus', 'availableMenus',
            'todayOrders', 'pendingOrders',
            'revenueToday', 'revenueMonth',
            'recentOrders', 'topMenus'
        ));
    }
}
