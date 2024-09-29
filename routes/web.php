<?php

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Product\IndividualProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('products/create', [IndividualProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [IndividualProductController::class, 'store'])->name('products.store');


    Route::get('products/edit/{id}', [IndividualProductController::class, 'edit'])->name('products.edit');

    Route::put('/products/update/{product}', [IndividualProductController::class, 'update'])->name('products.update');

    Route::post('products/upload-media', [IndividualProductController::class, 'uploadMedia'])->name('products.uploadMedia');
    Route::post('products/delete-media', [IndividualProductController::class, 'deleteMedia'])->name('products.deleteMedia');

});
