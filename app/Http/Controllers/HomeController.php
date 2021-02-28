<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Models\Tender;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $fromDate = Carbon::now();
        while($fromDate->isWeekend()) {
            $fromDate->subDay();
        }
        $searches = Search::where('created_at', '>=', $fromDate->subDay())->latest()->get();

        return view('home', compact('searches'));
    }

    public function all()
    {
        $searches = Search::latest()->get();

        return view('home', compact('searches'));
    }
}
