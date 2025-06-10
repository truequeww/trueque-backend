<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Condition;
use Illuminate\Http\Request;

class ConditionController extends Controller
{
    public function index()
    {
        return response()->json(DB::table('conditions')->get());
    }

    public function show($id)
    {
        $condition = Condition::findOrFail($id);
        return response()->json($condition);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $condition = Condition::create($request->all());
        return response()->json($condition, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $condition = Condition::findOrFail($id);
        $condition->update($request->all());
        return response()->json($condition);
    }

    public function destroy($id)
    {
        $condition = Condition::findOrFail($id);
        $condition->delete();
        return response()->json(null, 204);
    }
}
