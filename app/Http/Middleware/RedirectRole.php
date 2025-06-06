<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (Auth::check() && !in_array(Auth::user()->level, $roles)) {
            // Jika role tidak cocok, tampilkan 404 (tidak logout)
            // Tandai session berubah agar saat kembali ke login ada pesan
            session(['role_changed' => true]);
            return response()->view('errors.404', [], 404);
        }
        return $next($request);
    }
}
