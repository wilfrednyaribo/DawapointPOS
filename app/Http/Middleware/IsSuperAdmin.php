<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is Super Admin (pharmacy_id is null)
        if (auth()->check() && auth()->user()->pharmacy_id === null) {
            return $next($request);
        }

        abort(403, 'Unauthorized. Super Admins only.');
    }
}