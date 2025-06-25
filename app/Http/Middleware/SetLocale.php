<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->query('lang', session('locale', 'en')); // Default to 'en'
        if (in_array($lang, ['en', 'sw', 'fr'])) {
            App::setLocale($lang);
            session(['locale' => $lang]); // Persist the choice
        }
        return $next($request);
    }
}