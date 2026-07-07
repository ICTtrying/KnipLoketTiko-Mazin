<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVerkoopprijsWijzigenRequest;
use App\Models\Behandeling;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Controller voor het behandelingenoverzicht (User Story 05) en het wijzigen
 * van de verkoopprijs van een product binnen een behandeling (User Story 06).
 */
class BehandelingController extends Controller
{
    /**
     * Toon het overzicht van alle behandelingen, optioneel gefilterd op naam.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $gevalideerd = $request->validate([
                'behandeling' => ['nullable', 'string', 'max:100'],
            ], [
                'behandeling.max' => 'De geselecteerde behandeling is ongeldig.',
            ]);

            $naam = $gevalideerd['behandeling'] ?? 'Alle behandelingen';
            Log::info('Behandelingenoverzicht opgevraagd', ['behandeling' => $naam]);

            $behandelingen = $this->haalBehandelingenOp($naam);
            $paginator = $this->maakPaginatie($behandelingen, $request, 4);

            return view('behandelingen.index', [
                'behandelingen' => $paginator,
                'behandelingNamen' => $this->haalActieveBehandelingNamenOp(),
                'geselecteerdeBehandeling' => $naam,
                // Tekst exact volgens de user story, inclusief de tikfout 'bekent'
                'legeMelding' => $behandelingen->isEmpty() ? 'Er zijn geen behandelingen bekent met deze naam' : null,
            ]);
        } catch (ValidationException $e) {
            Log::warning('Validatiefout bij behandelingenfilter', ['errors' => $e->errors()]);

            return redirect()->route('behandelingen.index')->withErrors($e->errors());
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen behandelingenoverzicht', ['error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de behandelingen.');
        }
    }

    /**
     * Toon de producten die bij een specifieke behandeling horen (Wireframe-03).
     */
    public function producten(int $behandelingId): View|RedirectResponse
    {
        try {
            Log::info('Producten per behandeling opgevraagd', ['behandeling_id' => $behandelingId]);

            $behandeling = Behandeling::query()->where('IsActief', 1)->findOrFail($behandelingId);
            $producten = $this->haalProductenPerBehandelingOp($behandelingId);

            return view('behandelingen.producten', [
                'behandeling' => $behandeling,
                'producten' => $producten,
            ]);
        } catch (HttpExceptionInterface|ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen producten per behandeling', ['behandeling_id' => $behandelingId, 'error' => $e->getMessage()]);

            return redirect()->route('behandelingen.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de producten.');
        }
    }

    /**
     * Toon de detailpagina van één product (Wireframe-04).
     */
    public function productDetail(int $productId): View|RedirectResponse
    {
        try {
            Log::info('Productdetail opgevraagd', ['product_id' => $productId]);

            $product = $this->haalProductDetailOp($productId);

            if ($product === null) {
                abort(404, 'Product niet gevonden.');
            }

            return view('behandelingen.productdetail', [
                'product' => $product,
            ]);
        } catch (HttpExceptionInterface $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen productdetail', ['product_id' => $productId, 'error' => $e->getMessage()]);

            return redirect()->route('behandelingen.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van het product.');
        }
    }

    /**
     * Toon het wijzigformulier voor de verkoopprijs van een product (Wireframe-05).
     */
    public function wijzigForm(int $productId): View|RedirectResponse
    {
        try {
            Log::info('Product wijzig-formulier geopend', ['product_id' => $productId]);

            $product = $this->haalProductDetailOp($productId);

            if ($product === null) {
                abort(404, 'Product niet gevonden.');
            }

            return view('behandelingen.wijzigen', [
                'product' => $product,
            ]);
        } catch (HttpExceptionInterface $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Fout bij openen wijzig-formulier product', ['product_id' => $productId, 'error' => $e->getMessage()]);

            return redirect()->route('behandelingen.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van het product.');
        }
    }

