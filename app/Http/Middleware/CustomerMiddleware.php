<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // return $next($request);
    //     // if (!Auth::check() || Auth::user()->role !== 'customer') {
    //     //     abort(403, 'Unauthorized access.');
    //     // }
    
    //     // return $next($request);


    //     if (Auth::check() && Auth::user()->role === 'customer') {
    //         return $next($request);
    //     }
    //      // Check if it's an API request
    //     if ($request->expectsJson()|| $request->is('api/*')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized. This endpoint is only accessible to customers.',
    //         ], 403);
    //     }
        
    //     abort(403, 'This section is only accessible to customers.');
    // }

    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
    //         if ($request->expectsJson() || $request->is('api/*')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized. Customer access only.',
    //             ], 403);
    //         }
    //         return redirect()->route('login');
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and using web guard
        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Customer access only.',
                ], 403);
            }
            return redirect()->route('login');
        }

        // Ensure user is actually a customer
        if (Auth::guard('web')->user()->role !== 'customer') {
            Auth::guard('web')->logout();
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Customer access only.',
                ], 403);
            }
            return redirect()->route('login')
                ->with('error', 'Unauthorized access attempt.');
        }

        return $next($request);
    }
}
