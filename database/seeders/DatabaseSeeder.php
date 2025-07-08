<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DatabaseSeeder::call([
            PeranSeeder::class,
            UserSeeder::class,
            KlasifikasiSuratSeeder::class
        ]);
    }
}
