<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DeliveryBoyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (Auth::check() && Auth::user()->role === 'delivery_boy') {
    //         return $next($request);
    //     }
    //     return redirect('/');  // Redirect if not delivery boy
    // }

    // DeliveryBoyMiddleware.php
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // if (Auth::check() && Auth::user()->role === 'delivery_boy') {
    //     //     return $next($request);
    //     // }

    //     // abort(403, 'Unauthorized access.');

    //     // if (!Auth::check() || Auth::user()->role !== 'delivery_boy') {
    //     //     abort(403, 'Unauthorized access.');
    //     // }

    //     // return $next($request);


    //     if (Auth::check() && Auth::user()->role === 'delivery_boy') {
    //         return $next($request);
    //     }

    //     // Check if it's an API request (either expects JSON or using sanctum guard)

    //     if ($request->expectsJson() || $request->is('api/*')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized. This endpoint is only accessible to delivery personnel.',
    //         ], 403);
    //     }

    //     abort(403, 'This section is only accessible to delivery personnel.');
    // }


    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::guard('delivery')->check()) {
    //         if ($request->expectsJson() || $request->is('api/*')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized. Delivery access only.',
    //             ], 403);
    //         }
    //         return redirect()->route('delivery.login');
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and using delivery guard
        if (!Auth::guard('delivery')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Delivery access only.',
                ], 403);
            }
            return redirect()->route('delivery.login');
        }

        // Ensure user is actually a delivery boy
        if (Auth::guard('delivery')->user()->role !== 'delivery_boy') {
            Auth::guard('delivery')->logout();
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Delivery access only.',
                ], 403);
            }
            return redirect()->route('delivery.login')
                ->with('error', 'Unauthorized access attempt.');
        }

        return $next($request);
    }
}
