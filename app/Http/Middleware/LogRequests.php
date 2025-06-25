<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Incoming request', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'input' => $request->all(),
            'headers' => $request->headers->all(),
        ]);
        return $next($request);
    }
}