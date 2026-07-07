<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KlantSeeder extends Seeder
{
    /**
     * Seed de klanten tabel met testdata.
     * 
     * Plaats dit bestand in: database/seeders/KlantSeeder.php
     * Run met: php artisan db:seed --class=KlantSeeder
     * Of: php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        // Eerst een testuser toevoegen als niet bestaat
        $user = DB::table('users')->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'),
                'role' => 'user',
                'IsActief' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Klanten toevoegen
        $klanten = [
            [
                'UserId' => $user->Id,
                'Voornaam' => 'Jan',
                'Tussenvoegsel' => 'de',
                'Achternaam' => 'Vries',
                'Relatienummer' => 'REL-001',
                'Bijzonderheden' => 'Regelmatige klant, VIP',
                'IsActief' => 1,
                'Opmerking' => null,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'UserId' => $user->Id,
                'Voornaam' => 'Maria',
                'Tussenvoegsel' => null,
                'Achternaam' => 'Schmidt',
                'Relatienummer' => 'REL-002',
                'Bijzonderheden' => '',
                'IsActief' => 1,
                'Opmerking' => null,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'UserId' => $user->Id,
                'Voornaam' => 'Peter',
                'Tussenvoegsel' => 'van',
                'Achternaam' => 'Dijk',
                'Relatienummer' => 'REL-003',
                'Bijzonderheden' => 'Voormalige klant, terugwinnen',
                'IsActief' => 1,
                'Opmerking' => null,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'UserId' => $user->Id,
                'Voornaam' => 'Anita',
                'Tussenvoegsel' => null,
                'Achternaam' => 'Johnson',
                'Relatienummer' => 'REL-004',
                'Bijzonderheden' => 'Allergie advies: siliconen',
                'IsActief' => 1,
                'Opmerking' => 'Voorzichtig bij productkeuze',
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'UserId' => $user->Id,
                'Voornaam' => 'Thomas',
                'Tussenvoegsel' => null,
                'Achternaam' => 'Martinez',
                'Relatienummer' => 'REL-005',
                'Bijzonderheden' => 'Bedrijfsklant',
                'IsActief' => 1,
                'Opmerking' => null,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'UserId' => $user->Id,
                'Voornaam' => 'Sophie',
                'Tussenvoegsel' => null,
                'Achternaam' => 'Meyer',
                'Relatienummer' => 'REL-006',
                'Bijzonderheden' => '',
                'IsActief' => 1,
                'Opmerking' => null,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
        ];

        // Voeg klanten in
        $klantIds = [];
        foreach ($klanten as $klantData) {
            $klantId = DB::table('Klant')->insertGetId($klantData);
            $klantIds[] = $klantId;
        }

        // Contactgegevens toevoegen
        $contacten = [
            [
                'Straatnaam' => 'Kalverstraat',
                'Huisnummer' => 42,
                'Toevoeging' => null,
                'Postcode' => '1012NX',
                'Plaats' => 'Amsterdam',
                'Email' => 'jan@example.com',
                'Mobiel' => '+31 6 12345671',
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'Straatnaam' => 'Singel',
                'Huisnummer' => 100,
                'Toevoeging' => 'A',
                'Postcode' => '1012AB',
                'Plaats' => 'Amsterdam',
                'Email' => 'maria.schmidt@example.com',
                'Mobiel' => '+31 6 12345672',
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'Straatnaam' => 'Prinsengracht',
                'Huisnummer' => 263,
                'Toevoeging' => null,
                'Postcode' => '1015DF',
                'Plaats' => 'Amsterdam',
                'Email' => 'peter.van.dijk@example.com',
                'Mobiel' => '+31 6 12345673',
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'Straatnaam' => 'Herengracht',
                'Huisnummer' => 411,
                'Toevoeging' => null,
                'Postcode' => '1017BR',
                'Plaats' => 'Amsterdam',
                'Email' => 'anita.johnson@example.com',
                'Mobiel' => '+31 6 12345674',
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'Straatnaam' => 'Westerstraat',
                'Huisnummer' => 200,
                'Toevoeging' => 'B',
                'Postcode' => '1015ME',
                'Plaats' => 'Amsterdam',
                'Email' => 'thomas@martinez-bedrijf.nl',
                'Mobiel' => '+31 6 12345675',
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
            [
                'Straatnaam' => 'Jordaan',
                'Huisnummer' => 55,
                'Toevoeging' => null,
                'Postcode' => '1015PD',
                'Plaats' => 'Amsterdam',
                'Email' => 'sophie.meyer@example.com',
                'Mobiel' => '+31 6 12345676',
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ],
        ];

        // Voeg contacten in en koppel aan klanten
        $contactIds = [];
        foreach ($contacten as $contactData) {
            $contactId = DB::table('Contact')->insertGetId($contactData);
            $contactIds[] = $contactId;
        }

        // Koppel klanten aan contactgegevens
        for ($i = 0; $i < count($klantIds); $i++) {
            DB::table('KlantPerContact')->insert([
                'KlantId' => $klantIds[$i],
                'ContactId' => $contactIds[$i],
                'IsActief' => 1,
                'DatumAangemaakt' => Carbon::now(),
                'DatumGewijzigd' => Carbon::now(),
            ]);
        }

        $this->command->info('✅ Klanten en contacten succesvol geseeded!');
        $this->command->info('   - 6 klanten aangemaakt');
        $this->command->info('   - 6 contactgegevens aangemaakt');
        $this->command->info('   - Test login: test@example.com / password123');
    }
}