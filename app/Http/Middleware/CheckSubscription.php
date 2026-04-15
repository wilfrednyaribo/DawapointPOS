<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 1. FIX: If no user is logged in (e.g. during logout process), just continue
        if (!$user) {
            return $next($request);
        }

        // 2. If user is Super Admin (pharmacy_id is null), allow everything
        if ($user->pharmacy_id === null) {
            return $next($request);
        }

        // 3. Check if pharmacy relationship exists
        if (!$user->pharmacy) {
            return $next($request); 
        }
        
        $pharmacy = $user->pharmacy;

        // 4. Define routes that are ALWAYS accessible even if expired
        $allowedRoutes = [
            'subscriptions.show',
            'logout', // This is critical
        ];

        // 5. Check Expiry Status
        $isExpired = false;
        
        if ($pharmacy->is_active == false) {
            $isExpired = true;
        } elseif ($pharmacy->subscription_ends_at && $pharmacy->subscription_ends_at->isPast()) {
            $isExpired = true;
        }

        // 6. Logic: Block if Expired
        if ($isExpired) {
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Subscription Expired. Please contact admin.'], 403);
                }

                return redirect()->route('subscriptions.show', $pharmacy->id)
                                 ->with('error', 'Your subscription has expired. Access is restricted.');
            }
        }

        return $next($request);
    }
}