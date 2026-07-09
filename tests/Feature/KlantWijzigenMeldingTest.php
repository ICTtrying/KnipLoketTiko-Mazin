<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KlantWijzigenMeldingTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'voornaam' => 'Piet',
            'tussenvoegsel' => 'van',
            'achternaam' => 'Loenen',
            'email' => 'piet.van.loenen@gmail.com',
            'straatnaam' => 'Oudegracht',
            'huisnummer' => 88,
            'toevoeging' => 'A',
            'postcode' => '3512AB',
            'plaats' => 'Utrecht',
            'mobiel' => '+31 6 1234 61 71',
            'bijzonderheden' => 'x',
        ], $overrides);
    }

    public function test_succesvolle_wijziging_redirect_naar_overzicht_met_succesmelding(): void
    {
        $this->seed();

        $response = $this->from(route('klanten.edit', 1))
            ->followingRedirects()
            ->put(route('klanten.update', 1), $this->payload(['email' => 'nieuw.adres@example.com']));

        $response->assertSuccessful();
        $response->assertViewIs('klanten.index');
        $response->assertSee('Klantgegevens bijgewerkt');
    }

    public function test_dubbel_emailadres_toont_niet_bijgewerkt_en_veldmelding(): void
    {
        $this->seed();

        $response = $this->from(route('klanten.edit', 1))
            ->followingRedirects()
            ->put(route('klanten.update', 1), $this->payload(['email' => 'jan.jansen@outlook.com']));

        $response->assertSuccessful();
        $response->assertViewIs('klanten.edit');
        $response->assertSee('Klantgegevens zijn niet bijgewerkt');
        $response->assertSee('Het e-mailadres is al in gebruik');
    }
}
