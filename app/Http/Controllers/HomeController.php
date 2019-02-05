<?php

namespace App\Http\Controllers;

use App\Models\SuccessCommercialTender;
use App\Models\SuccessGovernmentTender;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $govTenders = SuccessGovernmentTender::where('new', true)->get();
        $comTenders = SuccessCommercialTender::where('new', true)->get();

        $tenders = $govTenders->merge($comTenders);
        return view('home', compact('tenders'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function all()
    {
        $govTenders = SuccessGovernmentTender::all();
        $comTenders = SuccessCommercialTender::all();

        $tenders = $govTenders->merge($comTenders);
        return view('home', compact('tenders'));
    }
}
