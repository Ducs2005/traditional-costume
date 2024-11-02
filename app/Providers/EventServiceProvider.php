<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            // You can define listeners here if needed
        ],
    ];

    public function boot()
    {
        parent::boot();

        // You can register any additional functionality here
        Event::listen(
            MessageSent::class,
            function ($event) {
                // Log or handle the event here
                Log::info('Message sent event dispatched', [
                    'message' => $event->message,
                    'receiverId' => $event->receiverId,
                ]);
            }
        );
    }
}
