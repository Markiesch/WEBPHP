<?php

use App\Http\Controllers\Api\AdvertisementApiController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/api/advertisements', [AdvertisementApiController::class, 'index']);
Route::get('/api/advertisements/{id}', [AdvertisementApiController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});

//Route::post('export-pdf', [ContractController::class, 'exportPdf']);

Route::get('/', function() {
    return 'API';
});
