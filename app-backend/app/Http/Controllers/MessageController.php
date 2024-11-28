<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,png,jpeg,gif,mp4,mp3', // Accept media
        ]);

        $chat = Chat::findOrFail($chatId);

        $mediaUrl = null;
        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('public/media');
            $mediaUrl = Storage::url($path);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'media_url' => $mediaUrl,
        ]);

        return response()->json($message, 201);
    }

    public function getMessages($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $messages = $chat->messages()->with('user')->get();

        return response()->json($messages, 200);
    }
}

