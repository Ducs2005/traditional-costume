
class Pagination {

    constructor(itemsPerPage) {
        this.products = []; // Array to hold products fetched from the server
        this.itemsPerPage = itemsPerPage; // Number of items per page
        this.currentPage = 1; // Start on the first page
        this.productContainer = document.querySelector('.list-item');
        this.paginationContainer = document.querySelector('.pagination');

        // Ensure that the elements exist before adding event listeners
        this.initEventListeners();

        // Fetch products from the server and initialize the display
        this.fetchProducts();
    }

    // Initialize event listeners for filtering
    initEventListeners() {
        const filterButton = document.getElementById('filterButton');
        if (filterButton) {
            filterButton.addEventListener('click', () => {
                this.filterProducts(); // Call the filter function on button click
            });
        }
    }

    // Fetch products from the server
    fetchProducts() {
        const apiUrl = `${baseUrl}/api/products`; // Set the API URL dynamically in your Blade template

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                this.products = data; // Assign the fetched products to the array
                this.displayProducts(type, currentProduct); // Display the products on initial load
                this.updatePagination();
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    // Method to display products
    displayProducts(type = null, currentProduct = null) {
        // Clear existing content in the product container
        let filteredProducts = this.products;
        if (type === 'popular') {
            // Sort products by the sold field in descending order
            filteredProducts = this.products.slice().sort((a, b) => b.sold - a.sold);
            document.getElementById('listType').textContent = 'Bán chạy nhất';
        } else if (type === 'related' && currentProduct) {
            // Filter products related to the current product
            document.getElementById('listType').textContent = 'Liên quan';
            filteredProducts = this.products.filter(product => {
                return (
                    product.id !== currentProduct.id && // Exclude the current product
                    (
                        product.type?.id === currentProduct.type?.id
                    )
                );
            });
        }
        this.productContainer.innerHTML = '';
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = this.currentPage * this.itemsPerPage;
        const paginatedProducts = filteredProducts.slice(start, end);

        this.productContainer.innerHTML = ''; // Clear existing content

        if (paginatedProducts.length === 0) {
            this.productContainer.innerHTML = '<p>No products to display.</p>';
            return;
        }
        // Check if any products match the criteria
        if (filteredProducts.length === 0) {
            this.productContainer.innerHTML = '<p>No products to display.</p>';
            return;
        }

        // Loop through the filtered products and create product elements
        paginatedProducts.forEach(product => {
            const productItem = document.createElement('div');
            productItem.classList.add('item', 'product-item');

            productItem.addEventListener('click', () => {
                window.location.href = `${baseUrl}/product_description/${product.id}`;
            });
            const formattedPrice = Number(product.price).toLocaleString('vi-VN');
            // Generate product item HTML
            const imageUrl = assetBaseUrl + '/' + product.img_path;
            productItem.innerHTML = `
                <img src="${imageUrl}" alt="${product.name}">
                <h4>${product.name}</h4>
                <div class="price-rating">
                    <div class="price">
                        ${formattedPrice} <span class="vnd-icon">₫</span>
                    </div>
                    <div class="rate">
                        ${product.average_rating} /5 <i class="fa-solid fa-star" style="color: gold;"></i> (${product.number_of_ratings} đã đánh giá)
                    </div>
                </div>


                <div class="product-attributes">
                    <div class="attribute-group">
                        <a href="/products/category/${product.color ? product.color.name : 'No Color'}" class="category-label">
                            ${product.color ? product.color.name : 'No Color'}
                        </a>
                    </div>
                    <div class="attribute-group">
                        <a href="/products/category/${product.material ? product.material.name : 'No Material'}" class="category-label">
                            ${product.material ? product.material.name : 'No Material'}
                        </a>
                    </div>
                    <div class="attribute-group">
                        <a href="/products/category/${product.button ? product.button.name : 'No Button'}" class="category-label">
                            ${product.button ? product.button.name : 'No Button'}
                        </a>
                    </div>
                    <div class="attribute-group">
                        <a href="/products/category/${product.type ? product.type.name : 'No Type'}" class="category-label">
                            ${product.type ? product.type.name : 'No Type'}
                        </a>
                    </div>
                </div>
            `;

            this.productContainer.appendChild(productItem);
        });
    }


    // Method to update pagination controls
    updatePagination() {
        this.paginationContainer.innerHTML = ''; // Clear existing controls

        const totalPages = Math.ceil(this.products.length / this.itemsPerPage);

        // Create "Previous" button
        const prevButton = document.createElement('button');
        prevButton.classList.add('pagination-button');

        prevButton.innerText = 'Previous';
        prevButton.disabled = this.currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.displayProducts();
                this.updatePagination();
                window.scrollTo({ top: 300, behavior: 'smooth' });

            }
        });
        this.paginationContainer.appendChild(prevButton);

        // Create page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.classList.add('pagination-button');
            if (i === this.currentPage) {
                pageButton.disabled = true; // Disable the button for the current page
            }
            pageButton.addEventListener('click', () => {
                this.currentPage = i;
                this.displayProducts();
                this.updatePagination();
                window.scrollTo({ top: 300, behavior: 'smooth' });

            });
            this.paginationContainer.appendChild(pageButton);
        }

        // Create "Next" button
        const nextButton = document.createElement('button');
        nextButton.classList.add('pagination-button');

        nextButton.innerText = 'Next';
        nextButton.disabled = this.currentPage === totalPages;
        nextButton.addEventListener('click', () => {
            if (this.currentPage < totalPages) {
                this.currentPage++;
                this.displayProducts();
                this.updatePagination();
                window.scrollTo({ top: 300, behavior: 'smooth' });

            }
        });
        this.paginationContainer.appendChild(nextButton);
    }

    // Function to filter products based on selected criteria
    filterProducts() {
        // Get the selected values from the dropdowns
        const colorId = document.getElementById('colorSelect').value;
        const materialId = document.getElementById('materialSelect').value;
        const buttonId = document.getElementById('buttonSelect').value;
        const sortOrder = document.getElementById('buttonSort').value;
        const searchQuery = document.getElementById('productSearch').value;
        const minPrice = parseInt(document.getElementById('minPrice').value, 10);
        const maxPrice = parseInt(document.getElementById('maxPrice').value, 10);

        // Create an object to hold the filter parameters
        const filters = {
            color_id: colorId,
            material_id: materialId,
            button_id: buttonId,
            sort_order: sortOrder,
            search: searchQuery,
            price_min: minPrice,
            price_max: maxPrice,
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
            console.log(this.products);
            this.displayProducts(this.products.slice(0, this.itemsPerPage)); // Display the first page of filtered products
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });
    }
}

