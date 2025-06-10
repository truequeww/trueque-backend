<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['user', 'ratedUser'])->get();
        return response()->json($ratings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rated_user_id' => 'required|exists:users,id',
            'score' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id(); // Use authenticated user ID

        $rating = Rating::create($data);

        return response()->json($rating, 201);
    }

    public function show($id)
    {
        $rating = Rating::with(['user', 'ratedUser'])->findOrFail($id);
        return response()->json($rating);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'score' => 'sometimes|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update($request->all());
        return response()->json($rating);
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        return response()->json(null, 204);
    }

}