    /**
     * Verwerk het wijzigen van de verkoopprijs en opmerking van een product.
     *
     * De 30 procent-regel wordt drie keer afgedwongen: client-side (JS),
     * server-side (Form Request) en in de stored procedure (SIGNAL).
     */
    public function wijzigOpslaan(ProductVerkoopprijsWijzigenRequest $request, int $productId): RedirectResponse
    {
        try {
            $gevalideerd = $request->validated();

            Log::info('Poging tot wijzigen verkoopprijs product', [
                'product_id' => $productId,
                'nieuwe_verkoopprijs' => $gevalideerd['nieuwe_verkoopprijs'],
            ]);

            [$succes, $foutmelding] = $this->wijzigVerkoopprijs(
                $productId,
                (float) $gevalideerd['nieuwe_verkoopprijs'],
                $gevalideerd['opmerking'] ?? null,
            );

            if (! $succes) {
                Log::warning('Wijzigen verkoopprijs geweigerd door businessregel', [
                    'product_id' => $productId,
                    'reden' => $foutmelding,
                ]);

                return back()
                    ->withInput()
                    ->with('foutmelding', 'Gegevens niet bijgewerkt')
                    ->withErrors(['nieuwe_verkoopprijs' => $foutmelding ?? 'De verkoopprijs kon niet worden gewijzigd.']);
            }

            Log::info('Verkoopprijs product succesvol gewijzigd', ['product_id' => $productId]);

            return redirect()
                ->route('behandelingen.product.detail', $productId)
                ->with('succesmelding', 'Productprijs bijgewerkt');
        } catch (Throwable $e) {
            Log::error('Fout bij wijzigen verkoopprijs product', ['product_id' => $productId, 'error' => $e->getMessage()]);

            return back()->withInput()->with('foutmelding', 'Er is een onverwachte fout opgetreden.');
        }
    }

