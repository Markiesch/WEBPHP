<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ContractController;

Route::get('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});

//Route::post('export-pdf', [ContractController::class, 'exportPdf']);

Route::get('/', function() {
    return 'API';
});
