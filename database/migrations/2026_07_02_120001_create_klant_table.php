<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabel Klant, exact volgens het create-script
 * (database/Createscript/SQL_Dag3.sql). Op MySQL is het create-script leidend
 * en bestaat de tabel al; deze migration slaat de aanmaak dan over zodat er
 * nooit een tweede definitie van het schema ontstaat.
 * 
 * FIX: Bijzonderheden VARCHAR(50) in plaats van VARCHAR(15)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('Klant')) {
            return;
        }

        Schema::create('Klant', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('UserId');
            $table->string('Voornaam', 100);
            $table->string('Tussenvoegsel', 30)->nullable();
            $table->string('Achternaam', 100);
            $table->string('Relatienummer', 20);
            $table->string('Bijzonderheden', 50);  // ✅ FIXED: Was 15, nu 50 zoals SQL_Dag3
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');

            $table->foreign('UserId')->references('Id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Klant');
    }
};