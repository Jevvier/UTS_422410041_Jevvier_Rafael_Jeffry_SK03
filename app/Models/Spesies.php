<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spesies extends Model
{
    /** Kode status konservasi IUCN yang valid. */
    public const STATUS_KONSERVASI = [
        'LC' => 'Least Concern (Risiko Rendah)',
        'NT' => 'Near Threatened (Hampir Terancam)',
        'VU' => 'Vulnerable (Rentan)',
        'EN' => 'Endangered (Terancam Punah)',
        'CR' => 'Critically Endangered (Kritis)',
        'DD' => 'Data Deficient (Data Kurang)',
    ];

    protected $table = 'spesies';

    protected $fillable = [
        'kategori_spesies_id',
        'nama_ilmiah',
        'nama_umum',
        'status_konservasi',
        'deskripsi',
    ];

    /** Setiap spesies termasuk dalam satu kategori (many-to-one). */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSpesies::class, 'kategori_spesies_id');
    }

    /** Multi-select habitat (many-to-many). */
    public function habitat(): BelongsToMany
    {
        return $this->belongsToMany(Habitat::class, 'spesies_habitat', 'spesies_id', 'habitat_id');
    }

    public function rekaman(): HasMany
    {
        return $this->hasMany(Rekaman::class);
    }

    public function getLabelKonservasiAttribute(): string
    {
        return self::STATUS_KONSERVASI[$this->status_konservasi] ?? $this->status_konservasi;
    }
}
