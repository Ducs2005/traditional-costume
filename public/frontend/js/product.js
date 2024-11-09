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
                this.displayProducts(this.products); // Display the products on initial load
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    // Method to display products
    displayProducts(productsArray) {
        // Clear existing content in the product container
        this.productContainer.innerHTML = '';
        console.log(productsArray);

        // Loop through the products array and create product elements
        productsArray.forEach(product => {
            const productItem = document.createElement('div');
            productItem.classList.add('item', 'product-item');
        
            productItem.addEventListener('click', () => {
                window.location.href = `${baseUrl}/product_description/${product.id}`;
            });
        
            // Generate HTML for types by mapping over the types array
            const typesHtml = product.types && product.types.length > 0
                ? product.types.map(type => `<a href="/products/category/${type.name}" class="category-label">${type.name}</a>`).join(', ')
                : `<span class="category-label">No Types</span>`;
            const formattedPrice = Number(product.price).toLocaleString('vi-VN');
            console.log(formattedPrice);
            productItem.innerHTML = `
                <img src="frontend/img/product/${product.img_path}" alt="${product.name}">
                <h4>${product.name}</h4>
                    <div class="price">
                        ${formattedPrice} <span class="vnd-icon">₫</span>
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
                        <!-- Display all type names as links -->
                        ${typesHtml}
                    </div>
                </div>
            `;
        
            this.productContainer.appendChild(productItem);
        });
        

        // Update pagination controls
        this.updatePagination();
    }

    // Method to update pagination controls
    updatePagination() {
        // Clear existing pagination
        this.paginationContainer.innerHTML = '';

        const totalPages = Math.ceil(this.products.length / this.itemsPerPage);

        // Create previous button
        const prevButton = document.createElement('button');
        prevButton.innerText = 'Previous';
        prevButton.disabled = this.currentPage === 1;
        prevButton.classList.add('pagination-button');
        prevButton.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.displayProducts(this.products.slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage));
            }
        });
        this.paginationContainer.appendChild(prevButton);

        // Create page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.classList.add('page-btn', 'pagination-button');
            if (i === this.currentPage) {
                pageButton.disabled = true; // Disable button for current page
            }
            pageButton.addEventListener('click', () => {
                this.currentPage = i;
                this.displayProducts(this.products.slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage));
            });
            this.paginationContainer.appendChild(pageButton);
        }

        // Create next button
        const nextButton = document.createElement('button');
        nextButton.innerText = 'Next';
        nextButton.disabled = this.currentPage === totalPages;
        nextButton.classList.add('pagination-button');
        nextButton.addEventListener('click', () => {
            if (this.currentPage < totalPages) {
                this.currentPage++;
                this.displayProducts(this.products.slice((this.currentPage - 1) * this.itemsPerPage, this.currentPage * this.itemsPerPage));
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
}

pagination = new Pagination(9); // Display 9 products per page on load
    function filterProducts()
    {
        pagination.filterProducts();
    }