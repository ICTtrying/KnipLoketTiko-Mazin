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

            $bestellingen = collect(DB::select('CALL sp_bestellingen_overzicht(?)', [
                $status === 'Alle statussen' ? null : $status,
            ]));

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
            $producten = collect(DB::select('CALL sp_bestelling_producten_overzicht(?)', [$id]));

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
            $bestelproduct = collect(DB::select('CALL sp_bestelproduct_ophalen(?)', [$id]))->first();

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

            DB::statement('SET @succes = 0');
            DB::statement('SET @foutmelding = NULL');
            DB::statement('CALL sp_bestelproduct_wijzigen(?, ?, @succes, @foutmelding)', [
                $id,
                $gevalideerd['aantal'],
            ]);

            $resultaat = DB::select('SELECT @succes AS succes, @foutmelding AS foutmelding');
            $succes = (bool) ($resultaat[0]->succes ?? false);
            $foutmelding = $resultaat[0]->foutmelding ?? null;

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
}
