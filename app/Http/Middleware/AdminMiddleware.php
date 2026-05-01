<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // Cek apakah sudah login DAN apakah dia admin
    if (auth()->check() && auth()->user()->is_admin) {
        return $next($request);
    }

    // Jika bukan admin, tendang ke halaman beranda atau berikan error 403
    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
}
}
