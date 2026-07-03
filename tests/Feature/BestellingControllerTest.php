<?php

namespace Tests\Feature;

use App\Models\Bestelling;
use App\Models\ProductPerBestelling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests voor BestellingController.
 *
 * De testdata komt via BestellingSeeder exact uit het gezaghebbende
 * create-script (bestellingen 600101 t/m 600120), zodat de tests hetzelfde
 * gedrag toetsen als de echte database.
 */
class BestellingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_overzicht_bestellingen_toont_alle_bestellingen(): void
    {
        $this->seed();

        $response = $this->get(route('bestellingen.index'));

        $response->assertStatus(200);
        $response->assertViewIs('bestellingen.index');
        $response->assertSee('Overzicht bestellingen');
        $response->assertSee('Gevonden bestellingen - 20 bestelling(en)');
        // Nieuwste bestelling (600105, 30-06-2026 10:30) staat op pagina 1,
        // met samengestelde klantnaam en het Relatienummer uit de Klant-tabel
        $response->assertSee('600105');
        $response->assertSee('Marieke van den Berg');
        $response->assertSee('KL-2026-005');
        // Totaal van 600105 inclusief korting en BTW, exact zoals de wireframe
        $response->assertSee('EUR 29,89');
    }

    public function test_overzicht_bestellingen_toont_melding_bij_geen_resultaten(): void
    {
        $this->seed();

        $response = $this->get(route('bestellingen.index', ['status' => 'Geannuleerd']));

        $response->assertStatus(200);
        $response->assertSee('Gevonden bestellingen - 0 bestelling(en)');
        $response->assertSee('Er zijn geen bestellingen bekend met deze status');
    }

    public function test_statusfilter_gebruikt_databasewaarde_en_toont_leesbaar_label(): void
    {
        $this->seed();

        // Filteren gebeurt op de exacte databasewaarde (zonder spatie) ...
        $response = $this->get(route('bestellingen.index', ['status' => 'Inverwerking']));

        $response->assertStatus(200);
        $response->assertSee('Gevonden bestellingen - 4 bestelling(en)');
        // ... terwijl de tabel het leesbare label (met spatie) toont
        $response->assertSee('In verwerking');
    }

    public function test_producten_per_bestelling_toont_productregels(): void
    {
        $this->seed();
        $bestelling = Bestelling::query()->where('BestelNummer', 600101)->firstOrFail();

        $response = $this->get(route('bestellingen.show', $bestelling->Id));

        $response->assertStatus(200);
        $response->assertViewIs('bestellingen.producten');
        // assertSeeText: de titel bestaat uit twee spans (rode statische tekst + zwart bestelnummer)
        $response->assertSeeText('Producten per bestelling 600101');
        $response->assertSee('Hydrating Shampoo');
        $response->assertSee('Repair Conditioner');
        $response->assertSee('Haarverzorging');
        $response->assertSee('Tiko Care');
        // Regeltotalen inclusief korting en BTW, exact zoals de wireframe
        $response->assertSee('EUR 36,18');
        $response->assertSee('EUR 18,46');
    }

    public function test_aantal_wijzigen_lukt_bij_niet_afgeleverde_bestelling(): void
    {
        $this->seed();
        $bestelling = Bestelling::query()->where('Bestelstatus', '!=', 'Afgeleverd')->firstOrFail();
        $product = ProductPerBestelling::query()->where('BestellingId', $bestelling->Id)->firstOrFail();
        $nieuwAantal = $product->Aantal + 1;

        $response = $this->put(route('bestellingen.producten.update', [
            'bestellingId' => $bestelling->Id,
            'id' => $product->Id,
        ]), ['aantal' => $nieuwAantal]);

        $response->assertRedirect(route('bestellingen.show', $bestelling->Id));
        $response->assertSessionHas('succesmelding', 'Aantal producten bijgewerkt.');
        $this->assertDatabaseHas('ProductPerBestelling', [
            'Id' => $product->Id,
            'Aantal' => $nieuwAantal,
        ]);
    }

    public function test_aantal_wijzigen_faalt_bij_afgeleverde_bestelling(): void
    {
        $this->seed();
        $bestelling = Bestelling::query()->where('Bestelstatus', 'Afgeleverd')->firstOrFail();
        $product = ProductPerBestelling::query()->where('BestellingId', $bestelling->Id)->firstOrFail();
        $oorspronkelijkAantal = $product->Aantal;

        $response = $this->put(route('bestellingen.producten.update', [
            'bestellingId' => $bestelling->Id,
            'id' => $product->Id,
        ]), ['aantal' => $oorspronkelijkAantal + 1]);

        $response->assertSessionHas('foutmelding', 'Gegevens zijn niet gewijzigd');
        $response->assertSessionHasErrors([
            'aantal' => 'Aantal kan niet worden gewijzigd omdat de bestelling al is afgeleverd.',
        ]);
        $this->assertDatabaseHas('ProductPerBestelling', [
            'Id' => $product->Id,
            'Aantal' => $oorspronkelijkAantal,
        ]);
    }

    public function test_aantal_wijzigen_vereist_geldige_invoer(): void
    {
        $this->seed();
        $bestelling = Bestelling::query()->where('Bestelstatus', '!=', 'Afgeleverd')->firstOrFail();
        $product = ProductPerBestelling::query()->where('BestellingId', $bestelling->Id)->firstOrFail();

        $response = $this->put(route('bestellingen.producten.update', [
            'bestellingId' => $bestelling->Id,
            'id' => $product->Id,
        ]), ['aantal' => '']);

        $response->assertSessionHasErrors('aantal');
        $response->assertSessionHas('foutmelding', 'Gegevens zijn niet gewijzigd');
    }
}
