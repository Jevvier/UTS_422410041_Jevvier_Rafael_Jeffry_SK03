<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSpesies extends Model
{
    protected $table = 'kategori_spesies';

    protected $fillable = ['nama', 'deskripsi'];

    /** Satu kategori memiliki banyak spesies (one-to-many). */
    public function spesies(): HasMany
    {
        return $this->hasMany(Spesies::class, 'kategori_spesies_id');
    }
}
