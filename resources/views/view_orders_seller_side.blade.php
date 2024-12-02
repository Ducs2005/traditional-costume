<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Order History</title>

    <!-- Update CSS link with asset helper -->
    <link rel="stylesheet" href="{{ asset('frontend/css/view_cart.css') }}"> <!-- Link to the CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRdijKvUnaw3sC1Tbq9IYNv3WOb1bXWlJQkT4dZpP" crossorigin="anonymous">

    <style>
    .modal-content-left {
        text-align: left; /* Align all text to the left */
        margin: 0; /* Reset any margin if necessary */
        padding: 10px; /* Optional: Add padding for better readability */
    }

    .modal-content-left h3 {
        margin-bottom: 10px;
    }

    .modal-content-left p {
        margin: 5px 0;
    }

    .modal-content-left img {
        float: left; /* Align images to the left */
        margin-right: 10px; /* Add spacing between image and text */
    }

    </style>
</head>

<!-- Include header and chat window -->
@include('header_footer.header')
@include('chat.chat_window')

<body>
    <br> <br> <br> <br> <br> <br>
    <div class="container mt-5">
    <h1 class="text-center mb-4">Đơn hàng đang chờ xử lý</h1>

    @if($orders->isEmpty())
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Người mua</th>
                    <th>Ngày tạo</th>
                    <th>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                            Trạng thái
                            <span id="arrow-down" style="cursor: pointer;">&#9660;</span>
                            <!-- Filter options -->
                            <ul id="filter-options" 
                                class="dropdown-menu position-absolute" 
                                style="display: none; left: 50%; transform: translateX(-50%);">
                                <form method="get" action="{{ route('viewOrder') }}">
                                    <li><button type="submit" name="status" value="all" class="dropdown-item">Tất cả</button></li>
                                    <li><button type="submit" name="status" value="Chờ xác nhận" class="dropdown-item">Chờ xác nhận</button></li>
                                    <li><button type="submit" name="status" value="Đang giao" class="dropdown-item">Đang giao</button></li>
                                    <li><button type="submit" name="status" value="Đã hủy" class="dropdown-item">Đã hủy</button></li>
                                    <li><button type="submit" name="status" value="Đã nhận" class="dropdown-item">Đã nhận</button></li>
                                </form>
                            </ul>
                        </div>
                    </th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="alert alert-info text-center" role="alert">
                Chưa có đơnss hàng nào
            </div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Người mua</th>
                    <th>Ngày tạo</th>
                    <th>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                            Trạng thái
                            <span id="arrow-down" style="cursor: pointer;">&#9660;</span>
                            <!-- Filter options -->
                            <ul id="filter-options" 
                                class="dropdown-menu position-absolute" 
                                style="display: none; left: 50%; transform: translateX(-50%);">
                                <form method="get" action="{{ route('seller.viewOrder') }}">
                                    <li><button type="submit" name="status" value="all" class="dropdown-item">Tất cả</button></li>
                                    <li><button type="submit" name="status" value="Chờ xác nhận" class="dropdown-item">Chờ xác nhận</button></li>
                                    <li><button type="submit" name="status" value="Đang giao" class="dropdown-item">Đang giao</button></li>
                                    <li><button type="submit" name="status" value="Đã hủy" class="dropdown-item">Đã hủy</button></li>
                                    <li><button type="submit" name="status" value="Đã nhận" class="dropdown-item">Đã nhận</button></li>
                                </form>
                            </ul>
                        </div>
                    </th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ number_format($orderTotals[$order->id], 0, ',', '.') }} VND</td>
                    <td>
                        <button class="btn btn-info btn-sm mb-2" onclick="viewOrderDetails({{ $order->id }})">Xem chi tiết</button>
                        @if ($order->status == 'Chờ xác nhận')
                        <button class="btn btn-success btn-sm mb-2" onclick="confirmOrder({{ $order->id }})">Xác nhận</button>
                        <button class="btn btn-danger btn-sm mb-2" onclick="cancelOrder({{ $order->id }})">Hủy đơn</button>
                        @elseif ($order->status == 'Đang giao')
                        <button class="btn btn-danger btn-sm mb-2" onclick="cancelOrder({{ $order->id }})">Hủy đơn</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

