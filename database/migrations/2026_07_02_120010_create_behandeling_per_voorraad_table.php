<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabel BehandelingPerVoorraad, exact volgens het
 * create-script (database/Createscript/SQL_Dag3.sql). Op MySQL is het
 * create-script leidend en bestaat de tabel al; deze migration slaat de
 * aanmaak dan over.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('BehandelingPerVoorraad')) {
            return;
        }

        Schema::create('BehandelingPerVoorraad', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('BehandelingId');
            $table->unsignedInteger('VoorraadId');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');
            $table->foreign('BehandelingId')->references('Id')->on('Behandeling');
            $table->foreign('VoorraadId')->references('Id')->on('Voorraad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BehandelingPerVoorraad');
    }
};
