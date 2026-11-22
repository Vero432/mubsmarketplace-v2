<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || ($user->role !== 'admin' && $user->account_type !== 'seller')) {
            abort(403, 'Only seller accounts can post and manage listings.');
        }

        return $next($request);
    }
}