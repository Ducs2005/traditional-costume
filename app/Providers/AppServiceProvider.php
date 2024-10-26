<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('chat.chat_window', function ($view) {
            $userId = Auth::id();
            $chattedUserIds = Message::where('sender_id', $userId)
                                    ->orWhere('recipient_id', $userId)
                                    ->pluck('sender_id', 'recipient_id')
                                    ->flatten()
                                    ->unique()
                                    ->filter(fn ($id) => $id != $userId)
                                    ->values();
    
            $chattedUsers = User::whereIn('id', $chattedUserIds)->get();
            $view->with('chattedUsers', $chattedUsers);
        });
    }
}
