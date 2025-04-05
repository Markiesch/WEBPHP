<?php

use App\Http\Controllers\Admin\ContractController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Contracts management
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('upload', [ContractController::class, 'showUploadForm'])->name('showUploadForm');
        Route::post('upload', [ContractController::class, 'upload'])->name('upload');
        Route::get('{contract}/review', [ContractController::class, 'review'])->name('review');
        Route::post('{contract}/status', [ContractController::class, 'updateStatus'])->name('updateStatus');
        Route::post('{business}/generate-pdf', [ContractController::class, 'generatePdf'])->name('generate-pdf');
        Route::get('{contract}/download', [ContractController::class, 'download'])->name('download');
        Route::delete('{contract}', [ContractController::class, 'destroy'])->name('destroy');
    });
});
