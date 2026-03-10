<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $orders          = $user->orders()->with('items.menu')->latest()->take(10)->get();
        $balanceHistories = $user->balanceHistories()->latest()->take(10)->get();
        return view('admin.users.show', compact('user', 'orders', 'balanceHistories'));
    }

    public function topup(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount'      => 'required|numeric|min:1000|max:1000000',
            'description' => 'nullable|string|max:255',
        ]);

        $balanceBefore = $user->balance;
        $balanceAfter  = $balanceBefore + $validated['amount'];

        $user->update(['balance' => $balanceAfter]);

        BalanceHistory::create([
            'user_id'        => $user->id,
            'type'           => 'credit',
            'amount'         => $validated['amount'],
            'balance_before' => $balanceBefore,
            'balance_after'  => $balanceAfter,
            'description'    => $validated['description'] ?? 'Top up saldo oleh admin',
        ]);

        return back()->with('success', 'Saldo berhasil ditambahkan sebesar Rp ' . number_format($validated['amount'], 0, ',', '.'));
    }
}