pagination = new Pagination(9); // Display 9 products per page on load
function filterProducts()
{
    pagination.filterProducts();
}

function updatePriceRange() {
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const priceLabel = document.getElementById('priceRangeLabel');
    const sliderTrack = document.querySelector('.slider-track');

    let minValue = parseInt(minPrice.value, 10);
    let maxValue = parseInt(maxPrice.value, 10);

    // Ensure the minimum knob doesn't exceed the maximum knob
    if (minValue >= maxValue) {
        minValue = maxValue - 1000;
        minPrice.value = minValue;
    }

    // Ensure the maximum knob doesn't go below the minimum knob
    if (maxValue <= minValue) {
        maxValue = minValue + 1000;
        maxPrice.value = maxValue;
    }

    // Update the label
    priceLabel.innerText = `Từ ${minValue.toLocaleString()}₫ đến ${maxValue.toLocaleString()}₫`;

    // Update the track fill
    const minPercent = (minValue / 100000) * 100;
    const maxPercent = (maxValue / 100000) * 100;
    sliderTrack.style.setProperty('--min', `${minPercent}%`);
    sliderTrack.style.setProperty('--max', `${maxPercent}%`);
    filterProducts();
}

document.addEventListener('DOMContentLoaded', () => {
    updatePriceRange();

});

