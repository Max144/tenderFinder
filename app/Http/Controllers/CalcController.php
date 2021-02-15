<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class CalcController extends Controller
{
    public function calc()
    {
        $materials = Material::query()->with('thicknesses.dimensions')->get();
        return view('calc')->with('materials', $materials);
    }
}
