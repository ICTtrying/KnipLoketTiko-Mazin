<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration die de stored procedures voor de productdetail- en wijzigfunctionaliteit
 * (User Story 08) registreert vanaf de .sql-bestanden in database/Createscript/Procedures.
 *
 * Alleen MySQL/MariaDB: sqlite kent geen stored procedures; de controller
 * gebruikt daar een query-fallback.
 */
return new class extends Migration
{
    /** @var array<string, string> Procedurenaam => bestandsnaam */
    private array $procedures = [
        'GetProductDetail' => 'Sp_GetProductDetail.sql',
        'UpdateProductHoudbaarheidsdatum' => 'Sp_UpdateProductHoudbaarheidsdatum.sql',
    ];

    public function up(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach ($this->procedures as $procedureNaam => $bestand) {
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

        foreach (array_keys($this->procedures) as $procedureNaam) {
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
