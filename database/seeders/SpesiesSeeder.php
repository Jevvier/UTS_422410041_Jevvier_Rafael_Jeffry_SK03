<?php

namespace Database\Seeders;

use App\Models\Habitat;
use App\Models\KategoriSpesies;
use App\Models\Spesies;
use Illuminate\Database\Seeder;

class SpesiesSeeder extends Seeder
{
    public function run(): void
    {
        // Status konservasi di bawah adalah data contoh; verifikasi ulang dengan IUCN sebelum dipakai.
        $data = [
            ['kategori' => 'Burung', 'ilmiah' => 'Pycnonotus zeylanicus', 'umum' => 'Cucak rawa', 'status' => 'EN', 'habitat' => ['Rawa', 'Hutan sekunder']],
            ['kategori' => 'Burung', 'ilmiah' => 'Strix seloputo', 'umum' => 'Kukuk beluk', 'status' => 'LC', 'habitat' => ['Hutan primer', 'Perkebunan']],
            ['kategori' => 'Burung', 'ilmiah' => 'Gallus gallus', 'umum' => 'Ayam hutan merah', 'status' => 'LC', 'habitat' => ['Hutan sekunder', 'Pekarangan']],
            ['kategori' => 'Mamalia', 'ilmiah' => 'Hylobates moloch', 'umum' => 'Owa jawa', 'status' => 'EN', 'habitat' => ['Hutan primer', 'Hutan pegunungan']],
            ['kategori' => 'Mamalia', 'ilmiah' => 'Pteropus vampyrus', 'umum' => 'Kalong besar', 'status' => 'NT', 'habitat' => ['Hutan mangrove', 'Hutan sekunder']],
            ['kategori' => 'Mamalia', 'ilmiah' => 'Tarsius spectrum', 'umum' => 'Tarsius', 'status' => 'NT', 'habitat' => ['Hutan sekunder', 'Perkebunan']],
            ['kategori' => 'Amfibi', 'ilmiah' => 'Rhacophorus reinwardtii', 'umum' => 'Katak pohon Reinwardt', 'status' => 'LC', 'habitat' => ['Tepi sungai', 'Hutan pegunungan']],
            ['kategori' => 'Amfibi', 'ilmiah' => 'Leptobrachium hasseltii', 'umum' => 'Katak serasah', 'status' => 'DD', 'habitat' => ['Hutan primer', 'Gua']],
            ['kategori' => 'Serangga', 'ilmiah' => 'Gryllus bimaculatus', 'umum' => 'Jangkrik lapang', 'status' => 'LC', 'habitat' => ['Padang rumput', 'Pekarangan']],
            ['kategori' => 'Serangga', 'ilmiah' => 'Platypleura kaempferi', 'umum' => 'Tonggeret', 'status' => 'DD', 'habitat' => ['Hutan sekunder', 'Perkebunan']],
        ];

        foreach ($data as $row) {
            $spesies = Spesies::updateOrCreate(
                ['nama_ilmiah' => $row['ilmiah']],
                [
                    'kategori_spesies_id' => KategoriSpesies::where('nama', $row['kategori'])->value('id'),
                    'nama_umum' => $row['umum'],
                    'status_konservasi' => $row['status'],
                    'deskripsi' => "Spesies {$row['umum']} ({$row['ilmiah']}) — data contoh untuk pengujian aplikasi.",
                ]
            );

            $spesies->habitat()->sync(Habitat::whereIn('nama', $row['habitat'])->pluck('id'));
        }
    }
}
