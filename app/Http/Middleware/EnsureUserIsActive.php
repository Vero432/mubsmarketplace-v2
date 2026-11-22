<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // pending, rejected and suspended users only see the status page
        if ($user && $user->status !== 'active') {
            return redirect()->route('account.status');
        }

        return $next($request);
    }
}