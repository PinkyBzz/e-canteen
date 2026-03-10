<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Warung;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $warungs = Warung::where('is_active', true)->orderBy('name')->get();

        $query = Menu::query()->where('status', 'available');

        if ($request->filled('warung')) {
            $warungId = $request->warung === 'none' ? null : (int) $request->warung;
            if ($warungId) {
                $query->where('warung_id', $warungId);
            } else {
                $query->whereNull('warung_id');
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $menus      = $query->with('warung:id,name,logo')->latest()->get();
        $categories = Menu::where('status', 'available')
            ->when($request->filled('warung') && $request->warung !== 'none', fn($q) => $q->where('warung_id', (int) $request->warung))
            ->when($request->warung === 'none', fn($q) => $q->whereNull('warung_id'))
            ->distinct()->pluck('category');

        $cart      = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('user.home', compact('menus', 'categories', 'warungs', 'cart', 'cartCount'));
    }
}
