<?php

namespace Tests\Feature;

use App\Models\Bestelling;
use App\Models\ProductPerBestelling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests voor BestellingController.
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
    }

    public function test_overzicht_bestellingen_toont_melding_bij_geen_resultaten(): void
    {
        $this->seed();

        $response = $this->get(route('bestellingen.index', ['status' => 'Geannuleerd']));

        $response->assertStatus(200);
        $response->assertSee('Er zijn geen bestellingen bekend met deze status');
    }

    public function test_producten_per_bestelling_toont_productregels(): void
    {
        $this->seed();
        $bestelling = Bestelling::query()->firstOrFail();

        $response = $this->get(route('bestellingen.show', $bestelling->Id));

        $response->assertStatus(200);
        $response->assertViewIs('bestellingen.producten');
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
        $response->assertSessionHasErrors('aantal');
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
    }
}
