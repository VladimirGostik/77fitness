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
       // Check if the authenticated user is a trainer
       if ($request->user() && $request->user()->role === 2) {
        return $next($request);
    }

    // If not a trainer, redirect or handle as needed
    return redirect('/home')->with('error', 'Unauthorized access for trainers');
    }
}