    /**
     * Haal het behandelingenoverzicht op via stored procedure of query-fallback,
     * met het aantal gekoppelde producten per behandeling.
     */
    private function haalBehandelingenOp(string $naam): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_behandelingen_overzicht(?)', [
                $naam === 'Alle behandelingen' ? null : $naam,
            ]));
        }

        return collect(DB::table('Behandeling as b')
            ->leftJoin('BehandelingPerVoorraad as bpv', function ($join): void {
                $join->on('bpv.BehandelingId', '=', 'b.Id')->where('bpv.IsActief', '=', 1);
            })
            ->leftJoin('Voorraad as v', function ($join): void {
                $join->on('v.Id', '=', 'bpv.VoorraadId')->where('v.IsActief', '=', 1);
            })
            ->leftJoin('Product as p', function ($join): void {
                $join->on('p.Id', '=', 'v.ProductId')->where('p.IsActief', '=', 1);
            })
            ->where('b.IsActief', 1)
            ->when($naam !== 'Alle behandelingen', fn ($query) => $query->where('b.Naam', $naam))
            ->groupBy('b.Id', 'b.Naam', 'b.Omschrijving', 'b.DuurMinuten', 'b.Prijs')
            ->orderBy('b.Id')
            ->selectRaw('b.Id AS BehandelingId, b.Naam, b.Omschrijving, b.DuurMinuten, b.Prijs, COUNT(p.Id) AS AantalProducten')
            ->get());
    }

    /**
     * Haal de actieve behandelnamen op voor het filter-dropdownmenu.
     */
    private function haalActieveBehandelingNamenOp(): Collection
    {
        return collect(DB::select('SELECT Naam FROM Behandeling WHERE IsActief = 1 ORDER BY Id ASC'))
            ->pluck('Naam');
    }

    /**
     * Haal de producten van één behandeling op via stored procedure of query-fallback.
     */
    private function haalProductenPerBehandelingOp(int $behandelingId): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_producten_per_behandeling(?)', [$behandelingId]));
        }

        return collect(DB::table('Behandeling as b')
            ->join('BehandelingPerVoorraad as bpv', function ($join): void {
                $join->on('bpv.BehandelingId', '=', 'b.Id')->where('bpv.IsActief', '=', 1);
            })
            ->join('Voorraad as v', function ($join): void {
                $join->on('v.Id', '=', 'bpv.VoorraadId')->where('v.IsActief', '=', 1);
            })
            ->join('Product as p', function ($join): void {
                $join->on('p.Id', '=', 'v.ProductId')->where('p.IsActief', '=', 1);
            })
            ->where('b.Id', $behandelingId)
            ->where('b.IsActief', 1)
            ->orderBy('p.Naam')
            ->selectRaw('p.Id AS ProductId, p.Naam, p.Merk, p.Omschrijving, p.EANcode, v.AantalOpVoorraad, p.VerkoopPrijs')
            ->get());
    }

    /**
     * Haal de detailgegevens van één product op via stored procedure of query-fallback,
     * inclusief de leverancier van de meest recente actieve leveranciersorder.
     */
    private function haalProductDetailOp(int $productId): ?object
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_product_detail(?)', [$productId]))->first();
        }

        return DB::table('Product as p')
            ->leftJoin('Voorraad as v', function ($join): void {
                $join->on('v.ProductId', '=', 'p.Id')->where('v.IsActief', '=', 1);
            })
            ->leftJoin('LeverancierOrder as lo', function ($join): void {
                $join->on('lo.ProductId', '=', 'p.Id')
                    ->where('lo.IsActief', '=', 1)
                    ->whereRaw('lo.Id = (SELECT MAX(lo2.Id) FROM LeverancierOrder lo2 WHERE lo2.ProductId = p.Id AND lo2.IsActief = 1)');
            })
            ->leftJoin('Leverancier as l', function ($join): void {
                $join->on('l.Id', '=', 'lo.LeverancierId')->where('l.IsActief', '=', 1);
            })
            ->where('p.Id', $productId)
            ->where('p.IsActief', 1)
            ->selectRaw('p.Id AS ProductId, p.Naam, p.Merk, p.Omschrijving, p.EANcode, p.Houdbaarheidsdatum, p.InkoopPrijs, p.VerkoopPrijs, COALESCE(v.AantalOpVoorraad, 0) AS AantalOpVoorraad, l.Naam AS LeverancierNaam, l.Postcode AS LeverancierPostcode, l.Plaats AS LeverancierPlaats, l.Email AS LeverancierEmail, l.Mobiel AS LeverancierMobiel, p.Opmerking')
            ->first();
    }

    /**
     * Wijzig de verkoopprijs en opmerking van een product via stored procedure of query-fallback.
     * De businessregel (minimaal 30 procent boven de inkoopprijs) zit in de stored procedure;
     * de fallback spiegelt die regel voor de testomgeving.
     *
     * @return array{0: bool, 1: ?string}
     */
    private function wijzigVerkoopprijs(int $productId, float $nieuweVerkoopprijs, ?string $nieuweOpmerking): array
    {
        if ($this->gebruiktStoredProcedures()) {
            try {
                DB::statement('CALL sp_product_verkoopprijs_bijwerken(?, ?, ?)', [
                    $productId,
                    $nieuweVerkoopprijs,
                    $nieuweOpmerking,
                ]);

                return [true, null];
            } catch (Throwable $e) {
                // De procedure gooit SQLSTATE 45000 met een leesbare melding als een businessregel faalt
                if (str_contains($e->getMessage(), 'Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen')) {
                    return [false, 'Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen'];
                }

                if (str_contains($e->getMessage(), 'Product niet gevonden')) {
                    return [false, 'Product niet gevonden'];
                }

                throw $e;
            }
        }

        $inkoopprijs = DB::table('Product')
            ->where('Id', $productId)
            ->where('IsActief', 1)
            ->value('InkoopPrijs');

        if ($inkoopprijs === null) {
            return [false, 'Product niet gevonden'];
        }

        if ($nieuweVerkoopprijs < (float) $inkoopprijs * 1.30) {
            return [false, 'Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen'];
        }

        DB::table('Product')
            ->where('Id', $productId)
            ->update([
                'VerkoopPrijs' => $nieuweVerkoopprijs,
                'Opmerking' => $nieuweOpmerking,
                'DatumGewijzigd' => now(),
            ]);

        return [true, null];
    }
}
