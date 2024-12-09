<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationUnreadCount
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            // Get unread notifications count for the authenticated user
            $unreadCount = $user->notifications()->wherePivot('is_read', false)->count();
        } else {
            $unreadCount = 0;
        }

        // Share the unread count with all views
        view()->share('unreadCount', $unreadCount);

        return $next($request);
    }
}

