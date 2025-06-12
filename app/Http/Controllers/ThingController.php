<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\User;
use App\Models\Dislike;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ThingController extends Controller
{
    public function index()
    {
        $things = Thing::with(['user', 'condition', 'category', 'material', 'color'])->get();
        return response()->json($things);
    }

    public function show($id)
    {
        $thing = Thing::with(['user', 'condition', 'category', 'material', 'color'])->findOrFail($id);
        return response()->json($thing);
    }

    // public function store(Request $request)
    // {
    //     $user = auth()->id();
    //     $location = User::findOrFail($user)->select('location');

    //     $validated = $request->validate([
    //         'user_id' => $user,
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric',
    //         'condition_id' => 'required|exists:conditions,id',
    //         'availability' => 1,
    //         'weight' => 'nullable|numeric',
    //         'category_id' => 'required|exists:categories,id',
    //         'material_id' => 'required|exists:materials,id',
    //         'color_id' => 'required|exists:colors,id',
    //         'location' => $location,
    //         'imagesUrl' => 'nullable|string',
    //     ]);

    //     $thing = Thing::create($validated);
    //     return response()->json($thing, 201);
    // }

    public function store(Request $request)
    {
        try {
            // Log the request data for debugging
            Log::info('Request data:', $request->all());

            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'condition_id' => 'required|exists:conditions,id',
                'weight' => 'nullable|numeric',
                'category_id' => 'required|exists:categories,id',
                'material_id' => 'required|exists:materials,id',
                'color_id' => 'required|exists:colors,id',
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|',
            ]);

            // Add user and location
            $validated['user_id'] = auth()->id();
            $validated['location'] = auth()->user()->location;

            // Handle image uploads
            // $imagePaths = [];
            // if ($request->hasFile('images')) {
            //     foreach ($request->file('images') as $image) {
            //         // Upload with public visibility
            //         $path = $image->store('things', [
            //             'disk' => 's3',
            //             'visibility' => 'public' // Set visibility during upload
            //         ]);

            //         $imagePaths[] = Storage::disk('s3')->url($path);
            //     }
            // }

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    try {
                        // // Generate unique filename
                        // $filename = 'things/' . uniqid() . '_' . $image->getClientOriginalName();
                        // // Store with public visibility
                        // Storage::disk('s3')->put($filename, file_get_contents($image));
                        // // Verify URL generation
                        // $url = Storage::disk('s3')->url($filename);

                        $path = $image->store('things', 's3');
                        $url = Storage::disk('s3')->url($path);


                        if (empty($url)) {
                            throw new \Exception("Failed to generate URL for file:");
                        }

                        $imagePaths[] = $url;

                    } catch (\Exception $e) {

                        throw $e;
                    }
                }
            }
            // Add image URLs and availability to the validated data
            $validated['imagesUrl'] = $imagePaths[0]; // Store as JSON
            $validated['availability'] = 1;

            $thing = Thing::create($validated);

            // Load the 'condition' relationship
            $thing->load('condition');

            // Add the condition_name attribute
            $thing->condition_name = $thing->condition ? $thing->condition->name : null;

            // Return the created Thing as a JSON response
            return response()->json($thing, 201);

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage(),
            ], 422);

        } catch (\Exception $e) {
            // Handle all other exceptions
            Log::error('Error in ThingController@store: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteThing($id)
    {
        try {
            $userId = auth()->id();
            $thing = Thing::where('id', $id)->where('user_id', $userId)->firstOrFail();

            // Extract the S3 key from the full URL
            $parsedUrl = parse_url($thing->imagesUrl, PHP_URL_PATH); // e.g. /bucket-name/things/image.jpg
            $key = ltrim($parsedUrl, '/'); // remove leading slash

            // Delete from S3
            Storage::disk('s3')->delete($key);

            $thing->delete();
            return response()->json(['success' => true, 'message' => 'Thing deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Thing not found or not owned by you'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // public function update(Request $request)
    // {
    //     try {
    //         $user = auth()->id();
    //         $thing = Thing::findOrFail($request->input("thingID"));

    //         // Get the request data as an array
    //         $data = $request->all();

    //         if ($request->hasFile('imagesUrl')) {
    //             $imagePaths = [];
    //             foreach ($request->file('imagesUrl') as $image) {
    //                 try {
    //                     $filename = 'things/' . $image->getClientOriginalName();
    //                     Storage::disk('s3')->put($filename, file_get_contents($image), 'public');
    //                     $url = Storage::disk('s3')->url($filename);
    //                     $imagePaths[] = $url;

    //                 } catch (\Exception $e) {

    //                     throw $e;
    //                 }
    //             }
    //             // Assign the array of image URLs
    //             $data['imagesUrl'] = $imagePaths[0];
    //         }else{
    //             unset($data['imagesUrl']);
    //         }

    //         // Remove unwanted keys
    //         unset($data['thingID']);

    //         // Update with the array
    //         $thing->update($data);


    //        return response()->json(['success' => true]);

    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return response()->json(['error' => 'Thing not found'], 404);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
    //     }
    // }


    public function update(Request $request)
    {
        try {
            $user = auth()->id();
            $thing = Thing::findOrFail($request->input("thingID"));

            // Get the request data as an array
            $data = $request->all();

            if ($request->hasFile('imagesUrl')) {

                if ($thing->imagesUrl) {
                    // Extract the S3 key from the full URL
                    $parsedUrl = parse_url($thing->imagesUrl, PHP_URL_PATH); // e.g. /bucket-name/things/image.jpg
                    $key = ltrim($parsedUrl, '/'); // remove leading slash

                    // Delete from S3
                    Storage::disk('s3')->delete($key);
                }

                $imagePaths = [];
                foreach ($request->file('imagesUrl') as $image) {
                    try {
                        // $filename = 'things/' . $image->getClientOriginalName();
                        // Storage::disk('s3')->put($filename, file_get_contents($image));
                        // $url = Storage::disk('s3')->url($filename);
                        // $imagePaths[] = $url;

                        $path = $image->store('things', 's3');
                        $url = Storage::disk('s3')->url($path);
                        $imagePaths[] = $url;

                    } catch (\Exception $e) {

                        throw $e;
                    }
                }
                // Assign the array of image URLs
                $data['imagesUrl'] = $imagePaths[0];
            }else{
                unset($data['imagesUrl']);
            }

            // Remove unwanted keys
            unset($data['thingID']);

            // Update with the array
            $thing->update($data);

                        // Load the 'condition' relationship
            $thing->load('condition');

            // Add the condition_name attribute
            $thing->condition_name = $thing->condition ? $thing->condition->name : null;

            return response()->json(['success' => true, 'thing' => $thing]);


        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Thing not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }



    public function destroy($id)
    {
        $thing = Thing::findOrFail($id);
        $thing->delete();
        return response()->json(null, 204);
    }


    public function getFilteredThings(Request $request)
    {
        $userId = auth()->id(); // Get the authenticated user's ID

        // Get IDs of liked and disliked things
        $likedThingIds = Like::where('user_id', $userId)->pluck('thing_id')->toArray();
        $dislikedThingIds = Dislike::where('user_id', $userId)->pluck('thing_id')->toArray();

        // Build the query for things
        $query = Thing::where('user_id', '!=', $userId) // Not owned by the user
            ->whereNotIn('id', $likedThingIds) // Not liked by the user
            ->whereNotIn('id', $dislikedThingIds) // Not disliked by the user
            ->where('availability', true)
            ->with('condition'); // Only retrieve available things

        // Filterable attributes that may be single values or arrays
        $filterableAttributes = ['category_id', 'condition_id', 'material_id', 'color_id'];

        foreach ($filterableAttributes as $attribute) {
            if ($request->has($attribute)) {
                $value = $request->$attribute;

                // Skip empty, null, or undefined values
                if (empty($value) && $value !== '0') {
                    continue;
                }

                // Check if the attribute is category_id and if it's an array
                if ($attribute == 'category_id' && is_array($value)) {

                    /// Extract the 'id' values from the category objects
                    $categoryIds = array_map(function ($category) {
                        return isset($category['id']) ? $category['id'] : null;
                    }, $value);

                    // Remove null values in case 'id' is not set in any category
                    $categoryIds = array_filter($categoryIds, function ($id) {
                        return !is_null($id);
                    });

                    // If there are any valid category IDs, apply the whereHas query
                    if (count($categoryIds) > 0) {
                        $query->whereHas('category', function ($q) use ($categoryIds) {
                            // Use whereIn to match any of the provided category IDs
                            $q->whereIn('id', $categoryIds);
                        });
                    }
                }elseif (is_array($value)) {
                    $query->whereIn($attribute, $value);
                } else {
                    $query->where($attribute, $value);
                }
            }
        }

        // Handle price and weight as arrays with min and max values
        foreach (['weight', 'price'] as $attribute) {
            if ($request->has($attribute)) {
                $range = $request->$attribute;
                if (is_array($range) && count($range) === 2) {
                    $min = $range[0];
                    $max = $range[1];
                    if ($min !== null) {
                        $query->where($attribute, '>=', $min);
                    }
                    if ($max !== null) {
                        $query->where($attribute, '<=', $max);
                    }
                }
            }
        }

        // Search for a string in the name and description columns
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%') // Match search term in name
                ->orWhere('description', 'like', '%' . $searchTerm . '%'); // Match search term in description
            });
        }

        // Get the authenticated user's location
        $user = User::find($userId);
        $userLocation = json_decode($user->location, true);

          // Apply distance filter if range is provided
        if ($request->has('range') && !empty($request->range) && is_numeric($request->range) && isset($userLocation['latitude'], $userLocation['longitude'])) {
            $rangeKm = floatval($request->range);
            $lat = $userLocation['latitude'];
            $lon = $userLocation['longitude'];

            $query->whereRaw("\n            (6371 * acos(\n                cos(radians(?)) * cos(radians(JSON_EXTRACT(location, '$.latitude'))) *\n                cos(radians(JSON_EXTRACT(location, '$.longitude')) - radians(?)) +\n                sin(radians(?)) * sin(radians(JSON_EXTRACT(location, '$.latitude')))\n            )) <= ?\n        ", [$lat, $lon, $lat, $rangeKm]);
        }

        // Get the filtered things
        $things = $query->get();

        $mythings = Thing::where('user_id', $userId)->pluck('id');

        $things->each(function ($thing) use ($mythings) {

            $thing->liked = Like::where('user_id', $thing->user_id)
                            ->whereIn('thing_id', $mythings)
                            ->exists();

            $thing->swiped = false;
            $thing->swipedCalled = false;

        });

        return response()->json($things);
    }

    public function toggleAvailability($id)
    {
        // Find the thing by its ID
        $thing = Thing::findOrFail($id);

        // Toggle the availability
        $thing->availability = !$thing->availability; // Switches true to false and vice versa
        $thing->save();

        return response()->json([
            'message' => 'Availability toggled successfully.',
            'thing' => $thing,
        ]);
    }


    // public function getUserThings(Request $request)
    // {
    //     $userId1 = auth()->id(); // Get the authenticated user's ID

    //     $userId2 = $request->userId;

    //     // Get the IDs of liked things for both users
    //     $likedByUser2 = Like::where('user_id', $userId2)->pluck('thing_id')->toArray();
    //     $likedByUser1 = Like::where('user_id', $userId1)->pluck('thing_id')->toArray();

    //     // Retrieve available things owned by userId1
    //     $thingsOwnedByUser1 = Thing::where('user_id', $userId1)
    //         ->where('availability', true)
    //         ->get();

    //     // Separate liked and non-liked things for userId1
    //     $likedThingsByUser1 = $thingsOwnedByUser1->filter(function ($thing) use ($likedByUser2) {
    //         return in_array($thing->id, $likedByUser2);
    //     })->values(); // Get liked things by userId2

    //     $otherThingsByUser1 = $thingsOwnedByUser1->filter(function ($thing) use ($likedByUser2) {
    //         return !in_array($thing->id, $likedByUser2);
    //     })->values(); // Get other things not liked by userId2


    //     // Retrieve available things owned by userId2
    //     $thingsOwnedByUser2 = Thing::where('user_id', $userId2)
    //         ->where('availability', true)
    //         ->get();

    //     // Separate liked and non-liked things for userId2
    //     $likedThingsByUser2 = $thingsOwnedByUser2->filter(function ($thing) use ($likedByUser1) {
    //         return in_array($thing->id, $likedByUser1);
    //     })->values(); // Get liked things by userId1

    //     $otherThingsByUser2 = $thingsOwnedByUser2->filter(function ($thing) use ($likedByUser1) {
    //         return !in_array($thing->id, $likedByUser1);
    //     })->values(); // Get other things not liked by userId1


    //     // Prepare the response
    //     return response()->json([
    //         'user1' => [
    //             'liked' => $likedThingsByUser1,
    //             'others' => $otherThingsByUser1,
    //         ],
    //         'user2' => [
    //             'liked' => $likedThingsByUser2,
    //             'others' => $otherThingsByUser2,
    //         ],
    //     ]);
    // }


    // public function getUserThings(Request $request)
    // {
    //     $userId1 = auth()->id(); // Get the authenticated user's ID
    //     $userId2 = $request->userId;

    //     // Get the IDs of liked things for both users
    //     $likedByUser2 = Like::where('user_id', $userId2)->pluck('thing_id')->toArray();
    //     $likedByUser1 = Like::where('user_id', $userId1)->pluck('thing_id')->toArray();

    //     // Retrieve available things owned by userId1
    //     $thingsOwnedByUser1 = Thing::with('condition')
    //         ->where('user_id',  $userId1)
    //         ->where('availability', true)
    //         ->get();

    //     // Retrieve available things owned by userId2
    //     $thingsOwnedByUser2 = Thing::with('condition')
    //         ->where('user_id', $userId2)
    //         ->where('availability', true)
    //         ->get();

    //     // Add 'liked' attribute to things for userId1 based on likes by userId2
    //     $thingsForUser1 = $thingsOwnedByUser1->map(function ($thing) use ($likedByUser2) {
    //         // Add the 'liked' attribute to each thing
    //         $thing->condition_name = $thing->condition->name;
    //         $thing->liked = in_array($thing->id, $likedByUser2); // true if userId2 liked it, false otherwise
    //         return $thing;
    //     });

    //     // Add 'liked' attribute to things for userId2 based on likes by userId1
    //     $thingsForUser2 = $thingsOwnedByUser2->map(function ($thing) use ($likedByUser1) {
    //         // Add the 'liked' attribute to each thing
    //         $thing->condition_name = $thing->condition->name;
    //         $thing->liked = in_array($thing->id, $likedByUser1); // true if userId1 liked it, false otherwise
    //         return $thing;
    //     });

    //     // Prepare the response
    //     return response()->json([
    //         'myThings' => $thingsForUser1,
    //         'hisThings' => $thingsForUser2,
    //     ]);

    // }


    public function getUserThings(Request $request)
    {
        $userId1 = auth()->id(); // Get the authenticated user's ID

        $userId2 = $request->userId;

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
