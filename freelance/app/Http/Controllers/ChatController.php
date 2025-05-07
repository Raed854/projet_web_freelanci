<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
    
        $chats = Chat::where('user1_id', $userId)
                    ->orWhere('user2_id', $userId)
                    ->with(['user1', 'user2']) // Charger les utilisateurs liés
                    ->get()
                    ->map(function ($chat) use ($userId) {
                        $otherUser = $chat->user1_id == $userId ? $chat->user2 : $chat->user1;
                        
                        // Vérifie si nom et prénom existent pour éviter erreur
                        $chat->other_user_full_name = $otherUser->nom . ' ' . $otherUser->prenom;
    
                        return $chat;
                    });
    
        return view('message.message', ['chats' => $chats]);
    }
    
    
    public function showChat($chatId)
    {
        $userId = auth()->id();
    
        $chats = Chat::where('user1_id', $userId)
                     ->orWhere('user2_id', $userId)
                     ->with(['user1', 'user2'])
                     ->get()
                     ->map(function ($chat) use ($userId) {
                         $otherUser = $chat->user1_id === $userId ? $chat->user2 : $chat->user1;
                         $chat->other_user_full_name = $otherUser->nom . ' ' . $otherUser->prenom;
                         return $chat;
                     });
    
        $selectedChat = Chat::with('messages.sender')->findOrFail($chatId);
    
        return view('message.message', [
            'chats' => $chats,
            'selectedChat' => $selectedChat,
            'messages' => $selectedChat->messages,
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user1_id' => 'required|exists:users,id',
            'user2_id' => 'required|exists:users,id',
        ]);
    
        // Check for existing chat between the same two users (in either order)
        $existingChat = Chat::where(function ($query) use ($validated) {
            $query->where('user1_id', $validated['user1_id'])
                  ->where('user2_id', $validated['user2_id']);
        })->orWhere(function ($query) use ($validated) {
            $query->where('user1_id', $validated['user2_id'])
                  ->where('user2_id', $validated['user1_id']);
        })->first();
        if (!$existingChat) {
            Chat::create($validated);
        }
        $userId = auth()->id();
    
        $chats = Chat::where('user1_id', $userId)
                    ->orWhere('user2_id', $userId)
                    ->with(['user1', 'user2']) // Charger les utilisateurs liés
                    ->get()
                    ->map(function ($chat) use ($userId) {
                        $otherUser = $chat->user1_id == $userId ? $chat->user2 : $chat->user1;
                        
                        // Vérifie si nom et prénom existent pour éviter erreur
                        $chat->other_user_full_name = $otherUser->nom . ' ' . $otherUser->prenom;
    
                        return $chat;
                    });
    
        return view('message.message', ['chats' => $chats]);
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