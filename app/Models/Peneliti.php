<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peneliti extends Model
{
    protected $table = 'peneliti';

    protected $fillable = ['nama', 'email', 'institusi', 'keahlian'];

    /** Many-to-many ke rekaman melalui tabel pivot beratribut 'peran'. */
    public function rekaman(): BelongsToMany
    {
        return $this->belongsToMany(Rekaman::class, 'rekaman_peneliti', 'peneliti_id', 'rekaman_id')
            ->withPivot('peran');
    }
}
