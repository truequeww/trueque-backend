<?php

namespace App\Http\Controllers;

use App\Models\OfferThing;
use Illuminate\Http\Request;

class OfferThingController extends Controller
{
    public function index()
    {
        $offerThings = OfferThing::with(['offer', 'thing'])->get();
        return response()->json($offerThings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'thing_id' => 'required|exists:things,id',
            'is_offered' => 'required|boolean',
        ]);

        $offerThing = OfferThing::create($request->all());
        return response()->json($offerThing, 201);
    }

    public function show($id)
    {
        $offerThing = OfferThing::with(['offer', 'thing'])->findOrFail($id);
        return response()->json($offerThing);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'offer_id' => 'sometimes|exists:offers,id',
            'thing_id' => 'sometimes|exists:things,id',
            'is_offered' => 'sometimes|boolean',
        ]);

        $offerThing = OfferThing::findOrFail($id);
        $offerThing->update($request->all());
        return response()->json($offerThing);
    }

    public function destroy($id)
    {
        $offerThing = OfferThing::findOrFail($id);
        $offerThing->delete();
        return response()->json(null, 204);
    }
}
