<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
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
     * Toon de detailpagina van één product (User Story 08, Wireframe-03).
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            Log::info('Productdetail opgevraagd', ['product_id' => $id]);

            $product = $this->haalProductDetailOp($id);

            if ($product === null) {
                abort(404, 'Product niet gevonden.');
            }

            return view('products.show', [
                'product' => $product,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen productdetail', ['product_id' => $id, 'error' => $e->getMessage()]);

            return redirect()->route('products.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van het product.');
        }
    }

    /**
     * Toon het wijzigformulier voor de houdbaarheidsdatum van een product
     * (User Story 08, Wireframe-04).
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            Log::info('Product wijzig-formulier geopend', ['product_id' => $id]);

            $product = $this->haalProductDetailOp($id);

            if ($product === null) {
                abort(404, 'Product niet gevonden.');
            }

            return view('products.update', [
                'product' => $product,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij openen wijzig-formulier product', ['product_id' => $id, 'error' => $e->getMessage()]);

            return redirect()->route('products.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van het product.');
        }
    }

    /**
     * Verwerk het wijzigen van de houdbaarheidsdatum van een product.
     *
     * Businessregel (User Story 08): de houdbaarheidsdatum mag met maximaal
     * 7 dagen worden verlengd; die controle zit in de stored procedure.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $gevalideerd = $request->validate([
                'nieuwe_houdbaarheidsdatum' => ['required', 'date'],
            ], [
                'nieuwe_houdbaarheidsdatum.required' => 'Het veld Nieuwe houdbaarheidsdatum is verplicht.',
                'nieuwe_houdbaarheidsdatum.date' => 'Het veld Nieuwe houdbaarheidsdatum moet een geldige datum zijn.',
            ]);

            Log::info('Poging tot wijzigen houdbaarheidsdatum product', [
                'product_id' => $id,
                'nieuwe_datum' => $gevalideerd['nieuwe_houdbaarheidsdatum'],
            ]);

            [$succes, $foutmelding] = $this->wijzigHoudbaarheidsdatum($id, $gevalideerd['nieuwe_houdbaarheidsdatum']);

            if (! $succes) {
                Log::warning('Wijzigen houdbaarheidsdatum geweigerd door businessregel', [
                    'product_id' => $id,
                    'reden' => $foutmelding,
                ]);

                return back()
                    ->withInput()
                    ->with('foutmelding', 'Gegevens niet bijgewerkt')
                    ->withErrors(['nieuwe_houdbaarheidsdatum' => $foutmelding ?? 'De houdbaarheidsdatum kon niet worden gewijzigd.']);
            }

            Log::info('Houdbaarheidsdatum product succesvol gewijzigd', ['product_id' => $id]);

            return redirect()
                ->route('products.show', $id)
                ->with('succesmelding', 'Houdbaarheidsdatum bijgewerkt');
        } catch (ValidationException $e) {
            Log::warning('Validatiefout bij wijzigen houdbaarheidsdatum', ['product_id' => $id, 'errors' => $e->errors()]);

            return back()->withInput()->with('foutmelding', 'Gegevens niet bijgewerkt')->withErrors($e->errors());
        } catch (Throwable $e) {
            Log::error('Fout bij wijzigen houdbaarheidsdatum product', ['product_id' => $id, 'error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een onverwachte fout opgetreden.');
        }
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function destroy(int $id): RedirectResponse
    {
        abort(404);
    }

    /**
     * Controleer of de database stored procedures ondersteunt.
     */
    private function gebruiktStoredProcedures(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);
    }

    /**
     * Haal het productenoverzicht op via de stored procedure GetAllProducten.
     *
     * De aanroep gebruikt een prepared statement met parameterbinding,
     * zodat SQL-injectie via het categorie-filter niet mogelijk is.
     * Voor databases zonder stored procedures (zoals SQLite in de tests)
     * wordt een gelijkwaardige query builder-fallback gebruikt.
     */
    private function haalProductenOp(?int $categorieId): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL GetAllProducten(?)', [$categorieId]));
        }

        return collect(DB::table('Product as p')
            ->join('Categorie as c', 'c.Id', '=', 'p.CategorieId')
            ->leftJoin('Voorraad as v', function ($join): void {
                $join->on('v.ProductId', '=', 'p.Id')
                    ->where('v.IsActief', '=', 1);
            })
            ->where('p.IsActief', 1)
            ->when($categorieId !== null && $categorieId !== 0, fn ($query) => $query->where('p.CategorieId', $categorieId))
            ->orderBy('p.Id')
            ->selectRaw('p.Id, p.Naam, c.Naam AS CategorieNaam, p.Merk, p.EANcode, p.VerkoopPrijs, COALESCE(v.AantalOpVoorraad, 0) AS AantalOpVoorraad')
            ->get());
    }

    /**
     * Haal de detailgegevens van één product op via de stored procedure GetProductDetail.
     *
     * Voor databases zonder stored procedures (zoals SQLite in de tests)
     * wordt een gelijkwaardige query builder-fallback gebruikt.
     */
    private function haalProductDetailOp(int $productId): object|null
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL GetProductDetail(?)', [$productId]))->first();
        }

        return DB::table('Product as p')
            ->leftJoin('Voorraad as v', function ($join): void {
                $join->on('v.ProductId', '=', 'p.Id')
                    ->where('v.IsActief', '=', 1);
            })
            ->leftJoin('LeverancierOrder as lo', function ($join): void {
                $join->on('lo.ProductId', '=', 'p.Id')
                    ->where('lo.IsActief', '=', 1)
                    ->whereRaw('lo.Id = (SELECT MAX(lo2.Id) FROM LeverancierOrder lo2 WHERE lo2.ProductId = p.Id AND lo2.IsActief = 1)');
            })
            ->leftJoin('Leverancier as l', function ($join): void {
                $join->on('l.Id', '=', 'lo.LeverancierId')
                    ->where('l.IsActief', '=', 1);
            })
            ->where('p.Id', $productId)
            ->where('p.IsActief', 1)
            ->selectRaw('p.Id, p.Naam, p.Merk, p.Omschrijving, p.EANcode, p.Houdbaarheidsdatum, p.InkoopPrijs, p.VerkoopPrijs, COALESCE(v.AantalOpVoorraad, 0) AS AantalOpVoorraad, l.Naam AS LeverancierNaam, l.Postcode AS LeverancierPostcode, l.Plaats AS LeverancierPlaats, l.Email AS LeverancierEmail, l.Mobiel AS LeverancierMobiel, p.Opmerking')
            ->first();
    }

    /**
     * Wijzig de houdbaarheidsdatum via stored procedure of query fallback.
     *
     * @return array{0: bool, 1: ?string}
     */
    private function wijzigHoudbaarheidsdatum(int $productId, string $nieuweDatum): array
    {
        if ($this->gebruiktStoredProcedures()) {
            DB::statement('SET @succes = 0');
            DB::statement('SET @foutmelding = NULL');
            DB::statement('CALL UpdateProductHoudbaarheidsdatum(?, ?, @succes, @foutmelding)', [
                $productId,
                $nieuweDatum,
            ]);

            $resultaat = DB::select('SELECT @succes AS succes, @foutmelding AS foutmelding');

            return [
                (bool) ($resultaat[0]->succes ?? false),
                $resultaat[0]->foutmelding ?? null,
            ];
        }

        $huidigeDatum = DB::table('Product')
            ->where('Id', $productId)
            ->where('IsActief', 1)
            ->value('Houdbaarheidsdatum');

        if ($huidigeDatum === null) {
            return [false, 'Product niet gevonden'];
        }

        if (Carbon::parse($huidigeDatum)->diffInDays(Carbon::parse($nieuweDatum), false) > 7) {
            return [false, 'De houdbaarheidsdatum is met meer dan 7 dagen verlengd.'];
        }

        DB::table('Product')
            ->where('Id', $productId)
            ->update([
                'Houdbaarheidsdatum' => $nieuweDatum,
                'DatumGewijzigd' => now(),
            ]);

        return [true, null];
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
