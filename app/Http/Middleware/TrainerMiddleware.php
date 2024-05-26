<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skontrolujte, či je autentifikovaný používateľ tréner
        if ($request->user() && $request->user()->role === 2) {
        return $next($request);
    }

        // Ak nie je tréner, presmerujte alebo spracujte podľa potreby
        return redirect('/home')->with('error', 'Neoprávnený prístup, iba pre trénerov');
    }
}
