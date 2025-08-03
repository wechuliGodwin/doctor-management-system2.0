<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // Add any custom middleware for the web group
        ]);

        $middleware->alias([
            'auth.booking' => \App\Http\Middleware\BookingAuthenticate::class,
            'admin' => \App\Http\Middleware\BookingAdmin::class,
            'superadmin' => \App\Http\Middleware\SuperadminMiddleware::class,
            'adminOrSuperadmin' => \App\Http\Middleware\AdminOrSuperadminMiddleware::class,
            'check.branch.write' => \App\Http\Middleware\CheckBranchWritePermission::class,
            'ensure.valid.branch' => \App\Http\Middleware\EnsureValidBranch::class,
            // Other middleware aliases
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
