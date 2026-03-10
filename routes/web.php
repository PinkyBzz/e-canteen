<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\User;
use App\Http\Controllers\Warung;

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'warung' => redirect()->route('warung.dashboard'),
            default  => redirect()->route('user.home'),
        };
    }
    return redirect()->route('login');
});

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Menu management
        Route::resource('menus', Admin\MenuController::class);
        Route::patch('/menus/{menu}/toggle-status', [Admin\MenuController::class, 'toggleStatus'])->name('menus.toggle-status');

        // Order management
        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Reports
        Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');

        // User management
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/topup', [Admin\UserController::class, 'topup'])->name('users.topup');

        // Warung management
        Route::get('/warungs', [Admin\WarungController::class, 'index'])->name('warungs.index');
        Route::get('/warungs/create', [Admin\WarungController::class, 'create'])->name('warungs.create');
        Route::post('/warungs', [Admin\WarungController::class, 'store'])->name('warungs.store');
        Route::patch('/warungs/{warung}/toggle', [Admin\WarungController::class, 'toggle'])->name('warungs.toggle');
        Route::delete('/warungs/{warung}', [Admin\WarungController::class, 'destroy'])->name('warungs.destroy');
    });

// User routes
Route::prefix('/')
    ->name('user.')
    ->middleware(['auth', 'user'])
    ->group(function () {
        Route::get('/home', [User\HomeController::class, 'index'])->name('home');

        // Cart
        Route::get('/cart', [User\CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{menu}', [User\CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{menuId}', [User\CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{menuId}', [User\CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [User\CartController::class, 'clear'])->name('cart.clear');

        // Orders
        Route::get('/checkout', [User\OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/checkout', [User\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [User\OrderController::class, 'history'])->name('orders.history');
        Route::get('/orders/{order}', [User\OrderController::class, 'show'])->name('orders.show');

        // Profile & balance
        Route::get('/profile', [User\ProfileController::class, 'index'])->name('profile');
        Route::patch('/profile', [User\ProfileController::class, 'update'])->name('profile.update');
    });

require __DIR__.'/auth.php';

// Warung routes
Route::prefix('warung')
    ->name('warung.')
    ->middleware(['auth', 'warung'])
    ->group(function () {
        Route::get('/dashboard', [Warung\DashboardController::class, 'index'])->name('dashboard');

        // Menu management (warung's own menus only)
        Route::resource('menus', Warung\MenuController::class);
        Route::patch('/menus/{menu}/toggle-status', [Warung\MenuController::class, 'toggleStatus'])->name('menus.toggle-status');

        // Orders containing warung's menu items
        Route::get('/orders', [Warung\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Warung\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [Warung\OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Revenue report
        Route::get('/reports', [Warung\ReportController::class, 'index'])->name('reports.index');

        // Warung profile settings
        Route::get('/settings', [Warung\SettingsController::class, 'edit'])->name('settings');
        Route::patch('/settings', [Warung\SettingsController::class, 'update'])->name('settings.update');
    });
