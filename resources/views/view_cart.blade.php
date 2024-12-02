<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shopping Cart</title>

    <!-- Update CSS link with asset helper -->
    <link rel="stylesheet" href="{{ asset('frontend/css/view_cart.css') }}"> <!-- Link to the CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<!-- Include header and chat window -->
@include('header_footer.header')
@include('chat.chat_window')

<body>
    <br> <br> <br> <br> <br> <br>
    <div class="container my-5">
    <h1 class="text-center cart-title">Giỏ hàng của tôi</h1>

    @if(empty($cartItems))
        <div class="alert alert-warning text-center" role="alert">
            Chưa có sản phẩm trong giỏ hàng
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Tên</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Tổng</th>
                    <th scope="col">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $cartItem)
                    <tr>
                        <td class="product">
                            <!-- Wrap product name and image with a link to the product description page -->
                            <a href="{{ url('/product_description/' . $cartItem->product->id) }}" class="text-decoration-none text-dark">
                                <img src="{{ asset($cartItem->product->img_path) }}" alt="Product Image" class="img-thumbnail" style="width: 50px; height: 50px;">
                                <span>{{ $cartItem->product->name }}</span>
                            </a>
                        </td>
                        <td>{{ number_format($cartItem->product->price, 0, ',', '.') }} VND</td>
                        <td>
                            <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" class="form-control" required readonly>
                        </td>
                        <td>{{ number_format($cartItem->product->price * $cartItem->quantity, 0, ',', '.') }} VND</td>
                        <td>
                            <!-- Form to remove item -->
                            <form action="{{ route('cart.removeItem', $cartItem->id) }}" method="POST" class="remove-item-form" onsubmit="confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cart-summary text-right">
            <p><strong>Tổng: </strong> <span id="total">VND</span></p>
            <button class="btn btn-primary" onclick="clearCart()">Thanh toán</button>
        </div>
    @endif

    </div>
</body>

<br> <br> <br> <br> <br> <br><br> <br> <br>
 
<!-- Include footer -->
@include('header_footer.footer')

<script>
    // Update total price dynamically using JavaScript
    const cartItems = @json($cartItems);
    let total = 0;

    cartItems.forEach(item => {
        total += item.product.price * item.quantity;
    });

    // Function to format price in VND without decimals
    function formatPrice(amount) {
        return amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replace('₫', '').trim();
    }

    document.getElementById('total').textContent = formatPrice(total);

    // Confirm delete action with SweetAlert
    function confirmDelete(event) {
    // Prevent form submission
        event.preventDefault();

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Sản phẩm sẽ bị xóa khỏi cửa hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                event.target.submit();
            }
        });
    }


    // Clear cart function with SweetAlert
    function clearCart() {
        Swal.fire({
            title: "Xác nhận thanh toán?",
            text: "Bạn có chắc chắn muốn thanh toán và xóa toàn bộ giỏ hàng?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Thanh toán",
            cancelButtonText: "Hủy",
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('cart.clear') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Thanh toán thành công!",
                            text: "Giỏ hàng đã được xóa.",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload the page to show the empty cart message
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Không thể thanh toán!",
                            text: "Vui lòng thử lại sau."
                        });
                    }
                })
                .catch(error => {
                    console.error("Error clearing cart:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Có lỗi xảy ra!",
                        text: "Vui lòng thử lại sau."
                    });
                });
            }
        });
    }

</script>

</html>
