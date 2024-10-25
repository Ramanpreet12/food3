<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // if (Auth::check() && Auth::user()->role === 'admin') {
    //     //     return $next($request);
    //     // }

    //     // abort(403, 'Unauthorized access.');

    //     if (Auth::check() && Auth::user()->role === 'admin') {
    //         return $next($request);
    //     }
    //      // Check if it's an API request
    //     if ($request->expectsJson() || $request->is('api/*')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized. This endpoint is only accessible to administrators.',
    //         ], 403);
    //     }
        
    //     abort(403, 'This section is only accessible to administrators.');
    // }

    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->role !== 'admin') {
    //         if ($request->expectsJson() || $request->is('api/*')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized. Administrator access only.',
    //             ], 403);
    //         }
    //         return redirect()->route('admin.login');
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and using admin guard
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access only.',
                ], 403);
            }
            return redirect()->route('admin.login');
        }

        // Ensure user is actually an admin
        if (Auth::guard('admin')->user()->role !== 'admin') {
            Auth::guard('admin')->logout();
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access only.',
                ], 403);
            }
            return redirect()->route('admin.login')
                ->with('error', 'Unauthorized access attempt.');
        }

        return $next($request);
    }

}