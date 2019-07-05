<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $guarded = ['id'];

    public function tenders()
    {
        return $this->hasMany(Tender::class, 'search_id');
    }

    public function successTenders()
    {
        return $this->hasMany(Tender::class, 'search_id')->has('successTender')
            ->whereIn('type', auth()->user()->tenderTypes()->pluck('name'))
            ->with('successTender', 'successTender.lots');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y H:i:s');
    }
}
