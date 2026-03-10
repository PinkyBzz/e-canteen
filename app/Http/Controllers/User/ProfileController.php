<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistory;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user             = auth()->user();
        $balanceHistories = $user->balanceHistories()->latest()->paginate(15);

        return view('user.profile', compact('user', 'balanceHistories'));
    }

    public function update(Request $request)
    {
        $user      = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
