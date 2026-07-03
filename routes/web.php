<?php

use App\Http\Controllers\BehandelingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::prefix('behandelingen')->group(function () {
    Route::get('/', [BehandelingController::class, 'index'])->name('behandelingen.index');
    Route::get('/{id}', [BehandelingController::class, 'show'])->name('behandelingen.show');
    Route::get('/{behandelingId}/producten/{id}/wijzigen', [BehandelingController::class, 'edit'])->name('behandelingen.edit');
    Route::put('/{behandelingId}/producten/{id}/wijzigen', [BehandelingController::class, 'update'])->name('behandelingen.update');
});

Route::prefix('bestellingen')->group(function () {
    Route::get('/', [BehandelingController::class, 'index'])->name('bestellingen.index');
    Route::get('/bestelling/{id}', [BehandelingController::class, 'show'])->name('bestellingen.show');
    Route::get('/{bestellingId}/producten/{id}/wijzigen', [BehandelingController::class, 'edit'])->name('bestellingen.edit');
    Route::put('/{bestellingId}/producten/{id}/wijzigen', [BehandelingController::class, 'update'])->name('bestellingen.update');
});