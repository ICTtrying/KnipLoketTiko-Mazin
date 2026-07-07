<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests voor KlantController.
 *
 * De testdata komt via BestellingSeeder exact uit het gezaghebbende
 * create-script (klanten KL-2026-001 t/m 006 met hun Contact-koppeling),
 * zodat de tests hetzelfde gedrag toetsen als de echte database.
 */
class KlantControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_klantenoverzicht_toont_alle_klanten_met_contactgegevens(): void
    {
        $this->seed();

        $response = $this->get(route('klanten.index'));

        $response->assertSuccessful();
        $response->assertViewIs('klanten.index');
        // Klant uit de BestellingSeeder inclusief de gekoppelde contactgegevens
        $response->assertSee('KL-2026-001');
        $response->assertSee('Piet');
        $response->assertSee('Oudegracht');
        $response->assertSee('piet.van.loenen@gmail.com');
    }

    public function test_klantenoverzicht_filtert_op_postcode(): void
    {
        $this->seed();

        $response = $this->get(route('klanten.index', ['postcode' => '3512AB']));

        $response->assertSuccessful();
        $response->assertSee('KL-2026-001');
        $response->assertDontSee('KL-2026-002');
    }

    public function test_klantenoverzicht_toont_melding_bij_geen_resultaten(): void
    {
        $this->seed();

        $response = $this->get(route('klanten.index', ['postcode' => '9999ZZ']));

        $response->assertSuccessful();
        $response->assertSee('Er zijn geen klanten bekent die de geselecteerde postcode hebben');
    }
}
