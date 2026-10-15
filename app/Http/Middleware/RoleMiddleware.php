<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Kalau belum login, lempar ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Kalau role user tidak sesuai, tolak akses
        if (!in_array(auth()->user()->role, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak punya izin.');
        }

        return $next($request);
    }
}