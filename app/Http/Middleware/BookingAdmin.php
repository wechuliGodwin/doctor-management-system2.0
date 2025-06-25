<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('booking')->check() && Auth::guard('booking')->user()->role === 'admin') {
            return $next($request);
        }

        Log::warning('Non-admin attempted to access admin route', [
            'user_id' => Auth::guard('booking')->id() ?? 'guest'
        ]);
        return redirect()->route('booking.dashboard')->withErrors(['access' => 'Only admins can perform this action.']);
    }
}