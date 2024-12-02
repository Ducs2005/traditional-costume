<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Color;
use App\Models\Button;
use App\Models\Material;
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
            
            // Get all user IDs that the authenticated user has chatted with
            $chattedUserIds = Message::where('sender_id', $userId)
                ->pluck('recipient_id') // Get all recipient IDs where the user is the sender
                ->merge(
                    Message::where('recipient_id', $userId)
                    ->pluck('sender_id') // Get all sender IDs where the user is the recipient
                )
                ->unique() // Ensure the IDs are unique
                ->filter(fn ($id) => $id != $userId) // Exclude the current user
                ->values();
    
            // Fetch the user objects for the unique user IDs
            $chattedUsers = User::whereIn('id', $chattedUserIds)->get();
            
            $view->with('chattedUsers', $chattedUsers);

            View::composer('product.product_list', function ($view) {
                // Fetch colors, materials, and buttons
                $colors = Color::all();
                $materials = Material::all();
                $buttons = Button::all();
        
                // Pass the variables to the view
                $view->with(compact('colors', 'materials', 'buttons'));
            });
        
        });
    }
}
