<?php

namespace App\Http\Controllers\Warung;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $warung  = auth()->user()->warung;
        $menuIds = $warung->menus()->pluck('id')->toArray();

        // --- Daily report ---
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $orders = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->with(['user', 'items' => fn($q) => $q->whereIn('menu_id', $menuIds)->with('menu')])
            ->whereDate('created_at', $date)
            ->whereIn('status', ['ready', 'completed'])
            ->get();

        $totalRevenue = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->whereDate('created_at', $date)->whereIn('status', ['ready','completed']))
            ->sum(DB::raw('price * quantity'));

        $totalOrders = $orders->count();

        // Top selling items for the day
        $topItems = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->whereDate('created_at', $date)->whereIn('status', ['ready','completed']))
            ->select('menu_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(price*quantity) as total_revenue'))
            ->groupBy('menu_id')
            ->with('menu')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();

        // Revenue by category for the day
        $categoryRevenue = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->whereDate('created_at', $date)->whereIn('status', ['ready','completed']))
            ->with('menu')
            ->get()
            ->groupBy(fn($item) => $item->menu->category ?? 'Lainnya')
            ->map(fn($items) => $items->sum(fn($i) => $i->price * $i->quantity));

        // --- Monthly report ---
        $month = $request->month ? Carbon::parse($request->month . '-01') : Carbon::today()->startOfMonth();

        $monthlyRevenue = OrderItem::whereIn('menu_id', $menuIds)
            ->whereHas('order', fn($q) => $q->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->whereIn('status', ['ready','completed']))
            ->sum(DB::raw('price * quantity'));

        $monthlyOrderCount = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->whereIn('status', ['ready','completed'])
            ->count();

        // Daily revenue chart for selected month
        $dailyRevenue = [];
        for ($d = 1; $d <= $month->daysInMonth; $d++) {
            $dayDate = Carbon::create($month->year, $month->month, $d);
            $dailyRevenue[] = [
                'day' => $d,
                'revenue' => OrderItem::whereIn('menu_id', $menuIds)
                    ->whereHas('order', fn($q) => $q->whereDate('created_at', $dayDate)->whereIn('status', ['ready','completed']))
                    ->sum(DB::raw('price * quantity')),
            ];
        }

        return view('warung.reports.index', compact(
            'warung', 'date', 'orders', 'totalRevenue', 'totalOrders',
            'topItems', 'categoryRevenue',
            'month', 'monthlyRevenue', 'monthlyOrderCount', 'dailyRevenue'
        ));
    }
}
