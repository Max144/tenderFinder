<?php

namespace App\Http\Controllers;

use App\Models\Search;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $searches = Search::where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->latest()->get();

        return view('home', compact('searches'));
    }

    public function all()
    {
        $searches = Search::latest()->get();

        return view('home', compact('searches'));
    }
}
