<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Urutan penting: master dahulu, lalu data transaksi yang bergantung padanya. */
    public function run(): void
    {
        $this->call([
            KategoriSpesiesSeeder::class,
            HabitatSeeder::class,
            LokasiSeeder::class,
            PenelitiSeeder::class,
            SpesiesSeeder::class,
            RekamanSeeder::class,
        ]);
    }
}
