<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration die de stored procedure GetAllProducten voor de Product-module registreert.
 *
 * De procedure staat in database/sql/procedures/Sp_GetAllProducten.sql. De DELIMITER-regels
 * in dat bestand zijn bedoeld voor handmatige uitvoering in bijv. phpMyAdmin/Workbench en
 * worden hier verwijderd, omdat de server die client-directive niet kent.
 */
return new class extends Migration
{
    private const PROCEDURE_NAAM = 'GetAllProducten';

    public function up(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $sql = file_get_contents(database_path('sql/procedures/Sp_GetAllProducten.sql'));

        // Verwijder de DELIMITER-directives en de $$-scheidingstekens voor uitvoering via PDO
        $sql = preg_replace('/^\s*DELIMITER.*$/mi', '', $sql);
        $sql = str_replace('$$', '', $sql);

        DB::unprepared('DROP PROCEDURE IF EXISTS '.self::PROCEDURE_NAAM);
        DB::unprepared($sql);
    }

    public function down(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS '.self::PROCEDURE_NAAM);
    }
};
