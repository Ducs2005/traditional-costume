<?php

namespace App\Http\Controllers;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    //
    
    public function show()
    {
        $user = auth()->user();

        if ($user)
        {
                // Lấy tất cả các thông báo của người dùng (bao gồm thông báo có receiver_type là 'all' và receiver_id = user_id nếu đã đăng nhập)
            $notifications = Notification::where(function($query) use ($user) {
                $query->where('receiver_type', 'all')
                    ->orWhere('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
            // Đánh dấu các thông báo của người dùng là đã đọc
            foreach ($notifications as $notification) {
                if (!$notification->users->contains($user)) {
                    // Nếu người dùng chưa đọc thông báo, đánh dấu là đã đọc
                    $notification->users()->attach($user->id, ['is_read' => true]);
                
                }
            }
            return view('notification', compact('notifications'));

        }
        else
        {
            $notifications = Notification::where(function($query) use ($user) {
                $query->where('receiver_type', 'all');
            })
            ->orderBy('created_at', 'desc')
            ->get();
            return view('notification', compact('notifications'));

        }

    }

    public function getUnreadCount(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Fetch notifications where receiver_type is 'all' or receiver_id matches the authenticated user
            $notifications = Notification::
                where(function ($query) {
                    $query->where('receiver_type', 'all')
                        ->orWhere('receiver_id', auth()->id());
                })
                ->get();

            // Count the unread notifications
            Log::info($notifications);
            $unreadCount = 0;
            foreach ($notifications as $notification) {
                // Check if there is no row in the pivot table for this notification and the authenticated user
                if (!$notification->users()->wherePivot('user_id', $user->id)->exists()) {
                    // Increment unread count if no pivot row exists
                    $unreadCount++;
                }
            }

            // Log for debugging
            Log::info("Unread notifications count: " . $unreadCount);

            return response()->json(['unread_count' => $unreadCount]);
        }

        // For guests or if not authenticated
        return response()->json(['unread_count' => 0]);
    }


}
