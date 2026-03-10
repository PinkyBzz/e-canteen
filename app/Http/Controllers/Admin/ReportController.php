<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $orders = Order::with(['user', 'items.menu'])
                       ->whereDate('created_at', $date)
                       ->whereIn('status', ['ready', 'completed'])
                       ->get();

        $totalRevenue  = $orders->sum('total_price');
        $totalOrders   = $orders->count();

        // Best selling items for the day
        $topItems = OrderItem::with('menu')
            ->whereHas('order', function ($q) use ($date) {
                $q->whereDate('created_at', $date)->whereIn('status', ['ready', 'completed']);
            })
            ->selectRaw('menu_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Revenue by category for the day
        $categoryRevenue = OrderItem::with('menu')
            ->whereHas('order', function ($q) use ($date) {
                $q->whereDate('created_at', $date)->whereIn('status', ['ready', 'completed']);
            })
            ->get()
            ->groupBy(fn($item) => $item->menu->category)
            ->map(fn($items) => $items->sum('subtotal'));

        // Monthly summary
        $month = $request->month ? Carbon::parse($request->month . '-01') : Carbon::today()->startOfMonth();

        $monthlyOrders = Order::whereMonth('created_at', $month->month)
                              ->whereYear('created_at', $month->year)
                              ->whereIn('status', ['ready', 'completed'])
                              ->get();

        $monthlyRevenue    = $monthlyOrders->sum('total_price');
        $monthlyOrderCount = $monthlyOrders->count();

        // Daily revenue for selected month (chart)
        $dailyRevenue = [];
        $daysInMonth  = $month->daysInMonth;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dayDate = Carbon::create($month->year, $month->month, $d);
            $dailyRevenue[] = [
                'day'     => $d,
                'revenue' => Order::whereDate('created_at', $dayDate)
                                   ->whereIn('status', ['ready', 'completed'])
                                   ->sum('total_price'),
            ];
        }

        return view('admin.reports.index', compact(
            'date', 'orders', 'totalRevenue', 'totalOrders',
            'topItems', 'categoryRevenue',
            'month', 'monthlyRevenue', 'monthlyOrderCount', 'dailyRevenue'
        ));
    }
}
