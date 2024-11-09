<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccessTimeController extends Controller
{
    public function index(Request $request)
    {
        // Nhận ngày và giờ bắt đầu từ form
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $startTime = $request->input('start_time');

        // Nếu có giờ bắt đầu, ghép với ngày bắt đầu
        if ($startDate && $startTime) {
            $startDate = Carbon::parse($request->input('start_date') . ' ' . $startTime);
        }

        // Truy vấn dữ liệu dựa trên ngày và giờ bắt đầu
        $query = DB::table('user_logins')
            ->select(DB::raw('DATE(login_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc');

        // Nếu có ngày và giờ bắt đầu, thêm điều kiện where
        if ($startDate) {
            $query->where('login_at', '>=', $startDate);
        }

        $loginStats = $query->get();

        // Trả về view với dữ liệu thống kê
        return view('admin.accessTime', [
            'loginStats' => $loginStats,
            'startDate' => $startDate ? $startDate->toDateString() : '',
        ]);
    }
}
