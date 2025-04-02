<?php
use App\Http\Controllers\Api\AdvertisementApiController;
use Illuminate\Support\Facades\Route;

Route::get('advertisements', [AdvertisementApiController::class, 'index']);
Route::get('advertisements/{id}', [AdvertisementApiController::class, 'show']);
