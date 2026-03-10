<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $menus      = $query->latest()->paginate(10)->withQueryString();
        $categories = Menu::distinct()->pluck('category');

        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:available,out',
            'description' => 'nullable|string|max:500',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:available,out',
            'description' => 'nullable|string|max:500',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($menu->photo) {
                Storage::disk('public')->delete($menu->photo);
            }
            $validated['photo'] = $request->file('photo')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->photo) {
            Storage::disk('public')->delete($menu->photo);
        }
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }

    public function toggleStatus(Menu $menu)
    {
        $menu->update([
            'status' => $menu->status === 'available' ? 'out' : 'available',
        ]);

        return back()->with('success', 'Status menu berhasil diubah!');
    }
}
