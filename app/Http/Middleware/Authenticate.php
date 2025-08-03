<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        Log::info('Authenticate middleware handle', [
            'guards' => $guards,
            'path' => $request->path(),
            'is_booking' => $request->is('booking/*')
        ]);
        $this->authenticate($request, $guards);
        return $next($request);
    }

    protected function redirectTo($request)
    {
        Log::info('Authenticate middleware redirectTo', [
            'path' => $request->path(),
            'is_booking' => $request->is('booking/*')
        ]);
        if (!$request->expectsJson()) {
            if ($request->is('booking/*')) {
                Log::info('Redirecting to booking.login for booking route');
                return route('booking.login');
            }
            Log::info('Redirecting to login for default guard');
            return route('login');
        }
    }
}