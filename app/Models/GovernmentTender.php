<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentTender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function successTender()
    {
        return $this->hasOne(SuccessGovernmentTender::class, 'tender_id');
    }
}
