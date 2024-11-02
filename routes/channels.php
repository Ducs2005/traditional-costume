<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.1', function ($user, $receiver_id) {
    Log::info('broadcasting this url chat' . $receiver_id);
    return (int) $user->id === (int) $receiver_id; /* Additional logic */
});

Broadcast::routes(['middleware' => ['auth']]); // or 'auth' depending on your setup
