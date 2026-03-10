<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\BalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('user.home')->with('error', 'Keranjang belanja kosong!');
        }

        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        $user = auth()->user();

        return view('user.checkout', compact('cart', 'cartTotal', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'break_time' => 'required|in:istirahat_1,istirahat_2',
            'notes'      => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('user.home')->with('error', 'Keranjang belanja kosong!');
        }

        $user      = auth()->user();
        $cartTotal = 0;

        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        if ($user->balance < $cartTotal) {
            return back()->with('error', 'Saldo tidak mencukupi! Saldo Anda: Rp ' . number_format($user->balance, 0, ',', '.'));
        }

        DB::transaction(function () use ($user, $cart, $cartTotal, $validated) {
            // Deduct balance
            $balanceBefore = $user->balance;
            $balanceAfter  = $balanceBefore - $cartTotal;
            $user->update(['balance' => $balanceAfter]);

            // Create order
            $order = Order::create([
                'user_id'    => $user->id,
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'break_time' => $validated['break_time'],
                'total_price'=> $cartTotal,
                'status'     => 'pending',
                'notes'      => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cart as $key => $item) {
                $menu = Menu::find($item['menu_id']);
                if ($menu) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id'  => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'price'    => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            // Record balance history
            BalanceHistory::create([
                'user_id'          => $user->id,
                'type'             => 'debit',
                'amount'           => $cartTotal,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $balanceAfter,
                'description'      => 'Pembayaran pesanan ' . $order->order_code,
                'sourceable_type'  => Order::class,
                'sourceable_id'    => $order->id,
            ]);

            session()->forget('cart');
            session()->put('last_order_id', $order->id);
        });

        return redirect()->route('user.orders.history')->with('success', 'Pesanan berhasil dibuat! Silakan ambil pada ' . ($validated['break_time'] === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2'));
    }

    public function history(Request $request)
    {
        $user   = auth()->user();
        $query  = $user->orders()->with('items.menu')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('user.orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.menu');

        return view('user.orders.show', compact('order'));
    }
}
