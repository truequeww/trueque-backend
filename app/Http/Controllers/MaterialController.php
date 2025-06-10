<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('materials')->get());
    }

    public function show($id)
    {
        return Material::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        return Material::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $request->validate(['name' => 'string|max:255']);

        $material->update($request->all());
        return $material;
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->noContent();
    }
}
