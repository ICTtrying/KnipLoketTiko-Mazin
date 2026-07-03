<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests voor User Story 08: productdetail en houdbaarheidsdatum wijzigen.
 *
 * Testproduct 4 (Baardolie Cedar) heeft in het createscript houdbaarheidsdatum
 * 2027-09-30 en leverancier BarberCare Nederland via leveranciersorder 8.
 */
class ProductWijzigenTest extends TestCase
{
    use RefreshDatabase;

    public function test_detailpagina_toont_productgegevens_met_leverancier(): void
    {
        $this->seed();

        $response = $this->get(route('products.show', 4));

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertSee('Productdetail');
        $response->assertSee('Baardolie Cedar');
        $response->assertSee('BarberCare Nederland');
        $response->assertSee('30-09-2027');
        $response->assertSee('Breda');
    }

    public function test_wijzigpagina_toont_formulier_met_huidige_gegevens(): void
    {
        $this->seed();

        $response = $this->get(route('products.edit', 4));

        $response->assertStatus(200);
        $response->assertViewIs('products.update');
        $response->assertSee('Product wijzigen');
        $response->assertSee('Nieuwe houdbaarheidsdatum');
        $response->assertSee('De houdbaarheidsdatum mag uiterlijk met 7 dagen worden verlengd.');
    }

    public function test_houdbaarheidsdatum_verlengen_met_maximaal_7_dagen_lukt(): void
    {
        $this->seed();

        $response = $this->put(route('products.update', 4), [
            'nieuwe_houdbaarheidsdatum' => '2027-10-06',
        ]);

        $response->assertRedirect(route('products.show', 4));
        $response->assertSessionHas('succesmelding', 'Houdbaarheidsdatum bijgewerkt');
        $this->assertDatabaseHas('Product', [
            'Id' => 4,
            'Houdbaarheidsdatum' => '2027-10-06',
        ]);
    }

    public function test_houdbaarheidsdatum_verlengen_met_meer_dan_7_dagen_faalt(): void
    {
        $this->seed();

        $response = $this->put(route('products.update', 4), [
            'nieuwe_houdbaarheidsdatum' => '2027-10-08',
        ]);

        $response->assertSessionHas('foutmelding', 'Gegevens niet bijgewerkt');
        $response->assertSessionHasErrors([
            'nieuwe_houdbaarheidsdatum' => 'De houdbaarheidsdatum is met meer dan 7 dagen verlengd.',
        ]);
        $this->assertDatabaseHas('Product', [
            'Id' => 4,
            'Houdbaarheidsdatum' => '2027-09-30',
        ]);
    }

    public function test_houdbaarheidsdatum_is_verplicht(): void
    {
        $this->seed();

        $response = $this->put(route('products.update', 4), [
            'nieuwe_houdbaarheidsdatum' => '',
        ]);

        $response->assertSessionHas('foutmelding', 'Gegevens niet bijgewerkt');
        $response->assertSessionHasErrors('nieuwe_houdbaarheidsdatum');
    }
}
