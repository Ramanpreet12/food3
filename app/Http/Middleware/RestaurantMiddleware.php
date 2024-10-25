<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestaurantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {

    //     if (Auth::check() && Auth::user()->role === 'restaurant_owner') {
    //         return $next($request);
    //     }

    //     if ($request->expectsJson() || $request->is('api/*')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized. Restaurant access only.',
    //         ], 403);
    //     }

    //     abort(403, 'This section is only accessible to restaurant owners.');
    // }


    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::guard('restaurant')->check()) {
    //         if ($request->expectsJson() || $request->is('api/*')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized. Restaurant access only.',
    //             ], 403);
    //         }
    //         return redirect()->route('restaurant.login');
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and using restaurant guard
        if (!Auth::guard('restaurant')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Restaurant access only.',
                ], 403);
            }
            return redirect()->route('restaurant.login');
        }

        // Ensure user is actually a restaurant owner
        if (Auth::guard('restaurant')->user()->role !== 'restaurant_owner') {
            Auth::guard('restaurant')->logout();
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Restaurant access only.',
                ], 403);
            }
            return redirect()->route('restaurant.login')
                ->with('error', 'Unauthorized access attempt.');
        }

        return $next($request);
    }
    
}
