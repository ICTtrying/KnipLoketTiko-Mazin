<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Voert het gezaghebbende SQL create-script van het team uit: database/Createscript/SQL_Dag3.sql.
 *
 * Uitgangspunten:
 * - Het create-script is leidend en wordt NIET overgetypt in PHP. Deze migration leest het
 *   bestand van schijf en biedt de statements ongewijzigd aan de database aan, zodat de vaste
 *   database-opzet van het team herhaalbaar is via `php artisan migrate`.
 * - Keuze rond DROP/CREATE/USE DATABASE (bewust optie a): het script begint met
 *   `DROP DATABASE IF EXISTS ...; CREATE DATABASE ...; USE ...;`. Een Laravel-migration draait
 *   echter altijd binnen de database die al via .env is gekozen. Daarom slaan we uitsluitend
 *   deze drie database-statements over en voeren we alle CREATE TABLE- en INSERT-statements
 *   uit binnen de huidige verbinding. Dit is veiliger (niet destructief voor andere databases)
 *   en volgt de Laravel-conventie dat .env de database bepaalt. Wil het team het script tóch
 *   inclusief DROP/CREATE DATABASE draaien, dan kan dat buiten Laravel om via de MySQL-client.
 * - Sql_mode: het script bevat testdata die langer is dan sommige kolomdefinities
 *   (bijv. Klant.Bijzonderheden VARCHAR(15) met langere teksten). Op MySQL 8 met strict mode
 *   zou de INSERT daardoor afbreken. Omdat het script niet gewijzigd mag worden, zetten we
 *   sql_mode alleen voor deze sessie op niet-strict (waarden worden dan afgekapt, het klassieke
 *   MySQL-gedrag) en herstellen we de oorspronkelijke modus na afloop.
 * - Idempotent: staan er al bestellingen in de tabel Bestelling, dan is het script al eerder
 *   volledig gedraaid en slaan we de uitvoering over (geen dubbele testdata). Bestaan de
 *   tabellen wel maar zonder data (een eerder afgebroken poging), dan ruimen we de
 *   script-tabellen eerst op zodat het script alsnog schoon en volledig kan draaien.
 * - Alleen MySQL/MariaDB: het script gebruikt MySQL-syntaxis (ENGINE=InnoDB, BIT, NOW(6)).
 *   Op sqlite (de geautomatiseerde testomgeving) wordt deze migration overgeslagen; daar
 *   verzorgen de sqlite-fallbackmigrations het schema.
 */
return new class extends Migration
{
    /**
     * @var list<string> Tabellen die het create-script aanmaakt, in veilige drop-volgorde
     */
    private array $scriptTabellen = [
        'LeverancierOrder',
        'Leverancier',
        'BehandelingPerVoorraad',
        'Voorraad',
        'ProductPerBestelling',
        'Product',
        'Categorie',
        'Bestelling',
        'Feedback',
        'Afspraak',
        'MedewerkerPerBehandeling',
        'Beschikbaarheid',
        'Behandeling',
        'MedewerkerPerContact',
        'KlantPerContact',
        'Contact',
        'Medewerker',
        'Klant',
        'users',
    ];

    /**
     * Voer het create-script uit binnen de door .env gekozen database.
     */
    public function up(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            Log::info('Createscript-migration overgeslagen: geen MySQL/MariaDB-verbinding (sqlite-fallbackmigrations verzorgen het schema).');

            return;
        }

        if (Schema::hasTable('Bestelling') && DB::table('Bestelling')->count() > 0) {
            Log::info('Createscript-migration overgeslagen: schema en testdata bestaan al, dubbele testdata voorkomen.');

            return;
        }

        if (Schema::hasTable('Bestelling')) {
            // Eerder afgebroken poging (tabellen zonder data): eerst opruimen zodat het script schoon kan draaien
            Log::warning('Createscript-migration: lege script-tabellen aangetroffen, deze worden opnieuw aangemaakt.');
            $this->dropScriptTabellen();
        }

        $pad = database_path('Createscript/SQL_Dag3.sql');
        $sql = file_get_contents($pad);

        /*
         * Statements splitsen op ';' zodat DB::unprepared ze één voor één kan uitvoeren.
         * Alleen de drie database-statements (DROP/CREATE/USE DATABASE) worden overgeslagen;
         * alle overige statements gaan letterlijk en ongewijzigd naar de database.
         * Het script bevat geen stored procedures, dus DELIMITER-blokken spelen hier niet.
         */
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn (string $statement): bool => $statement !== ''
                && preg_match('/^(DROP\s+DATABASE|CREATE\s+DATABASE|USE)\b/i', ltrim(preg_replace('/^\s*--.*$/m', '', $statement))) !== 1
        );

        $origineleSqlMode = DB::select('SELECT @@SESSION.sql_mode AS sql_mode')[0]->sql_mode;

        try {
            // Niet-strict draaien zodat de testdata uit het (ongewijzigde) script laadbaar is; zie klasse-commentaar
            DB::unprepared("SET SESSION sql_mode = ''");

            foreach ($statements as $statement) {
                DB::unprepared($statement.';');
            }
        } finally {
            DB::unprepared("SET SESSION sql_mode = '".$origineleSqlMode."'");
        }

        Log::info('Createscript uitgevoerd via migration.', ['bestand' => $pad]);
    }

    /**
     * Let op: bewust destructief geïmplementeerd, met voorzichtigheid te gebruiken.
     * Dropt alle tabellen die het create-script aanmaakt.
     */
    public function down(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $this->dropScriptTabellen();
    }

    /**
     * Drop de script-tabellen met uitgeschakelde foreign key-controles,
     * zodat de volgorde geen rol speelt.
     */
    private function dropScriptTabellen(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->scriptTabellen as $tabel) {
            Schema::dropIfExists($tabel);
        }

        Schema::enableForeignKeyConstraints();
    }
};
