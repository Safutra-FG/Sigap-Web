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
        // Urutan sangat penting: Bidang dulu, baru User!
        $this->call([
            BidangSeeder::class,
            UserSeeder::class,
        ]);
    }
}
