<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.menu'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('break_time')) {
            $query->where('break_time', $request->break_time);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders = $query->paginate(15)->withQueryString();

        $pendingCount   = Order::whereIn('status', ['pending'])->whereDate('created_at', today())->count();
        $preparingCount = Order::where('status', 'preparing')->whereDate('created_at', today())->count();
        $readyCount     = Order::where('status', 'ready')->whereDate('created_at', today())->count();

        return view('admin.orders.index', compact('orders', 'pendingCount', 'preparingCount', 'readyCount'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.menu']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Admin tidak bisa mengubah status pesanan
        // Hanya warung yang memiliki menu dalam pesanan yang bisa mengubah status
        abort(403, 'Admin tidak memiliki akses untuk mengubah status pesanan. Hanya warung yang bisa mengubah status.');
    }
}
