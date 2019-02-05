<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessCommercialTender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function lots()
    {
        return $this->hasMany(CommercialTenderLot::class);
    }

    public function tender()
    {
        return $this->belongsTo(CommercialTender::class, 'tender_id');
    }
}
