<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/product_description.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <br><br><br><br><br>
    <div class="container mt-5">
        <div class="row">
            <!-- Product Image Gallery -->
            <div class="col-md-6">
                <div class="product-image-gallery">
                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img id="productImage" src="{{ asset($product->img_path) }}" class="d-block w-100" alt="Product Image">
                            </div>
                        </div>
                    </div>
                    <div class="thumbnail-container mt-3 d-flex justify-content-start">
                        @foreach ($product->productImages as $image)
                            <img class="thumbnail img-thumbnail me-2" src="{{ asset($image->img_path) }}" alt="Thumbnail" onclick="setImage('{{ asset($image->img_path) }}')">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="product-info">
                    <h1 id="productName" class="h3">{{ $product->name }}</h1>
                    <p id="productPrice" class="product-price">
                        {{ number_format($product->price, 0, ',', '.') }}
                        <span class="price-icon">₫</span>
                    </p>
                    <p id="productDescription" class="text-muted">{{ $product->description }}</p>

                    <!-- Product Tags -->
                    <div class="product-tags mb-3">
                        <ul class="list-inline">
                            <li class="list-inline-item"><a href="#" class="badge bg-secondary">{{ $product->color ? $product->color->name : 'No Color' }}</a></li>
                            <li class="list-inline-item"><a href="#" class="badge bg-secondary">{{ $product->button ? $product->button->name : 'No Button' }}</a></li>
                            <li class="list-inline-item"><a href="#" class="badge bg-secondary">{{ $product->material ? $product->material->name : 'No Material' }}</a></li>
                            <li class="list-inline-item"><a href="#" class="badge bg-secondary">{{ $product->type ? $product->type->name : 'No Type' }}</a></li>
                        </ul>
                    </div>

                    <!-- Seller Information -->
                    @if ($product->seller)
                        <div class="seller-info mb-4">
                            <p><span class="fw-bold">Người bán: </span>
                                <a href="{{ route('contact.seller', $product->seller->id) }}" class="btn btn-link">{{ $product->seller->name }}</a>
                            </p>
                        </div>
                    @endif

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Quantity Field -->
                        <div class="mb-3 d-flex align-items-center">
                            <label for="quantity" class="form-label me-3">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" min="1" value="1" required class="form-control w-25">
                        </div>

                        <!-- Add to Cart Button -->
                        @if (!Auth::check())
                        <button disabled type="submit" class="add-to-cart-btn" style="background-color:grey">Add to Cart</button>
                        @elseif ($product->seller->id == auth()->user()->id)
                        <button disabled type="submit" class="add-to-cart-btn" style="background-color:grey">Add to Cart</button>
                        @else
                        <button type="submit" class="add-to-cart-btn" >Add to Cart</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
        @include('components.comment', ['product'=>$product])

    @include ('product.product', ['type' => 'related', 'currentProduct' => $product])


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

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>
