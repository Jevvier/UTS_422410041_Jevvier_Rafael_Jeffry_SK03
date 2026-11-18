<?php

namespace App\Services;

use App\Models\Rekaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Logika bisnis utama. Semua penulisan yang melibatkan banyak tabel
 * (rekaman + pivot peneliti + detail analisis) dibungkus transaksi.
 */
class RekamanService
{
    public function search(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($filters)
            ->with(['spesies:id,nama_umum,nama_ilmiah', 'lokasi:id,nama,provinsi', 'peneliti'])
            ->withCount('analisis')
            ->orderByDesc('tanggal_rekam')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /** @return array{total:int,total_durasi:int} */
    public function ringkasan(array $filters): array
    {
        $query = $this->baseQuery($filters);

        return [
            'total' => (clone $query)->count(),
            'total_durasi' => (int) (clone $query)->sum('durasi_detik'),
        ];
    }

    public function create(array $data): Rekaman
    {
        return DB::transaction(function () use ($data) {
            $rekaman = Rekaman::create(Arr::only($data, $this->rekamanColumns()));

            $this->syncPeneliti($rekaman, $data);
            $this->syncAnalisis($rekaman, $data['analisis'] ?? []);

            return $rekaman->refresh();
        });
    }

    public function update(Rekaman $rekaman, array $data): Rekaman
    {
        return DB::transaction(function () use ($rekaman, $data) {
            $rekaman->update(Arr::only($data, $this->rekamanColumns()));

            $this->syncPeneliti($rekaman, $data);

            // Detail analisis diperbarui dengan strategi hapus-lalu-tulis ulang
            // agar baris yang dihapus pengguna di form ikut terhapus.
            $rekaman->analisis()->delete();
            $this->syncAnalisis($rekaman, $data['analisis'] ?? []);

            return $rekaman->refresh();
        });
    }

    public function delete(Rekaman $rekaman): void
    {
        DB::transaction(function () use ($rekaman) {
            $rekaman->analisis()->delete();
            $rekaman->peneliti()->detach();
            $rekaman->delete();
        });
    }

    /** Pivot peran: penanggung jawab mendapat peran khusus, lainnya 'Anggota'. */
    private function syncPeneliti(Rekaman $rekaman, array $data): void
    {
        $pjId = (int) ($data['penanggung_jawab_id'] ?? 0);

        $payload = collect($data['peneliti_ids'] ?? [])
            ->mapWithKeys(fn ($id) => [
                (int) $id => ['peran' => (int) $id === $pjId ? 'Penanggung Jawab' : 'Anggota'],
            ])
            ->all();

        $rekaman->peneliti()->sync($payload);
    }

    private function syncAnalisis(Rekaman $rekaman, array $rows): void
    {
        foreach ($rows as $row) {
            $rekaman->analisis()->create(Arr::only($row, [
                'jenis_analisis', 'frekuensi_dominan_hz', 'tingkat_keyakinan', 'hasil',
            ]));
        }
    }

    private function rekamanColumns(): array
    {
        return [
            'spesies_id', 'lokasi_id', 'tanggal_rekam', 'waktu_mulai', 'durasi_detik',
            'sample_rate', 'format', 'kualitas', 'kondisi_cuaca', 'catatan',
        ];
    }

    private function baseQuery(array $filters): Builder
    {
        $q = trim((string) ($filters['q'] ?? ''));

        return Rekaman::query()
            ->when($q !== '', function (Builder $query) use ($q) {
                $like = '%'.addcslashes($q, '%_\\').'%';
                $query->where(fn (Builder $w) => $w
                    ->where('catatan', 'like', $like)
                    ->orWhere('kondisi_cuaca', 'like', $like)
                    ->orWhereHas('spesies', fn (Builder $s) => $s
                        ->where('nama_umum', 'like', $like)
                        ->orWhere('nama_ilmiah', 'like', $like))
                    ->orWhereHas('lokasi', fn (Builder $l) => $l->where('nama', 'like', $like)));
            })
            ->when(! empty($filters['spesies_id']), fn (Builder $query) =>
                $query->where('spesies_id', $filters['spesies_id']))
            ->when(! empty($filters['lokasi_id']), fn (Builder $query) =>
                $query->where('lokasi_id', $filters['lokasi_id']))
            ->when(! empty($filters['kualitas']), fn (Builder $query) =>
                $query->where('kualitas', $filters['kualitas']))
            ->when(! empty($filters['peneliti_id']), fn (Builder $query) =>
                $query->whereHas('peneliti', fn (Builder $p) => $p->where('peneliti.id', $filters['peneliti_id'])))
            ->when(! empty($filters['dari']), fn (Builder $query) =>
                $query->whereDate('tanggal_rekam', '>=', $filters['dari']))
            ->when(! empty($filters['sampai']), fn (Builder $query) =>
                $query->whereDate('tanggal_rekam', '<=', $filters['sampai']));
    }
}
