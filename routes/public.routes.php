<?php

use App\Http\Controllers\Admin\AdvertisementCrudController;
use App\Http\Controllers\Public\AdvertisementController;
use App\Http\Controllers\Public\LoginController;
use App\Http\Controllers\Public\SignupController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('/')->group(function () {
    Route::get('/', [AdvertisementController::class, 'advertisements'])->name('home');
    Route::get('advertisements/{id}', [AdvertisementController::class, 'advertisement'])->name('advertisement');

    // Advertisement review route
    Route::post('/advertisements/{id}/review', [App\Http\Controllers\AdvertisementReviewController::class, 'store'])
        ->middleware(['auth'])
        ->name('advertisement.review');

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
