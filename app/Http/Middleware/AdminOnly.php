<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        // only logged-in users with the admin role may continue
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Administrators only.');
        }

        return $next($request);
    }
}