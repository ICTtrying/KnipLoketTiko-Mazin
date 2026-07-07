<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KlantSeeder extends Seeder
{
    /**
     * Seed de Klant-, Contact- en KlantPerContact-tabellen met testdata.
     *
     * Run met: php artisan db:seed --class=KlantSeeder
     * Of: php artisan migrate:fresh --seed
     *
     * De seeder is idempotent: klanten die al bestaan (op Relatienummer)
     * worden overgeslagen, zodat herhaald draaien geen duplicaten geeft.
     */
    public function run(): void
    {
        $nu = Carbon::now();

        // Testuser aanmaken als die nog niet bestaat (Eloquent hasht het wachtwoord via de cast)
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password123',
                'role' => 'user',
            ]
        );

        /** @var array<int, array{klant: array<string, mixed>, contact: array<string, mixed>}> $records */
        $records = [
            [
                'klant' => ['Voornaam' => 'Jan', 'Tussenvoegsel' => 'de', 'Achternaam' => 'Vries', 'Relatienummer' => 'REL-001', 'Bijzonderheden' => 'Regelmatige klant, VIP', 'Opmerking' => null],
                'contact' => ['Straatnaam' => 'Kalverstraat', 'Huisnummer' => 42, 'Toevoeging' => null, 'Postcode' => '1012NX', 'Plaats' => 'Amsterdam', 'Email' => 'jan@example.com', 'Mobiel' => '+31 6 12345671'],
            ],
            [
                'klant' => ['Voornaam' => 'Maria', 'Tussenvoegsel' => null, 'Achternaam' => 'Schmidt', 'Relatienummer' => 'REL-002', 'Bijzonderheden' => '', 'Opmerking' => null],
                'contact' => ['Straatnaam' => 'Singel', 'Huisnummer' => 100, 'Toevoeging' => 'A', 'Postcode' => '1012AB', 'Plaats' => 'Amsterdam', 'Email' => 'maria.schmidt@example.com', 'Mobiel' => '+31 6 12345672'],
            ],
            [
                'klant' => ['Voornaam' => 'Peter', 'Tussenvoegsel' => 'van', 'Achternaam' => 'Dijk', 'Relatienummer' => 'REL-003', 'Bijzonderheden' => 'Voormalige klant, terugwinnen', 'Opmerking' => null],
                'contact' => ['Straatnaam' => 'Prinsengracht', 'Huisnummer' => 263, 'Toevoeging' => null, 'Postcode' => '1015DF', 'Plaats' => 'Amsterdam', 'Email' => 'peter.van.dijk@example.com', 'Mobiel' => '+31 6 12345673'],
            ],
            [
                'klant' => ['Voornaam' => 'Anita', 'Tussenvoegsel' => null, 'Achternaam' => 'Johnson', 'Relatienummer' => 'REL-004', 'Bijzonderheden' => 'Allergie advies: siliconen', 'Opmerking' => 'Voorzichtig bij productkeuze'],
                'contact' => ['Straatnaam' => 'Herengracht', 'Huisnummer' => 411, 'Toevoeging' => null, 'Postcode' => '1017BR', 'Plaats' => 'Amsterdam', 'Email' => 'anita.johnson@example.com', 'Mobiel' => '+31 6 12345674'],
            ],
            [
                'klant' => ['Voornaam' => 'Thomas', 'Tussenvoegsel' => null, 'Achternaam' => 'Martinez', 'Relatienummer' => 'REL-005', 'Bijzonderheden' => 'Bedrijfsklant', 'Opmerking' => null],
                'contact' => ['Straatnaam' => 'Westerstraat', 'Huisnummer' => 200, 'Toevoeging' => 'B', 'Postcode' => '1015ME', 'Plaats' => 'Amsterdam', 'Email' => 'thomas@martinez-bedrijf.nl', 'Mobiel' => '+31 6 12345675'],
            ],
            [
                'klant' => ['Voornaam' => 'Sophie', 'Tussenvoegsel' => null, 'Achternaam' => 'Meyer', 'Relatienummer' => 'REL-006', 'Bijzonderheden' => '', 'Opmerking' => null],
                'contact' => ['Straatnaam' => 'Jordaan', 'Huisnummer' => 55, 'Toevoeging' => null, 'Postcode' => '1015PD', 'Plaats' => 'Amsterdam', 'Email' => 'sophie.meyer@example.com', 'Mobiel' => '+31 6 12345676'],
            ],
        ];

        // Verweesde contacten van eerdere (mislukte) seed-runs opruimen:
        // alleen de eigen e-mailadressen en alleen zonder bestaande koppeling
        DB::table('Contact')
            ->whereIn('Email', array_column(array_column($records, 'contact'), 'Email'))
            ->whereNotExists(fn ($query) => $query->select(DB::raw(1))
                ->from('KlantPerContact')
                ->whereColumn('KlantPerContact.ContactId', 'Contact.Id'))
            ->delete();

        $aantalToegevoegd = 0;

        foreach ($records as $record) {
            // Overslaan als de klant al bestaat, zodat de seeder idempotent blijft
            $bestaat = DB::table('Klant')
                ->where('Relatienummer', $record['klant']['Relatienummer'])
                ->exists();

            if ($bestaat) {
                continue;
            }

            DB::transaction(function () use ($user, $record, $nu): void {
                $klantId = DB::table('Klant')->insertGetId($record['klant'] + [
                    'UserId' => $user->Id,
                    'IsActief' => 1,
                    'DatumAangemaakt' => $nu,
                    'DatumGewijzigd' => $nu,
                ]);

                $contactId = DB::table('Contact')->insertGetId($record['contact'] + [
                    'IsActief' => 1,
                    'DatumAangemaakt' => $nu,
                    'DatumGewijzigd' => $nu,
                ]);

                DB::table('KlantPerContact')->insert([
                    'KlantId' => $klantId,
                    'ContactId' => $contactId,
                    'IsActief' => 1,
                    'DatumAangemaakt' => $nu,
                    'DatumGewijzigd' => $nu,
                ]);
            });

            $aantalToegevoegd++;
        }

        $this->command->info("✅ Klanten geseeded: {$aantalToegevoegd} toegevoegd, ".(count($records) - $aantalToegevoegd).' bestonden al.');
        $this->command->info('   Test login: test@example.com / password123');
    }
}
