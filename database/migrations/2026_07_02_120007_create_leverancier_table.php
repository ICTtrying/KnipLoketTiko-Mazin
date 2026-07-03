<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabel Leverancier, exact volgens het create-script
 * (database/Createscript/SQL_Dag3.sql). Op MySQL is het create-script leidend
 * en bestaat de tabel al; deze migration slaat de aanmaak dan over.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('Leverancier')) {
            return;
        }

        Schema::create('Leverancier', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Naam', 100);
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Leverancier');
    }
};
