<?php

namespace App\Http\Controllers;

// Importeer de benodigde modellen en klassen
use App\Models\Behandeling;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BehandelingController extends Controller
{
    // Private eigenschap voor de model-instantie
    private Behandeling $BehandelingModel;

    // Constructor: initialiseer het model bij het aanmaken van de controller
    public function __construct()
    {
        $this->BehandelingModel = new Behandeling;
    }

    // Haal alle behandelingen op en toon de overzichtspagina, met optioneel
    // een filter op behandelnaam via de select in het formulier
    public function index(Request $request)
    {
        try {
            // Haal eerst alle behandelingen ongefilterd op; deze lijst gebruiken we
            // zowel als standaardweergave als om de namen voor de select te bepalen
            $alleBehandelingen = $this->BehandelingModel->sp_PakAlleBehandelingen('Alle behandelingen');
            $behandelingNamen = collect($alleBehandelingen)->pluck('Naam')->unique()->values();
        } catch (\Exception $e) {
            // Vang onverwachte fouten op bij het ophalen van de behandelingen
            Log::error('Fout bij laden behandelingen: '.$e->getMessage());

        }

        // Server-side spiegel van de select-opties: alleen 'Alle behandelingen',
        // 'Overig' of een bestaande behandelnaam zijn geldig
        $validatedData = $request->validate([
            'behandeling' => ['nullable', 'string', Rule::in($behandelingNamen->concat(['Alle behandelingen', 'Overig']))],
        ]);

        // Zonder keuze (of expliciet 'Alle behandelingen') tonen we de volledige lijst
        $geselecteerdeBehandeling = $validatedData['behandeling'] ?? 'Alle behandelingen';

        try {
            // 'Overig' matcht bewust op geen enkele behandeling (scenario 2): de
            // stored procedure filtert dan op een naam die niet bestaat, en geeft
            // dus terecht een lege lijst terug
            $behandelingen = $geselecteerdeBehandeling === 'Alle behandelingen'
                ? $alleBehandelingen
                : $this->BehandelingModel->sp_PakAlleBehandelingen($geselecteerdeBehandeling);

            Log::info('Behandelingen succesvol geladen', ['behandeling' => $geselecteerdeBehandeling]);
        } catch (\Exception $e) {
            Log::error('Fout bij laden behandelingen: '.$e->getMessage());

        }

        // De stored procedure geeft een gewone array terug (geen Eloquent query builder),
        // dus we bouwen de paginatie hier zelf op met de resultaten die we al hebben
        $perPage = 10;
        $huidigePagina = LengthAwarePaginator::resolveCurrentPage();

        $behandelingenVoorPagina = collect($behandelingen)
            ->forPage($huidigePagina, $perPage)
            ->values();

        $behandelingenGepagineerd = new LengthAwarePaginator(
            $behandelingenVoorPagina,
            count($behandelingen),
            $perPage,
            $huidigePagina,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        // Stuur de behandelingen, de dropdown-namen en de huidige selectie door naar de view
        return view('behandelingen.index', [
            'title' => 'Behandelingen overzicht',
            'behandelingen' => $behandelingenGepagineerd,
            'behandelingCount' => count($behandelingen),
            'behandelingNamen' => $behandelingNamen,
            'geselecteerdeBehandeling' => $geselecteerdeBehandeling,
        ]);
    }

    // Toon alle producten die bij een specifieke behandeling horen
    public function producten($id)
    {
        try {
            // Zoek de producten op via het opgegeven behandeling-ID
            $behandelingnaam = $this->BehandelingModel->find($id, ['Naam'])->Naam ?? 'Onbekend';
            $producten = $this->BehandelingModel->sp_PakProductenPerBehandeling($id);

            // Als er geen producten zijn, log een waarschuwing maar toon wel de (lege) pagina
            if (! $producten) {
                Log::warning('Geen producten gevonden voor behandeling: '.$id);
            } else {
                Log::info('Producten succesvol geladen voor behandeling: '.$id);
            }

        } catch (\Exception $e) {
            // Vang onverwachte fouten op bij het ophalen van de producten
            Log::error('Fout bij laden producten per behandeling: '.$e->getMessage());

        }

        // Stuur de producten door naar de bijbehorende view
        return view('behandelingen.producten', [
            'title' => 'Producten per behandeling',
            'producten' => $producten,
            'behandelingId' => $id,
            'behandelingnaam' => $behandelingnaam,
        ]);
    }

    // Toon de detailpagina van één specifiek product
    public function productDetail($id)
    {
        try {
            // Zoek het product op via het opgegeven product-ID
            $product = $this->BehandelingModel->sp_PakProductDetail($id);

            // Als het product niet bestaat, log een waarschuwing en stuur terug met foutmelding
            if (! $product) {
                Log::warning('Product niet gevonden: '.$id);

                return redirect()->route('behandelingen.index')->with('error', 'Product niet gevonden.');
            }

            // Product succesvol geladen, log ter bevestiging
            Log::info('Product succesvol geladen: '.$id);
        } catch (\Exception $e) {
            // Vang onverwachte fouten op bij het ophalen van het product
            Log::error('Fout bij laden productdetail: '.$e->getMessage());

            return redirect()->route('behandelingen.index')->with('error', 'Fout bij het laden van het product.');
        }

        // Stuur de productdata door naar de detail-view
        return view('behandelingen.productdetail', [
            'title' => 'Productdetail',
            'product' => $product,
        ]);

    }

    public function edit($id)
    {
        try {
            $product = $this->BehandelingModel->sp_PakProductDetail($id);

            Log::info('Product succesvol geladen voor bewerking: '.$id);
        } catch (\Exception $e) {
            Log::error('Fout bij laden product voor bewerking: '.$e->getMessage());

            return redirect()->route('behandelingen.index')->with('error', 'Fout bij het laden van het product.');
        }

        return view('behandelingen.edit', [
            'title' => 'Behandeling wijzigen',
            'behandelingId' => $id,
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Valideer de inkomende gegevens
        try {
            $validated = $request->validate([
                'nieuwe_verkoopprijs' => 'required|numeric|min:0',
                'opmerking' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validatiefout bij bijwerken product: '.$id, ['errors' => $e->errors()]);

            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        try {
            // Werk de verkoopprijs en opmerking bij via de stored procedure
            $this->BehandelingModel->sp_UpdateProductPrijs(
                $id,
                $validated['nieuwe_verkoopprijs'],
                $validated['opmerking']
            );

            Log::info('Product succesvol bijgewerkt: '.$id);
        } catch (\Exception $e) {
            Log::error('Fout bij bijwerken product: '.$e->getMessage());

        }

        return redirect()->route('behandelingen.product.detail', ['product' => $id])->with('success', 'Product succesvol bijgewerkt.');
    }
}
