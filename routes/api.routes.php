<?php

use App\Http\Controllers\Api\AdvertisementApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::middleware('auth')->get('/advertisements/list', [AdvertisementApiController::class, 'index'])->name('advertisements.list');
    Route::get('advertisements/{id}', [AdvertisementApiController::class, 'show']);
});
