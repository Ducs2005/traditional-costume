<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccessTimeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy ngày hôm nay từ Carbon
        $today = Carbon::now()->toDateString();
    
        // Nhận ngày bắt đầu từ form, nếu không có thì lấy ngày bắt đầu của tuần này
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfWeek();
    
        // Xử lý hành động tuần (quay lại tuần trước hoặc tiến tới tuần sau)
        $weekAction = $request->input('week_action');
        if ($weekAction === 'prev') {
            $startDate = $startDate->subWeek(); // Quay lại tuần trước
        } elseif ($weekAction === 'next') {
            $startDate = $startDate->addWeek(); // Tiến tới tuần sau
        }
    
        // Xác định ngày kết thúc tuần (Chủ nhật)
        $endDate = $startDate->copy()->endOfWeek();
    
        // Truy vấn dữ liệu thống kê theo ngày trong tuần
        $loginStats = DB::table('user_logins')
            ->select(DB::raw('DATE(login_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('login_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('total', 'date');
    
        // Mảng để chứa số lượt truy cập của 7 ngày trong tuần
        $weekStats = [];
        $datesOfWeek = [];
    
        // Lặp qua 7 ngày trong tuần (từ thứ 2 đến Chủ nhật)
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->startOfWeek()->addDays($i); // Lấy ngày trong tuần
            $datesOfWeek[] = $date->format('d-m-Y');
            $weekStats[$date->format('d-m-Y')] = $loginStats->get($date->format('Y-m-d'), 0); // Lấy số lượt truy cập cho ngày, nếu không có thì mặc định 0
        }
    
        // Prepare data for the chart
        $chartData = [
            'labels' => $datesOfWeek, // Dates for the x-axis
            'data' => array_values($weekStats) // Login counts for the y-axis
        ];
    
        return view('admin_views.accessTime', [
            'today' => $today, // Truyền ngày hôm nay vào view
            'startDate' => $startDate->toDateString(),
            'weekAction' => $weekAction,
            'weekStats' => $weekStats,
            'datesOfWeek' => $datesOfWeek,
            'chartData' => $chartData // Pass chart data to the view
        ]);
    }


}
