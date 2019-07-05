<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function successTender()
    {
        return $this->hasOne(SuccessTender::class, 'tender_id');
    }


    public function search()
    {
        return $this->belongsTo(Search::class, 'search_id');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->toDateTimeString();
    }
}
