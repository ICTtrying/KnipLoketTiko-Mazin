<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabel Product, exact volgens het create-script
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
        if (Schema::hasTable('Product')) {
            return;
        }

        Schema::create('Product', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('CategorieId');
            $table->string('Naam', 100);
            $table->string('Omschrijving', 255);
            $table->string('Merk', 50);
            $table->string('EANcode', 20)->unique();
            $table->date('Houdbaarheidsdatum');
            $table->decimal('InkoopPrijs', 6, 2);
            $table->decimal('VerkoopPrijs', 6, 2);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');

            $table->foreign('CategorieId')->references('Id')->on('Categorie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Product');
    }
};
