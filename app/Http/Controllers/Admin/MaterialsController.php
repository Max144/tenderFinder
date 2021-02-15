<?php

namespace App\Http\Controllers\Admin;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialsController extends Controller
{
    public function index()
    {
        $materials = Material::all();

        return view('admin.materials.index')->with(['materials' => $materials]);
    }

    public function create()
    {
        return view('admin.materials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $material = Material::create($request->all());

        return redirect()->route('admin.materials.show', $material->id);
    }

    public function show($id)
    {
        $material = Material::with('thicknesses.dimensions')->findOrFail($id);

        return view('admin.materials.show')->with(['material' => $material]);
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);

        return view('admin.materials.edit')->with(['material' => $material]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $material = Material::find($id);

        $material->update($request->all());
        return response()->redirectToRoute('admin.materials.show', $material);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->redirectToRoute('admin.materials.index');
    }

    public function updateThicknesses(Material $material, Request $request)
    {
        $dimensions = $request->get('dimensions', []);
        $material->thicknesses()->delete();
        foreach ($request->thicknesses as $key => $thickness) {
            $thickness = $material->thicknesses()->create([
                'thickness' => $thickness
            ]);

            if (isset($dimensions[$key])){
                $thickness->dimensions()->createMany($dimensions[$key]);
            }
        }

        return response()->redirectToRoute('admin.materials.show', $material);
    }
}
