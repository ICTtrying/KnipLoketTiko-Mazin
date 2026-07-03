<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration die alle stored procedures voor de Bestelling-module registreert
 * vanaf de .sql-bestanden in database/Createscript/Procedures.
 *
 * Alleen MySQL/MariaDB: sqlite kent geen stored procedures; de controller
 * gebruikt daar een query-fallback.
 */
return new class extends Migration
{
    /** @var list<string> Bestandsnamen van de stored procedures, in aanmaakvolgorde */
    private array $procedureBestanden = [
        'sp_bestellingen_overzicht.sql',
        'sp_bestelling_producten_overzicht.sql',
        'sp_bestelproduct_ophalen.sql',
        'sp_bestelproduct_wijzigen.sql',
    ];

    public function up(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach ($this->procedureBestanden as $bestand) {
            $procedureNaam = pathinfo($bestand, PATHINFO_FILENAME);
            $pad = database_path('Createscript/Procedures/'.$bestand);

            DB::unprepared('DROP PROCEDURE IF EXISTS '.$procedureNaam);
            DB::unprepared($this->verwijderDelimiterSyntax(file_get_contents($pad)));
        }
    }

    public function down(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach ($this->procedureBestanden as $bestand) {
            $procedureNaam = pathinfo($bestand, PATHINFO_FILENAME);

            DB::unprepared('DROP PROCEDURE IF EXISTS '.$procedureNaam);
        }
    }

    /**
     * DELIMITER is een commando van de mysql-commandline-client, geen server-SQL.
     * Via PDO wordt de hele CREATE PROCEDURE als één statement verstuurd, dus de
     * DELIMITER-regels moeten eruit en END$$ wordt weer gewoon END.
     */
    private function verwijderDelimiterSyntax(string $sql): string
    {
        $sql = preg_replace('/^\s*DELIMITER\b.*$/mi', '', $sql);

        return str_replace('$$', ';', $sql);
    }
};
