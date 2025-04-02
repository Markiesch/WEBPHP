<?php

use App\Http\Controllers\Admin\AdvertisementCrudController;
use App\Http\Controllers\Seller\SellerAdvertisementController;
use App\Http\Controllers\Seller\ContractController;
use Illuminate\Support\Facades\Route;

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('seller')->group(function () {
        Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');

        // Contract routes
        Route::prefix('contracts')->group(function () {
            Route::get('/', [ContractController::class, 'index'])->name('contracts.index');
            Route::get('upload', [ContractController::class, 'showUploadForm'])->name('contracts.showUploadForm');
            Route::post('upload', [ContractController::class, 'upload'])->name('contracts.upload');
            Route::post('export-pdf/{id}', [ContractController::class, 'exportPdf'])->name('export-pdf');
        });

        // Advertisement routes
        Route::prefix('advertisements')->group(function () {
            Route::get('/', [SellerAdvertisementController::class, 'index'])->name('advertisements.index');
            Route::get('create', [SellerAdvertisementController::class, 'create'])->name('advertisements.create');
            Route::post('/', [SellerAdvertisementController::class, 'store'])->name('advertisements.store');
            Route::get('{advertisement}', [SellerAdvertisementController::class, 'show'])->name('advertisements.show');
            // Advertisement CSV upload routes
            Route::get('upload-csv', [AdvertisementCrudController::class, 'showUploadForm'])->name('crud.advertisement.uploadCsvForm');
            Route::post('upload-csv', [AdvertisementCrudController::class, 'uploadCsv'])->name('crud.advertisement.uploadCsv');
        });

        // Rental routes
        Route::get('calendar', function () {
            return view('calendar');
        })->name('calendar');
    });
});
