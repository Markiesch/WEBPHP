<?php

use App\Http\Controllers\Public\AdvertisementController;
use App\Http\Controllers\Public\AdvertisementFavoriteController;
use App\Http\Controllers\Public\AdvertisementReviewController;
use App\Http\Controllers\Public\BusinessController;
use App\Http\Controllers\Public\LoginController;
use App\Http\Controllers\Public\SignupController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('/')->group(function () {
    Route::get('/', [AdvertisementController::class, 'advertisements'])->name('home');
    Route::get('advertisements/{id}', [AdvertisementController::class, 'advertisement'])->name('advertisement');
    Route::post('advertisements/{id}/reviews', [AdvertisementReviewController::class, 'store'])
        ->middleware(['auth'])
        ->name('reviews.submit');
    Route::post('advertisements/{advertisementId}/reviews/{id}', [AdvertisementReviewController::class, 'delete'])
        ->middleware(['auth'])
        ->name('reviews.delete');
    Route::post('advertisements/{id}/favorite', [AdvertisementFavoriteController::class, 'store'])
        ->middleware(['auth'])
        ->name('advertisement.favorite');

    Route::get('businesses/{url}', [BusinessController::class, 'index'])
        ->name('business-page');

    // Login routes
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Signup routes
    Route::view('/signup', 'auth.register')->name('signup');
    Route::post('/signup', [SignupController::class, 'submit'])->name('signup.submit');

    // Locale switcher route
    Route::get('/language/{locale}', function ($locale) {
        if (in_array($locale, config('languages.available'))) {
            App::setLocale($locale);
            session()->put('locale', $locale);
        }
        return redirect()->back();
    })->name('language.switch');
});
