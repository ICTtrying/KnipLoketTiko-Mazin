<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder voor de bestellingenmodule met vaste wireframe-testdata.
 */
class BestellingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ProductPerBestelling')->delete();
        DB::table('Bestelling')->delete();
        DB::table('Product')->delete();
        DB::table('Categorie')->delete();
        DB::table('Klant')->delete();
        DB::table('User')->delete();

        DB::table('User')->insert([
            ['Id' => 1, 'name' => 'Piet van Loenen', 'email' => 'piet.van.loenen@example.test', 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 2, 'name' => 'Jan Jansen', 'email' => 'jan.jansen@example.test', 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 3, 'name' => 'Saskia de Boer', 'email' => 'saskia.de.boer@example.test', 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 4, 'name' => 'Ahmed Mansouri', 'email' => 'ahmed.mansouri@example.test', 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 5, 'name' => 'Marieke van den Berg', 'email' => 'marieke.vandenberg@example.test', 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
            ['Id' => 6, 'name' => 'Daan Visser', 'email' => 'daan.visser@example.test', 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('Klant')->insert([
            ['Id' => 1, 'UserId' => 1, 'Naam' => 'Piet van Loenen', 'Telefoonnummer' => '0612345678', 'WensenAllergieen' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'UserId' => 2, 'Naam' => 'Jan Jansen', 'Telefoonnummer' => '0612345679', 'WensenAllergieen' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'UserId' => 3, 'Naam' => 'Saskia de Boer', 'Telefoonnummer' => '0612345680', 'WensenAllergieen' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'UserId' => 4, 'Naam' => 'Ahmed Mansouri', 'Telefoonnummer' => '0612345681', 'WensenAllergieen' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'UserId' => 5, 'Naam' => 'Marieke van den Berg', 'Telefoonnummer' => '0612345682', 'WensenAllergieen' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'UserId' => 6, 'Naam' => 'Daan Visser', 'Telefoonnummer' => '0612345683', 'WensenAllergieen' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        DB::table('Categorie')->insert([
            ['Id' => 1, 'Naam' => 'Haarverzorging', 'Omschrijving' => 'Verzorgingsproducten voor het haar', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'Naam' => 'Styling', 'Omschrijving' => 'Stylingproducten', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'Naam' => 'Kleur', 'Omschrijving' => 'Kleurproducten', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        DB::table('Product')->insert([
            ['Id' => 1, 'CategorieId' => 1, 'Naam' => 'Hydrating Shampoo', 'Omschrijving' => 'Hydraterende shampoo', 'Merk' => 'Tiko Care', 'EANcode' => 'EAN000000000001', 'Houdbaarheidsdatum' => '2027-12-31', 'InkoopPrijs' => 8.50, 'VerkoopPrijs' => 14.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'CategorieId' => 1, 'Naam' => 'Repair Conditioner', 'Omschrijving' => 'Herstellende conditioner', 'Merk' => 'Tiko Care', 'EANcode' => 'EAN000000000002', 'Houdbaarheidsdatum' => '2027-12-31', 'InkoopPrijs' => 9.25, 'VerkoopPrijs' => 16.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'CategorieId' => 2, 'Naam' => 'Heat Protect Spray', 'Omschrijving' => 'Beschermende spray', 'Merk' => 'Tiko Style', 'EANcode' => 'EAN000000000003', 'Houdbaarheidsdatum' => '2027-12-31', 'InkoopPrijs' => 9.50, 'VerkoopPrijs' => 15.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'CategorieId' => 2, 'Naam' => 'Strong Hold Gel', 'Omschrijving' => 'Sterke stylinggel', 'Merk' => 'Tiko Style', 'EANcode' => 'EAN000000000004', 'Houdbaarheidsdatum' => '2027-12-31', 'InkoopPrijs' => 5.50, 'VerkoopPrijs' => 9.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'CategorieId' => 2, 'Naam' => 'Volume Mousse', 'Omschrijving' => 'Volume mousse', 'Merk' => 'Tiko Style', 'EANcode' => 'EAN000000000005', 'Houdbaarheidsdatum' => '2027-12-31', 'InkoopPrijs' => 6.10, 'VerkoopPrijs' => 11.50, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'CategorieId' => 3, 'Naam' => 'Color Mask', 'Omschrijving' => 'Kleurmasker', 'Merk' => 'Tiko Color', 'EANcode' => 'EAN000000000006', 'Houdbaarheidsdatum' => '2027-12-31', 'InkoopPrijs' => 7.10, 'VerkoopPrijs' => 12.90, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        DB::table('Bestelling')->insert([
            ['Id' => 1, 'KlantId' => 1, 'BestelNummer' => 600101, 'Omschrijving' => 'Bestelling 600101', 'Datum' => '2026-06-20', 'Tijd' => '09:15:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'KlantId' => 4, 'BestelNummer' => 600104, 'Omschrijving' => 'Bestelling 600104', 'Datum' => '2026-06-28', 'Tijd' => '16:05:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'KlantId' => 5, 'BestelNummer' => 600105, 'Omschrijving' => 'Bestelling 600105', 'Datum' => '2026-06-30', 'Tijd' => '10:30:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'KlantId' => 3, 'BestelNummer' => 600108, 'Omschrijving' => 'Bestelling 600108', 'Datum' => '2026-06-15', 'Tijd' => '13:22:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'KlantId' => 6, 'BestelNummer' => 600111, 'Omschrijving' => 'Bestelling 600111', 'Datum' => '2026-06-17', 'Tijd' => '10:44:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'KlantId' => 1, 'BestelNummer' => 600112, 'Omschrijving' => 'Bestelling 600112', 'Datum' => '2026-06-18', 'Tijd' => '11:10:00', 'Bestelstatus' => 'In verwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 7, 'KlantId' => 2, 'BestelNummer' => 600119, 'Omschrijving' => 'Bestelling 600119', 'Datum' => '2026-06-29', 'Tijd' => '15:43:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 8, 'KlantId' => 3, 'BestelNummer' => 600120, 'Omschrijving' => 'Bestelling 600120', 'Datum' => '2026-06-30', 'Tijd' => '09:58:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 9, 'KlantId' => 2, 'BestelNummer' => 600121, 'Omschrijving' => 'Bestelling 600121', 'Datum' => '2026-07-01', 'Tijd' => '08:20:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 10, 'KlantId' => 4, 'BestelNummer' => 600122, 'Omschrijving' => 'Bestelling 600122', 'Datum' => '2026-07-01', 'Tijd' => '09:05:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 11, 'KlantId' => 5, 'BestelNummer' => 600123, 'Omschrijving' => 'Bestelling 600123', 'Datum' => '2026-07-01', 'Tijd' => '09:55:00', 'Bestelstatus' => 'In verwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 12, 'KlantId' => 6, 'BestelNummer' => 600124, 'Omschrijving' => 'Bestelling 600124', 'Datum' => '2026-07-01', 'Tijd' => '10:10:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 13, 'KlantId' => 1, 'BestelNummer' => 600125, 'Omschrijving' => 'Bestelling 600125', 'Datum' => '2026-07-02', 'Tijd' => '11:00:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 14, 'KlantId' => 2, 'BestelNummer' => 600126, 'Omschrijving' => 'Bestelling 600126', 'Datum' => '2026-07-02', 'Tijd' => '11:25:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 15, 'KlantId' => 3, 'BestelNummer' => 600127, 'Omschrijving' => 'Bestelling 600127', 'Datum' => '2026-07-02', 'Tijd' => '12:00:00', 'Bestelstatus' => 'In verwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 16, 'KlantId' => 4, 'BestelNummer' => 600128, 'Omschrijving' => 'Bestelling 600128', 'Datum' => '2026-07-02', 'Tijd' => '12:45:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 17, 'KlantId' => 5, 'BestelNummer' => 600129, 'Omschrijving' => 'Bestelling 600129', 'Datum' => '2026-07-03', 'Tijd' => '08:05:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 18, 'KlantId' => 6, 'BestelNummer' => 600130, 'Omschrijving' => 'Bestelling 600130', 'Datum' => '2026-07-03', 'Tijd' => '08:40:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 19, 'KlantId' => 1, 'BestelNummer' => 600131, 'Omschrijving' => 'Bestelling 600131', 'Datum' => '2026-07-03', 'Tijd' => '09:10:00', 'Bestelstatus' => 'In verwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 20, 'KlantId' => 2, 'BestelNummer' => 600132, 'Omschrijving' => 'Bestelling 600132', 'Datum' => '2026-07-03', 'Tijd' => '09:35:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        DB::table('ProductPerBestelling')->insert([
            ['Id' => 1, 'ProductId' => 1, 'BestellingId' => 1, 'Aantal' => 2, 'UnitPrijs' => 14.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'ProductId' => 2, 'BestellingId' => 1, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 10.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'ProductId' => 3, 'BestellingId' => 2, 'Aantal' => 1, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'ProductId' => 3, 'BestellingId' => 3, 'Aantal' => 1, 'UnitPrijs' => 15.95, 'BTWPercentage' => 21.00, 'Korting' => 7.50, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'ProductId' => 4, 'BestellingId' => 3, 'Aantal' => 1, 'UnitPrijs' => 9.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'ProductId' => 1, 'BestellingId' => 4, 'Aantal' => 1, 'UnitPrijs' => 11.65, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 7, 'ProductId' => 4, 'BestellingId' => 5, 'Aantal' => 1, 'UnitPrijs' => 9.71, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 8, 'ProductId' => 5, 'BestellingId' => 6, 'Aantal' => 1, 'UnitPrijs' => 11.50, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 9, 'ProductId' => 2, 'BestellingId' => 7, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 10, 'ProductId' => 1, 'BestellingId' => 8, 'Aantal' => 1, 'UnitPrijs' => 11.65, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 11, 'ProductId' => 6, 'BestellingId' => 9, 'Aantal' => 1, 'UnitPrijs' => 12.90, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 12, 'ProductId' => 5, 'BestellingId' => 10, 'Aantal' => 2, 'UnitPrijs' => 11.50, 'BTWPercentage' => 21.00, 'Korting' => 5.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 13, 'ProductId' => 4, 'BestellingId' => 11, 'Aantal' => 2, 'UnitPrijs' => 9.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 14, 'ProductId' => 3, 'BestellingId' => 12, 'Aantal' => 1, 'UnitPrijs' => 15.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 15, 'ProductId' => 2, 'BestellingId' => 13, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 16, 'ProductId' => 1, 'BestellingId' => 14, 'Aantal' => 3, 'UnitPrijs' => 14.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 17, 'ProductId' => 6, 'BestellingId' => 15, 'Aantal' => 1, 'UnitPrijs' => 12.90, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 18, 'ProductId' => 5, 'BestellingId' => 16, 'Aantal' => 1, 'UnitPrijs' => 11.50, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 19, 'ProductId' => 4, 'BestellingId' => 17, 'Aantal' => 1, 'UnitPrijs' => 9.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 20, 'ProductId' => 2, 'BestellingId' => 18, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 21, 'ProductId' => 3, 'BestellingId' => 19, 'Aantal' => 2, 'UnitPrijs' => 15.95, 'BTWPercentage' => 21.00, 'Korting' => 7.50, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 22, 'ProductId' => 6, 'BestellingId' => 20, 'Aantal' => 1, 'UnitPrijs' => 12.90, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);
    }
}
