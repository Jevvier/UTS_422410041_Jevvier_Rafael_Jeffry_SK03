<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use App\Models\Peneliti;
use App\Models\Rekaman;
use App\Models\Spesies;
use App\Services\RekamanService;
use Illuminate\Database\Seeder;

class RekamanSeeder extends Seeder
{
    public function __construct(private RekamanService $service)
    {
    }

    public function run(): void
    {
        // Data acak tetapi deterministik agar hasil seed selalu sama.
        mt_srand(42);

        $spesies = Spesies::pluck('id')->all();
        $lokasi = Lokasi::pluck('id')->all();
        $peneliti = Peneliti::pluck('id')->all();

        $jenisAnalisis = ['Identifikasi spesies', 'Deteksi vokalisasi', 'Pengukuran frekuensi dominan', 'Hitung panggilan'];
        $cuaca = ['Cerah', 'Berawan', 'Hujan ringan', 'Berkabut'];
        $kualitas = ['Baik', 'Baik', 'Sedang', 'Rendah'];
        $format = ['WAV', 'WAV', 'FLAC', 'MP3'];

        for ($i = 0; $i < 30; $i++) {
            $pilihPeneliti = collect($peneliti)->shuffle()->take(mt_rand(1, 3))->values()->all();

            $data = [
                'spesies_id' => $spesies[array_rand($spesies)],
                'lokasi_id' => $lokasi[array_rand($lokasi)],
                'tanggal_rekam' => now()->subDays(mt_rand(1, 170))->toDateString(),
                'waktu_mulai' => sprintf('%02d:%02d', mt_rand(4, 18), mt_rand(0, 59)),
                'durasi_detik' => mt_rand(60, 1800),
                'sample_rate' => [44100, 48000, 96000][mt_rand(0, 2)],
                'format' => $format[array_rand($format)],
                'kualitas' => $kualitas[array_rand($kualitas)],
                'kondisi_cuaca' => $cuaca[array_rand($cuaca)],
                'catatan' => 'Rekaman lapangan ke-'.($i + 1).' (data contoh).',
                'peneliti_ids' => $pilihPeneliti,
                'penanggung_jawab_id' => $pilihPeneliti[0],
                'analisis' => collect(range(1, mt_rand(1, 3)))->map(fn () => [
                    'jenis_analisis' => $jenisAnalisis[array_rand($jenisAnalisis)],
                    'frekuensi_dominan_hz' => mt_rand(800, 9000) + mt_rand(0, 99) / 100,
                    'tingkat_keyakinan' => mt_rand(45, 99),
                    'hasil' => 'Hasil analisis awal, perlu verifikasi oleh peneliti.',
                ])->all(),
            ];

            $this->service->create($data);
        }
    }
}
