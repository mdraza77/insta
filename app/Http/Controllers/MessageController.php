<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    // 1. Inbox: Saari chats dikhao
    public function index()
    {
        $conversations = auth()->user()->conversations()
            ->with(['lastMessage', 'users'])
            ->orderByDesc('updated_at')
            ->get();

        return view('messages.index', compact('conversations'));
    }

    // 2. Chat Window: Specific user ke saath chat dhoondo ya banao
    public function chat($username)
    {
        // dd("Chat method hit for user: " . $username);
        $receiver = User::where('username', $username)->firstOrFail();
        $sender = auth()->user();

        // Conversation find logic
        $conversation = Conversation::whereHas('users', function ($q) use ($receiver) {
            $q->where('user_id', $receiver->id);
        })
            ->whereHas('users', function ($q) use ($sender) {
                $q->where('user_id', $sender->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'private']);
            $conversation->users()->attach([$sender->id, $receiver->id]);
        }

        $messages = $conversation->messages()->with('sender')->oldest()->get();

        // YAHAN CHECK KAREIN: Kya aap chat view hi return kar rahe hain?
        return view('messages.chat', compact('conversation', 'receiver', 'messages'));
    }

    // 3. Send Message logic
    public function send(Request $request, Conversation $conversation)
    {
        $request->validate(['body' => 'required']);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'body' => $request->body,
            'type' => 'text'
        ]);

        // Conversation ka timestamp aur last_message_id update karein taaki wo inbox mein top par aaye
        $conversation->update(['last_message_id' => $message->id]);

        return response()->json(['success' => true, 'message' => $message]);
    }
}
