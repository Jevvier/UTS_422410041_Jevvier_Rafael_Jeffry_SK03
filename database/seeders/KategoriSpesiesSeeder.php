<?php

namespace Database\Seeders;

use App\Models\KategoriSpesies;
use Illuminate\Database\Seeder;

class KategoriSpesiesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Burung', 'deskripsi' => 'Aves: vokalisasi kicau dan panggilan teritorial.'],
            ['nama' => 'Mamalia', 'deskripsi' => 'Mammalia: lolongan, ultrasonik kelelawar, dan panggilan sosial.'],
            ['nama' => 'Amfibi', 'deskripsi' => 'Anura: paduan suara katak dan panggilan kawin.'],
            ['nama' => 'Serangga', 'deskripsi' => 'Insecta: stridulasi jangkrik dan tonggeret.'],
        ];

        foreach ($data as $row) {
            KategoriSpesies::updateOrCreate(['nama' => $row['nama']], $row);
        }
    }
}
