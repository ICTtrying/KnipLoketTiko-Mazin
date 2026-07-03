<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder voor de Bestelling-module met exact de testdata uit het gezaghebbende
 * create-script (database/Createscript/SQL_Dag3.sql): bestellingen 600101 t/m
 * 600120, statuswaarden zonder spatie (o.a. Inverwerking) en de echte
 * Klant-kolommen (Voornaam/Tussenvoegsel/Achternaam/Relatienummer).
 *
 * Deze seeder is bedoeld voor de sqlite-testomgeving; op MySQL vult het
 * create-script de database al en is seeden niet nodig. Alleen de tabellen die
 * de Bestelling-user-stories raken worden gevuld (users beperkt tot de
 * eigenaar en de zes klanten).
 */
class BestellingSeeder extends Seeder
{
    public function run(): void
    {
        // Eerst legen in omgekeerde FK-volgorde zodat de seeder herhaalbaar is
        DB::table('ProductPerBestelling')->delete();
        DB::table('Bestelling')->delete();
        DB::table('LeverancierOrder')->delete();
        DB::table('Leverancier')->delete();
        DB::table('Voorraad')->delete();
        DB::table('Product')->delete();
        DB::table('Categorie')->delete();
        DB::table('Klant')->delete();
        DB::table('users')->delete();

        $nu = now();

        DB::table('users')->insert([
            ['Id' => 1, 'name' => 'Salon Eigenaar', 'email' => 'eigenaar@kniplokettiko.nl', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'eigenaar', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
            ['Id' => 12, 'name' => 'Piet van Loenen', 'email' => 'piet.van.loenen@gmail.com', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'klant', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
            ['Id' => 13, 'name' => 'Jan Jansen', 'email' => 'jan.jansen@outlook.com', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'klant', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
            ['Id' => 14, 'name' => 'Saskia de Boer', 'email' => 'saskia.deboer@yahoo.com', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'klant', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
            ['Id' => 15, 'name' => 'Ahmed Mansouri', 'email' => 'ahmed.mansouri@icloud.com', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'klant', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
            ['Id' => 16, 'name' => 'Marieke van den Berg', 'email' => 'marieke.vandenberg@ziggo.nl', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'klant', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
            ['Id' => 17, 'name' => 'Daan Visser', 'email' => 'daan.visser@live.nl', 'email_verified_at' => null, 'password' => bcrypt('password'), 'role' => 'klant', 'remember_token' => null, 'IsActief' => 1, 'Opmerking' => null, 'created_at' => $nu, 'updated_at' => $nu],
        ]);

        DB::table('Klant')->insert([
            ['Id' => 1, 'UserId' => 12, 'Voornaam' => 'Piet', 'Tussenvoegsel' => 'van', 'Achternaam' => 'Loenen', 'Relatienummer' => 'KL-2026-001', 'Bijzonderheden' => 'Voorkeur voor ochtendafspraken.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'UserId' => 13, 'Voornaam' => 'Jan', 'Tussenvoegsel' => null, 'Achternaam' => 'Jansen', 'Relatienummer' => 'KL-2026-002', 'Bijzonderheden' => 'Allergie voor sterk geparfumeerde producten.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'UserId' => 14, 'Voornaam' => 'Saskia', 'Tussenvoegsel' => 'de', 'Achternaam' => 'Boer', 'Relatienummer' => 'KL-2026-003', 'Bijzonderheden' => 'Komt elke zes weken.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'UserId' => 15, 'Voornaam' => 'Ahmed', 'Tussenvoegsel' => null, 'Achternaam' => 'Mansouri', 'Relatienummer' => 'KL-2026-004', 'Bijzonderheden' => 'Wil strakke fade.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'UserId' => 16, 'Voornaam' => 'Marieke', 'Tussenvoegsel' => 'van den', 'Achternaam' => 'Berg', 'Relatienummer' => 'KL-2026-005', 'Bijzonderheden' => 'Gevoelige hoofdhuid.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'UserId' => 17, 'Voornaam' => 'Daan', 'Tussenvoegsel' => null, 'Achternaam' => 'Visser', 'Relatienummer' => 'KL-2026-006', 'Bijzonderheden' => 'Liefst einde middag.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        // Categorieën, producten en voorraad komen 1-op-1 uit database/creatscript/createscript.sql
        DB::table('Categorie')->insert([
            ['Id' => 1, 'Naam' => 'Haarverzorging', 'Omschrijving' => 'Producten voor wassen en verzorgen.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'Naam' => 'Kleurproducten', 'Omschrijving' => 'Producten voor kleurbehandelingen.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'Naam' => 'Styling', 'Omschrijving' => 'Producten voor afwerking en styling.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'Naam' => 'Accessoires', 'Omschrijving' => 'Accessoires voor verkoop in de salon.', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        DB::table('Product')->insert([
            ['Id' => 1, 'CategorieId' => 1, 'Naam' => 'Hydrating Shampoo', 'Omschrijving' => 'Milde salonshampoo voor dagelijks gebruik.', 'Merk' => 'Tiko Care', 'EANcode' => '0871234500001', 'Houdbaarheidsdatum' => '2027-07-01', 'InkoopPrijs' => 6.50, 'VerkoopPrijs' => 14.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'CategorieId' => 1, 'Naam' => 'Repair Conditioner', 'Omschrijving' => 'Voedende conditioner voor beschadigd haar.', 'Merk' => 'Tiko Care', 'EANcode' => '0871234500002', 'Houdbaarheidsdatum' => '2027-10-15', 'InkoopPrijs' => 7.25, 'VerkoopPrijs' => 16.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'CategorieId' => 1, 'Naam' => 'Scalp Balance Masker', 'Omschrijving' => 'Kalmerend haarmasker voor gevoelige hoofdhuid.', 'Merk' => 'Tiko Care', 'EANcode' => '0871234500003', 'Houdbaarheidsdatum' => '2027-05-20', 'InkoopPrijs' => 8.75, 'VerkoopPrijs' => 19.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'CategorieId' => 1, 'Naam' => 'Baardolie Cedar', 'Omschrijving' => 'Verzorgende olie voor baardbehandelingen.', 'Merk' => 'Tiko Beard', 'EANcode' => '0871234500004', 'Houdbaarheidsdatum' => '2027-09-30', 'InkoopPrijs' => 5.75, 'VerkoopPrijs' => 12.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'CategorieId' => 2, 'Naam' => 'Color Creme 6.1', 'Omschrijving' => 'Professionele asdonkerblonde kleurcreme.', 'Merk' => 'Tiko Color', 'EANcode' => '0871234500005', 'Houdbaarheidsdatum' => '2026-12-31', 'InkoopPrijs' => 12.50, 'VerkoopPrijs' => 24.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'CategorieId' => 2, 'Naam' => 'Color Creme 7.43', 'Omschrijving' => 'Koperblonde salonkleur met warme ondertoon.', 'Merk' => 'Tiko Color', 'EANcode' => '0871234500006', 'Houdbaarheidsdatum' => '2027-01-31', 'InkoopPrijs' => 12.75, 'VerkoopPrijs' => 25.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 7, 'CategorieId' => 2, 'Naam' => 'Developer 6 Procent', 'Omschrijving' => 'Oxidatiecreme voor kleurbehandelingen.', 'Merk' => 'Tiko Color', 'EANcode' => '0871234500007', 'Houdbaarheidsdatum' => '2027-03-31', 'InkoopPrijs' => 5.95, 'VerkoopPrijs' => 11.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 8, 'CategorieId' => 3, 'Naam' => 'Matte Styling Clay', 'Omschrijving' => 'Matte clay met flexibele hold.', 'Merk' => 'Tiko Style', 'EANcode' => '0871234500008', 'Houdbaarheidsdatum' => '2027-08-31', 'InkoopPrijs' => 4.95, 'VerkoopPrijs' => 12.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 9, 'CategorieId' => 3, 'Naam' => 'Strong Hold Gel', 'Omschrijving' => 'Sterke hold styling gel.', 'Merk' => 'Tiko Style', 'EANcode' => '0871234500009', 'Houdbaarheidsdatum' => '2027-03-31', 'InkoopPrijs' => 4.25, 'VerkoopPrijs' => 9.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 10, 'CategorieId' => 3, 'Naam' => 'Heat Protect Spray', 'Omschrijving' => 'Beschermende spray voor föhnen en stylen.', 'Merk' => 'Tiko Style', 'EANcode' => '0871234500010', 'Houdbaarheidsdatum' => '2027-11-30', 'InkoopPrijs' => 6.10, 'VerkoopPrijs' => 15.95, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

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

        DB::table('Bestelling')->insert([
            ['Id' => 1, 'KlantId' => 1, 'BestelNummer' => 600101, 'Omschrijving' => 'Salonproducten besteld na knipafspraak.', 'Datum' => '2026-06-20', 'Tijd' => '09:15:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'KlantId' => 2, 'BestelNummer' => 600102, 'Omschrijving' => 'Aanvulling op thuisverzorging na kleuradvies.', 'Datum' => '2026-06-23', 'Tijd' => '11:40:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'KlantId' => 3, 'BestelNummer' => 600103, 'Omschrijving' => 'Stylingproducten besteld na behandeling.', 'Datum' => '2026-06-25', 'Tijd' => '14:20:00', 'Bestelstatus' => 'Inverwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'KlantId' => 4, 'BestelNummer' => 600104, 'Omschrijving' => 'Baardverzorging besteld voor verzending.', 'Datum' => '2026-06-28', 'Tijd' => '16:05:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'KlantId' => 5, 'BestelNummer' => 600105, 'Omschrijving' => 'Stylingproducten afgerond en afgeleverd.', 'Datum' => '2026-06-30', 'Tijd' => '10:30:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'KlantId' => 1, 'BestelNummer' => 600106, 'Omschrijving' => 'Salonproducten status-test (Ontvangen)', 'Datum' => '2026-06-10', 'Tijd' => '08:20:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 7, 'KlantId' => 2, 'BestelNummer' => 600107, 'Omschrijving' => 'Haarverzorging status-test (Ontvangen)', 'Datum' => '2026-06-14', 'Tijd' => '11:35:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 8, 'KlantId' => 3, 'BestelNummer' => 600108, 'Omschrijving' => 'Stylingproducten status-test (Ontvangen)', 'Datum' => '2026-06-15', 'Tijd' => '13:22:00', 'Bestelstatus' => 'Ontvangen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 9, 'KlantId' => 4, 'BestelNummer' => 600109, 'Omschrijving' => 'Bevestigde bestelling Wax Gel', 'Datum' => '2026-06-11', 'Tijd' => '09:15:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 10, 'KlantId' => 5, 'BestelNummer' => 600110, 'Omschrijving' => 'Bevestigde bestelling Color Creme', 'Datum' => '2026-06-13', 'Tijd' => '15:50:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 11, 'KlantId' => 6, 'BestelNummer' => 600111, 'Omschrijving' => 'Bevestigde bestelling Conditioner', 'Datum' => '2026-06-17', 'Tijd' => '10:44:00', 'Bestelstatus' => 'Bevestigd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 12, 'KlantId' => 1, 'BestelNummer' => 600112, 'Omschrijving' => 'Order in verwerking Masker', 'Datum' => '2026-06-18', 'Tijd' => '11:10:00', 'Bestelstatus' => 'Inverwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 13, 'KlantId' => 2, 'BestelNummer' => 600113, 'Omschrijving' => 'Order in verwerking Baardolie', 'Datum' => '2026-06-21', 'Tijd' => '16:25:00', 'Bestelstatus' => 'Inverwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 14, 'KlantId' => 3, 'BestelNummer' => 600114, 'Omschrijving' => 'Order in verwerking Styling Clay', 'Datum' => '2026-06-22', 'Tijd' => '14:45:00', 'Bestelstatus' => 'Inverwerking', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 15, 'KlantId' => 4, 'BestelNummer' => 600115, 'Omschrijving' => 'Verzonden testorder Shampoo', 'Datum' => '2026-06-23', 'Tijd' => '13:05:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 16, 'KlantId' => 5, 'BestelNummer' => 600116, 'Omschrijving' => 'Verzonden testorder Heat Protect', 'Datum' => '2026-06-24', 'Tijd' => '15:30:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 17, 'KlantId' => 6, 'BestelNummer' => 600117, 'Omschrijving' => 'Verzonden testorder Strong Hold Gel', 'Datum' => '2026-06-27', 'Tijd' => '16:18:00', 'Bestelstatus' => 'Verzonden', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 18, 'KlantId' => 1, 'BestelNummer' => 600118, 'Omschrijving' => 'Afgeleverde kleurcreme and conditioner', 'Datum' => '2026-06-28', 'Tijd' => '10:12:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 19, 'KlantId' => 2, 'BestelNummer' => 600119, 'Omschrijving' => 'Afgeleverde haarverzorgingsset', 'Datum' => '2026-06-29', 'Tijd' => '15:43:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 20, 'KlantId' => 3, 'BestelNummer' => 600120, 'Omschrijving' => 'Afgeleverde stylingproducten', 'Datum' => '2026-06-30', 'Tijd' => '09:58:00', 'Bestelstatus' => 'Afgeleverd', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);

        DB::table('ProductPerBestelling')->insert([
            ['Id' => 1, 'ProductId' => 1, 'BestellingId' => 1, 'Aantal' => 2, 'UnitPrijs' => 14.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 2, 'ProductId' => 2, 'BestellingId' => 1, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 10.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 3, 'ProductId' => 2, 'BestellingId' => 2, 'Aantal' => 3, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 4, 'ProductId' => 5, 'BestellingId' => 2, 'Aantal' => 2, 'UnitPrijs' => 24.95, 'BTWPercentage' => 21.00, 'Korting' => 5.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 5, 'ProductId' => 3, 'BestellingId' => 3, 'Aantal' => 1, 'UnitPrijs' => 19.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 6, 'ProductId' => 8, 'BestellingId' => 3, 'Aantal' => 2, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 15.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 7, 'ProductId' => 4, 'BestellingId' => 4, 'Aantal' => 1, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 8, 'ProductId' => 9, 'BestellingId' => 5, 'Aantal' => 1, 'UnitPrijs' => 9.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 9, 'ProductId' => 10, 'BestellingId' => 5, 'Aantal' => 1, 'UnitPrijs' => 15.95, 'BTWPercentage' => 21.00, 'Korting' => 7.50, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 10, 'ProductId' => 1, 'BestellingId' => 6, 'Aantal' => 2, 'UnitPrijs' => 14.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 11, 'ProductId' => 2, 'BestellingId' => 6, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 30.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 12, 'ProductId' => 3, 'BestellingId' => 7, 'Aantal' => 1, 'UnitPrijs' => 19.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 13, 'ProductId' => 4, 'BestellingId' => 8, 'Aantal' => 1, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 10.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 14, 'ProductId' => 5, 'BestellingId' => 9, 'Aantal' => 1, 'UnitPrijs' => 24.95, 'BTWPercentage' => 21.00, 'Korting' => 15.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 15, 'ProductId' => 2, 'BestellingId' => 10, 'Aantal' => 2, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 16, 'ProductId' => 8, 'BestellingId' => 11, 'Aantal' => 1, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 25.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 17, 'ProductId' => 1, 'BestellingId' => 12, 'Aantal' => 1, 'UnitPrijs' => 14.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 18, 'ProductId' => 3, 'BestellingId' => 13, 'Aantal' => 1, 'UnitPrijs' => 19.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 19, 'ProductId' => 8, 'BestellingId' => 14, 'Aantal' => 2, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 50.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 20, 'ProductId' => 1, 'BestellingId' => 15, 'Aantal' => 1, 'UnitPrijs' => 14.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 21, 'ProductId' => 10, 'BestellingId' => 16, 'Aantal' => 1, 'UnitPrijs' => 15.95, 'BTWPercentage' => 21.00, 'Korting' => 40.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 22, 'ProductId' => 9, 'BestellingId' => 17, 'Aantal' => 1, 'UnitPrijs' => 9.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 23, 'ProductId' => 5, 'BestellingId' => 18, 'Aantal' => 1, 'UnitPrijs' => 24.95, 'BTWPercentage' => 21.00, 'Korting' => 20.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 24, 'ProductId' => 2, 'BestellingId' => 19, 'Aantal' => 1, 'UnitPrijs' => 16.95, 'BTWPercentage' => 21.00, 'Korting' => 0.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
            ['Id' => 25, 'ProductId' => 8, 'BestellingId' => 20, 'Aantal' => 1, 'UnitPrijs' => 12.95, 'BTWPercentage' => 21.00, 'Korting' => 10.00, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => $nu, 'DatumGewijzigd' => $nu],
        ]);
    }
}
