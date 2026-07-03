<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Controller voor het beheren van producten.
 *
 * User Story 07: overzicht van alle producten met categorie-filter.
 * User Story 08 (product wijzigen) wordt later aan deze controller toegevoegd.
 */
class ProductController extends Controller
{
    /**
     * Toon het overzicht van alle producten.
     */
    public function index(Request $request): View|RedirectResponse
    {
        return view('products.index', [
            'producten' => collect(),
            'categorieen' => collect(),
            'geselecteerdeCategorieId' => null,
        ]);
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function create(): RedirectResponse
    {
        abort(404);
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function store(Request $request): RedirectResponse
    {
        abort(404);
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function show(int $id): RedirectResponse
    {
        abort(404);
    }

    /**
     * Wordt ingevuld bij User Story 08 (product wijzigen).
     */
    public function edit(int $id): RedirectResponse
    {
        abort(404);
    }

    /**
     * Wordt ingevuld bij User Story 08 (product wijzigen).
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        abort(404);
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function destroy(int $id): RedirectResponse
    {
        abort(404);
    }
}
