<link rel="stylesheet" href="{{ asset('frontend/css/product.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
<script>
    const baseUrl = '{{ env('APP_URL') }}';
</script>

<div class="product">
    <div class="tag-container">
        <div class="centered-text"> Nổi bật nhất</div>
        <br>
    </div>
    
        <div class="filter-container">
        <input type="text" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()">
        
        <select id="colorSelect" onchange="filterProducts()">
            <option value="">Màu chính</option>
            @foreach($colors as $color)
                <option value="{{ $color->id }}">{{ $color->name }}</option>
            @endforeach
        </select>
        
        <select id="materialSelect" onchange="filterProducts()">
            <option value="">Chất liệu</option>
            @foreach($materials as $material)
                <option value="{{ $material->id }}">{{ $material->name }}</option>
            @endforeach
        </select>
        
        <select id="buttonSelect" onchange="filterProducts()">
            <option value="">Loại nút</option>
            @foreach($buttons as $button)
                <option value="{{ $button->id }}">{{ $button->name }}</option>
            @endforeach
        </select>
        <select id="buttonSort" onchange="filterProducts()">
            <option value="">Sắp xếp</option>
                <option value="byName">A-Z</option>
                <option value="byPriceDecrease">Theo giá giảm dần</option>
                <option value="byPriceIncrease">Theo giá tăng dần</option>

        </select>
    </div>
    <br> <br> 

    <div class="list-item"></div> <!-- Product items will be appended here -->
    <div class="pagination">

    </div> <!-- Pagination container -->
</div>

<script src="{{ asset('frontend/js/product.js') }}"></script>
