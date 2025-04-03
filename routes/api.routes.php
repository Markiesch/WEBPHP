<?php

use App\Http\Controllers\Api\AdvertisementApiController;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    Route::get('b/{id}/advertisements', [AdvertisementApiController::class, 'index'])->name('api.advertisements.list');
    Route::get('b/{id}/advertisements/{adId}', [AdvertisementApiController::class, 'show'])->name('api.advertisements.show');
});
