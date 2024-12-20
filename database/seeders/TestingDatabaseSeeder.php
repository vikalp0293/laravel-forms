<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestingDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            // HotelsSeeder::class,
            CurrenciesSeeder::class,
        ]);
    }
}
