<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;

// Home route
Route::view('/', 'home');

// Login routes
Route::view('/login', 'login')->name('login');
Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');

// Signup routes
Route::get('/signup', [SignupController::class, 'index'])->name('signup');
Route::post('/signup', [SignupController::class, 'submit'])->name('signup.submit');

// Locale switcher route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, config('languages.available'))) {
        App::setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');
