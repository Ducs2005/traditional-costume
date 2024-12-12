@extends('layout_home')

@section('content_homePage')

    <div class="bannerList">
        <img src="{{ asset('frontend/img/banner/banner5.png') }}" alt="Sample Banner Image">
        <div class="banner-content">
            <h1>Ao Dai - A Symbol of Vietnamese Culture</h1>
            <p>
                The Ao Dai is a traditional Vietnamese garment known for its elegance and graceful silhouette, typically worn on formal occasions and national celebrations.
            </p>
            <a href="#" class="btn banner-btn">See more</a>
        </div>
        <div class="dot-nav">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <div class="product_body">
        <div class="filter-container">
            <div class="tag-container">
                <div class="centered-text"> Bộ lọc</div>
                <br>
            </div>

            <input type="text" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()">
            <div class="select">
                <select id="materialSelect" onchange="filterProducts()">
                    <option value="">Chất liệu</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}"
                            {{ isset($attributeData) && $attribute === 'material' && $attributeData->id === $material->id ? 'selected' : '' }}>
                            {{ $material->name }}
                        </option>
                    @endforeach
                </select>

                <select id="colorSelect" onchange="filterProducts()">
                    <option value="">Màu sắc</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}"
                            {{ isset($attributeData) && $attribute === 'color' && $attributeData->id === $color->id ? 'selected' : '' }}>
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>

                <select id="buttonSelect" onchange="filterProducts()">
                    <option value="">Loại nút</option>
                    @foreach($buttons as $button)
                        <option value="{{ $button->id }}"
                            {{ isset($attributeData) && $attribute === 'button' && $attributeData->id === $button->id ? 'selected' : '' }}>
                            {{ $button->name }}
                        </option>
                    @endforeach
                </select>

                <select id="typeSelect" onchange="filterProducts()">
                    <option value="">Thể loại</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}"
                            {{ isset($attributeData) && $attribute === 'type' && $attributeData->id === $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

                <select id="buttonSort" onchange="filterProducts()">
                    <option value="">Sắp xếp</option>
                        <option value="byName">A-Z</option>
                        <option value="byPriceDecrease">Theo giá giảm dần</option>
                        <option value="byPriceIncrease">Theo giá tăng dần</option>
                </select>
                <select id="ratingFilter" onchange="filterProducts()">
                    <option value="">Lọc theo đánh giá</option>
                    <option value="1">1 sao trở lên</option>
                    <option value="2">2 sao trở lên</option>
                    <option value="3">3 sao trở lên</option>
                    <option value="4">4 sao trở lên</option>
                    <option value="5">5 sao</option>
                </select>
            </div>
            <div class="price-range-container">
                <label for="priceRange">Lọc theo giá:</label>
                <div class="range-slider">
                    <input type="range" id="minPrice" min="1000" max="10000000" step="1000" value="0" oninput="updatePriceRange()">
                    <input type="range" id="maxPrice" min="1000" max="10000000" step="1000" value="100000" oninput="updatePriceRange()">
                    <div class="slider-track"></div>
                </div>
                <div id="priceRangeLabel" class="price-label">Từ 0₫ đến 100,000₫</div>
            </div>
        </div>
        @include ('product.product', ['type' => 'popular', 'currentProduct' => null, 'products' =>$products] )
    </div>

    <div class="product product-discount">
        <div class="tag-container">
            <div class="centered-text" id="listType">Giá trung bình</div>
            <br>
        </div>
        <div class="list-item">
            @if($discountedProducts->isEmpty())
            <p>No discounted products found.</p>
            @else
            @foreach($discountedProducts as $product)
                <div class="item product-item">
                    <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}">
                    <h4>{{ $product->name }}</h4>
                    <div class="price-rating">
                        <div class="price">
                            {{ number_format($product->price, 0, ',', '.') }} <span class="vnd-icon">₫</span>
                        </div>
                        <div class="rate">
                            5 /5 <i class="fa-solid fa-star" style="color: gold;"></i> (0 đã đánh giá)
                        </div>
                    </div>
                    <div class="product-attributes">
                        @if($product->color)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->color->name }}</a>
                            </div>
                        @endif
                        @if($product->material)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->material->name }}</a>
                            </div>
                        @else
                            <a class="category-label">No material</a>
                        @endif
                        @if($product->button)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->button->name }}</a>
                            </div>
                        @endif

                        @if($product->type)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->type->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="product product-discount">
        <div class="tag-container">
            <div class="centered-text" id="listType">Sản phẩm bán chạy nhất</div>
            <br>
        </div>
        <div class="list-item">
            @if($popularProducts->isEmpty())
            <p>No discounted products found.</p>
            @else
            @foreach($popularProducts as $product)
                <div class="item product-item">
                    <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}">
                    <h4>{{ $product->name }}</h4>
                    <div class="price-rating">
                        <div class="price">
                            {{ number_format($product->price, 0, ',', '.') }} <span class="vnd-icon">₫</span>
                        </div>
                        <div class="rate">
                            5 /5 <i class="fa-solid fa-star" style="color: gold;"></i> (0 đã đánh giá)
                        </div>
                    </div>
                    <div class="product-attributes">
                        @if($product->color)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->color->name }}</a>
                            </div>
                        @endif
                        @if($product->material)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->material->name }}</a>
                            </div>
                        @else
                            <a class="category-label">No material</a>
                        @endif
                        @if($product->button)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->button->name }}</a>
                            </div>
                        @endif

                        @if($product->type)
                            <div class="attribute-group">
                                <a class="category-label">{{ $product->type->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>

<script src="{{asset('frontend/js/banner.js')}}"></script>
<script>
    initEventListeners() {
        const filterButton = document.getElementById('filterButton');
        if (filterButton) {
            filterButton.addEventListener('click', () => {
                this.filterProducts(); // Call the filter function on button click
            });
        }
    }
    // Function to filter products based on selected criteria
    filterProducts() {
        // Get the selected values from the dropdowns
        const colorId = document.getElementById('colorSelect').value;
        const materialId = document.getElementById('materialSelect').value;
        const buttonId = document.getElementById('buttonSelect').value;
        const sortOrder = document.getElementById('buttonSort').value;
        const searchQuery = document.getElementById('productSearch').value;

        // Create an object to hold the filter parameters
        const filters = {
            color_id: colorId,
            material_id: materialId,
            button_id: buttonId,
            sort_order: sortOrder,
            search: searchQuery
        };

        const filterUrl = `${baseUrl}/api/products/filter`; // Use template literals for clarity

        // Make an AJAX request to fetch filtered products
        fetch(filterUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token for Laravel
            },
            body: JSON.stringify(filters)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Check if there are products returned
            if (data.products.length === 0) {
                this.productContainer.innerHTML = '<p>No products found.</p>'; // Display message if no products found
                return;
            }

            // Display the filtered products
            this.products = data.products; // Update the products array
            this.currentPage = 1; // Reset to the first page
            this.displayProducts(this.products.slice(0, this.itemsPerPage)); // Display the first page of filtered products
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });
    }
</script>

@endsection
