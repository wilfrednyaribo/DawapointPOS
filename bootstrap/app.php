<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// Import your middleware class
use App\Http\Middleware\CheckSubscription;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    
    // 1. Register Aliases for specific routes (like 'is.superadmin')
    $middleware->alias([
        'is.superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
    ]);

    // 2. Append global middleware (runs on every web request)
    $middleware->web(append: [
        CheckSubscription::class,
    ]);
})
->withExceptions(function (Exceptions $exceptions) {
    //
})->create();