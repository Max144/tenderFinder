<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $tenders = Tender::whereIn('type', $user->tenderTypes()->pluck('name'))->
        whereHas('successTender', function($q){
            $q->where('new', true);
        })->with('successTender', 'successTender.lots')->get();

        $title = "Новые тендеры";
        return view('home', compact('tenders', 'title'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function all()
    {
        $user = Auth::user();
        $tenders = Tender::whereIn('type', $user->tenderTypes()->pluck('name'))->
        has('successTender')->with('successTender', 'successTender.lots')->get();

        $title = "Все тендеры";
        return view('home', compact('tenders', 'title'));
    }
}
