@extends('layouts.admin') <!-- Assuming layouts.app is your layout file -->

@section('content')
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="text-align: center; margin-left: 20px;">Order Dashboard</h1>
                </div>
                
                <br><br>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableOrders" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Total Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>{{ number_format($orderTotals[$order->id], 0, ',', '.') }} VND</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewDetailModal"
                                                data-id="{{ $order->id }}" 
                                                data-customer="{{ $order->user->name }}" 
                                                data-created-at="{{ $order->created_at->format('Y-m-d') }}"
                                                data-status="{{ ucfirst($order->status) }}"
                                                data-total-price="{{ number_format($orderTotals[$order->id], 0, ',', '.') }} VND">Chi tiết</button>

                                                <form id="cancelOrderForm" action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="handleCancelOrder(event)">Hủy</button>
                                                </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Detail Modal -->
        <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDetailModalLabel">Order Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="updateOrderForm" action="{{ route('order.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Hidden Input for Order ID -->
                            <input type="hidden" name="order_id" id="order-id">
                            
                            <!-- Order Details -->
                            <div class="form-group">
                                <label for="customer-name">Customer</label>
                                <input type="text" name="customer" id="customer-name-input" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="order-status">Status</label>
                                <input type="text" name="status" id="order-status-input" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="order-total">Total Price (VND)</label>
                                <input type="text" name="total_price" id="order-total-input" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="order-created-at">Created At</label>
                                <input type="text" name="created_at" id="order-created-at-input" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="order-items">Order Items</label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th> ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price (VND)</th>
                                            <th>Subtotal (VND)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td> {{$item->product->id}} </td>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td>{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTableOrders').DataTable();
        });

        // Populate the modal with order details
        $(document).ready(function () {
            $('#viewDetailModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var customer = button.data('customer');
                var createdAt = button.data('created-at');
                var status = button.data('status');
                var totalPrice = button.data('total-price');
                
                // Fill modal form fields with order details
                $('#order-id').val(id);
                $('#customer-name-input').val(customer);
                $('#order-status-input').val(status);
                $('#order-total-input').val(totalPrice);
                $('#order-created-at-input').val(createdAt);
                
                // Fetch and display order items (if needed)
                $.get(`/order/${id}/items`, function (items) {
                    var itemsText = items.map(item => `${item.product_name} x${item.quantity}`).join('\n');
                    $('#order-items-input').val(itemsText);
                });
            });
        });

        function handleCancelOrder(event) {
            event.preventDefault();  // Prevent the default form submission

            if (confirm('Are you sure you want to cancel this order?')) {
                var form = event.target.closest('form');  // Get the form element

                var formData = new FormData(form);  // Get the form data

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),  // Add CSRF token
                    },
                    body: formData
                })
                .then(response => response.json())  // Parse JSON response
                .then(data => {
                    // Handle the success message
                    alert(data.message);  // Show the success message
                    // Optionally, you can reload the page or update the UI here
                    location.reload();  // Reload the page to reflect changes
                })
                .catch(error => {
                    // Handle any errors
                    console.error('Error:', error);
                    alert('There was an error processing your request.');
                });
            }
        }

    </script>
@endpush
