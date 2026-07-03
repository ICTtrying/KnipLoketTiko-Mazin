<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            $this->maakSQLiteTestSchemaAan();
        }
    }

    protected function maakSQLiteTestSchemaAan(): void
    {
        if (Schema::hasTable('Bestelling')) {
            return;
        }

        Schema::create('User', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('Klant', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('UserId');
            $table->string('Naam', 150);
            $table->string('Telefoonnummer', 20);
            $table->string('WensenAllergieen', 100)->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');
        });

        Schema::create('Categorie', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam', 150)->unique();
            $table->string('Omschrijving', 255);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');
        });

        Schema::create('Product', function (Blueprint $table): void {
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
        });

        Schema::create('Bestelling', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('KlantId');
            $table->unsignedInteger('BestelNummer')->unique();
            $table->string('Omschrijving', 255);
            $table->date('Datum');
            $table->time('Tijd');
            $table->string('Bestelstatus', 30);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');
        });

        Schema::create('ProductPerBestelling', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('BestellingId');
            $table->integer('Aantal');
            $table->decimal('UnitPrijs', 6, 2);
            $table->decimal('BTWPercentage', 5, 2);
            $table->decimal('Korting', 6, 2);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt');
            $table->dateTime('DatumGewijzigd');
        });
    }
}
