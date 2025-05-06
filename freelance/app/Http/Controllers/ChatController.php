<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return view('message.message', ['chats' => $chat]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user1_id' => 'required|exists:users,id',
            'user2_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::create($validated);
        return response()->json($chat, 201);
    }

    public function show(Chat $chat)
    {
        return response()->json($chat);
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();
        return response()->json(null, 204);
    }
}