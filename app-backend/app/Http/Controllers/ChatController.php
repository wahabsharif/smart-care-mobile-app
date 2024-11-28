<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:private,group',
            'user_ids' => 'required_if:type,group|array', // For group chat
            'name' => 'required_if:type,group|string|max:255', // Group name
        ]);

        // Create the chat
        $chat = Chat::create([
            'type' => $request->type,
            'created_by' => auth()->id(),
            'name' => $request->name,
        ]);

        // If it's a group chat, add users to the chat
        if ($request->type === 'group') {
            $chat->users()->attach($request->user_ids);
        } else {
            // For private chat, add just the authenticated user and the second user
            $chat->users()->attach([auth()->id(), $request->user_ids[0]]);
        }

        return response()->json($chat, 201);
    }

    public function getChats()
    {
        $chats = auth()->user()->chats;
        return response()->json($chats, 200);
    }
}
