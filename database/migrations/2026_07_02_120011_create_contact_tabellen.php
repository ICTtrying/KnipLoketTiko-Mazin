<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabellen Contact en KlantPerContact, exact volgens
 * het create-script (database/Createscript/SQL_Dag3.sql). Op MySQL is het
 * create-script leidend en bestaan de tabellen al; deze migration slaat de
 * aanmaak dan over zodat er nooit een tweede definitie van het schema ontstaat.
 *
 * Mobiel is VARCHAR(20) conform de correctie in
 * 2026_07_03_000006_corrigeer_contact_mobiel_kolom.php.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('Contact')) {
            Schema::create('Contact', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('Straatnaam', 100);
                $table->smallInteger('Huisnummer');
                $table->string('Toevoeging', 10)->nullable();
                $table->string('Postcode', 10);
                $table->string('Plaats', 100);
                $table->string('Email', 255);
                $table->string('Mobiel', 20);
                $table->boolean('IsActief')->default(true);
                $table->string('Opmerking', 255)->nullable();
                $table->dateTime('DatumAangemaakt');
                $table->dateTime('DatumGewijzigd');
            });
        }

        if (! Schema::hasTable('KlantPerContact')) {
            Schema::create('KlantPerContact', function (Blueprint $table) {
                $table->increments('Id');
                $table->unsignedInteger('KlantId');
                $table->unsignedInteger('ContactId');
                $table->boolean('IsActief')->default(true);
                $table->string('Opmerking', 255)->nullable();
                $table->dateTime('DatumAangemaakt', 6);
                $table->dateTime('DatumGewijzigd', 6);

                $table->foreign('KlantId')->references('Id')->on('Klant')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('ContactId')->references('Id')->on('Contact')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('KlantPerContact');
        Schema::dropIfExists('Contact');
    }
};
