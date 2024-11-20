@extends('layouts.admin')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('frontend/css/admin/accessTime.css') }}">
    <title>Thống kê lượt truy cập</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h1>Thống kê lượt truy cập</h1>

    <!-- Form tìm kiếm và điều hướng tuần -->
    <form method="GET" action="{{ route('accessTime') }}">
        <label for="start_date">Ngày bắt đầu:</label>
        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}">

        <label for="start_time">Giờ bắt đầu:</label>
        <input type="time" name="start_time" id="start_time">

        <button type="submit">Tìm kiếm</button>
        <button type="button" class="cancel-btn" onclick="resetToToday()">Hủy</button>
    </form>

    <!-- Điều hướng tuần -->
    <div class="chart-container">
        <button class="nav-btn prev-btn" onclick="navigateWeek('prev')">&#8592;</button>
        <canvas id="loginStatsChart"></canvas>
        <button class="nav-btn next-btn" onclick="navigateWeek('next')">&#8594;</button>
    </div>



    <!-- Biểu đồ -->
    <div class="chart-container">
        <canvas id="loginStatsChart"></canvas>
    </div>
</div>

<script>
    // Dữ liệu biểu đồ từ controller với các ngày trong tuần
    const labels = {!! json_encode($datesOfWeek) !!}; // Danh sách các ngày trong tuần
    const data = {!! json_encode(array_values($weekStats)) !!}; // Danh sách lượt truy cập cho mỗi ngày

    // Cấu hình biểu đồ
    const ctx = document.getElementById('loginStatsChart').getContext('2d');
    const loginStatsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số lượng người truy cập',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Số lượng người truy cập'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Ngày'
                    }
                }
            }
        }
    });

    function resetToToday() {
        const url = new URL(window.location.href); // Lấy URL hiện tại
        const today = '{{ $today }}'; // Ngày hôm nay từ controller

        // Đặt lại tham số start_date thành ngày hôm nay
        url.searchParams.set('start_date', today);

        // Xóa tham số 'week_action' khi reset về ngày hôm nay
        url.searchParams.delete('week_action');

        // Điều hướng lại trang về ngày hôm nay
        window.location.href = url.toString();
    }

    // Hàm điều hướng giữa các tuần khi nhấn prev hoặc next
    function navigateWeek(direction) {
        const url = new URL(window.location.href);
        const currentDate = '{{ $startDate }}'; // Ngày hiện tại từ controller

        // Thiết lập tham số để điều hướng tới tuần trước hoặc tuần sau
        url.searchParams.set('week_action', direction);
        url.searchParams.set('start_date', currentDate); // Giữ nguyên ngày bắt đầu

        // Điều hướng trang mới
        window.location.href = url.toString();
    }
</script>

</body>
</html>
@endsection
