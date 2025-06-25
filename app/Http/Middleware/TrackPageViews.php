<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackPageViews
{
    public function handle(Request $request, Closure $next)
    {
        $url = $request->path();
        DB::statement(
            'INSERT INTO page_views (url, view_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE view_count = view_count + 1',
            [$url]
        );
        return $next($request);
    }
}