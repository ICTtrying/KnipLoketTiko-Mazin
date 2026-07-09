<?php

namespace App\Http\Controllers;

// Importeer de benodigde modellen en klassen
use App\Models\Behandeling;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BehandelingController extends Controller
{
    // Private eigenschap voor de model-instantie
    private Behandeling $BehandelingModel;

    // Constructor: initialiseer het model bij het aanmaken van de controller
    public function __construct()
    {
        $this->BehandelingModel = new Behandeling;
    }

    // Haal alle behandelingen op en toon de overzichtspagina
    public function index(Request $request)
    {
        try {
            $alleBehandelingen = $this->BehandelingModel->sp_PakAlleBehandelingen('Alle behandelingen');
            $behandelingNamen = collect($alleBehandelingen)->pluck('Naam')->unique()->values();

            // Direct valideren
            $validatedData = $request->validate([
                'behandeling' => ['nullable', 'string', Rule::in($behandelingNamen->concat(['Alle behandelingen', 'Overig']))],
            ]);

            $geselecteerdeBehandeling = $validatedData['behandeling'] ?? 'Alle behandelingen';

            $behandelingen = $geselecteerdeBehandeling === 'Alle behandelingen'
                ? $alleBehandelingen
                : $this->BehandelingModel->sp_PakAlleBehandelingen($geselecteerdeBehandeling);

            Log::info('Behandelingen succesvol geladen', ['behandeling' => $geselecteerdeBehandeling]);

            // Bouw de paginatie direct op
            $huidigePagina = LengthAwarePaginator::resolveCurrentPage();
            $behandelingenGepagineerd = new LengthAwarePaginator(
                collect($behandelingen)->forPage($huidigePagina, 4)->values(),
                count($behandelingen),
                4,
                $huidigePagina,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Direct de succesvolle view retourneren vanuit de try
            return view('behandelingen.index', [
                'title' => 'Behandelingen overzicht',
                'behandelingen' => $behandelingenGepagineerd,
                'behandelingCount' => count($behandelingen),
                'behandelingNamen' => $behandelingNamen,
                'geselecteerdeBehandeling' => $geselecteerdeBehandeling,
            ]);

        } catch (ValidationException $e) {
            Log::warning('Validatiefout bij behandelingenfilter', ['errors' => $e->errors()]);

            return redirect()->route('behandelingen.index')->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Fout bij laden behandelingen: '.$e->getMessage());
        }

        // Één centrale fout-return buiten de try-catch (geen return IN de catch)
        return redirect()->route('behandelingen.index')->with('error', 'Fout bij het laden van de behandelingen.');
    }

    // Toon alle producten die bij een specifieke behandeling horen
    public function producten($id)
    {
        try {
            $behandelingnaam = $this->BehandelingModel->find($id, ['Naam'])->Naam ?? 'Onbekend';
            $producten = $this->BehandelingModel->sp_PakProductenPerBehandeling($id);

            if (! $producten) {
                Log::warning('Geen producten gevonden voor behandeling: '.$id);
            } else {
                Log::info('Producten succesvol geladen voor behandeling: '.$id);
            }

            return view('behandelingen.producten', [
                'title' => 'Producten per behandeling',
                'producten' => $producten,
                'behandelingId' => $id,
                'behandelingnaam' => $behandelingnaam,
            ]);

        } catch (\Exception $e) {
            Log::error('Fout bij laden producten per behandeling: '.$e->getMessage());
        }

        return redirect()->route('behandelingen.index')->with('error', 'Fout bij het laden van de producten.');
    }

    // Toon de detailpagina van één specifiek product
    public function productDetail($id)
    {
        try {
            $product = $this->BehandelingModel->sp_PakProductDetail($id);

            if (! $product) {
                Log::warning('Product niet gevonden: '.$id);

                return redirect()->route('behandelingen.index')->with('error', 'Product niet gevonden.');
            }

            Log::info('Product succesvol geladen: '.$id);

            return view('behandelingen.productdetail', [
                'title' => 'Productdetail',
                'product' => $product,
            ]);

        } catch (\Exception $e) {
            Log::error('Fout bij laden productdetail: '.$e->getMessage());
        }

        return redirect()->route('behandelingen.index')->with('error', 'Fout bij het laden van het product.');
    }

    // Toon het wijzigformulier voor de verkoopprijs van een product
    public function edit($id)
    {
        try {
            $product = $this->BehandelingModel->sp_PakProductDetail($id);

            if (! $product) {
                Log::warning('Product niet gevonden voor bewerking: '.$id);

                return redirect()->route('behandelingen.index')->with('error', 'Product niet gevonden.');
            }

            Log::info('Product succesvol geladen voor bewerking: '.$id);

            return view('behandelingen.edit', [
                'title' => 'Behandeling wijzigen',
                'behandelingId' => $id,
                'product' => $product,
            ]);

        } catch (\Exception $e) {
            Log::error('Fout bij laden product voor bewerking: '.$e->getMessage());
        }

        return redirect()->route('behandelingen.index')->with('error', 'Fout bij het laden van het product.');
    }

    // Verwerk het formulier om de productprijs en opmerking aan te passen
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nieuwe_verkoopprijs' => 'required|numeric|min:0',
                'opmerking' => 'nullable|string|max:255',
            ]);

            $product = $this->BehandelingModel->sp_PakProductDetail($id);

            if (! $product) {
                Log::warning('Product niet gevonden bij bijwerken: '.$id);

                return redirect()->route('behandelingen.index')->with('error', 'Product niet gevonden.');
            }

            // Direct controleren tegen de 30% regel
            if ((float) $validated['nieuwe_verkoopprijs'] < ((float) $product->InkoopPrijs * 1.30)) {
                Log::warning('Verkoopprijs te laag opgegeven voor product: '.$id);

                return redirect()->back()
                    ->withErrors(['nieuwe_verkoopprijs' => 'Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen'])
                    ->with('error', 'Gegevens niet bijgewerkt')
                    ->withInput();
            }

            $this->BehandelingModel->sp_UpdateProductPrijs($id, $validated['nieuwe_verkoopprijs'], $validated['opmerking']);
            Log::info('Product succesvol bijgewerkt: '.$id);

            return redirect()->route('behandelingen.product.detail', ['product' => $id])->with('success', 'Product succesvol bijgewerkt.');

        } catch (ValidationException $e) {
            Log::warning('Validatiefout bij bijwerken product: '.$id, ['errors' => $e->errors()]);

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Fout bij bijwerken product: '.$e->getMessage());
        }

        // Fallback return als er in de try een database of serverfout optreedt
        return redirect()->back()->with('error', 'Fout bij het bijwerken van het product.')->withInput();
    }
}
