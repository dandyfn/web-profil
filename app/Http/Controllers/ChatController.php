<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function start(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $conversation = ChatConversation::create([
            'token' => Str::random(40),
            'visitor_name' => $data['name'],
            'last_message_at' => now(),
        ]);

        return response()->json(['token' => $conversation->token]);
    }

    public function messages(Request $request)
    {
        $conversation = ChatConversation::where('token', (string) $request->query('token'))->first();

        if (! $conversation) {
            return response()->json(['error' => 'not_found'], 404);
        }

        $messages = $conversation->messages()
            ->where('id', '>', (int) $request->query('after', 0))
            ->orderBy('id')
            ->get(['id', 'sender', 'body']);

        return response()->json(['messages' => $messages]);
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'body' => 'required|string|max:1000',
        ]);

        $conversation = ChatConversation::where('token', $data['token'])->first();

        if (! $conversation) {
            return response()->json(['error' => 'not_found'], 404);
        }

        $message = $conversation->messages()->create([
            'sender' => 'visitor',
            'body' => $data['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json(['id' => $message->id]);
    }
}
