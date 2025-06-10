<?php
namespace App\Http\Controllers;

use App\Models\OfferStatus;
use Illuminate\Http\Request;

class OfferStatusController extends Controller
{
    public function index()
    {
        $offerStatuses = OfferStatus::all();
        return response()->json($offerStatuses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $offerStatus = OfferStatus::create($request->all());
        return response()->json($offerStatus, 201);
    }

    public function show($id)
    {
        $offerStatus = OfferStatus::findOrFail($id);
        return response()->json($offerStatus);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $offerStatus = OfferStatus::findOrFail($id);
        $offerStatus->update($request->all());
        return response()->json($offerStatus);
    }

    public function destroy($id)
    {
        $offerStatus = OfferStatus::findOrFail($id);
        $offerStatus->delete();
        return response()->json(null, 204);
    }
}
