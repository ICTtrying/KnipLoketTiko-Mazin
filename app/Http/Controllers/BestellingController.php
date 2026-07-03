<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
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
 * Controller voor het beheren van bestellingen.
 */
class BestellingController extends Controller
{
    /**
     * Toon het overzicht van alle bestellingen.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $status = (string) $request->input('status', 'Alle statussen');
            Log::info('Bestellingen overzicht opgevraagd', ['status' => $status]);

            $bestellingen = $this->haalBestellingenOp($status);

            $paginator = $this->maakPaginatie($bestellingen, $request, 4);

            return view('bestellingen.index', [
                'bestellingen' => $paginator,
                'geselecteerdeStatus' => $status,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen bestellingen overzicht', ['error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de bestellingen.');
        }
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function create(): RedirectResponse
    {
        try {
            abort(404);
        } catch (Throwable $e) {
            Log::error('Fout bij create bestelling', ['error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            abort(404);
        } catch (Throwable $e) {
            Log::error('Fout bij store bestelling', ['error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Toon de producten die bij een specifieke bestelling horen.
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            Log::info('Producten per bestelling opgevraagd', ['bestelling_id' => $id]);

            $bestelling = Bestelling::query()->findOrFail($id);
            $producten = $this->haalProductenPerBestellingOp($id);

            return view('bestellingen.producten', [
                'bestelling' => $bestelling,
                'producten' => $producten,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen producten per bestelling', ['bestelling_id' => $id, 'error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de producten.');
        }
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function edit(int $id): RedirectResponse
    {
        try {
            abort(404);
        } catch (Throwable $e) {
            Log::error('Fout bij edit bestelling', ['error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            abort(404);
        } catch (Throwable $e) {
            Log::error('Fout bij update bestelling', ['error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Niet gebruikt, maar aanwezig voor CRUD-consistentie.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            abort(404);
        } catch (Throwable $e) {
            Log::error('Fout bij destroy bestelling', ['error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Toon het wijzigformulier voor een bestelproduct.
     */
    public function editProduct(int $bestellingId, int $id): View|RedirectResponse
    {
        try {
            Log::info('Bestelproduct wijzig-formulier geopend', ['id' => $id]);

            $bestelling = Bestelling::query()->findOrFail($bestellingId);
            $bestelproduct = $this->haalBestelproductOp($id);

            if ($bestelproduct === null) {
                abort(404, 'Bestelproduct niet gevonden.');
            }

            return view('bestellingen.wijzigen', [
                'bestelling' => $bestelling,
                'bestelproduct' => $bestelproduct,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij openen wijzig-formulier bestelproduct', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Verwerk het wijzigen van het aantal van een bestelproduct.
     */
    public function updateProduct(Request $request, int $bestellingId, int $id): RedirectResponse
    {
        try {
            $gevalideerd = $request->validate([
                'aantal' => ['required', 'integer', 'min:1'],
            ], [
                'aantal.required' => 'Het veld Aantal is verplicht.',
                'aantal.integer' => 'Het veld Aantal moet een geheel getal zijn.',
                'aantal.min' => 'Het aantal moet minimaal 1 zijn.',
            ]);

            Log::info('Poging tot wijzigen bestelproduct', [
                'id' => $id,
                'nieuw_aantal' => $gevalideerd['aantal'],
            ]);

            [$succes, $foutmelding] = $this->wijzigBestelproduct($id, (int) $gevalideerd['aantal']);

            if (! $succes) {
                Log::warning('Wijzigen bestelproduct geweigerd door businessregel', [
                    'id' => $id,
                    'reden' => $foutmelding,
                ]);

                return back()
                    ->withInput()
                    ->with('foutmelding', 'Gegevens zijn niet gewijzigd')
                    ->withErrors(['aantal' => $foutmelding ?? 'Het aantal kon niet worden gewijzigd.']);
            }

            Log::info('Bestelproduct succesvol gewijzigd', ['id' => $id]);

            return redirect()
                ->route('bestellingen.show', $bestellingId)
                ->with('succesmelding', 'Aantal producten bijgewerkt.');
        } catch (ValidationException $e) {
            Log::warning('Validatiefout bij wijzigen bestelproduct', ['id' => $id, 'errors' => $e->errors()]);

            return back()->withInput()->with('foutmelding', 'Gegevens zijn niet gewijzigd')->withErrors($e->errors());
        } catch (Throwable $e) {
            Log::error('Fout bij wijzigen bestelproduct', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een onverwachte fout opgetreden.');
        }
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

    /**
     * Controleer of de database stored procedures ondersteunt.
     */
    private function gebruiktStoredProcedures(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);
    }

    /**
     * Haal het bestellingenoverzicht op via stored procedure of query fallback.
     */
    private function haalBestellingenOp(string $status): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_bestellingen_overzicht(?)', [
                $status === 'Alle statussen' ? null : $status,
            ]));
        }

        return collect(DB::table('Bestelling as b')
            ->join('Klant as k', 'k.Id', '=', 'b.KlantId')
            ->leftJoin('ProductPerBestelling as ppb', function ($join): void {
                $join->on('ppb.BestellingId', '=', 'b.Id')
                    ->where('ppb.IsActief', '=', 1);
            })
            ->where('b.IsActief', 1)
            ->when($status !== 'Alle statussen', fn ($query) => $query->where('b.Bestelstatus', $status))
            ->groupBy('b.Id', 'b.BestelNummer', 'k.Naam', 'k.Id', 'b.Datum', 'b.Tijd', 'b.Bestelstatus')
            ->orderByDesc('b.Datum')
            ->orderByDesc('b.Tijd')
            ->selectRaw('b.Id AS BestellingId, b.BestelNummer, k.Id AS KlantId, k.Naam AS KlantNaam, b.Datum, b.Tijd, b.Bestelstatus, COUNT(ppb.Id) AS AantalProducten, COALESCE(SUM(ppb.UnitPrijs * ppb.Aantal * (1 - ppb.Korting / 100) * (1 + ppb.BTWPercentage / 100)), 0) AS Totaal')
            ->get()
            ->map(function ($rij): object {
                $rij->Relatienummer = sprintf('KL-2026-%03d', $rij->KlantId);

                return $rij;
            }));
    }

