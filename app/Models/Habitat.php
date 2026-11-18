<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Habitat extends Model
{
    protected $table = 'habitats';

    protected $fillable = ['nama'];

    /** Many-to-many: habitat dapat dimiliki banyak spesies. */
    public function spesies(): BelongsToMany
    {
        return $this->belongsToMany(Spesies::class, 'spesies_habitat', 'habitat_id', 'spesies_id');
    }
}
