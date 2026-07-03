<?php

use App\Http\Controllers\BestellingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlantController;


Route::view('/', 'home')->name('home');

Route::prefix('bestellingen')->name('bestellingen.')->group(function () {
    Route::get('/', [BestellingController::class, 'index'])->name('index');
    Route::get('/{id}', [BestellingController::class, 'show'])->name('show');
    Route::get('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'editProduct'])->name('producten.wijzigen');
    Route::put('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'updateProduct'])->name('producten.update');
});
Route::prefix('klanten')->name('klanten.')->group(function () {
    Route::get('/', [KlantController::class, 'index'])->name('index');
    Route::get('/{id}', [KlantController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [KlantController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KlantController::class, 'update'])->name('update');
});