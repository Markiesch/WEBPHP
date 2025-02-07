<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('change-locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
})->name('change-locale');

require __DIR__ . '/auth.php';
