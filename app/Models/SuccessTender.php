<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessTender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function lots()
    {
        return $this->hasMany(TenderLot::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }
}
