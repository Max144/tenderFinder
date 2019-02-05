<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommercialTender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function successTender()
    {
        return $this->hasOne(SuccessCommercialTender::class, 'tender_id');
    }
}
