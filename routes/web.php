<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login/{locale?}', function ($locale = null) {
    if ($locale && in_array($locale, ['en', 'nl'])) {
        App::setLocale($locale);
        session(['locale' => $locale]);
    }
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');

// Add the route for displaying the signup form with account type parameter
Route::get('/signup', [SignupController::class, 'index'])->name('signup');

// Keep the existing submit route
Route::post('/signup', [SignupController::class, 'submit'])->name('signup.submit');

