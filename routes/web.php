<?php

use App\Http\Controllers\Admin\AdvertisementCrudController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ContractController;
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

//Route::middleware('auth')->group(function () {
//    Route::view('/dashboard', 'dashboard')->name('dashboard');
//});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');


    // Contract routes
    Route::prefix('contracts')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('contracts.index');
        Route::get('upload', [ContractController::class, 'showUploadForm'])->name('contracts.showUploadForm');
        Route::post('upload', [ContractController::class, 'upload'])->name('contracts.upload');
        Route::post('export-pdf/{id}', [ContractController::class, 'exportPdf'])->name('export-pdf');
    });


    // Advertisement routes
    Route::prefix('advertisements')->group(function () {
        Route::get('/', [AdvertisementController::class, 'index'])->name('advertisements.index');
        Route::get('create', [AdvertisementController::class, 'create'])->name('advertisements.create');
        Route::post('/', [AdvertisementController::class, 'store'])->name('advertisements.store');
        Route::get('{advertisement}', [AdvertisementController::class, 'show'])->name('advertisements.show');

    });


    // Rental routes
    Route::get('calendar', function () {
        return view('calendar');
    })->name('calendar');



    // Advertisement CSV upload routes
    Route::prefix('advertisement')->group(function () {
        Route::get('upload-csv', [AdvertisementCrudController::class, 'showUploadForm'])->name('crud.advertisement.uploadCsvForm');
        Route::post('upload-csv', [AdvertisementCrudController::class, 'uploadCsv'])->name('crud.advertisement.uploadCsv');
    });
});


// Locale switcher route
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, config('languages.available'))) {
        App::setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');
