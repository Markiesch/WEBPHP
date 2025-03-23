<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // Other middleware
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // ... other middleware
            \App\Http\Middleware\SetLocale::class,
        ],
    ];
//    protected $middlewareGroups = [
////        'api' => [
////            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
////            'throttle:api',
////            \Illuminate\Routing\Middleware\SubstituteBindings::class,
////        ],
//    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [

    ];
}
