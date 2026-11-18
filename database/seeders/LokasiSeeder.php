<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'TN Gunung Halimun Salak', 'provinsi' => 'Jawa Barat', 'latitude' => -6.7000000, 'longitude' => 106.5000000],
            ['nama' => 'TN Ujung Kulon', 'provinsi' => 'Banten', 'latitude' => -6.7500000, 'longitude' => 105.3500000],
            ['nama' => 'TN Gunung Leuser', 'provinsi' => 'Aceh', 'latitude' => 3.6167000, 'longitude' => 97.8000000],
            ['nama' => 'TN Bukit Barisan Selatan', 'provinsi' => 'Lampung', 'latitude' => -5.2000000, 'longitude' => 104.2000000],
            ['nama' => 'HL Sungai Wain', 'provinsi' => 'Kalimantan Timur', 'latitude' => -1.1000000, 'longitude' => 116.9000000],
            ['nama' => 'TN Bantimurung Bulusaraung', 'provinsi' => 'Sulawesi Selatan', 'latitude' => -4.8000000, 'longitude' => 119.7000000],
        ];

        foreach ($data as $row) {
            Lokasi::updateOrCreate(['nama' => $row['nama']], $row + ['deskripsi' => 'Lokasi contoh untuk perekaman lapangan.']);
        }
    }
}
