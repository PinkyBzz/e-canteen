<?php

namespace App\Http\Controllers\Warung;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function warungMenuIds(): array
    {
        return auth()->user()->warung->menus()->pluck('id')->toArray();
    }

    public function index(Request $request)
    {
        $warung  = auth()->user()->warung;
        $menuIds = $this->warungMenuIds();

        $query = Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))
            ->with(['user', 'items' => fn($q) => $q->whereIn('menu_id', $menuIds)->with('menu')]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'pending'   => Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))->where('status','pending')->whereDate('created_at',today())->count(),
            'preparing' => Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))->where('status','preparing')->whereDate('created_at',today())->count(),
            'ready'     => Order::whereHas('items', fn($q) => $q->whereIn('menu_id', $menuIds))->where('status','ready')->whereDate('created_at',today())->count(),
        ];

        return view('warung.orders.index', compact('warung', 'orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $menuIds = $this->warungMenuIds();

        // Ensure this order contains at least one of warung's items
        if (!$order->items()->whereIn('menu_id', $menuIds)->exists()) {
            abort(403, 'Pesanan ini bukan untuk warung Anda.');
        }

        $myItems = $order->items()->whereIn('menu_id', $menuIds)->with('menu')->get();

        return view('warung.orders.show', compact('order', 'myItems'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $menuIds = $this->warungMenuIds();

        if (!$order->items()->whereIn('menu_id', $menuIds)->exists()) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:pending,preparing,ready,completed,cancelled']);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui!');
    }
}
