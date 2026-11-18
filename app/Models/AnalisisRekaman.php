<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisRekaman extends Model
{
    protected $table = 'analisis_rekaman';

    protected $fillable = [
        'rekaman_id',
        'jenis_analisis',
        'frekuensi_dominan_hz',
        'tingkat_keyakinan',
        'hasil',
    ];

    protected function casts(): array
    {
        return [
            'frekuensi_dominan_hz' => 'float',
            'tingkat_keyakinan' => 'float',
        ];
    }

    public function rekaman(): BelongsTo
    {
        return $this->belongsTo(Rekaman::class);
    }
}
