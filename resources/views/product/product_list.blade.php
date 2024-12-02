<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home view</title>

    <!-- Include CSS file -->
    <style>
    /* General styling for the filter container */
        .filter-container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping for better responsiveness */
            justify-content: center;
            align-items: center;
            gap: 16px;
            width: 96%; /* Adjusted for better alignment */
            margin: 0 20px; /* Centering for larger screens */
            
            background-image: #f9f9f9; /* Light background for contrast */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Styling for inputs and dropdowns */
        .filter-container input[type="text"],
        .filter-container select {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            width: 40%; /* Default to full width for small screens */
            transition: all 0.3s ease-in-out;
            box-sizing: border-box; 
            margin-bottom: 10px;
        }

        /* Focus and hover effects for better interactivity */
        .filter-container input[type="text"]:focus,
        .filter-container select:focus {
            border-color: #f4500e; /* Highlight on focus */
            outline: none; /* Remove default outline */
            background-color: #fffdf0; /* Light highlight background */
        }

        .filter-container input[type="text"]:hover,
        .filter-container select:hover {
            border-color: #ddd; /* Slight change on hover */
        }

        /* Styling for input and select placeholders */
        .filter-container select {
            background-color: #ffffff;
            color: #555;
            cursor: pointer;
            width: 13%; 
        }

        .filter-container option {
            color: #333; /* Ensure options are legible */
        }

        /* Responsiveness: Adjust layout for smaller screens */
        @media (max-width: 768px) {
            .filter-container {
                gap: 12px; /* Reduce gap on smaller screens */
            }

            .filter-container input[type="text"],
            .filter-container select {
                font-size: 14px; /* Reduce font size for smaller devices */
                padding: 8px 10px;
            }
            .filter-container input[type="text"] {
                width: 85%; /* Allow full width on small screens */
            }
            .filter-container select {
                width: 20%; /* Allow full width on small screens */
            }
        }

        @media (max-width: 480px) {
            .filter-container {
                gap: 8px; /* Further reduce gap on very small screens */
            }

            .filter-container input[type="text"],
            .filter-container select {
                font-size: 14px; /* Reduce font size for smaller devices */
                padding: 8px 10px;
            }
            .filter-container input[type="text"] {
                width: 85%; /* Allow full width on small screens */
            }
            .filter-container select {
                width: 20%; /* Allow full width on small screens */
            }
        }
         .tag-container {
            text-align: center;
            height: 40px;
            background-image: url('{{ asset('frontend/img/background/backgr.jpg') }}');
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            margin-bottom: 15px;
            width: 100%;
        }

         .tag-container .centered-text {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-weight: bold;
        }
    </style>

</head>

<body>
    @include ('header_footer.header')
   @include ('chat.chat_window')
    
    <br><br><br><br> <br><br><br>

    @include ('banner')

    <br><br><br>
    <div class="filter-container">
        <div class="tag-container">
            <div class="centered-text"> Bộ lọc</div>
            <br>
        </div>
        
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
    @include ('product.product', ['type' => 'popular', 'currentProduct' => null])

    <br><br><br>

    @include ('header_footer.footer')

   
<!-- Include the products.js script -->

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
</body>


</html>
