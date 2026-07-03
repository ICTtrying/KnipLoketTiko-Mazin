<?php

use App\Http\Controllers\BehandelingController;
use App\Http\Controllers\BestellingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Routes voor het behandelingenoverzicht (User Story 05) en het wijzigen van de
// verkoopprijs van een product binnen een behandeling (User Story 06)
Route::prefix('behandelingen')->group(function () {
    Route::get('/', [BehandelingController::class, 'index'])->name('behandelingen.index');
    Route::get('/{behandeling}/producten', [BehandelingController::class, 'producten'])->name('behandelingen.producten');
    Route::get('/producten/{product}/details', [BehandelingController::class, 'productDetail'])->name('behandelingen.product.detail');
    Route::get('/producten/{product}/wijzigen', [BehandelingController::class, 'wijzigForm'])->name('behandelingen.product.wijzigen');
    Route::put('/producten/{product}/wijzigen', [BehandelingController::class, 'wijzigOpslaan'])->name('behandelingen.product.opslaan');
});

Route::prefix('bestellingen')->name('bestellingen.')->group(function () {
    Route::get('/', [BestellingController::class, 'index'])->name('index');
    Route::get('/{id}', [BestellingController::class, 'show'])->name('show');
    Route::get('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'editProduct'])->name('producten.wijzigen');
    Route::put('/{bestellingId}/producten/{id}/wijzigen', [BestellingController::class, 'updateProduct'])->name('producten.update');
});
