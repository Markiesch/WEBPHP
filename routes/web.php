<?php

use App\Http\Controllers\LandingPageController;
use App\View\Components\Header;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\App;

Route::view('/', 'welcome')
    ->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('dashboard/contract', [ContractController::class, 'showUploadForm'])
    ->middleware(['auth'])
    ->name('contracts.showUploadForm');

Route::middleware(['auth'])->group(function () {
    Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::get('/advertisements/calendar', [AdvertisementController::class, 'calendar'])->name('advertisements.calendar');
    Route::get('/advertisements/expiry-calendar', [AdvertisementController::class, 'expiryCalendar'])->name('advertisements.expiryCalendar');
});

Route::post('contracts/upload', [ContractController::class, 'upload'])
    ->middleware(['auth'])
    ->name('contracts.upload');

Route::get('/landing-page/{url}', [LandingPageController::class, 'show']);

Route::group(['middleware' => 'web'], function () {
    Route::get('change-locale/{locale}', [Header::class, 'changeLocale'])->name('change-locale');
});

require __DIR__ . '/auth.php';
