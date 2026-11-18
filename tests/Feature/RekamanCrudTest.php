<?php

namespace Tests\Feature;

use App\Models\AnalisisRekaman;
use App\Models\Habitat;
use App\Models\KategoriSpesies;
use App\Models\Lokasi;
use App\Models\Peneliti;
use App\Models\Rekaman;
use App\Models\Spesies;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekamanCrudTest extends TestCase
{
    use RefreshDatabase;

    private function masterData(): array
    {
        $kategori = KategoriSpesies::create(['nama' => 'Burung']);
        $spesies = Spesies::create([
            'kategori_spesies_id' => $kategori->id,
            'nama_ilmiah' => 'Pycnonotus zeylanicus',
            'nama_umum' => 'Cucak rawa',
            'status_konservasi' => 'EN',
        ]);
        $habitat = Habitat::create(['nama' => 'Rawa']);
        $spesies->habitat()->sync([$habitat->id]);

        return [
            'spesies' => $spesies,
            'lokasi' => Lokasi::create(['nama' => 'TN Ujung Kulon', 'provinsi' => 'Banten']),
            'peneliti' => [
                Peneliti::create(['nama' => 'A', 'email' => 'a@example.test']),
                Peneliti::create(['nama' => 'B', 'email' => 'b@example.test']),
            ],
        ];
    }

    private function payload(array $m, array $override = []): array
    {
        return array_merge([
            'spesies_id' => $m['spesies']->id,
            'lokasi_id' => $m['lokasi']->id,
            'tanggal_rekam' => now()->subDay()->toDateString(),
            'durasi_detik' => 300,
            'sample_rate' => 48000,
            'format' => 'WAV',
            'kualitas' => 'Baik',
            'peneliti_ids' => [$m['peneliti'][0]->id, $m['peneliti'][1]->id],
            'penanggung_jawab_id' => $m['peneliti'][1]->id,
            'analisis' => [
                ['jenis_analisis' => 'Identifikasi spesies', 'tingkat_keyakinan' => 90, 'frekuensi_dominan_hz' => 2400.5],
            ],
        ], $override);
    }

    public function test_store_menyimpan_rekaman_pivot_dan_analisis_dalam_transaksi(): void
    {
        $m = $this->masterData();

        $this->post(route('rekaman.store'), $this->payload($m))
            ->assertRedirect();

        $rekaman = Rekaman::with('peneliti', 'analisis')->first();
        $this->assertNotNull($rekaman);
        $this->assertCount(2, $rekaman->peneliti);
        $this->assertSame('Penanggung Jawab', $rekaman->peneliti->firstWhere('id', $m['peneliti'][1]->id)->pivot->peran);
        $this->assertSame('Anggota', $rekaman->peneliti->firstWhere('id', $m['peneliti'][0]->id)->pivot->peran);
        $this->assertCount(1, $rekaman->analisis);
    }

    public function test_penanggung_jawab_harus_salah_satu_peneliti_terpilih(): void
    {
        $m = $this->masterData();

        $this->post(route('rekaman.store'), $this->payload($m, [
            'peneliti_ids' => [$m['peneliti'][0]->id],
            'penanggung_jawab_id' => $m['peneliti'][1]->id,
        ]))->assertSessionHasErrors('penanggung_jawab_id');

        $this->assertDatabaseCount('rekaman', 0);
    }

    public function test_tanggal_rekam_tidak_boleh_di_masa_depan(): void
    {
        $m = $this->masterData();

        $this->post(route('rekaman.store'), $this->payload($m, [
            'tanggal_rekam' => now()->addDays(3)->toDateString(),
        ]))->assertSessionHasErrors('tanggal_rekam');
    }

    public function test_update_mengganti_analisis_lama_dengan_yang_baru(): void
    {
        $m = $this->masterData();
        $this->post(route('rekaman.store'), $this->payload($m));
        $rekaman = Rekaman::firstOrFail();

        $this->put(route('rekaman.update', $rekaman), $this->payload($m, [
            'analisis' => [
                ['jenis_analisis' => 'Hitung panggilan', 'tingkat_keyakinan' => 70],
                ['jenis_analisis' => 'Deteksi vokalisasi', 'tingkat_keyakinan' => 85],
            ],
        ]))->assertRedirect();

        $this->assertSame(2, AnalisisRekaman::where('rekaman_id', $rekaman->id)->count());
    }

    public function test_spesies_yang_sudah_punya_rekaman_tidak_bisa_dihapus(): void
    {
        $m = $this->masterData();
        $this->post(route('rekaman.store'), $this->payload($m));

        $this->delete(route('spesies.destroy', $m['spesies']))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('spesies', ['id' => $m['spesies']->id]);
    }

    public function test_filter_kualitas_dan_pencarian_berfungsi(): void
    {
        $m = $this->masterData();
        $this->post(route('rekaman.store'), $this->payload($m, ['kualitas' => 'Rendah']));
        $this->post(route('rekaman.store'), $this->payload($m, ['kualitas' => 'Baik']));

        $this->get(route('rekaman.index', ['kualitas' => 'Rendah', 'q' => 'Cucak']))
            ->assertOk()
            ->assertViewHas('ringkasan', fn ($r) => $r['total'] === 1);
    }
}
