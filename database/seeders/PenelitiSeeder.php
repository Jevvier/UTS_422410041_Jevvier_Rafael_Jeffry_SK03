<?php

namespace Database\Seeders;

use App\Models\Peneliti;
use Illuminate\Database\Seeder;

class PenelitiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Dr. Ayu Lestari', 'email' => 'ayu.lestari@example.ac.id', 'institusi' => 'Fakultas Biologi', 'keahlian' => 'Bioakustik burung'],
            ['nama' => 'Bagas Pratama, M.Si.', 'email' => 'bagas.pratama@example.ac.id', 'institusi' => 'Fakultas Biologi', 'keahlian' => 'Analisis spektrogram'],
            ['nama' => 'Dr. Citra Wulandari', 'email' => 'citra.wulandari@example.ac.id', 'institusi' => 'Fakultas Peternakan', 'keahlian' => 'Mamalia & ultrasonik'],
            ['nama' => 'Dimas Saputra, S.Si.', 'email' => 'dimas.saputra@example.ac.id', 'institusi' => 'Laboratorium Bioakustik', 'keahlian' => 'Perekaman lapangan'],
            ['nama' => 'Eka Rahmawati, M.Sc.', 'email' => 'eka.rahmawati@example.ac.id', 'institusi' => 'Laboratorium Bioakustik', 'keahlian' => 'Amfibi & serangga'],
        ];

        foreach ($data as $row) {
            Peneliti::updateOrCreate(['email' => $row['email']], $row);
        }
    }
}
