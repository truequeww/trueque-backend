<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::routes(['middleware' => ['auth:sanctum']]);

// use Illuminate\Support\Facades\Log;


// Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
//     $chatId = (int) $chatId; // Ensure $chatId is an integer

//     Log::info('Broadcasting auth', ['userId' => $user->id, 'chatId' => $chatId]);

//     $chat = Chat::find($chatId);

//     Log::info($chat);

//     if (!$chat) {
//         return false; // Deny access if chat is not found
//     }


//     // return $chat->user1_id === $user->id || $chat->user2_id === $user->id;
//     return true;
// });
