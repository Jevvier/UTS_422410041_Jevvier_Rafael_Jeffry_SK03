<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rekaman extends Model
{
    public const FORMAT = ['WAV', 'FLAC', 'MP3'];
    public const KUALITAS = ['Baik', 'Sedang', 'Rendah'];

    protected $table = 'rekaman';

    protected $fillable = [
        'spesies_id',
        'lokasi_id',
        'tanggal_rekam',
        'waktu_mulai',
        'durasi_detik',
        'sample_rate',
        'format',
        'kualitas',
        'kondisi_cuaca',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rekam' => 'date',
            'durasi_detik' => 'integer',
            'sample_rate' => 'integer',
        ];
    }

    public function spesies(): BelongsTo
    {
        return $this->belongsTo(Spesies::class);
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    /** Multi-select peneliti (many-to-many dengan atribut pivot 'peran'). */
    public function peneliti(): BelongsToMany
    {
        return $this->belongsToMany(Peneliti::class, 'rekaman_peneliti', 'rekaman_id', 'peneliti_id')
            ->withPivot('peran');
    }

    /** Detail analisis (one-to-many, dikelola sebagai bagian dari rekaman). */
    public function analisis(): HasMany
    {
        return $this->hasMany(AnalisisRekaman::class);
    }

    public function getDurasiFormatAttribute(): string
    {
        $m = intdiv($this->durasi_detik, 60);
        $s = $this->durasi_detik % 60;

        return sprintf('%02d:%02d', $m, $s);
    }
}