    /**
     * Haal de productregels per bestelling op via stored procedure of query fallback.
     */
    private function haalProductenPerBestellingOp(int $bestellingId): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_bestelling_producten_overzicht(?)', [$bestellingId]));
        }

        return collect(DB::table('ProductPerBestelling as ppb')
            ->join('Bestelling as b', 'b.Id', '=', 'ppb.BestellingId')
            ->join('Klant as k', 'k.Id', '=', 'b.KlantId')
            ->join('Product as p', 'p.Id', '=', 'ppb.ProductId')
            ->join('Categorie as c', 'c.Id', '=', 'p.CategorieId')
            ->where('ppb.IsActief', 1)
            ->where('b.IsActief', 1)
            ->where('b.Id', $bestellingId)
            ->orderBy('p.Naam')
            ->selectRaw('b.Id AS BestellingId, b.BestelNummer, b.Bestelstatus, k.Id AS KlantId, k.Naam AS KlantNaam, ppb.Id AS ProductPerBestellingId, p.Id AS ProductId, p.Naam AS ProductNaam, c.Naam AS CategorieNaam, p.Merk, ppb.Aantal, ppb.UnitPrijs, ppb.BTWPercentage, ppb.Korting, (ppb.UnitPrijs * ppb.Aantal * (1 - ppb.Korting / 100) * (1 + ppb.BTWPercentage / 100)) AS RegelTotaal')
            ->get()
            ->map(function ($rij): object {
                $rij->Relatienummer = sprintf('KL-2026-%03d', $rij->KlantId);

                return $rij;
            }));
    }

    /**
     * Haal één bestelproduct op voor het wijzigformulier.
     */
    private function haalBestelproductOp(int $productPerBestellingId): object|null
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_bestelproduct_ophalen(?)', [$productPerBestellingId]))->first();
        }

        return DB::table('ProductPerBestelling as ppb')
            ->join('Bestelling as b', 'b.Id', '=', 'ppb.BestellingId')
            ->join('Klant as k', 'k.Id', '=', 'b.KlantId')
            ->join('Product as p', 'p.Id', '=', 'ppb.ProductId')
            ->join('Categorie as c', 'c.Id', '=', 'p.CategorieId')
            ->where('ppb.Id', $productPerBestellingId)
            ->where('ppb.IsActief', 1)
            ->where('b.IsActief', 1)
            ->limit(1)
            ->selectRaw('b.Id AS BestellingId, b.BestelNummer, b.Bestelstatus, k.Id AS KlantId, k.Naam AS KlantNaam, ppb.Id AS ProductPerBestellingId, p.Naam AS ProductNaam, c.Naam AS CategorieNaam, p.Merk, ppb.UnitPrijs, ppb.Aantal')
            ->first();

        if ($bestelproduct !== null) {
            $bestelproduct->Relatienummer = sprintf('KL-2026-%03d', $bestelproduct->KlantId);
        }

        return $bestelproduct;
    }

    /**
     * Wijzig het aantal van een bestelproduct via stored procedure of query fallback.
     *
     * @return array{0: bool, 1: ?string}
     */
    private function wijzigBestelproduct(int $productPerBestellingId, int $nieuwAantal): array
    {
        if ($this->gebruiktStoredProcedures()) {
            DB::statement('SET @succes = 0');
            DB::statement('SET @foutmelding = NULL');
            DB::statement('CALL sp_bestelproduct_wijzigen(?, ?, @succes, @foutmelding)', [
                $productPerBestellingId,
                $nieuwAantal,
            ]);

            $resultaat = DB::select('SELECT @succes AS succes, @foutmelding AS foutmelding');

            return [
                (bool) ($resultaat[0]->succes ?? false),
                $resultaat[0]->foutmelding ?? null,
            ];
        }

        $bestelstatus = DB::table('ProductPerBestelling as ppb')
            ->join('Bestelling as b', 'b.Id', '=', 'ppb.BestellingId')
            ->where('ppb.Id', $productPerBestellingId)
            ->where('ppb.IsActief', 1)
            ->where('b.IsActief', 1)
            ->value('b.Bestelstatus');

        if ($bestelstatus === null) {
            return [false, 'Bestelproduct niet gevonden'];
        }

        if ($bestelstatus === 'Afgeleverd') {
            return [false, 'Aantal kan niet worden gewijzigd omdat de bestelling al is afgeleverd'];
        }

        DB::table('ProductPerBestelling')
            ->where('Id', $productPerBestellingId)
            ->update([
                'Aantal' => $nieuwAantal,
                'DatumGewijzigd' => now(),
            ]);

        return [true, null];
    }
}
