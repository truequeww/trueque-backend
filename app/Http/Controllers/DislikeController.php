<?php
namespace App\Http\Controllers;

use App\Models\Dislike;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'thing_id' => 'required|exists:things,id',
        ]);

        $dislike = Dislike::create($request->all());

        return response()->json($dislike, 201);
    }

    public function index()
    {
        $dislikes = Dislike::with(['user', 'thing'])->get();
        return response()->json($dislikes);
    }

    public function destroy($id)
    {
        $dislike = Dislike::findOrFail($id);
        $dislike->delete();

        return response()->json(null, 204);
    }
}
