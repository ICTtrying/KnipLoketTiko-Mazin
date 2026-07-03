<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        try {
            $gevalideerd = $request->validate([
                'categorie' => ['nullable', 'integer', 'min:0'],
            ], [
                'categorie.integer' => 'De geselecteerde categorie is ongeldig.',
                'categorie.min' => 'De geselecteerde categorie is ongeldig.',
            ]);

            $categorieId = isset($gevalideerd['categorie']) ? (int) $gevalideerd['categorie'] : null;
            Log::info('Productenoverzicht opgevraagd', ['categorie_id' => $categorieId]);

            $producten = $this->haalProductenOp($categorieId);
            $paginator = $this->maakPaginatie($producten, $request, 4);

            return view('products.index', [
                'producten' => $paginator,
                'categorieen' => $this->haalActieveCategorieenOp(),
                'geselecteerdeCategorieId' => $categorieId,
                // Terugkoppeling naar de gebruiker wanneer het filter geen producten oplevert
                'legeMelding' => $producten->isEmpty() ? 'Er zijn geen producten bekend binnen de geselecteerde categorie' : null,
            ]);
        } catch (ValidationException $e) {
            Log::warning('Validatiefout bij categorie-filter productenoverzicht', ['errors' => $e->errors()]);

            return redirect()->route('products.index')->withErrors($e->errors());
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen productenoverzicht', ['error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de producten.');
        }
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

    /**
     * Haal het productenoverzicht op via de stored procedure GetAllProducten.
     *
     * De aanroep gebruikt een prepared statement met parameterbinding,
     * zodat SQL-injectie via het categorie-filter niet mogelijk is.
     */
    private function haalProductenOp(?int $categorieId): Collection
    {
        return collect(DB::select('CALL GetAllProducten(?)', [$categorieId]));
    }

    /**
     * Haal de actieve categorieën op voor het filter-dropdownmenu.
     */
    private function haalActieveCategorieenOp(): Collection
    {
        return collect(DB::select('SELECT Id, Naam FROM Categorie WHERE IsActief = 1 ORDER BY Naam ASC'));
    }

    /**
     * Bouw een paginator op basis van een collectie.
     */
    private function maakPaginatie(Collection $items, Request $request, int $perPagina): LengthAwarePaginator
    {
        $huidigePagina = LengthAwarePaginator::resolveCurrentPage();
        $totaal = $items->count();
        $resultaten = $items->slice(($huidigePagina - 1) * $perPagina, $perPagina)->values();

        return new LengthAwarePaginator($resultaten, $totaal, $perPagina, $huidigePagina, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}
