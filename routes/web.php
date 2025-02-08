<?php

use App\View\Components\Header;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::view('/', 'welcome')
    ->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => 'web'], function () {
    Route::get('change-locale/{locale}', [Header::class, 'changeLocale'])->name('change-locale');
});

require __DIR__ . '/auth.php';
