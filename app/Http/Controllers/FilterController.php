<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Color;
use App\Models\Condition;

class FilterController extends Controller
{
    public function index()
    {
        return response()->json([
            'categories' => Category::all(),
            'materials'  => Material::all(),
            'colors'     => Color::all(),
            'conditions' => Condition::all(),
        ]);
    }
}
