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

    public function sendMessage(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|integer|exists:users,id'
        ]);

        try {
            // Create and save the message
            $message = new Message();
            $message->sender_id = Auth::id(); // Current authenticated user
            $message->recipient_id = $request->receiver_id;
            $message->message = $request->message;
            $message->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            // Return error response in case of failure
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }
}
