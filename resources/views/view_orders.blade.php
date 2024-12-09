@extends('layout_home')

@section('content_homePage')

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





    <br> <br> <br> <br>
    <div class="container my-5">
    <h1 class="text-center mb-4">Đơn Hàng Của Tôi </h1>

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
                Chưa có đơn hàng nào
            </div>
    @else
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Mã đơn hàng</th>
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
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ number_format($orderTotals[$order->id], 0, ',', '.') }} VND</td>
                        <td>
                        
                                <button class="btn btn-info btn-sm mb-2" onclick="viewOrderDetails({{ $order->id }})">Xem chi tiết</button>
                                @if($order->status == 'Đang giao')
                                    <button class="btn btn-success btn-sm mb-2" onclick="confirmReceived({{ $order->id }})">Đã nhận</button>
                                @elseif($order->status == 'Chờ xác nhận')
                                    <button class="btn btn-danger btn-sm mb-2" onclick="cancelOrder({{ $order->id }})">Hủy</button>
                               
                                 @endif
        
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <div id="order-details-container" style="display:none;">
    <div>
        <h3>Thông tin đơn hàng</h3>
        <p><strong>Ngày tạo:</strong> <span id="order-created"></span></p>
        <p><strong>Trạng thái:</strong> <span id="order-status"></span></p>
        <p><strong>Tổng tiền:</strong> <span id="order-total-price"></span></p>
    </div>
    <hr>
    <div>
        <h3>Danh sách sản phẩm</h3>
        <div id="order-items-container"></div>
    </div>
</div>
    <div id="orderDetailModal" class="modal fade" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <section id="orderInfo">
                        <h3>Thông tin đơn hàng</h3>
                        <p><strong>Ngày tạo:</strong> <span id="orderCreatedDate"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="orderStatus"></span></p>
                        <p><strong>Tổng tiền:</strong> <span id="orderTotal"></span></p>
                    </section>
                    <hr>
                    <section id="orderItems">
                        <h3>Danh sách sản phẩm</h3>
                        <div id="productList"></div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Rate Product Modal -->
    <div id="rateProductModal" class="modal fade" tabindex="-1" aria-labelledby="rateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateProductModalLabel">Đánh giá sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rateProductForm" method="POST" action="{{ route('rate.product') }}">
                        @csrf
                        <input type="hidden" name="product_id" id="modal-product-id">
                        <input type="hidden" name="order_id" id="modal-order-id">
                        <input type="hidden" name="rating" id="modal-rating" value="0">
                        
                        <div class="mb-3">
                            <label class="form-label">Đánh giá</label>
                            <div id="modal-star-rating" style="font-size: 24px; color: gold;">
                                <i class="fa-regular fa-star" data-value="1" onclick="setRating(1)"></i>
                                <i class="fa-regular fa-star" data-value="2" onclick="setRating(2)"></i>
                                <i class="fa-regular fa-star" data-value="3" onclick="setRating(3)"></i>
                                <i class="fa-regular fa-star" data-value="4" onclick="setRating(4)"></i>
                                <i class="fa-regular fa-star" data-value="5" onclick="setRating(5)"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Nhận xét</label>
                            <textarea class="form-control" name="comment" id="modal-comment" rows="4" placeholder="Viết nhận xét về sản phẩm" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
            });
        </script>
    @endif
    
</div>


<br> <br> <br> <br> <br> <br><br> <br> <br>

<!-- Include footer -->

<script>
    // Function to show order details using SweetAlert
    function viewOrderDetails(orderId) {
    Swal.fire({
        title: 'Đang tải thông tin đơn hàng...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`{{url('/order-details/${orderId}')}}`) // Update this endpoint as necessary
        .then(response => response.json())
        .then(data => {
            // Update order info
            document.getElementById('orderCreatedDate').innerText = new Date(data.order.created_at).toLocaleDateString();
            document.getElementById('orderStatus').innerText = data.order.status;
            document.getElementById('orderTotal').innerText = `${data.order.total_price.toLocaleString()} VND`;

            // Populate product list
            const productList = document.getElementById('productList');
            productList.innerHTML = ''; // Clear existing content

            data.order.items.forEach(item => {
                let productHTML = `
                    <div class="modal-content-left">
                        <img src="${item.product.img_path}" alt="Product Image" style="width: 120px; border-radius: 5px; margin-right: 15px;">
                        <div style="flex: 1;">
                            <h5 style="margin: 0 0 5px;">${item.product.name}</h5>
                            <p style="margin: 0; font-size: 16px;"><strong>Số lượng:</strong> ${item.quantity}</p>
                            <p style="margin: 0; font-size: 16px;"><strong>Đơn giá:</strong> ${item.product.price.toLocaleString()} VND</p>
                            <p style="margin: 0; font-size: 16px;"><strong>Tổng:</strong> ${(item.product.price * item.quantity).toLocaleString()} VND</p>
                            <p style="margin: 0; font-size: 16px;"><strong>Người bán:</strong> ${item.product.seller.name}</p>
                `;

                if (data.order.status === 'Đã nhận') {
                    productHTML += `
                        <button class="btn btn-warning btn-sm mt-2" onclick="openRateProductModal(${data.order.id}, ${item.product.id})">Đánh giá</button>
                    `;
                }

                productHTML += `
                        </div>
                    </div>
                    <br>
                    <br>
                `;
                

                productList.innerHTML += productHTML;
            });

            // Show the modal
            const orderDetailModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
            orderDetailModal.show();

            // Close loading dialog
            Swal.close();
        })
        .catch(error => {
            console.error('Error fetching order details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi xảy ra',
                text: 'Vui lòng thử lại sau.'
            });
        });
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


    function confirmReceived(orderId) {
        Swal.fire({
            title: 'Xác nhận đã nhận được hàng?',
            text: "Bạn có chắc chắnđã nhận được đơn hàng này chưa?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#58e04e',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{url('/orders/${orderId}/confirm-received')}}`, {
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

    
function openRateProductModal(orderId, productId) {
    document.getElementById('modal-order-id').value = orderId;
    document.getElementById('modal-product-id').value = productId;

    const modal = new bootstrap.Modal(document.getElementById('rateProductModal'));
    modal.show();
}
function setRating(rating) {
    const stars = document.querySelectorAll('#modal-star-rating .fa-star');
    console.log(stars);
    // Reset all stars
    stars.forEach(star => star.classList.remove('fa-solid'));
    // Highlight the selected stars
    for (let i = 0; i < rating; i++) {
        stars[i].classList.add('fa-solid');
    }
    // Update the hidden rating field
    document.getElementById('modal-rating').value = rating;
}





</script>

@endsection