<?php

use App\Http\Controllers\BestellingController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Routes voor het productenoverzicht (User Story 07); wijzigen (User Story 08) volgt later
Route::prefix('producten')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
});

Route::prefix('bestellingen')->name('bestellingen.')->group(function () {
    Route::get('/', [BestellingController::class, 'index'])->name('index');
    Route::get('/{id}', [BestellingController::class, 'show'])->name('show');
    Route::get('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'editProduct'])->name('producten.wijzigen');
    Route::put('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'updateProduct'])->name('producten.update');
});
