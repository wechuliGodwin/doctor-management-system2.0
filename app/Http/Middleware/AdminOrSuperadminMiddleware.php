<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOrSuperadminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure a user is authenticated with the 'booking' guard
        // And their role is either 'admin' or 'superadmin'
        if (!Auth::guard('booking')->check() || !in_array(Auth::guard('booking')->user()->role, ['admin', 'superadmin'])) {
            // If unauthorized, redirect to the dashboard or show a 403 forbidden page
            return redirect()->route('booking.dashboard')->with('error', 'You are not authorized to access this page.');
            // Or: abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
