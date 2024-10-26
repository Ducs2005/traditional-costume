<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function fetchMessages($userId)
    {
        $authUserId = Auth::id();

        // Retrieve messages between the authenticated user and the selected user
        $messages = Message::where(function ($query) use ($authUserId, $userId) {
                $query->where('sender_id', $authUserId)->where('recipient_id', $userId);
            })
            ->orWhere(function ($query) use ($authUserId, $userId) {
                $query->where('sender_id', $userId)->where('recipient_id', $authUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
