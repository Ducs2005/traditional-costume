<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
class PusherController extends Controller
{
    /**
     * Authenticates logged-in user in the Pusher JS app
     * For private channels
     */
    public function pusherAuth(Request $request)
    {
        $user = auth()->user();
        $socket_id = $request->input('socket_id');
        $channel_name = 'chat.' . auth()->id();
        $key = env('PUSHER_APP_KEY');
        $secret = env('PUSHER_APP_SECRET');
        $app_id = env('PUSHER_APP_ID');

        if ($user) {
    
            $pusher = new Pusher($key, $secret, $app_id);
            $auth = $pusher->socketAuth($channel_name, $socket_id);
            // Log the authentication string for debugging
            Log::info('chanel name in controller' . $channel_name);

            // Return the auth string directly
            return response()->json( $auth, 200);
        } else {
            return response(['error' => 'Forbidden'], 403); // improved response
        }
    }
}
