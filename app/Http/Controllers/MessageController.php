<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function chatWindow()
    {
        // Get the current user's ID
        $userId = Auth::id();

        // Fetch distinct user IDs of users the current user has chatted with
        $chattedUserIds = Message::where('sender_id', $userId)
                                ->orWhere('recipient_id', $userId)
                                ->pluck('sender_id', 'recipient_id')
                                ->flatten()
                                ->unique()
                                ->filter(function ($id) use ($userId) {
                                    return $id != $userId; // Exclude the current user from the list
                                })
                                ->values(); // Re-index the collection after filtering

        // Fetch the user details of the users that the current user has chatted with
        $chattedUsers = User::whereIn('id', $chattedUserIds)->get();

        // Pass the list of chatted users to the view
        return view('chat_window', compact('chattedUsers'));
    }
}
