<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Corrigeert de kolom Contact.Mobiel en de daarop afgekapte testdata.
 *
 * Het create-script (database/Createscript/SQL_Dag3.sql) definieert
 * Mobiel als VARCHAR(15), maar de testdata voor de klanten KL-2026-001
 * t/m KL-2026-006 bevat genummerde waarden als '+31 6 1234 61 71'
 * (16 tekens). De createscript-migration draait bewust met een niet-strikte
 * sql_mode (zie 0000_00_00_000000_voer_createscript_uit.php) zodat het
 * ongewijzigde examenscript kan laden; daardoor is elk van deze zes
 * mobiele nummers stilzwijgend afgekapt tot dezelfde 15 tekens
 * '+31 6 1234 61 7', met verlies van het onderscheidende laatste cijfer.
 *
 * Deze migration verruimt de kolom zodat toekomstige wijzigingen via
 * sp_klant_wijzigen niet opnieuw kunnen afknappen, en herstelt de zes
 * bestaande rijen naar de volledige waarde uit de testdata-specificatie.
 */
return new class extends Migration
{
    /** @var array<int, string> Contact.Id => volledig mobiel nummer conform de testdata-specificatie */
    private array $correcteMobielPerContactId = [
        11 => '+31 6 1234 61 71',
        12 => '+31 6 1234 61 72',
        13 => '+31 6 1234 61 73',
        14 => '+31 6 1234 61 74',
        15 => '+31 6 1234 61 75',
        16 => '+31 6 1234 61 76',
    ];

    public function up(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        if (! Schema::hasTable('Contact')) {
            return;
        }

        Schema::table('Contact', function ($table): void {
            $table->string('Mobiel', 20)->nullable(false)->change();
        });

        foreach ($this->correcteMobielPerContactId as $contactId => $mobiel) {
            DB::table('Contact')
                ->where('Id', $contactId)
                ->where('Mobiel', '+31 6 1234 61 7')
                ->update(['Mobiel' => $mobiel]);
        }
    }

    public function down(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        if (! Schema::hasTable('Contact')) {
            return;
        }

        Schema::table('Contact', function ($table): void {
            $table->string('Mobiel', 15)->nullable(false)->change();
        });
    }
};
