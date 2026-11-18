<?php

namespace App\Services;

use App\Models\KategoriSpesies;
use DomainException;
use Illuminate\Pagination\LengthAwarePaginator;

class KategoriSpesiesService
{
    public function search(?string $q, int $perPage = 10): LengthAwarePaginator
    {
        return KategoriSpesies::query()
            ->withCount('spesies')
            ->when($q, fn ($query) => $query->where('nama', 'like', $this->like($q)))
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): KategoriSpesies
    {
        return KategoriSpesies::create($data);
    }

    public function update(KategoriSpesies $kategori, array $data): KategoriSpesies
    {
        $kategori->update($data);

        return $kategori->refresh();
    }

    /** Aturan bisnis: kategori yang masih dipakai spesies tidak boleh dihapus. */
    public function delete(KategoriSpesies $kategori): void
    {
        if ($kategori->spesies()->exists()) {
            throw new DomainException(
                "Kategori \"{$kategori->nama}\" masih digunakan oleh spesies dan tidak dapat dihapus."
            );
        }

        $kategori->delete();
    }

    private function like(string $q): string
    {
        return '%'.addcslashes($q, '%_\\').'%';
    }
}
