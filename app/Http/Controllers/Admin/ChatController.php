<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getUsers()
    {
        $adminId = Auth::id();

        $customerIds = Message::where(function ($query) use ($adminId) {
            $query->where('sender_id', $adminId)
                ->orWhere('receiver_id', $adminId);
        })
            ->pluck('sender_id')
            ->merge(Message::where('receiver_id', $adminId)->pluck('sender_id'))
            ->unique()
            ->filter(fn ($id) => (int) $id !== (int) $adminId)
            ->values();

        $users = User::whereIn('id', $customerIds)
            ->whereIn('role', ['user', 'customer'])
            ->select('id', 'name')
            ->get();

        if ($users->isEmpty()) {
            $users = User::whereIn('role', ['user', 'customer'])->select('id', 'name')->get();
        }

        return response()->json($users);
    }

    public function getMessages($userId)
    {
        $adminId = Auth::id();

        $messages = Message::with(['sender', 'receiver'])
            ->where(function ($query) use ($adminId, $userId) {
                $query->where('sender_id', $adminId)
                    ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($adminId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $adminId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->input('user_id');

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'content' => trim($request->message),
            'is_read' => false,
        ]);

        return response()->json($message->load('sender'));
    }
}