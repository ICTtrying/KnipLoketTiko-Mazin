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
use Throwable;

/**
 * Controller voor het beheren van behandelingen.
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
            $status = (string) $request->input('status', 'Alle behandelingen');
            Log::info('Behandelingen overzicht opgevraagd', ['status' => $status]);

            // Beschikbare opties voor de statusfilter in de view
            $statusLabels = [
                'Knipppen',
                'Combi behandelingen',
                'Kleuren',
                'Permanent',
                'Extensions',
                'Overig'
            ];

            $behandelingen = $this->haalBehandelingenOp($status);
            $paginator = $this->maakPaginatie($behandelingen, $request, 4);

            return view('behandelingen.index', [
                'bestellingen' => $paginator, // We behouden de naam '$bestellingen' zodat de view niet breekt
                'geselecteerdeStatus' => $status,
                'statusLabels' => $statusLabels,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen behandelingen overzicht', ['error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de behandelingen.');
        }
    }

    /**
     * Toon de details van een specifieke behandeling.
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            Log::info('Behandeling details opgevraagd', ['behandeling_id' => $id]);

            $behandeling = Behandeling::query()->findOrFail($id);

            return view('behandelingen.show', [
                'behandeling' => $behandeling,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen behandeling details', ['behandeling_id' => $id, 'error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de behandeling.');
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
     * Haal het behandelingenoverzicht op via stored procedure of query fallback.
     */
    private function haalBehandelingenOp(string $status): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            // Zorg ervoor dat je stored procedure (indien aanwezig) overweg kan met deze parameters
            return collect(DB::select('CALL sp_behandelingen_overzicht(?)', [
                $status === 'Alle behandelingen' ? null : $status,
            ]));
        }

        return collect(DB::table('Behandeling as b')
            ->when($status !== 'Alle behandelingen', fn ($query) => $query->where('b.IsActief', $status))
            ->orderBy('b.Naam')
            ->selectRaw('
                b.Id AS BestellingId, -- Gefaket als BestellingId voor compatibiliteit met de view
                b.Naam AS BestelNummer, -- Gefaket als BestelNummer
                b.Omschrijving AS KlantNaam, -- Gefaket als KlantNaam
                b.DuurMinuten AS Relatienummer, -- Gefaket als Relatienummer
                b.DatumAangemaakt AS Datum, 
                b.DatumGewijzigd AS Tijd, 
                b.IsActief AS Bestelstatus, -- Map IsActief (0 of 1) naar de statuskolom
                b.DuurMinuten AS AantalProducten, 
                b.Prijs AS Totaal
            ')
            ->get());
    }
}