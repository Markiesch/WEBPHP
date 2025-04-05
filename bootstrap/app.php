<?php

use App\Http\Middleware\CheckIfAdmin;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/public.routes.php',
            __DIR__ . '/../routes/seller.routes.php',
            __DIR__ . '/../routes/admin.routes.php'
        ],
        api: __DIR__ . '/../routes/api.routes.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [SetLocale::class]);
        $middleware->alias([
            'admin' => CheckIfAdmin::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
