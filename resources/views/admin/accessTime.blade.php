<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê lượt truy cập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        h1 {
            margin-top: 20px;
            text-align: center;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="date"],
        input[type="time"] {
            padding: 8px;
            margin-right: 10px;
            width: 200px;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .chart-container {
            width: 100%;
            max-width: 1200px;
            margin: 40px 0;
        }
    </style>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h1>Thống kê lượt truy cập</h1>

    <!-- Form tìm kiếm -->
    <form method="GET" action="{{ route('accessTime') }}">
        <label for="start_date">Ngày bắt đầu:</label>
        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}">

        <label for="start_time">Giờ bắt đầu:</label>
        <input type="time" name="start_time" id="start_time">

        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Biểu đồ -->
    <div class="chart-container">
        <canvas id="loginStatsChart"></canvas>
    </div>
</div>

<script>
    // Dữ liệu biểu đồ
    const labels = {!! json_encode($loginStats->pluck('date')) !!}; // Ngày
    const data = {!! json_encode($loginStats->pluck('total')) !!}; // Số lượng người truy cập

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
                        text: 'Ngày truy cập'
                    }
                }
            }
        }
    });
</script>

</body>
</html>
