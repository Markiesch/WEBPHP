<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::middleware('auth:sanctum')->get('/advertisements', [RentalController::class, 'getAdvertisements']);
