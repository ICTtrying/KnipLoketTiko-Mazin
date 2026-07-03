<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder voor de Behandeling-module met exact de testdata uit het gezaghebbende
 * create-script (database/Createscript/SQL_Dag3.sql): behandelingen, voorraad,
 * de koppeltabel BehandelingPerVoorraad en de leveranciers(orders).
 *
 * Deze seeder is bedoeld voor de sqlite-testomgeving en draait NA de
 * BestellingSeeder (de producten moeten al bestaan vanwege de foreign keys).
 * Op MySQL vult het create-script de database al en is seeden niet nodig.
 */
class BehandelingSeeder extends Seeder
{
    public function run(): void
    {
        // Eerst legen in omgekeerde FK-volgorde zodat de seeder herhaalbaar is
        DB::table('LeverancierOrder')->delete();
        DB::table('Leverancier')->delete();
        DB::table('BehandelingPerVoorraad')->delete();
        DB::table('Behandeling')->delete();
        DB::table('Voorraad')->delete();

        $nu = now();

        DB::table('Voorraad')->insert([
            ['Id' => 1, 'ProductId' => 1, 'AantalOpVoorraad' => 40, 'Aantaluitgegeven' => 0, 'Aantalbijgekomen' => 40, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'ProductId' => 2, 'AantalOpVoorraad' => 28, 'Aantaluitgegeven' => 2, 'Aantalbijgekomen' => 30, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'ProductId' => 3, 'AantalOpVoorraad' => 18, 'Aantaluitgegeven' => 0, 'Aantalbijgekomen' => 18, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'ProductId' => 4, 'AantalOpVoorraad' => 20, 'Aantaluitgegeven' => 0, 'Aantalbijgekomen' => 20, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'ProductId' => 5, 'AantalOpVoorraad' => 25, 'Aantaluitgegeven' => 0, 'Aantalbijgekomen' => 25, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'ProductId' => 6, 'AantalOpVoorraad' => 16, 'Aantaluitgegeven' => 1, 'Aantalbijgekomen' => 17, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 7, 'ProductId' => 7, 'AantalOpVoorraad' => 32, 'Aantaluitgegeven' => 3, 'Aantalbijgekomen' => 35, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 8, 'ProductId' => 8, 'AantalOpVoorraad' => 22, 'Aantaluitgegeven' => 0, 'Aantalbijgekomen' => 22, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 9, 'ProductId' => 9, 'AantalOpVoorraad' => 35, 'Aantaluitgegeven' => 0, 'Aantalbijgekomen' => 35, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 10, 'ProductId' => 10, 'AantalOpVoorraad' => 24, 'Aantaluitgegeven' => 1, 'Aantalbijgekomen' => 25, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        DB::table('Behandeling')->insert([
            ['Id' => 1, 'Naam' => 'Knippen', 'Omschrijving' => 'Haar knippen en eventueel stylen.', 'DuurMinuten' => 30, 'Prijs' => 30.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'Naam' => 'Combi behandelingen', 'Omschrijving' => 'Combinatie van knippen, kleuren en stylen.', 'DuurMinuten' => 90, 'Prijs' => 90.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'Naam' => 'Kleuren', 'Omschrijving' => 'Haar kleuren (diverse technieken).', 'DuurMinuten' => 60, 'Prijs' => 60.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'Naam' => 'Permanent', 'Omschrijving' => 'Permanente omvorming van het haar.', 'DuurMinuten' => 120, 'Prijs' => 110.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'Naam' => 'Extensions', 'Omschrijving' => 'Plaatsen en verzorgen van extensions.', 'DuurMinuten' => 180, 'Prijs' => 250.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        DB::table('BehandelingPerVoorraad')->insert([
            ['Id' => 1, 'BehandelingId' => 1, 'VoorraadId' => 1, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'BehandelingId' => 1, 'VoorraadId' => 3, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'BehandelingId' => 2, 'VoorraadId' => 1, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'BehandelingId' => 2, 'VoorraadId' => 2, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'BehandelingId' => 2, 'VoorraadId' => 3, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'BehandelingId' => 3, 'VoorraadId' => 2, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 7, 'BehandelingId' => 4, 'VoorraadId' => 3, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 8, 'BehandelingId' => 5, 'VoorraadId' => 4, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        DB::table('Leverancier')->insert([
            ['Id' => 1, 'Naam' => 'Van Duuren Haircosmetics', 'Straatnaam' => 'Prinses Irenestraat', 'Huisnummer' => 12, 'Toevoeging' => 'A', 'Postcode' => '3584AN', 'Plaats' => 'Utrecht', 'Email' => 'inkoop@vanduurenhaircosmetics.nl', 'Mobiel' => '+31 623456121', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'Naam' => 'ColorPro Benelux', 'Straatnaam' => 'Gibraltarstraat', 'Huisnummer' => 234, 'Toevoeging' => null, 'Postcode' => '5611AA', 'Plaats' => 'Eindhoven', 'Email' => 'orders@colorpro-benelux.nl', 'Mobiel' => '+31 623456122', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'Naam' => 'SalonStyle Supplies', 'Straatnaam' => 'Der Kinderenstraat', 'Huisnummer' => 456, 'Toevoeging' => 'Bis', 'Postcode' => '3011AB', 'Plaats' => 'Rotterdam', 'Email' => 'service@salonstylesupplies.nl', 'Mobiel' => '+31 623456123', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'Naam' => 'BarberCare Nederland', 'Straatnaam' => 'Nachtegaalstraat', 'Huisnummer' => 233, 'Toevoeging' => 'A', 'Postcode' => '4811AA', 'Plaats' => 'Breda', 'Email' => 'bestellingen@barbercare-nederland.nl', 'Mobiel' => '+31 623456124', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'Naam' => 'HairTools Groothandel', 'Straatnaam' => 'Bertram Russellstraat', 'Huisnummer' => 45, 'Toevoeging' => null, 'Postcode' => '8011AB', 'Plaats' => 'Zwolle', 'Email' => 'contact@hairtools-groothandel.nl', 'Mobiel' => '+31 623456125', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        DB::table('LeverancierOrder')->insert([
            ['Id' => 1, 'Ordernummer' => 'ORD-2026-1001', 'ProductId' => 1, 'LeverancierId' => 1, 'Aantal' => 12, 'Orderdatum' => '2026-05-04', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'Ordernummer' => 'ORD-2026-1002', 'ProductId' => 5, 'LeverancierId' => 2, 'Aantal' => 8, 'Orderdatum' => '2026-05-05', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'Ordernummer' => 'ORD-2026-1003', 'ProductId' => 9, 'LeverancierId' => 3, 'Aantal' => 10, 'Orderdatum' => '2026-05-06', 'Leverdatum' => '2026-05-08', 'Leverstatus' => 'Geleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'Ordernummer' => 'ORD-2026-1004', 'ProductId' => 7, 'LeverancierId' => 2, 'Aantal' => 6, 'Orderdatum' => '2026-05-07', 'Leverdatum' => null, 'Leverstatus' => 'Nietleverbaar', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'Ordernummer' => 'ORD-2026-1005', 'ProductId' => 10, 'LeverancierId' => 4, 'Aantal' => 9, 'Orderdatum' => '2026-05-08', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'Ordernummer' => 'ORD-2026-1006', 'ProductId' => 2, 'LeverancierId' => 1, 'Aantal' => 7, 'Orderdatum' => '2026-05-09', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 7, 'Ordernummer' => 'ORD-2026-1007', 'ProductId' => 3, 'LeverancierId' => 1, 'Aantal' => 6, 'Orderdatum' => '2026-05-10', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 8, 'Ordernummer' => 'ORD-2026-1008', 'ProductId' => 4, 'LeverancierId' => 4, 'Aantal' => 5, 'Orderdatum' => '2026-05-10', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 9, 'Ordernummer' => 'ORD-2026-1009', 'ProductId' => 6, 'LeverancierId' => 2, 'Aantal' => 6, 'Orderdatum' => '2026-05-11', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 10, 'Ordernummer' => 'ORD-2026-1010', 'ProductId' => 8, 'LeverancierId' => 3, 'Aantal' => 8, 'Orderdatum' => '2026-05-11', 'Leverdatum' => null, 'Leverstatus' => 'Inbehandeling', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);
    }
}
