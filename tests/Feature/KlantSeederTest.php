<?php

namespace Tests\Feature;

use Database\Seeders\KlantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Feature tests voor de KlantSeeder.
 *
 * De seeder moet klanten mét gekoppelde contactgegevens aanmaken zodat ze
 * zichtbaar zijn in het klantenoverzicht, en idempotent zijn zodat herhaald
 * draaien geen duplicaten oplevert.
 */
class KlantSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_maakt_klanten_met_contact_en_koppeling_aan(): void
    {
        $this->seed(KlantSeeder::class);

        $this->assertSame(6, DB::table('Klant')->where('Relatienummer', 'like', 'REL-%')->count());
        $this->assertSame(6, DB::table('KlantPerContact')->count());
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_seeder_is_idempotent_bij_herhaald_draaien(): void
    {
        $this->seed(KlantSeeder::class);
        $this->seed(KlantSeeder::class);

        $this->assertSame(6, DB::table('Klant')->where('Relatienummer', 'like', 'REL-%')->count());
        $this->assertSame(6, DB::table('KlantPerContact')->count());
        $this->assertSame(1, DB::table('users')->where('email', 'test@example.com')->count());
    }

    public function test_geseede_klanten_zijn_zichtbaar_in_klantenoverzicht(): void
    {
        $this->seed();

        $response = $this->get(route('klanten.index'));

        $response->assertSuccessful();
        $response->assertViewIs('klanten.index');
        // Klant uit de KlantSeeder inclusief de gekoppelde contactgegevens
        $response->assertSee('REL-001');
        $response->assertSee('Jan');
        $response->assertSee('Kalverstraat');
        $response->assertSee('jan@example.com');
    }
}
