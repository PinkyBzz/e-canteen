<?php

namespace App\Http\Controllers\Warung;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    private function warung()
    {
        return auth()->user()->warung;
    }

    public function index(Request $request)
    {
        $warung = $this->warung();
        $query  = $warung->menus();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $menus      = $query->latest()->paginate(12)->withQueryString();
        $categories = $warung->menus()->distinct()->pluck('category');

        return view('warung.menus.index', compact('warung', 'menus', 'categories'));
    }

    public function create()
    {
        return view('warung.menus.create');
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

        $validated['warung_id'] = $this->warung()->id;
        Menu::create($validated);

        return redirect()->route('warung.menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $this->authorizeMenu($menu);
        return view('warung.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorizeMenu($menu);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:available,out',
            'description' => 'nullable|string|max:500',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($menu->photo) Storage::disk('public')->delete($menu->photo);
            $validated['photo'] = $request->file('photo')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('warung.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        $this->authorizeMenu($menu);
        if ($menu->photo) Storage::disk('public')->delete($menu->photo);
        $menu->delete();

        return redirect()->route('warung.menus.index')->with('success', 'Menu berhasil dihapus!');
    }

    public function toggleStatus(Menu $menu)
    {
        $this->authorizeMenu($menu);
        $menu->update(['status' => $menu->status === 'available' ? 'out' : 'available']);
        return back()->with('success', 'Status menu diubah!');
    }

    private function authorizeMenu(Menu $menu): void
    {
        if ($menu->warung_id !== $this->warung()->id) {
            abort(403, 'Bukan menu warung Anda.');
        }
    }
}
