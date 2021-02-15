<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = ['name', 'density'];

    public function thicknesses(): HasMany
    {
        return $this->hasMany(MaterialThickness::class);
    }
}
