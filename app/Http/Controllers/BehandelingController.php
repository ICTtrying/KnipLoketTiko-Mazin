<?php

namespace App\Http\Controllers;

use App\Models\Behandeling;
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
class BehandelingController extends Controller
{
    private Behandeling $behandeling;

    public function __construct(Behandeling $behandeling)
    {
        $this->behandeling = $behandeling;
    }

    public function index(Request $request): View|RedirectResponse
    {
        try {
            $status = (string) $request->input('status', 'Alle statussen');
            Log::info('Bestellingen overzicht opgevraagd', ['status' => $status]);

            $bestellingen = $this->haalBestellingenOp($status);

            $paginator = $this->maakPaginatie($bestellingen, $request, 4);

            return view('behandelingen.index', [
                'bestellingen' => $paginator,
                'geselecteerdeStatus' => $status,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen bestellingen overzicht', ['error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de bestellingen.');
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

            return view('behandelingen.producten', [
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
     * Verwerk het wijzigen van het aantal van een bestelproduct.
     */
    

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
    
}
