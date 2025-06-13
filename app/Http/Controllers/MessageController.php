<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Services\NotificationService;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        return Message::with(['chat', 'user'])->get();
    }

    // public function store(Request $request, NotificationService $notificationService)
    public function store(Request $request)
    {
        // Get the authenticated user's ID
        $userid = auth()->id();

        // Validate the incoming request
        $request->validate([
            'chat_id' => 'required|exists:chats,id',  // Make sure chat exists
            'content' => 'required|string',           // Ensure content is provided and is a string
        ]);

        // Create the message, ensuring 'user_id' and 'read' are correctly handled
        $message = Message::create([
            'chat_id' => $request->chat_id,
            'user_id' => $userid, // Use the authenticated user's ID
            'content' => $request->content,
            'read' => false,       // Set the default value for 'read' to false (or 0)
        ]);

        $chat = Chat::find($request->chat_id);

        $userIdToNotify = $chat->user1_id == $userid ? $chat->user2_id : $chat->user1_id;
        $userToNotify = User::find($userIdToNotify);

        if($userToNotify->language == 'en'){
            $title = 'New Message Received';
            $body = 'Nuevo Mensaje Recibido';
        }else{
            $title = 'Nuevo Mensaje Recibido';
            $body = 'Tienes un nuevo mensaje esperándote. Toca para leerlo ahora.';
        }

        $data =  [
                "type" => "newMessage",
                "data" => $message->toArray(),
            ];

        // $success = $notificationService->sendNotificationToUser($userToNotify, $title, $body, $data);

        return response()->json($message, 201);
    }


    public function show($id)
    {
        return Message::with(['chat', 'user'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $request->validate([
            'content' => 'required|string',
            'read' => 'boolean',
        ]);
        $message->update($request->only('content', 'read'));
        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        return response()->json(null, 204);
    }

    public function getMessagesByChatId(Request $request)
    {
        $chatId = $request->chat_Id;

        // Step 1: Retrieve messages for the given chat_id
        $messages = Message::where('chat_id', $chatId)->with("thing:id,name,imagesUrl")->get();

        // Step 2: Update the "read" status of messages where the user is not the authenticated user
        $this->markMessagesAsRead($chatId, auth()->id());

        // Step 3: Return the messages as a response
        return response()->json($messages, 200);
    }

    protected function markMessagesAsRead($chatId, $authUserId)
    {
        // Update messages where user_id != authUserId and chat_id matches
        Message::where('chat_id', $chatId)
            ->where('user_id', '!=', $authUserId)
            ->where('read', 0) // Ensure we only update unread messages
            ->update(['read' => 1]); // Mark as read
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'user_id' => $request->user_id,
            'content' => $request->content,
            'read' => false,
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }


    // <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    // const pusher = new Pusher('your_app_key', {
    //     cluster: 'your_app_cluster',
    // });

    // const channel = pusher.subscribe('private-chat.' + chatId); // Use the chat ID

    // channel.bind('MessageSent', function(data) {
    //     // Handle the incoming message
    //     console.log(data.message);
    //     // Update the chat UI with the new message
    // });


    // Echo.private(`chat.${chatId}`)
    // .listen('MessageSent', (e) => {
    //     console.log(e.message); // Handle the received message
    // });

    // import Echo from 'laravel-echo';
    // window.Pusher = require('pusher-js');

    // window.Echo = new Echo({
    //     broadcaster: 'pusher',
    //     key: 'your-pusher-key',
    //     cluster: 'your-cluster',
    //     encrypted: true,
    // });




}
