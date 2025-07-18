<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('booking')->check() || Auth::guard('booking')->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
