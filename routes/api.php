<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

    Route::get('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        // Protected routes
    });

    Route::get('/', function() {
        return 'API';
    });

