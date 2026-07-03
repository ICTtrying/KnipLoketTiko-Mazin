<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests voor BehandelingController.
 *
 * User Story 05: overzicht behandelingen met naamfilter.
 * User Story 06: verkoopprijs van een product binnen een behandeling wijzigen.
 *
 * Testdata volgens het createscript: product 1 (Hydrating Shampoo) heeft
 * inkoopprijs 6,50 (30 procent-grens = 8,45) en verkoopprijs 14,95.
 */
class BehandelingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_overzicht_behandelingen_toont_alle_behandelingen(): void
    {
        $this->seed();

        $response = $this->get(route('behandelingen.index'));

        $response->assertStatus(200);
        $response->assertViewIs('behandelingen.index');
        $response->assertSee('Overzicht behandelingen');
        $response->assertSee('Gevonden behandelingen - 5 behandeling(en)');
        $response->assertSee('Knippen');
    }

    public function test_overzicht_behandelingen_filtert_op_naam(): void
    {
        $this->seed();

        $response = $this->get(route('behandelingen.index', ['behandeling' => 'Kleuren']));

        $response->assertStatus(200);
        $response->assertSee('Gevonden behandelingen - 1 behandeling(en)');
        $response->assertSee('Haar kleuren (diverse technieken).');
    }

    public function test_overzicht_toont_melding_bij_filter_overig(): void
    {
        $this->seed();

        $response = $this->get(route('behandelingen.index', ['behandeling' => 'Overig']));

        $response->assertStatus(200);
        $response->assertSee('Gevonden behandelingen - 0 behandeling(en)');
        // Tekst exact volgens de user story, inclusief de tikfout 'bekent'
        $response->assertSee('Er zijn geen behandelingen bekent met deze naam');
    }

    public function test_producten_per_behandeling_toont_gekoppelde_producten(): void
    {
        $this->seed();

        // Behandeling 2 = Combi behandelingen met drie gekoppelde producten
        $response = $this->get(route('behandelingen.producten', 2));

        $response->assertStatus(200);
        $response->assertViewIs('behandelingen.producten');
        $response->assertSee('Producten per behandeling');
        $response->assertSee('Combi behandelingen');
        $response->assertSee('Hydrating Shampoo');
        $response->assertSee('Repair Conditioner');
        $response->assertSee('Scalp Balance Masker');
    }

    public function test_productdetail_toont_product_met_leverancier(): void
    {
        $this->seed();

        $response = $this->get(route('behandelingen.product.detail', 1));

        $response->assertStatus(200);
        $response->assertViewIs('behandelingen.productdetail');
        $response->assertSee('Productdetail');
        $response->assertSee('Hydrating Shampoo');
        $response->assertSee('Van Duuren Haircosmetics');
        $response->assertSee('Utrecht');
    }

    public function test_wijzigformulier_toont_product_en_hint(): void
    {
        $this->seed();

        $response = $this->get(route('behandelingen.product.wijzigen', 1));

        $response->assertStatus(200);
        $response->assertViewIs('behandelingen.wijzigen');
        $response->assertSee('Product wijzigen');
        $response->assertSee('Nieuwe verkoopprijs');
        $response->assertSee('Minimaal 30 procent boven de inkoopprijs.');
    }

    public function test_verkoopprijs_wijzigen_lukt_bij_minimaal_30_procent_boven_inkoopprijs(): void
    {
        $this->seed();

        $response = $this->put(route('behandelingen.product.opslaan', 1), [
            'nieuwe_verkoopprijs' => '9.99',
            'opmerking' => 'Actieprijs zomer',
        ]);

        $response->assertRedirect(route('behandelingen.product.detail', 1));
        $response->assertSessionHas('succesmelding', 'Productprijs bijgewerkt');
        $this->assertDatabaseHas('Product', [
            'Id' => 1,
            'VerkoopPrijs' => 9.99,
            'Opmerking' => 'Actieprijs zomer',
        ]);
    }

    public function test_verkoopprijs_wijzigen_faalt_onder_30_procent_boven_inkoopprijs(): void
    {
        $this->seed();

        $response = $this->put(route('behandelingen.product.opslaan', 1), [
            'nieuwe_verkoopprijs' => '8.00',
        ]);

        $response->assertSessionHas('foutmelding', 'Gegevens niet bijgewerkt');
        $response->assertSessionHasErrors([
            'nieuwe_verkoopprijs' => 'Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen',
        ]);
        $this->assertDatabaseHas('Product', [
            'Id' => 1,
            'VerkoopPrijs' => 14.95,
        ]);
    }

    public function test_nieuwe_verkoopprijs_is_verplicht(): void
    {
        $this->seed();

        $response = $this->put(route('behandelingen.product.opslaan', 1), [
            'nieuwe_verkoopprijs' => '',
        ]);

        $response->assertSessionHas('foutmelding', 'Gegevens niet bijgewerkt');
        $response->assertSessionHasErrors('nieuwe_verkoopprijs');
    }
}
