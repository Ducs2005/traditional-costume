@extends('layouts.admin')  <!-- Extend the base layout -->

@section('content')
    <!-- Page Wrapper -->
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="align-items: center; text-align: center; margin-left: 20px;">Payments</h1>
                </div>

                <br><br>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Transaction ID</th>
                                    <th>User ID</th>
                                    <th>Money</th>
                                    <th>Note</th>
                                    <th>VNPAY Code</th>
                                    <th>Bank Code</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>{{ $payment->user_id }}</td>
                                        <td>{{ number_format($payment->money, 0, ',', '.') }} ₫</td>
                                        <td>{{ $payment->note ?? 'N/A' }}</td>
                                        <td>{{ $payment->code_vnpay ?? 'N/A' }}</td>
                                        <td>{{ $payment->code_bank ?? 'N/A' }}</td>
                                        <td>{{ $payment->time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush