<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WarungController extends Controller
{
    public function index()
    {
        $warungs = Warung::with('user')->latest()->get();
        return view('admin.warungs.index', compact('warungs'));
    }

    public function create()
    {
        return view('admin.warungs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'warung_name' => 'required|string|max:255',
            'phone'       => 'nullable|string|max:30',
            'address'     => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'warung',
            'balance'  => 0,
        ]);

        Warung::create([
            'user_id'  => $user->id,
            'name'     => $data['warung_name'],
            'phone'    => $data['phone'],
            'address'  => $data['address'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.warungs.index')
            ->with('success', "Warung \"{$data['warung_name']}\" berhasil dibuat.");
    }

    public function toggle(Warung $warung)
    {
        $warung->update(['is_active' => !$warung->is_active]);
        $status = $warung->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Warung \"{$warung->name}\" berhasil {$status}.");
    }

    public function destroy(Warung $warung)
    {
        $name = $warung->name;
        $warung->user->delete(); // cascade deletes warung + menus via FK
        return redirect()->route('admin.warungs.index')
            ->with('success', "Warung \"{$name}\" berhasil dihapus.");
    }
}
