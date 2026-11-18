<?php

namespace App\Services;

use App\Models\Spesies;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SpesiesService
{
    /** Pencarian (q) + filter kategori dan status konservasi. */
    public function search(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($filters)
            ->with(['kategori', 'habitat'])
            ->withCount('rekaman')
            ->orderBy('nama_umum')
            ->paginate($perPage)
            ->withQueryString();
    }

    /** Buat spesies + sinkronisasi habitat (multi-select) dalam satu transaksi. */
    public function create(array $data): Spesies
    {
        return DB::transaction(function () use ($data) {
            $spesies = Spesies::create($data);
            $spesies->habitat()->sync($data['habitat_ids'] ?? []);

            return $spesies->refresh();
        });
    }

    public function update(Spesies $spesies, array $data): Spesies
    {
        return DB::transaction(function () use ($spesies, $data) {
            $spesies->update($data);
            $spesies->habitat()->sync($data['habitat_ids'] ?? []);

            return $spesies->refresh();
        });
    }

    /** Aturan bisnis: spesies yang sudah memiliki rekaman tidak boleh dihapus. */
    public function delete(Spesies $spesies): void
    {
        if ($spesies->rekaman()->exists()) {
            throw new DomainException(
                "Spesies \"{$spesies->nama_umum}\" sudah memiliki rekaman dan tidak dapat dihapus."
            );
        }

        DB::transaction(function () use ($spesies) {
            $spesies->habitat()->detach();
            $spesies->delete();
        });
    }

    private function baseQuery(array $filters): Builder
    {
        $q = trim((string) ($filters['q'] ?? ''));

        return Spesies::query()
            ->when($q !== '', function (Builder $query) use ($q) {
                $like = '%'.addcslashes($q, '%_\\').'%';
                $query->where(fn (Builder $w) => $w
                    ->where('nama_umum', 'like', $like)
                    ->orWhere('nama_ilmiah', 'like', $like));
            })
            ->when(! empty($filters['kategori_spesies_id']), fn (Builder $query) =>
                $query->where('kategori_spesies_id', $filters['kategori_spesies_id']))
            ->when(! empty($filters['status_konservasi']), fn (Builder $query) =>
                $query->where('status_konservasi', $filters['status_konservasi']));
    }
}
