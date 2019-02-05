<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessGovernmentTender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function lots()
    {
        return $this->hasMany(GovernmentTenderLot::class);
    }

    public function tender()
    {
        return $this->belongsTo(GovernmentTender::class, 'tender_id');
    }
}
