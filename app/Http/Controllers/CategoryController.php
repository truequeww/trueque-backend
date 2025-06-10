<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('categories')->get());
    }


    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:categories,name']);

        return Category::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate(['name' => 'required|string|unique:categories,name,' . $category->id]);

        $category->update($request->all());

        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->noContent();
    }
}
