<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sqlite-fallback voor de tabel LeverancierOrder, exact volgens het create-script
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
        if (Schema::hasTable('LeverancierOrder')) {
            return;
        }

        Schema::create('LeverancierOrder', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Ordernummer', 20)->unique();
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('LeverancierId');
            $table->integer('Aantal');
            $table->date('Orderdatum');
            $table->date('Leverdatum')->nullable();
            $table->string('Leverstatus', 20);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');
            $table->foreign('ProductId')->references('Id')->on('Product');
            $table->foreign('LeverancierId')->references('Id')->on('Leverancier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LeverancierOrder');
    }
};
