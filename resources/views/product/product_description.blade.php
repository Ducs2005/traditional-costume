<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/product_description.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom styles for SweetAlert buttons */
        .swal-button--red {
            background-color: #ff4b4b !important;
            color: white !important;
            border: none !important;
        }
        .swal-button--red:hover {
            background-color: #c0392b !important;
        }
        .swal-button--green {
            background-color: #28a745 !important;
            color: white !important;
            border: none !important;
        }
        .swal-button--green:hover {
            background-color: #218838 !important;
        }
        .swal-button--yellow {
            background-color: #f1c40f !important;
            color: white !important;
            border: none !important;
        }
        .swal-button--yellow:hover {
            background-color: #f39c12 !important;
        }
    </style>
</head>

@include ('header_footer.header')

<body>
    <br><br><br><br><br><br><br><br>
    <div class="container">
        <div class="product-details">
            <div class="product-image-gallery">
                <div class="carousel">
                    <span class="arrow prev" onclick="prevImage()">&#8249;</span>
                    <img id="productImage" src="{{ asset('frontend/img/product/' . $product->img_path) }}" alt="Product Image">
                    <span class="arrow next" onclick="nextImage()">&#8250;</span>
                </div>
                <div class="thumbnail-container">
                    @foreach ($product->images as $image)
                        <img class="thumbnail" src="{{ asset('frontend/img/product/' . $image->img_path) }}" alt="Thumbnail" onclick="setImage('{{ asset('frontend/img/product/' . $image->img_path) }}')">
                    @endforeach
                </div>
            </div>

            <div class="product-info">
                <h1 id="productName">{{ $product->name }}</h1>
                <p id="productPrice">
                    {{ number_format($product->price, 0, ',', '.') }}
                    <span class="price-icon">₫</span>
                </p>
                <p id="productDescription">{{ $product->description }}</p>

                <!-- Product Tags -->
                <div class="product-tags">
                    <ul>
                        <li><a href="#">{{ $product->color ? $product->color->name : 'No Color' }}</a></li>
                        <li><a href="#">{{ $product->button ? $product->button->name : 'No Button' }}</a></li>
                        <li><a href="#">{{ $product->material ? $product->material->name : 'No Material' }}</a></li>
                        <li><a href="#">{{ $product->type ? $product->type->name : 'No type' }}</a></li>
                    </ul>
                </div>
                <!-- Seller Information -->
                @if ($product->seller)
                    <div class="seller-info">
                        <p> <span>Người bán: </span>
                            <span> 
                                <a href="{{ route('contact.seller', $product->seller->id) }}" class="btn-contact">
                                    {{ $product->seller->name }}
                                </a>
                            </span>
                            
                        </p>
                    </div>
                @endif



                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Quantity Field -->
                     <span>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                     </span>
                     <span>
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>

                     </span>
                    
                </form>
            </div>
        </div>
    </div>

    @include ('header_footer.footer')

    @if(session('showChatWindow'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatWindow = document.querySelector('.chat-window');
                if (chatWindow) {
                    chatWindow.style.display = 'block';
                }
            });
        </script>
    @endif

    @if(session('alert'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Alert',
                text: '{{ session('alert')['message'] }}',
                customClass: {
                    confirmButton: 'swal-button--yellow'
                }
            });
        </script>
    @endif

    <script>
        function setImage(imagePath) {
            document.getElementById('productImage').src = imagePath;
        }

        // Check for session success or error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'swal-button--green'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'swal-button--red'
                }
            });
        @endif
        
    </script>
</body>

</html>
