<?php

namespace Database\Seeders;

use App\Models\Habitat;
use Illuminate\Database\Seeder;

class HabitatSeeder extends Seeder
{
    public function run(): void
    {
        $habitat = [
            'Hutan primer', 'Hutan sekunder', 'Hutan mangrove', 'Hutan pegunungan',
            'Perkebunan', 'Tepi sungai', 'Rawa', 'Gua', 'Padang rumput', 'Pekarangan',
        ];

        foreach ($habitat as $nama) {
            Habitat::updateOrCreate(['nama' => $nama], ['nama' => $nama]);
        }
    }
}
