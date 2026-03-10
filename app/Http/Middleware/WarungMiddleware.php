<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WarungMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isWarung()) {
            if (auth()->user()?->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user() && !auth()->user()->isWarung()) {
                return redirect()->route('user.home');
            }
            abort(403, 'Akses ditolak. Hanya pemilik warung yang diizinkan.');
        }

        // Ensure warung profile exists
        if (!auth()->user()->warung) {
            abort(403, 'Profil warung belum dibuat. Hubungi admin.');
        }

        return $next($request);
    }
}
