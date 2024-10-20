<link rel="stylesheet" href="{{ asset('frontend/css/product.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">

<div class="product">
    <div class="tag-container">
        <div class="centered-text"> Nổi bật nhất</div>
        <br>
    </div>
    
    <div class="filter-container">
        <input type="text" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()">
        <select id="productCategory" onchange="filterProducts()">
            <option value="">All Categories</option>
            <option value="category1">Category 1</option>
            <option value="category2">Category 2</option>
            <option value="category3">Category 3</option>
        </select>
    </div>
    <br> <br> 

    <div class="list-item"></div> <!-- Product items will be appended here -->
    <div class="pagination">

    </div> <!-- Pagination container -->
</div>

<script src="{{ asset('frontend/js/product.js') }}"></script>
