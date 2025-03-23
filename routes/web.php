<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [SignupController::class, 'submit'])->name('signup.submit');