</body>

<br> <br> <br> <br> <br> <br><br> <br> <br>

<!-- Include footer -->
@include('header_footer.footer')

<script>

   
    // Function to show order details using SweetAlert
    function viewOrderDetails(orderId) {
        Swal.fire({
            title: 'Đang tải thông tin đơn hàng...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        fetch(`{{url('/seller/order-details/${orderId}')}}`) // Assuming you have an endpoint to fetch order details
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: `Chi tiết đơn hàng #${data.order.id}`,
                html: generateOrderDetailsHtml(data.order),
                icon: 'info',
                confirmButtonText: 'Đóng',
                width:'60%'
            });
        })
        .catch(error => {
            console.error("Error fetching order details:", error);
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi xảy ra',
                text: 'Vui lòng thử lại sau.'
            });
        });
    }

    // Function to generate order details HTML dynamically
    function generateOrderDetailsHtml(order) {
        let orderDetailsHtml = `
            <div class="modal-content-left">
                <section>
                    <h3>Thông tin đơn hàng</h3>
                    <p><strong>Ngày tạo:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                    <p><strong>Trạng thái:</strong> ${order.status}</p>
                    <p><strong>Tổng tiền:</strong> ${order.total_price.toLocaleString()} VND</p>
                </section>
                <hr>
                <section>
                    <h3>Danh sách sản phẩm</h3>
        `;

        order.items.forEach(item => {
           
            orderDetailsHtml += `
                <div style="margin-bottom: 15px;">
                    <img src="{{url('${item.product.img_path}')}}" alt="Product Image" style="width: 100px; display: inline-block; margin-right: 10px; border-radius: 5px">
                    <div style="display: inline-block; vertical-align: top;">
                        <h5>${item.product.name}</h5>
                        <p>Số lượng: ${item.quantity}</p>
                        <p>Đơn giá: ${item.product.price.toLocaleString()} VND</p>
                        <p>Tổng: ${(item.product.price * item.quantity).toLocaleString()} VND</p>
                        
                    </div>
                </div>
                <br>
            `;
        });

        orderDetailsHtml += `</section></div>`;
        return orderDetailsHtml;
    }



    function confirmOrder(orderId) {
        Swal.fire({
            title: 'Xác nhận đơn hàng?',
            text: "Bạn có chắc chắn muốn xác nhận đơn hàng này không?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#58e04e',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{url('/orders/${orderId}/confirm')}}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: 'Thành công!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500, // 5 seconds
                        timerProgressBar: true
                    }).then(() => {
                    location.reload(); // Reload after alert closes
                });
                })
                .catch(error => Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại.', 'error'));
            }
        });
    }

    function cancelOrder(orderId) {
        console.log('click cance');
        Swal.fire({
            title: 'Hủy đơn hàng?',
            text: "Bạn có chắc chắn muốn hủy đơn hàng này không?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hủy đơn'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{url('/orders/${orderId}/cancel')}}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: 'Thành công!',
                        text: data.message,
                        icon: 'success',
                        timer: 1400, // 5 seconds
                        timerProgressBar: true
                    }).then(() => {
                    location.reload(); // Reload after alert closes
                });
                })
                .catch(error => Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại.', 'error'));
            }
        });
    }


    // Get the arrow element and filter options list
    const arrowDown = document.getElementById("arrow-down");
    const filterOptions = document.getElementById("filter-options");

    // Add click event to toggle the visibility of the options list
    arrowDown.addEventListener("click", function() {
        const isVisible = filterOptions.style.display === "block";
        
        // Toggle display of the options list
        filterOptions.style.display = isVisible ? "none" : "block";

        // Rotate the arrow based on the visibility of the options
        arrowDown.style.transform = isVisible ? "rotate(0deg)" : "rotate(180deg)";
    });

    // Function to filter orders based on the selected status
    function filterOrders(status) {
        console.log("Filtering orders by status:", status);
        
        // Perform filtering actions here
        // You can send an AJAX request or filter orders locally based on the 'status'

        // Hide the options list after selection
        filterOptions.style.display = "none";
    }

</script>

</html>
