<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabel ProductPerBestelling, exact volgens het
 * create-script (database/Createscript/SQL_Dag3.sql): Aantal is SMALLINT en
 * BTWPercentage/Korting zijn DECIMAL(4,2). Op MySQL is het create-script
 * leidend en bestaat de tabel al; deze migration slaat de aanmaak dan over.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('ProductPerBestelling')) {
            return;
        }

        Schema::create('ProductPerBestelling', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('BestellingId');
            $table->smallInteger('Aantal');
            $table->decimal('UnitPrijs', 6, 2);
            $table->decimal('BTWPercentage', 4, 2);
            $table->decimal('Korting', 4, 2);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');

            $table->foreign('ProductId')->references('Id')->on('Product');
            $table->foreign('BestellingId')->references('Id')->on('Bestelling');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductPerBestelling');
    }
};
