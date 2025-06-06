<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        $user = auth()->user();
        if (!$user) {
            // Jika belum login, tampilkan 404
            return response()->view('errors.404', [], 404);
        }

        if (!in_array($user->level, $levels)) {
            // Jika role tidak cocok, tampilkan 404 (tidak logout)
            // Tandai session berubah agar saat kembali ke login ada pesan
            session(['role_changed' => true]);
            return response()->view('errors.404', [], 404);
        }

        return $next($request);
    }
}
