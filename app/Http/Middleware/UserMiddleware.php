<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Akses ditolak.');
        }
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->isWarung()) {
            return redirect()->route('warung.dashboard');
        }
        return $next($request);
    }
}
