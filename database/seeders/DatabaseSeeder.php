<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // BestellingSeeder eerst: de producten moeten bestaan voordat de
        // BehandelingSeeder de voorraad- en behandelingsdata kan koppelen
        $this->call(BestellingSeeder::class);
        $this->call(BehandelingSeeder::class);
    }
}
