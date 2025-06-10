<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Thing;
use App\Models\Offer;
use App\Models\Rating;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Store a newly created user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $request->profile_picture,
            'bio' => $request->bio,
            'location' => $request->location,
        ]);

        return response()->json($user, 201);
    }

    // Display the specified user
    public function show(User $user)
    {
        return response()->json($user);
    }

    // Update the specified user
    public function updateUser(Request $request)
    {
        try {
            $userId = auth()->id();

            $user = User::findOrFail($userId);

            // Get the request data as an array
            $data = $request->all();

            if ($request->hasFile('profileImg')) {
                $imagePaths = [];
                foreach ($request->file('profileImg') as $image) {
                    try {
                        $filename = 'user/' . $image->getClientOriginalName();
                        Storage::disk('s3')->put($filename, file_get_contents($image), 'public');
                        $url = Storage::disk('s3')->url($filename);
                        $imagePaths[] = $url;

                        Log::info('Uploaded file:', [
                            'filename' => $filename,
                            'url' => $url,
                            'size' => $image->getSize()
                        ]);
                    } catch (\Exception $e) {
                        Log::error('File upload failed:', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e;
                    }
                }

                // Assign the array of image URLs
                $data['profile_picture'] = $imagePaths[0];

                // Delete old image if it exists
                if (!empty($user->profile_picture)) {
                    // Extract the file path after the S3 bucket base URL
                    $parsedUrl = parse_url($user->profile_picture);
                    if (isset($parsedUrl['path'])) {
                        // Remove leading slash
                        $oldFilePath = ltrim($parsedUrl['path'], '/');
                        if (Storage::disk('s3')->exists($oldFilePath)) {
                            Storage::disk('s3')->delete($oldFilePath);
                            Log::info('Deleted old profile image from S3:', ['path' => $oldFilePath]);
                        }
                    }
                }

            }

            unset($data['profileImg']);

            // Handle address fields and check if all are valid
            $addressData = $data['address'] ?? [];

            // Check if at least one address field is null or empty
            if (
                empty($addressData['street']) ||
                empty($addressData['latitude']) ||
                empty($addressData['longitude'])
            ) {
                // If any address field is invalid, do not update address at all
                unset($data['address']);
            } else {
                // If all fields are valid, serialize the address into a JSON string
                $addressString = json_encode($addressData);
                $data['location'] = $addressString;  // Store the address as a JSON string in 'location' field
            }

            // Update with the array
            $user->update($data);
            $user->save();

            return response()->json(['success' => true]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }

    }

    // Remove the specified user
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function getUserDetails(Request $request)
    {
        $authUserId = auth()->id(); // Get the authenticated user's ID
        if($request->userId){
            $userid = $request->userId;
        } else {
            $userid = $authUserId;
        }

        $user = User::where('id', $userid)->firstOrFail(); // Find the user by id

        // Check if there's an offer between the authenticated user and the specified user
        $offerExists = Offer::where(function ($query) use ($authUserId, $user) {
                $query->where('from_user_id', $authUserId)
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id)
                    ->where('to_user_id', $authUserId);
            })
            ->whereIn('status_id', [1, 2])
            ->exists();

        // Determine rateable and owner
        $rateable = false;
        $owner = false;

        if ($authUserId === $user->id) {
            $owner = true;
            $rateable = false;
        } elseif ($offerExists) {
            $rateable = true;
        }

        // Retrieve all things owned by the specified user
        $thingsQuery = Thing::where('user_id', $user->id);

        // If the authenticated user and the specified user are not the same, filter by availability
        if ($authUserId !== $user->id) {
            $thingsQuery->where('availability', true);
        }

        $things = $thingsQuery->with('condition')->get();

        // Add the condition_name attribute to each thing
        $things->map(function ($thing) {
            // Append the condition_name dynamically to each thing
            $thing->condition_name = $thing->condition ? $thing->condition->name : null;
            return $thing;
        });

        // Retrieve all ratings where rated_user_id matches the specified user's ID
        $ratings = Rating::where('rated_user_id', $user->id)
                ->join('users', 'ratings.user_id', '=', 'users.id') // Join with users table
                ->select('users.name as userName', 'ratings.score as ratingScore', 'ratings.comment as ratingComment', 'ratings.created_at as ratingCreatedAt') // Select the desired columns
                ->orderBy('ratings.created_at', 'desc') // Order by created_at in descending order
                ->get();

        // Count the number of swaps (offers) where the user is either from_user_id or to_user_id
        $swapsCount = Offer::where(function ($query) use ($user) {
            $query->where('from_user_id', $user->id)
                ->orWhere('to_user_id', $user->id);
        })
        ->whereIn('status_id', [1, 2])
        ->count();

        $address = json_decode($user->location);
        $user->location = $address->street;

        return response()->json([
            'user' => $user,
            'rateable' => $rateable,
            'owner' => $owner,
            'things' => $things,
            'ratings' => $ratings,
            'swaps' => $swapsCount,
            'average_rating' => $ratings->avg('ratingScore') == null ? 0 : $ratings->avg('ratingScore'),
            'things_count' => $things->count(),
        ]);
    }


}
