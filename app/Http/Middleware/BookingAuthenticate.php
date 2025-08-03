<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingAuthenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Set the session connection for booking
        config(['session.connection' => 'booking']);
        // Log to verify
        Log::info('BookingAuthenticate middleware', [
            'guards' => $guards,
            'session_connection' => config('session.connection')
        ]);

        $guards = empty($guards) ? ['booking'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        return $this->unauthenticated($request, $guards);
    }

    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request, $guards)
        );
    }

    protected function redirectTo($request, array $guards)
    {
        if (!$request->expectsJson()) {
            $guard = $guards[0] ?? 'booking';
            if ($guard === 'booking') {
                return route('booking.login');
            }
            return route('login');
        }
    }
}