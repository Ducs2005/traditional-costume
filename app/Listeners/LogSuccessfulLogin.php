<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Lấy user id từ bảng users
        $userId = $event->user->id;

        // Lấy thời gian hiện tại
        $now = Carbon::now();

        // Lưu bản ghi đăng nhập mới cho người dùng
        DB::table('user_logins')->insert([
            'user_id' => $userId,
            'login_at' => $now, // Lưu thời gian hiện tại (gồm ngày, giờ, phút, giây)
        ]);
    }
}
