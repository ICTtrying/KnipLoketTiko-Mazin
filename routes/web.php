<?php

use App\Http\Controllers\BestellingController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Routes voor het productenoverzicht (User Story 07) en productdetail/wijzigen (User Story 08)
Route::prefix('producten')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/wijzigen', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}/wijzigen', [ProductController::class, 'update'])->name('update');
});

Route::prefix('bestellingen')->name('bestellingen.')->group(function () {
    Route::get('/', [BestellingController::class, 'index'])->name('index');
    Route::get('/{id}', [BestellingController::class, 'show'])->name('show');
    Route::get('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'editProduct'])->name('producten.wijzigen');
    Route::put('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'updateProduct'])->name('producten.update');
});
