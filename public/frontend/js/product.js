class Pagination {
    constructor(itemsPerPage) {
        this.products = []; // Empty array to hold products fetched from the server
        this.itemsPerPage = itemsPerPage; // Number of items per page
        this.currentPage = 1; // Start on the first page
        this.productContainer = document.querySelector('.list-item');
        this.paginationContainer = document.querySelector('.pagination');

        // Fetch products from the server and initialize the display
        
        this.fetchProducts();
        displayProducts() ;
    }

    // Fetch products from the server
    fetchProducts() {
        // Use a dynamically set URL for the API
        const apiUrl = 'http://localhost/traditional_costume/public/api/products'; // Set the API URL dynamically in your Blade template

        fetch(apiUrl)
            
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`); // Check for HTTP errors
                }
                return response.json();
            })
            .then(data => {

                this.products = data; // Assign the fetched products to the array
                console.log(data);
                this.displayProducts();
            })
            
            .catch(error => console.error('Error fetching products:', error));
    }

    // Method to display products for the current page
    displayProducts() {
        // Clear existing content in the product container
        this.productContainer.innerHTML = '';

        // Calculate start and end indices for the current page
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;

        // Get the products for the current page
        const currentProducts = this.products.slice(startIndex, endIndex);

        // Loop through the current products array and create product elements
        currentProducts.forEach(product => {
            const productItem = document.createElement('div');
            productItem.classList.add('item');

            // Adding a click event to navigate to the product details page
            productItem.addEventListener('click', () => {
                window.location.href = `/traditional_costume/public/product_description/${product.id}`; // Ensure this matches your route
            });
            
            
            productItem.innerHTML = `
            <img src="frontend/img/product/${product.img_path}" alt="${product.name}">
            <h4>${product.name}</h4>
            <p>${product.price}</p>
            <div class="product-attributes">
                <div class="attribute-group">
                    <a href="/products/category/${product.color}" class="category-label">${product.color}</a>
                </div>
                <div class="attribute-group">
                    <a href="/products/category/${product.material}" class="category-label">${product.material}</a>
                </div>
                <div class="attribute-group">
                    <a href="/products/category/${product.button}" class="category-label">${product.button}</a>
                </div>
            </div>
        `;
            // Append each product item to the product container
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
        prevButton.classList.add('pagination-button'); // Add a class for styling
        prevButton.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.displayProducts();
            }
        });
        this.paginationContainer.appendChild(prevButton);

        // Create page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.classList.add('page-btn', 'pagination-button'); // Add a class for styling
            if (i === this.currentPage) {
                pageButton.disabled = true; // Disable button for current page
            }
            pageButton.addEventListener('click', () => {
                this.currentPage = i;
                this.displayProducts();
            });
            this.paginationContainer.appendChild(pageButton);
        }

        // Create next button
        const nextButton = document.createElement('button');
        nextButton.innerText = 'Next';
        nextButton.disabled = this.currentPage === totalPages;
        nextButton.classList.add('pagination-button'); // Add a class for styling
        nextButton.addEventListener('click', () => {
            if (this.currentPage < totalPages) {
                this.currentPage++;
                this.displayProducts();
            }
        });
        this.paginationContainer.appendChild(nextButton);
    }
}
document.addEventListener("DOMContentLoaded", () => {
    new Pagination(9); // Display 9 products per page
});