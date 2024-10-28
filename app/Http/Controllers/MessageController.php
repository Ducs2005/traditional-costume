<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function fetchMessages($userId)
    {
        $authUserId = Auth::id();
        Log::info("Fetching messages between user $authUserId and user $userId");

        $messages = Message::where(function ($query) use ($authUserId, $userId) {
                $query->where('sender_id', $authUserId)->where('recipient_id', $userId);
            })
            ->orWhere(function ($query) use ($authUserId, $userId) {
                $query->where('sender_id', $userId)->where('recipient_id', $authUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        Log::info("Messages found: ", $messages->toArray());

        return response()->json(['messages' => $messages]);
    }
}
