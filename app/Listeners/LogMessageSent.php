<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogMessageSent
{
    public function handle(MessageSent $event)
    {
        Log::info('Event Message sent: ' . $event->message . ' to ' . $event->receiverId);
    }
}
