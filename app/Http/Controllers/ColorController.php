<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('colors')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:colors,name',
        ]);

        $color = Color::create($request->all());

        return response()->json($color, 201);
    }

    public function show($id)
    {
        return Color::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|string|unique:colors,name,' . $color->id,
        ]);
        $color->update($request->all());

        return response()->json($color);
    }

    public function destroy($id)
    {
        Color::destroy($id);
        return response()->json(null, 204);
    }
}
