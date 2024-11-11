<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
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
            //Log::info('Broadcasting message to Pusher', ['message' => $message->message]);
            //broadcast(new MessageSent($message->message, $request->receiver_id))->toOthers()->via('pusher');
            //Log::info('Message broadcasted');

            
            // Return success response
            return $this->broadcastMessage($request->message, $request->receiver_id);
        } catch (\Exception $e) {
            Log::error('Error sending message:', ['exception' => $e]);
        
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message. Please try again.',
                'exception' => $e->getMessage() // Optional: Include the exception message for debugging
            ], 500);
        }
    }


    public function broadcastMessage($message, $recipient_id)
    {
        // Retrieve Pusher credentials from configuration
        $app_id = config('broadcasting.connections.pusher.app_id');
        $app_key = config('broadcasting.connections.pusher.key');
        $app_secret = config('broadcasting.connections.pusher.secret');
        $cluster = config('broadcasting.connections.pusher.options.cluster');
        Log::info($app_id . 'gdfgdf' .  $app_key);
        $data = ['message' => $message, 'sender' => auth()->id() ]; // Your actual payload
        $channel = 'chat.' . $recipient_id; // Specify the channel to send the event to
        $data = [
            'name' => 'SendMessage', // Event name
            'data' => json_encode($data), // Convert the message array to JSON
            'channel' => $channel // Add the channel parameter
        ];
        $body = json_encode($data); // JSON-encode the data

        // Calculate the MD5 hash of the body
        $body_md5 = md5($body);

        // Get the current timestamp
        $timestamp = time();

        // Create query parameters
        $queryParams = [
            'auth_key' => $app_key,
            'auth_timestamp' => $timestamp,
            'auth_version' => '1.0',
            'body_md5' => $body_md5,
        ];

        // Build the query string
        $queryString = http_build_query($queryParams);

        // Create the string to sign
        $stringToSign = "POST\n/apps/$app_id/events\n$queryString";

        // Generate the HMAC SHA256 signature
        $auth_signature = hash_hmac('sha256', $stringToSign, $app_secret);

        // Add the signature to the query parameters
        $queryParams['auth_signature'] = $auth_signature;

        // Prepare the full URL
        $fullUrl = "https://api-{$cluster}.pusher.com/apps/{$app_id}/events?" . http_build_query($queryParams);

        // Send the request using Laravel's Http client
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($fullUrl, $data);

        // Return response for debugging
        return response()->json([
            'status_code' => $response->status(),
            'response' => $response->body(),
            'full_url' => $fullUrl,
            'body_md5' => $body_md5,
            'auth_signature' => $auth_signature,
            'timestamp' => $timestamp,
            'string_to_sign' => $stringToSign,
            'query_params' => $queryParams,
        ]);
    }

    // MessageController.php
    public function contactSeller($sellerId)
    {
        $userId = Auth::id();
        
        // Check if the seller is the same as the authenticated user
        if ($sellerId == $userId) {
            // Redirect back with a SweetAlert message
            return redirect()->to(url()->previous()) 
                            ->with('alert', [
                                'type' => 'warning', 
                                'message' => 'This is your own product. You cannot contact yourself.'
                            ]);
        }

        try {
            // Create a new message from the authenticated user to the seller
            $message = new Message();
            $message->sender_id = $userId;
            $message->recipient_id = $sellerId;
            $message->message = 'Hello, I would like to discuss more about your product.';
            $message->save();

            // Optionally, broadcast the message to the seller if using real-time notifications
            $this->broadcastMessage($message->message, $sellerId);

            // Redirect back and include a success message
            return redirect()->to(url()->previous()) // Redirect to the previous URL
                         ->with('showChatWindow', true);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error sending message to seller:', ['exception' => $e]);

            // Redirect back with an error message
            return redirect()->to(url()->previous())
                            ->with('alert', [
                                'type' => 'error', 
                                'message' => 'Failed to send message to the seller. Please try again.'
                            ]);
        }
    }




}
