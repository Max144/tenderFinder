<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialThickness extends Model
{
    protected $guarded = ['id'];

    public function dimensions(): HasMany
    {
        return $this->hasMany(MaterialDimensions::class);
    }
}
