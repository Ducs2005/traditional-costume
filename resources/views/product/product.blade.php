<link rel="stylesheet" href="{{ asset('frontend/css/product.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
<script>
    const baseUrl = '{{ env('APP_URL') }}';
</script>

<div class="product">
    <div class="tag-container">
        <div class="centered-text" id="listType"> Nổi bật nhất</div>
        <br>
    </div>
    <div class="list-item"></div> <!-- Product items will be appended here -->
    <div class="pagination">

    </div>
</div>
<script>
    // Pass the PHP variable into JavaScript
    const type = @json($type);
    const currentProduct = @json($currentProduct);
    const assetBaseUrl = "{{ asset('') }}";
 //   const productByFilter = @json($products);
    console.log("Type:", type); // Output: "popular"
    console.log("Current Product:", currentProduct); // Output: Object with product details
</script>


<script src="{{ asset('frontend/js/product.js') }}"></script>
