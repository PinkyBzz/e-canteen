<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart      = session()->get('cart', []);
        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }
        return view('user.cart', compact('cart', 'cartTotal'));
    }

    public function add(Request $request, Menu $menu)
    {
        if ($menu->status !== 'available') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Menu ini sedang tidak tersedia.'], 422);
            }
            return back()->with('error', 'Menu ini sedang tidak tersedia.');
        }

        $cart = session()->get('cart', []);
        $key  = 'menu_' . $menu->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->input('quantity', 1);
        } else {
            $cart[$key] = [
                'menu_id'  => $menu->id,
                'name'     => $menu->name,
                'price'    => $menu->price,
                'photo'    => $menu->photo,
                'quantity' => $request->input('quantity', 1),
            ];
        }

        session()->put('cart', $cart);
        $cartCount = array_sum(array_column(session()->get('cart', []), 'quantity'));

        if ($request->expectsJson()) {
            return response()->json([
                'message'    => $menu->name . ' ditambahkan ke keranjang!',
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', $menu->name . ' ditambahkan ke keranjang!');
    }

    public function update(Request $request, $menuId)
    {
        $cart     = session()->get('cart', []);
        $key      = 'menu_' . $menuId;
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($menuId)
    {
        $cart = session()->get('cart', []);
        $key  = 'menu_' . $menuId;
        unset($cart[$key]);
        session()->put('cart', $cart);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
