<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Laravel-standaardtabellen: users, password_reset_tokens en sessions.
 *
 * De users-tabel is in het create-script bewust Engels en lowercase gehouden
 * (Laravel's eigen default-tabel) en wordt op MySQL door de createscript-migration
 * aangemaakt. Deze migration maakt users daarom alleen aan als de tabel nog niet
 * bestaat (sqlite-testomgeving), met exact de kolommen uit het create-script.
 * password_reset_tokens en sessions staan niet in het create-script en zijn puur
 * Laravel-infrastructuur; die worden hier altijd aangemaakt.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('name', 150);
                $table->string('email')->unique();
                $table->dateTime('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role', 30);
                $table->rememberToken();
                $table->boolean('IsActief')->default(true);
                $table->string('Opmerking', 255)->nullable();
                $table->timestamps();
            });
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Op MySQL wordt users gedropt door de createscript-migration (met
     * uitgeschakelde foreign key-controles); hier alleen buiten MySQL droppen
     * om foreign key-fouten vanuit Klant/Medewerker te voorkomen.
     */
    public function down(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            Schema::dropIfExists('users');
        }

        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
