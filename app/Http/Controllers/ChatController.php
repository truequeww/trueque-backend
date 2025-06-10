<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Models\Thing;
use App\Models\Like;


class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with(['user1', 'user2'])->get();
        return response()->json($chats);
    }

    public function show($id)
    {
        $chat = Chat::with(['user1', 'user2', 'messages'])->findOrFail($id);
        return response()->json($chat);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user1_id' => 'required|exists:users,id',
            'user2_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::create($request->all());
        return response()->json($chat, 201);
    }

    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();
        return response()->json(null, 204);
    }
    public function getUserChats()
    {
        $userId = auth()->id();

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



    public function getUserChatsTest()
    {
        $userId = auth()->id();

        // Step 1: Retrieve chats where user1 or user2 matches the provided userId
        $chats = Chat::where(function ($query) use ($userId) {
            $query->where('user1_id', $userId)
                ->orWhere('user2_id', $userId);
        })->with(['user1', 'user2', 'messages', 'offers' => function ($query) use ($userId) {
            // Step 2: Filter offers based on userId
            $query->where(function ($q) use ($userId) {
                $q->where(function ($r) use ($userId) {
                    $r->where('from_user_id', '=', $userId)
                    ->whereIn('status_id', [1, 3]);
                })
                ->orWhere(function ($r) use ($userId) {
                    $r->where('to_user_id', '=', $userId)
                    ->where('status_id', '=', 5);
                });
            });
        }])->get();

        // Step 3: Prepare the response data
        $response = $chats->map(function ($chat) use ($userId) {

            // Step 4: Check messages
            $unreadMessages = $chat->messages->filter(function ($message) use ($userId) {
                return $message->user_id !== $userId && $message->read === 0;
            });

            return [
                'chat_id' => $chat->id,
                'user1' => [
                    'id' => $chat->user1->id,
                    'name' => $chat->user1->name,
                    'profile_picture' => $chat->user1->profile_picture,
                ],
                'user2' => [
                    'id' => $chat->user2->id,
                    'name' => $chat->user2->name,
                    'profile_picture' => $chat->user2->profile_picture,
                ],
                'offers' => $chat->offers, // Already filtered in the query
                'unread_messages' => $unreadMessages,
            ];
        });

        // Step 5: Sort the response based on presence of offers and unread messages
        $sortedResponse = $response->sortByDesc(function ($chat) {
            return (count($chat['offers']) > 0 ? 1 : 0) + (count($chat['unread_messages']) > 0 ? 1 : 0);
        })->values(); // Re-index the collection after sorting

        // Step 6: Return the response
        return response()->json($sortedResponse);
    }



}
