<?php

use App\Http\Controllers\LandingPageController;
use App\View\Components\Header;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\App;

// Public routes
Route::view('/', 'welcome')->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Contract routes
    Route::prefix('contracts')->group(function () {
        Route::get('dashboard', [ContractController::class, 'showUploadForm'])->name('contracts.showUploadForm');
        Route::post('upload', [ContractController::class, 'upload'])->name('contracts.upload');
    });

    // Advertisement routes
    Route::prefix('advertisements')->group(function () {
        Route::get('/', [AdvertisementController::class, 'index'])->name('advertisements.index');
        Route::get('create', [AdvertisementController::class, 'create'])->name('advertisements.create');
        Route::post('/', [AdvertisementController::class, 'store'])->name('advertisements.store');
        Route::get('calendar', [AdvertisementController::class, 'calendar'])->name('advertisements.calendar');
        Route::get('expiry-calendar', [AdvertisementController::class, 'expiryCalendar'])->name('advertisements.expiryCalendar');
    });
});

// Landing page route
Route::get('/landing-page/{url}', [LandingPageController::class, 'show']);

// Locale change route
Route::middleware('web')->group(function () {
    Route::get('change-locale/{locale}', [Header::class, 'changeLocale'])->name('change-locale');
});

require __DIR__ . '/auth.php';
