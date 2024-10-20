<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="../frontend/css/product_description.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>

@include ('header_footer.header')

<body>
    <br><br><br><br><br><br>

    <div class="container">
        <div class="product-details">
            <div class="product-image-gallery">
                <div class="carousel">
                    <span class="arrow prev" onclick="prevImage()">&#8249;</span> <!-- Left arrow -->
                    <img id="productImage" src="{{ asset('frontend/img/product/' . $product->img_path) }}" alt="Product Image"> <!-- Use the first image -->
                    <span class="arrow next" onclick="nextImage()">&#8250;</span> <!-- Right arrow -->
                </div>
                <div class="thumbnail-container">
                    @foreach ($product->images as $image)
                        <img class="thumbnail" src="{{ asset('frontend/img/product/' . $image->img_path) }}" alt="Thumbnail" onclick="setImage('{{ asset('frontend/img/product/' . $image->path) }}')">
                    @endforeach
                </div>
            </div>
            
            <div class="product-info">
                <h1 id="productName">{{ $product->name }}</h1>
                <p id="productPrice">{{ $product->price }}</p>
                <p id="productDescription">{{ $product->description }}</p>

                <!-- Product Tags -->
                <div class="product-tags">
                    <strong>Attributes:</strong>
                    <ul>
                        <li><a href="#">{{ $product->color }}</a></li>
                        <li><a href="#">{{ $product->button }}</a></li>
                        <li><a href="#">{{ $product->material }}</a></li>
                        @foreach ($product->types as $type)
                                    <li><a href="$type->id)">{{ $type->name }}</a></li>
                                @endforeach    
                          
                    </ul>
                </div>

                <!-- Add to Cart Button -->
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
        </div>
    </div>

    @include ('header_footer.footer')
</body>

</html>
