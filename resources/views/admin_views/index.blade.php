@extends('layouts.admin')

@section('content')
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="text-align: center; margin-left: 20px;">Dashboard</h1>
                </div>
                
                <br><br>

                <!-- Begin Page Content -->
                <form method="GET" action="{{ route('admin.accessTime') }}" id="dateRangeForm">
                    <div class="form-row">
                        <div class="col">
                            <input type="date" name="start_date" value="{{ request()->input('start_date', now()->startOfWeek()->toDateString()) }}" class="form-control" id="start_date" />
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" value="{{ request()->input('end_date', now()->endOfWeek()->toDateString()) }}" class="form-control" id="end_date" />
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Show Stats</button>
                        </div>
                    </div>
                </form>

                <br>

                <!-- Chart Section -->
                <div class="mt-5">
                    <h3>Weekly Login Statistics</h3>
                    <canvas id="loginStatsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Retrieve chart data from the controller
        var chartData = @json($chartData);

        // Create the chart
        var ctx = document.getElementById('loginStatsChart').getContext('2d');
        var loginStatsChart = new Chart(ctx, {
            type: 'line', // Type of chart (line, bar, etc.)
            data: {
                labels: chartData.labels, // Dates
                datasets: [{
                    label: 'Login Counts',
                    data: chartData.data, // Login counts
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
