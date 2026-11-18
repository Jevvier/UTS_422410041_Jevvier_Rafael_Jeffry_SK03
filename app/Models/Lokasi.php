<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $table = 'lokasi';

    protected $fillable = ['nama', 'provinsi', 'latitude', 'longitude', 'deskripsi'];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function rekaman(): HasMany
    {
        return $this->hasMany(Rekaman::class);
    }
}
