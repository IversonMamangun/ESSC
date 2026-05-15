<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user exists and is a seller
        if (!$user || $user->userType?->slug !== 'seller') {
            abort(403, 'Only sellers can access this page.');
        }

        return $next($request);
    }
}