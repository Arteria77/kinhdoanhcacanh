<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMessages()
    {
        $user = Auth::user();
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return response()->json([]);
        }

        return Message::with('sender')
            ->where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $admin->id);
            })
            ->orWhere(function ($query) use ($user, $admin) {
                $query->where('sender_id', $admin->id)
                    ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $admin = User::where('role', 'admin')->firstOrFail();

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $admin->id,
            'content' => trim($request->message),
            'is_read' => false,
        ]);

        return response()->json($message->load('sender'));
    }
}