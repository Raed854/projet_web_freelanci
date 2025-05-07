<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display the messages for a specific chat.
     */
    public function index($chatId)
    {
        $chat = Chat::findOrFail($chatId);

        // Fetch all messages for the chat
        $messages = $chat->messages;  // Assuming the Chat model has a 'messages' relationship

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'sender_id' => $message->sender_id,
                    'text' => $message->contenu,
                    'created_at' => $message->created_at,
                    'id' => $message->id,
                ];
            })
        ]);
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request, $chatId)
    {
        $request->validate([
            'text' => 'required|string',
            'sender_id' => 'required|integer|exists:users,id', // Validate sender exists
        ]);

        $chat = Chat::findOrFail($chatId);

        $message = new Message();
        $message->contenu = $request->text;
        $message->sender_id = $request->sender_id;
        $message->chat_id = $chatId;
        $message->save();

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message)
    {
        // You can implement this method if you need to show a specific message, e.g., to edit or view details.
        return response()->json($message);
    }

    /**
     * Show the form for editing the specified message.
     */
    public function edit(Message $message)
    {
        // Implement logic to show the form for editing a message if needed
        return view('messages.edit', compact('message'));
    }

    /**
     * Update the specified message in storage.
     */
    public function update(Request $request, $id)
{
    $message = Message::findOrFail($id);
    if ($message->sender_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $message->contenu = $request->input('text');
    $message->save();

    return response()->json(['message' => 'Message updated']);
}

public function destroy($id)
{
    $message = Message::findOrFail($id);
    if ($message->sender_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $message->delete();

    return response()->json(['message' => 'Message deleted']);
}

}
