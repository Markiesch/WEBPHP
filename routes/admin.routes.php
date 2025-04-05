<?php

use App\Http\Controllers\Admin\ContractController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Contract generation
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('{business}/show', [ContractController::class, 'show'])->name('show');
        Route::post('{business}/generate', [ContractController::class, 'generatePdf'])->name('generate-pdf');
        Route::get('{business}/upload', [ContractController::class, 'showUpload'])->name('upload');
        Route::post('{business}/upload', [ContractController::class, 'upload'])->name('upload.store');
        Route::post('{business}/status', [ContractController::class, 'updateStatus'])->name('update-status');
    });
});
