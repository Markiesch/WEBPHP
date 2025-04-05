<?php

use App\Http\Controllers\Seller\AgendaController;
use App\Http\Controllers\Seller\BusinessEditorController;
use App\Http\Controllers\Seller\SellerAdvertisementController;
use App\Http\Controllers\Seller\ContractController;
use App\Http\Controllers\Seller\SellerAPIController;
use Illuminate\Support\Facades\Route;

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('seller')->group(function () {
        // Contract routes
        Route::prefix('contracts')->group(function () {
            Route::get('/', [ContractController::class, 'index'])->name('contracts.index');
            Route::get('upload', [ContractController::class, 'showUploadForm'])->name('contracts.showUploadForm');
            Route::post('upload', [ContractController::class, 'upload'])->name('contracts.upload');
            Route::post('export-pdf/{id}', [ContractController::class, 'exportPdf'])->name('export-pdf');
        });

        Route::prefix('advertisements')->group(function () {
            Route::get('upload', [SellerAdvertisementController::class, 'uploadCsv'])
                ->name('advertisements.upload-csv');
            Route::post('upload', [SellerAdvertisementController::class, 'processCsv'])
                ->name('advertisements.process-csv');

            // Resource routes
            Route::get('/', [SellerAdvertisementController::class, 'index'])
                ->name('advertisements.index');
            Route::get('create', [SellerAdvertisementController::class, 'create'])
                ->name('advertisements.create');
            Route::post('/', [SellerAdvertisementController::class, 'store'])
                ->name('advertisements.store');
            Route::get('{advertisement}', [SellerAdvertisementController::class, 'show'])
                ->name('advertisements.show');
            Route::get('{advertisement}/edit', [SellerAdvertisementController::class, 'edit'])
                ->name('advertisements.edit');
            Route::put('{advertisement}', [SellerAdvertisementController::class, 'update'])
                ->name('advertisements.update');

            // Related advertisements routes
            Route::get('{advertisement}/related', [SellerAdvertisementController::class, 'editRelated'])
                ->name('advertisements.edit-related');
            Route::put('{advertisement}/related', [SellerAdvertisementController::class, 'updateRelated'])
                ->name('advertisements.update-related');
        });

        // Business routes
        Route::prefix('business')->group(function () {
            Route::get('/', [BusinessEditorController::class, 'index'])->name('business.index');

            // Block management routes
            Route::put('blocks/{block}', [BusinessEditorController::class, 'updateBlock'])->name('business.blocks.update');
            Route::put('/', [BusinessEditorController::class, 'update'])->name('business.update');
            Route::post('blocks/order', [BusinessEditorController::class, 'updateOrder'])->name('business.blocks.order');
            Route::post('blocks/create', [BusinessEditorController::class, 'createBlock'])->name('business.blocks.create');
            Route::delete('blocks/{block}', [BusinessEditorController::class, 'deleteBlock'])->name('business.blocks.delete');
        });

        Route::prefix("agenda")->group(function () {
            Route::get('/', [AgendaController::class, 'index'])->name('agenda.index');
        });

        Route::prefix("api")->group(function () {
            Route::get('/', [SellerAPIController::class, 'index'])->name('api.index');
        });

        // Rental routes
        Route::get('calendar', function () {
            return view('calendar');
        })->name('calendar');
    });

    Route::get('locale/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'nl'])) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }
        return redirect()->back();
    })->name('locale');
});
