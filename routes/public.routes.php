<?php

use App\Http\Controllers\Public\AdvertisementController;
use App\Http\Controllers\Public\AdvertisementFavoriteController;
use App\Http\Controllers\Public\AdvertisementReviewController;
use App\Http\Controllers\Public\BusinessController;
use App\Http\Controllers\Public\BusinessReviewController;
use App\Http\Controllers\Public\LoginController;
use App\Http\Controllers\Public\SignupController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->group(function () {
    // Home and advertisement details
    Route::get('/', [AdvertisementController::class, 'advertisements'])->name('home');
    Route::get('advertisements/{id}', [AdvertisementController::class, 'advertisement'])->name('advertisement');

    // Advertisement actions (authenticated users only)
    Route::middleware(['auth'])->group(function () {
        // Changed GET to POST for purchase action
        Route::post('advertisements/{id}/buy', [AdvertisementController::class, 'purchase'])
            ->name('advertisement.buy');

        Route::post('advertisements/{id}/bid', [AdvertisementController::class, 'placeBid'])
            ->name('advertisements.bid');

        Route::get('purchases', [AdvertisementController::class, 'purchases'])
            ->name('purchase.history');
    });

    // Reviews
    Route::middleware(['auth'])->group(function () {
        Route::post('advertisements/{id}/reviews', [AdvertisementReviewController::class, 'store'])
            ->name('reviews.submit');

        Route::post('advertisements/{advertisementId}/reviews/{id}/delete', [AdvertisementReviewController::class, 'delete'])
            ->name('reviews.delete');
    });

    // Favorites
    Route::post('advertisements/{id}/favorite', [AdvertisementFavoriteController::class, 'store'])
        ->middleware(['auth'])
        ->name('advertisement.favorite');

    // Business routes
    Route::get('businesses/{url}', [BusinessController::class, 'index'])
        ->name('business-page');

    Route::middleware(['auth'])->group(function () {
        Route::post('businesses/{business}/reviews', [BusinessReviewController::class, 'store'])
            ->name('business.reviews.submit');

        Route::post('businesses/{business}/reviews/{review}/delete', [BusinessReviewController::class, 'delete'])
            ->name('business.reviews.delete');
    });

    // Auth routes
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/signup', [SignupController::class, 'index'])->name('signup');
    Route::post('/signup', [SignupController::class, 'submit'])->name('signup.submit');

    // Language switcher
    Route::get('/language/{locale}', function ($locale) {
        if (in_array($locale, config('languages.available'))) {
            App::setLocale($locale);
            session()->put('locale', $locale);
        }
        return redirect()->back();
    })->name('language.switch');
});
