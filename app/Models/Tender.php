<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function successTender()
    {
        return $this->hasOne(SuccessTender::class, 'tender_id');
    }
}
