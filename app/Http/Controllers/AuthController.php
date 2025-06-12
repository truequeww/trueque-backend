<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Verified;
use App\Mail\SendingVerificationCode;
use App\Mail\SendinfVeCodeForPassword;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Thing;
use App\Models\Offer;
use App\Models\Rating;
use App\Models\Like;
use App\Models\Dislike;



class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'language' => 'required|string',
            'profileImg' => 'required',
            'profileImg.*' => 'required|image|mimes:jpeg,png,jpg,gif|',
            'address.street' => 'required|string|max:255', // street
            'address.latitude' => 'required|numeric',     // latitude
            'address.longitude' => 'required|numeric',    // longitude
        ]);

        // $image = $request->file('profileImg');
        // $filename = 'users/' . $image[0]->getClientOriginalName();

        // // Store with public visibility
        // Storage::disk('s3')->put($filename, file_get_contents($image[0]), );
        // $url = Storage::disk('s3')->url($filename);

        $path = $request->file('profileImg')[0]->store('user', 's3');
        $url = Storage::disk('s3')->url($path);


        if (empty($url)) {
            throw new \Exception("Failed to generate URL for file:");
        }

        $addressData = [
            'street' => $validated['address']['street'],
            'latitude' => $validated['address']['latitude'],
            'longitude' => $validated['address']['longitude']
        ];

        // Store the address as a JSON string
        $addressString = json_encode($addressData);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'location' => $addressString,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'language' => $validated['language'],
            'profile_picture' => $url,
        ]);

        self::sendVerificationCode($user);

        if($validated['language']=="es"){
            $messages = [
                "🐾 ¡Hola, compañero de intercambio! ¡Soy yo, Truequi! 🐶✨",
                "¡Tengo unos juguetes geniales para intercambiar! ¿Quieres verlos? Toca mi carita linda (sí, mi foto de perfil 🐶) y mira lo que tengo. 👀🎾",
                "¿Te gusta algo? ¿Tienes algo de dinero? Solo toca el 🔄 (en la esquina superior derecha) y hazme una oferta. 💰🎁",
                "Ohhh, ¿tienes un juguete que quieras intercambiar conmigo? ¡Genial! Solo ve a tu perfil, toca el ➕ (en la esquina superior) para agregarlo, luego regresa y hagamos un trato. 🤝🐾",
                "Y oye, si necesitas algo más, solo ve a la página principal de intercambio, ¡usa los filtros para encontrar exactamente lo que buscas! 🔍✨",
                "🐾 ¡Seguridad ante todo! 🛡️ Si ves algo que te haga sentir incómodo, como contenido dañino o alguien menor de 13 años, ¡ladra aquí! El equipo de Trueque investigará de inmediato. 🐶🚨",
                "¿Tienes alguna pregunta? ¡Ladra aquí! El equipo de Trueque te traerá una respuesta lo antes posible. 🐶💬",
                "¡Feliz intercambio, guau guau! 🚀🎉",
            ];
            $truequi_id = 2;
        }else{
            $messages = [
                "🐾 Hey hey, trade buddy!! It’s me, Truequi! 🐶✨",
                "I’ve got some pawsome toys up for trade! Wanna check ‘em out? Tap on my cute lil’ face (yep, my profile pic 🐶) and see what I got! 👀🎾",
                "Like something? Got some cash? Just hit the 🔄 (top right corner) and make me an offer! 💰🎁",
                "Ohhh, got a toy you wanna swap with me? Sweet! Just go to your profile, hit the ➕ (top corner) to add it, then come back and let’s make a deal! 🤝🐾",
                "And hey, if you need something else, just head to the main swipe page—use the filters to sniff out exactly what you’re looking for! 🔍✨",
                "🐾Safety First! 🛡️If you see something that makes you uncomfortable, like harmful content or someone under 13, bark at me here! The Trueque Team will investigate ASAP. 🐶🚨",
                "Any questions? Bark at me here! The Trueque Team will fetch you an answer ASAP! 🐶💬",
                "Happy trading, woof woof! 🚀🎉",
            ];
            $truequi_id = 1;
        }

        $chat = Chat::create([
            'user1_id' => $user->id,
            'user2_id' => $truequi_id,
        ]);

        foreach ($messages as $content) {
            Message::create([
                'chat_id' => $chat->id,
                'user_id' => $truequi_id,
                'content' => $content,
                'read' => false,
            ]);
        }

        return response()->json(['message' => 'User registered successfully.']);
    }

    public function sendVerificationCode($user){
        // Generate the verification code: 4 digits user ID (padded) + 4 random digits
        $userIdPadded = str_pad($user->id, 4, '0', STR_PAD_LEFT); // Ensure user ID is 4 digits
        $randomDigits = rand(1000, 9999); // Generate 4 random digits
        $verificationCode = $userIdPadded . $randomDigits;

        // Update the user's bio with the new verification code
        $user->bio = $verificationCode;
        $user->save();

        // Send verification code email
        Mail::to($user->email)->send(new SendingVerificationCode($verificationCode));
    }

    public function sendVerificationCodeForPasswortReset($user){
        // Generate the verification code: 4 digits user ID (padded) + 4 random digits
        $userIdPadded = str_pad($user->id, 4, '0', STR_PAD_LEFT); // Ensure user ID is 4 digits
        $randomDigits = rand(1000, 9999); // Generate 4 random digits
        $verificationCode = $userIdPadded . $randomDigits;

        // Update the user's bio with the new verification code
        $user->bio = $verificationCode;
        $user->save();

        // Send verification code email
        Mail::to($user->email)->send(new SendinfVeCodeForPassword($verificationCode));
    }

    public function resetPasswordSendVerCode($email){

        $user = User::where('email', $email)->first();

        if($user == null){
            return response()->json(['no' => ':(']);
        }

        self::sendVerificationCodeForPasswortReset($user);

        return response()->json(['success' => 'Verify your email.']);
    }

    public function checkAndVerifyBio($VerCode)
    {
        // Extract the user ID from the first 4 digits of the verification code
        $userId = (int)substr($VerCode, 0, 4); // Get the first 4 digits and remove leading zeros

        // Get the user associated with the extracted ID
        $user = User::find($userId);

        // If the user doesn't exist, return an error
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'verified' => false
            ], 404);
        }

        // Check if the user's bio matches the verification code
        if ($user->bio === $VerCode) {
            // If the codes match, update the email_verified_at column
            $user->email_verified_at = Carbon::now();  // Set the current timestamp
            $user->save();

            // Return a success message
            return response()->json([
                'message' => 'Email successfully verified.',
                'verified' => true
            ]);
        }

        // If codes don't match, return an error message
        return response()->json([
            'message' => 'Verification codes do not match.',
            'verified' => false
        ], 400);
    }

    public function verifyAndChanegPassword(Request $request)
    {
        $verCode = $request->verCode;
        $password = $request->password;
        // Extract the user ID from the first 4 digits of the verification code
        $userId = (int)substr($verCode, 0, 4); // Get the first 4 digits and remove leading zeros

        // Get the user associated with the extracted ID
        $user = User::find($userId);

        // If the user doesn't exist, return an error
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'success' => false
            ], 404);
        }

        // Check if the user's bio matches the verification code
        if ($user->bio === $verCode) {

            // If the codes match, update the email_verified_at column
            $user->password = Hash::make($password);
            $user->save();

            // Return a success message
            return response()->json([
                'message' => 'Verification successfully.',
                'success' => true
            ]);
        }

        // If codes don't match, return an error message
        return response()->json([
            'message' => 'Verification codes do not match.',
            'success' => false
        ], 400);
    }


    // Register new user
    // public function register(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'city' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     $codeUser = rand(1000, 9999);
    //     $codeServer = rand(1000, 9999);

    //     $user = User::create([
    //         'name' => $validated['name'],
    //         'city' => $validated['city'],
    //         'email' => $validated['email'],
    //         "bio" =>  [$codeUser, $codeServer],
    //         'password' => Hash::make($validated['password']),
    //     ]);

    //     // $user->sendEmailVerificationNotification();

    //     Mail::to($user->email)->send(new SendingVerificationCode($code));

    //     return response()->json(['message' => 'User registered successfully.', "VerCode" =>  $codeServer]);
    // }

    // public function checkAndVerifyBio($code1, $code2)
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Check if the 'bio' column exists and is an array
    //     if (is_array($user->bio) && count($user->bio) === 2) {
    //         // Compare the codes
    //         if (
    //             ($user->bio[0] === $code1 && $user->bio[1] === $code2) ||
    //             ($user->bio[0] === $code2 && $user->bio[1] === $code1)
    //         ) {
    //             // If the codes match, update the email_verified_at column
    //             $user->email_verified_at = Carbon::now();  // Set the current timestamp
    //             $user->save();

    //             // Optionally, return a success message or boolean
    //             return response()->json([
    //                 'message' => 'Email successfully verified.',
    //                 'verified' => true
    //             ]);
    //         }
    //     }

    //     // If codes don't match, return an error message
    //     return response()->json([
    //         'message' => 'Verification codes do not match.',
    //         'verified' => false
    //     ], 400);
    // }


    // Login user and issue token
    public function login(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $validated['email'])->first();

        // Check if the user exists and if the password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Return 400 for incorrect email or password
            return response()->json(['message' => 'Email or password is incorrect.'], 400);
        }

        // Check if the user has verified their email
        if (!$user->email_verified_at) {
            // Send verification email again and return 403
            self::sendVerificationCode($user);
            return response()->json(['message' => 'Please verify your email address before logging in.', "verify" => false]);
        }

        // Create token for the user
        $token = $user->createToken('mobile-app')->plainTextToken;

        $uid = $user->id;

        // Return 200 if login is successful
        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'language' => $user->language,
            // 'things' => $this->getFilteredThings($uid),
            // 'userDetails' => $this->getUserDetails($uid),
            // 'userChats' => $this->getUserChats($uid),
            'things' => ($this->getFilteredThings($uid))->original,
            'userDetails' => ($this->getUserDetails($uid))->original,
            'userChats' => ($this->getUserChats($uid))->original,
            'message' => 'Login successful.',
        ], 200);
    }



    // Logout user and revoke token
    public function logout()
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // If no user is authenticated, return a response indicating the user is not logged in
        if (!$user) {
            return response()->json(['message' => 'No user authenticated'], 200);
        }

        // Revoke the current user's token
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }



    public function verifyEmail(Request $request)
    {
        // Find the user by ID (the ID is included in the verification link)
        $user = User::findOrFail($request->id);

        // Check if the user has already verified their email
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.']);
        }

        // Mark the email as verified
        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully.']);
    }













    //chambonada temporal que hay que arreglar


    // public function getFilteredThings($uid)
    // {
    //     $userId = $uid; // Get the authenticated user's ID

    //     // Get IDs of liked and disliked things
    //     $likedThingIds = Like::where('user_id', $userId)->pluck('thing_id')->toArray();
    //     $dislikedThingIds = Dislike::where('user_id', $userId)->pluck('thing_id')->toArray();

    //     // Build the query for things
    //     $query = Thing::where('user_id', '!=', $userId) // Not owned by the user
    //         ->whereNotIn('id', $likedThingIds) // Not liked by the user
    //         ->whereNotIn('id', $dislikedThingIds) // Not disliked by the user
    //         ->where('availability', true)
    //         ->with('condition'); // Only retrieve available things

    //     // Get the filtered things
    //     $things = $query->get();

    //     $mythings = Thing::where('user_id', $userId)->pluck('id');

    //     $things->each(function ($thing) use ($mythings) {

    //         $thing->liked = Like::where('user_id', $thing->user_id)
    //                         ->whereIn('thing_id', $mythings)
    //                         ->exists();

    //         $thing->swiped = false;
    //         $thing->swipedCalled = false;

    //     });

    //     return response()->json($things);
    // }

    public function getFilteredThings($uid)
    {
        $userId = $uid;

        // Get IDs of liked and disliked things
        $likedThingIds = Like::where('user_id', $userId)->pluck('thing_id')->toArray();
        $dislikedThingIds = Dislike::where('user_id', $userId)->pluck('thing_id')->toArray();

        // Get the authenticated user's location
        $user = User::find($userId);
        $userLocation = json_decode($user->location, true);

        if (!$userLocation || !isset($userLocation['latitude']) || !isset($userLocation['longitude'])) {
            return response()->json(['error' => 'Invalid user location'], 400);
        }

        $lat = $userLocation['latitude'];
        $lon = $userLocation['longitude'];

        // Build the query to find the 30 closest Things
        $things = Thing::where('user_id', '!=', $userId)
            ->whereNotIn('id', $likedThingIds)
            ->whereNotIn('id', $dislikedThingIds)
            ->where('availability', true)
            ->with('condition')
            ->selectRaw("
                things.*,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(JSON_EXTRACT(location, '$.latitude'))) *
                    cos(radians(JSON_EXTRACT(location, '$.longitude')) - radians(?)) +
                    sin(radians(?)) * sin(radians(JSON_EXTRACT(location, '$.latitude')))
                )) AS distance
            ", [$lat, $lon, $lat])
            ->orderBy('distance')
            ->limit(30)
            ->get();

        // Get the user's own things
        $mythings = Thing::where('user_id', $userId)->pluck('id');

        // Add liked and swiped flags
        $things->each(function ($thing) use ($mythings) {
            $thing->liked = Like::where('user_id', $thing->user_id)
                                ->whereIn('thing_id', $mythings)
                                ->exists();
            $thing->swiped = false;
            $thing->swipedCalled = false;
        });

        return response()->json($things);
    }

    public function getUserDetails($uid)
    {
        $authUserId = $uid; // Get the authenticated user's ID
        $userid = $authUserId;

        $user = User::where('id', $userid)->firstOrFail(); // Find the user by id

        $owner = true;
        $rateable = false;

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

    public function getUserChats($uid)
    {
        $userId = $uid;

        // Step 1: Retrieve chats where user1 or user2 matches the provided userId
        $chats = Chat::where(function ($query) use ($userId) {
            $query->where('user1_id', $userId)
                ->orWhere('user2_id', $userId);
        })
        ->with(['user1', 'user2', 'messages', 'offers' => function ($query) use ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->where(function ($r) use ($userId) {
                    $r->where('offers.from_user_id', '=', $userId)
                        ->whereIn('offers.status_id', [1, 3]);
                })
                ->orWhere(function ($r) use ($userId) {
                    $r->where('offers.to_user_id', '=', $userId)
                        ->where('offers.status_id', '=', 5);
                });
            });
        }])->get();

        // Step 3: Prepare the response data
        $response = $chats->map(function ($chat) use ($userId) {

            // Determine the other user (not the authenticated user)
            $otherUser = $chat->user1->id === $userId ? $chat->user2 : $chat->user1;

            // Filter and count unread messages
            $unreadMessagesCount = $chat->messages->filter(function ($message) use ($userId) {
                return $message->user_id !== $userId && $message->read === 0;
            })->count();

            return [
                'chat_id' => $chat->id,
                'userId' => $otherUser->id,
                'name' => $otherUser->name, // The other user's name
                'profile_picture' => $otherUser->profile_picture, // The other user's profile picture
                'offers_count' => $chat->offers->count(), // Count of offers (instead of the full offers)
                'unread_messages_count' => $unreadMessagesCount, // Count of unread messages
            ];
        });

        // Step 4: Sort the response based on presence of offers and unread messages
        $sortedResponse = $response->sortByDesc(function ($chat) {
            return ($chat['offers_count'] > 0 ? 1 : 0) + ($chat['unread_messages_count'] > 0 ? 1 : 0);
        })->values(); // Re-index the collection after sorting

        // Step 5: Return the response
        return response()->json($sortedResponse);
    }



}
