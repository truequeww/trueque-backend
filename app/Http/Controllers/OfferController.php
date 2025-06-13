<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OfferThing;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ThingController;
use App\Models\Thing;
use App\Models\Like;
use App\Services\NotificationService;

class OfferController extends Controller
{
    public function index()
    {
        return Offer::with(['fromUser', 'toUser', 'chat', 'status', 'offerThings'])->get();
    }

    public function show($id)
    {
        return Offer::with(['fromUser', 'toUser', 'chat', 'status', 'offerThings'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $offer = Offer::create($request->all());
        return response()->json($offer, 201);
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->update($request->all());
        return response()->json($offer);
    }

    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();
        return response()->json(null, 204);
    }

    public function createOffer(Request $request, NotificationService $notificationService)
    {
        $authId = auth()->id();

        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'chat_id' => 'required|exists:chats,id', // Assuming chat_id is required for the offer
            'things_from_user' => 'nullable|array', // Ensure this is an array
            'things_to_user' => 'nullable|array', // Ensure this is an array
            'cash_value_offered' => 'nullable|numeric', // Optional, defaults to 0 if not provided
            'cash_value_requested' => 'nullable|numeric', // Optional, defaults to 0 if not provided
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the offer
            $offer = Offer::create([
                'from_user_id' => $authId,
                'to_user_id' => $request->to_user_id,
                'chat_id' => $request->chat_id,
                'status_id' => 5, // Setting the status_id to 5(pending)
                'cash_value_offered' => $request->cash_value_offered ?? 0, // Default to 0 if not provided
                'cash_value_requested' => $request->cash_value_requested ?? 0, // Default to 0 if not provided
            ]);

            if ($request->has('things_from_user') && !empty($request->things_from_user)) {
                // Create offer_things for things owned by from_user_id
                foreach ($request->things_from_user as $thing) {
                    OfferThing::create([
                        'offer_id' => $offer->id,
                        'thing_id' => $thing['id'],
                        'is_offered' => true, // Set is_offered to false
                    ]);
                }
            }

            if ($request->has('things_to_user') && !empty($request->things_to_user)) {
                // Create offer_things for things owned by to_user_id
                foreach ($request->things_to_user as $thing) {
                    OfferThing::create([
                        'offer_id' => $offer->id,
                        'thing_id' => $thing['id'],
                        'is_offered' => false, // Set is_offered to true
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Retrieve the name of the user making the offer
            $fromUser = User::findOrFail($authId);

            Notification::create([
                'user_id' => $request->to_user_id,
                'message' => "You have a new offer from " . $fromUser->name,
                'is_read' => false, // Set is_offered to true
            ]);

            $userToNotify = User::findOrFail($request->to_user_id);

            if($userToNotify->language == 'en'){
                $title = 'You have a new offer!';
                $body = 'Check it out and respond now.';
            }else{
                $title = '¡Tienes una nueva oferta!';
                $body = 'Revisa y responde ya.';
            }

            $data =  [
                "type" => "newOffer",
                "data" => [
                    'status_id' => $offer['status_id'], // offer status from the Offer model
                    'offerSend' => false, // determine if the authenticated user sent the offer
                    'created_at' => $offer['created_at'], // creation timestamp from the Offer model
                    'id' => $offer['id'], // offer ID (primary key of the Offer model, not the offerThings relation)
                    'offer' => ($this->getOfferWithDetails($offer['id'], $request->to_user_id))->original,
                    "userId" => $authId,
                    "chat_id" => $request->chat_id,
                ],
            ];

            // $success = $notificationService->sendNotificationToUser($userToNotify, $title, $body, $data);




            if (($offer['status_id'] == 1 || $offer['status_id'] == 3) && $offer['from_user_id'] == $authId) {
                // Update the status based on the current status
                if ($offer['status_id'] == 1) {
                    // If status is 1 (pending), set it to 2 (accepted)
                    $this->updateOfferStatus($offer['id'], 2);
                } elseif ($offer['status_id'] == 3) {
                    // If status is 3 (rejected), set it to 4 (cancelled)
                    $this->updateOfferStatus($offer['id'], 4);
                }
            }

            // Return only the relevant fields from the Offer model
            return [
                'status_id' => $offer['status_id'], // offer status from the Offer model
                'offerSend' => $offer['from_user_id'] == $authId, // determine if the authenticated user sent the offer
                'created_at' => $offer['created_at'], // creation timestamp from the Offer model
                'id' => $offer['id'], // offer ID (primary key of the Offer model, not the offerThings relation)
                'offer' => ($this->getOfferWithDetails($offer['id']))->original,
            ];


        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            return response()->json(['error' => 'Could not create offer.'], 500);
        }
    }

    // public function getOfferWithDetails(Request $request)
    // public function getOfferWithDetails($offerId)
    public function getOfferWithDetails($offerId, $userId = null)
    {
        // $id = $request->offerId;
        $id = $offerId;
        $authId = $userId ? $userId : auth()->id();  // Get the authenticated user's ID

        // Find the offer by its ID, along with its related OfferThings and condition for each Thing
        $offer = Offer::with('offerThings.thing.condition')->findOrFail($id);

        // Retrieve the offer's status from the offer_status table
        $offerStatus = $offer->status_id;

        // Determine if the authenticated user is the one who made the offer
        $isFromUser = $offer->from_user_id == $authId;

        if($offerStatus==5 && !$isFromUser){
            $offerStatus = 6;
        };

        if($isFromUser){
            $myCash = $offer->cash_value_requested;
            $hisCash = $offer->cash_value_offered;
        }else{
            $myCash = $offer->cash_value_offered;
            $hisCash = $offer->cash_value_requested;
        }

        // Filter offerThings to get myThings and hisThings with the related thing and condition data
        $myThings = $offer->offerThings->filter(function ($offerThing) use ($isFromUser) {
            // If the user is the one who made the offer, we want is_offered = true
            return $isFromUser ? $offerThing->is_offered : !$offerThing->is_offered;
        })->map(function ($offerThing) {
            // Add the 'condition_name' to each thing based on the related condition
            $thing = $offerThing->thing;
            $thing->condition_name = $thing->condition->name; // Assuming 'name' is the condition's name
            return $thing;
        });

        // Similarly, get the "hisThings" with the related thing and condition data
        $hisThings = $offer->offerThings->filter(function ($offerThing) use ($isFromUser) {
            // If the user is the one who made the offer, we want is_offered = false
            return !$isFromUser ? $offerThing->is_offered : !$offerThing->is_offered;
        })->map(function ($offerThing) {
            // Add the 'condition_name' to each thing based on the related condition
            $thing = $offerThing->thing;
            $thing->condition_name = $thing->condition->name; // Assuming 'name' is the condition's name
            return $thing;
        });

        // Prepare the response structure
        return response()->json([
            'offerStatus' => $offerStatus,
            'myThings' => $myThings,
            'hisThings' => $hisThings,
            'myCash' => $myCash,
            'hisCash' => $hisCash,
        ]);
    }

    public function changeOfferStatus(Request $request, NotificationService $notificationService)
    {
        // Validate the incoming request
        $request->validate([
            'offer_id' => 'required|exists:offers,id', // Validate that the offer exists
            'status_id' => 'required|exists:offer_status,id', // Validate that the status ID exists in the offer_status table
        ]);

        // Start a database transaction (if necessary, although not strictly required for a single update)
        DB::beginTransaction();

        try {
            // Find the offer by offer_id
            $offer = Offer::findOrFail($request->offer_id);

            // Update the status_id of the offer
            $offer->status_id = $request->status_id;
            $offer->save();

            // Commit the transaction (if any operations are done inside transaction)
            DB::commit();

            if($request->status_id == 1 || $request->status_id == 3){

                $userToNotify = User::findOrFail($offer->from_user_id);

                if($request->status_id == 1){
                    if($userToNotify->language == 'en'){
                        $title = 'Offer Accepted!';
                        $body = 'Great news—your offer was accepted! Start the conversation and keep it going.';
                    }else{
                        $title = '¡Oferta Aceptada!';
                        $body = '¡Buenas noticias! Tu oferta fue aceptada. Comienza a hablar y sigue adelante.';
                    }
                }else{
                    if($userToNotify->language == 'en'){
                        $title = 'Offer Not Accepted';
                        $body = 'This offer didn’t go through, but don’t stop—keep trying!';
                    }else{
                        $title = 'Oferta No Aceptada';
                        $body = 'Esta oferta no fue aceptada, pero sigue adelante. ¡La próxima puede ser la tuya!';
                    }
                }

                $data =  [
                    "type" => "newOfferStatus",
                    "data" => [
                        'userId' => auth()->id(), // offer status from the Offer model
                        'offerId' => $offer->id, // determine if the authenticated user sent the offer
                        "newStatusId" => $offer['status_id'],
                        "chat_id" => $offer->chat_id,
                    ],
                ];

                // $success = $notificationService->sendNotificationToUser($userToNotify, $title, $body, $data);

            }

            // Return the updated offer
            return response()->json($offer, 200); // Return the updated offer with a 200 status
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            return response()->json(['error' => 'Could not update the offer status.'], 500);
        }
    }

    public function getOffersBetweenUsers(Request $request)
    {
        $userId = $request->userId;
        $authUserId = auth()->id(); // Get the authenticated user's ID

        // Retrieve offers where either from_user_id or to_user_id matches either user
        $offers = Offer::with('offerThings') // eager load related offerThings
            ->where(function ($query) use ($authUserId, $userId) {
                $query->where('from_user_id', $authUserId)
                    ->where('to_user_id', $userId);
            })
            ->orWhere(function ($query) use ($authUserId, $userId) {
                $query->where('from_user_id', $userId)
                    ->where('to_user_id', $authUserId);
            })
            ->get();

        // Convert offers to array first
        $offersArray = $offers->toArray();

        // Map over the offers and include only the necessary fields
        $finalOffers = array_map(function ($offer) use ($authUserId) {
            // Check if the offer should be updated
            if (($offer['status_id'] == 1 || $offer['status_id'] == 3) && $offer['from_user_id'] == $authUserId) {
                // Update the status based on the current status
                if ($offer['status_id'] == 1) {
                    // If status is 1 (pending), set it to 2 (accepted)
                    $this->updateOfferStatus($offer['id'], 2);
                } elseif ($offer['status_id'] == 3) {
                    // If status is 3 (rejected), set it to 4 (cancelled)
                    $this->updateOfferStatus($offer['id'], 4);
                }
            }

            // Return only the relevant fields from the Offer model
            return [
                'status_id' => $offer['status_id'], // offer status from the Offer model
                'offerSend' => $offer['from_user_id'] == $authUserId, // determine if the authenticated user sent the offer
                'created_at' => $offer['created_at'], // creation timestamp from the Offer model
                'id' => $offer['id'], // offer ID (primary key of the Offer model, not the offerThings relation)
                'offer' => ($this->getOfferWithDetails($offer['id']))->original,
            ];
        }, $offersArray);

        $makeOfferData = $this->getUserThings($userId);

        // Return the JSON response with the necessary attributes
        // return response()->json($finalOffers);
        return response()->json(['offers' => $finalOffers, 'makeOffer' => $makeOfferData->original]);

    }

    protected function updateOfferStatus($offerId, $newStatus)
    {
        // Find the offer by ID
        $offer = Offer::findOrFail($offerId);

        // Update the status
        $offer->status_id = $newStatus;

        // Save the offer
        $offer->save();
    }



    //chambonada que despues hay que arreglar

    public function getUserThings($uId)
    {
        $userId1 = auth()->id(); // Get the authenticated user's ID

        $userId2 = $uId;
        // $userId2 = $request->userId;

        // Get the IDs of liked things for both users
        $likedByUser2 = Like::where('user_id', $userId2)->pluck('thing_id')->toArray();
        $likedByUser1 = Like::where('user_id', $userId1)->pluck('thing_id')->toArray();

        // Retrieve available things owned by userId1
        $thingsOwnedByUser1 = Thing::with('condition')
            ->where('user_id', $userId1)
            ->where('availability', true)
            ->get()
            ->toArray();  // Convert the models to arrays

        // Retrieve available things owned by userId2
        $thingsOwnedByUser2 = Thing::with('condition')
            ->where('user_id', $userId2)
            ->where('availability', true)
            ->get()
            ->toArray();  // Convert the models to arrays

        // Add 'liked' attribute to things for userId1 based on likes by userId2
        $thingsForUser1 = array_map(function ($thing) use ($likedByUser2) {
            $thing['condition_name'] = $thing['condition']['name'] ?? null;  // Add condition_name
            $thing['liked'] = in_array($thing['id'], $likedByUser2);  // Add liked
            return $thing;
        }, $thingsOwnedByUser1);

        // Add 'liked' attribute to things for userId2 based on likes by userId1
        $thingsForUser2 = array_map(function ($thing) use ($likedByUser1) {
            $thing['condition_name'] = $thing['condition']['name'] ?? null;  // Add condition_name
            $thing['liked'] = in_array($thing['id'], $likedByUser1);  // Add liked
            return $thing;
        }, $thingsOwnedByUser2);

        // Prepare the response
        return response()->json([
            'myThings' => $thingsForUser1,
            'hisThings' => $thingsForUser2,
        ]);
    }

}
