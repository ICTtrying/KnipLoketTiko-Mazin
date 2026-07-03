<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration die alle stored procedures voor de Bestelling-module registreert.
 */
return new class extends Migration
{
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
            $pad = database_path('sql/procedures/'.$bestand);

            DB::unprepared('DROP PROCEDURE IF EXISTS '.$procedureNaam);
            DB::unprepared(file_get_contents($pad));
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
};
