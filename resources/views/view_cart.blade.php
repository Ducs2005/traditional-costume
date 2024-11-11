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
    <div class="cart-container">
        <h1 class="cart-title">Giỏ hàng của tôi</h1>

        @if(empty($cartItems))
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="product" style="text-align: center;">
                        Chưa có sản phẩm trong giỏ hàng
                    </td> 
                </tr>
            </tbody>
        </table>
        <br> <br>  <br> <br>  <br> <br>  <br> <br>  <br> <br>  <br> <br> 

        @else
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $cartItem)
                        <tr>
                            <td class="product">
                                <!-- Wrap product name and image with a link to the product description page -->
                                <a style="text-decoration: none; color: black;" href="{{ url('/product_description/' . $cartItem->product->id) }}">
                                    <img src="{{ asset('frontend/img/product/' . $cartItem->product->img_path) }}" alt="Product Image">
                                    <span>{{ $cartItem->product->name }}</span>
                                </a>
                            </td>
                            <td>{{ number_format($cartItem->product->price, 0, ',', '.') }} VND</td>
                            <td>
                                <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" class="quantity-input" required readonly>
                            </td>
                            <td>{{ number_format($cartItem->product->price * $cartItem->quantity, 0, ',', '.') }} VND</td>
                            <td>
                                <!-- Form to remove item -->
                                <form action="{{ route('cart.removeItem', $cartItem->id) }}" method="POST" class="remove-item-form" onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-summary">
                <p>Tổng: <strong id="total"></strong></p>
                <button class="checkout-btn" onclick="clearCart()">Thanh toán</button>
            </div>
        @endif
    </div>
</body>

<br> <br> <br>

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
    function confirmDelete() {
        return Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Sản phẩm sẽ bị xóa khỏi giỏ hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            return result.isConfirmed; // Only proceed if the user confirms
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
