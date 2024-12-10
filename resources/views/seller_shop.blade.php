<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shopping Cart</title>

    <!-- Update CSS link with asset helper -->
    <link rel="stylesheet" href="{{ asset('frontend/css/view_cart.css') }}"> <!-- Link to the CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Thêm link CSS của Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <!-- Thêm script của Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


</head>

<!-- Include header and chat window -->
@include('header_footer.header')
@include('chat.chat_window')

<body>
    <!-- Modal for Adding Product -->


    <div id="requestModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Nhập thông tin</h2>
            <form id="requestForm" method="POST" action="{{ route('seller.request') }}">
                @csrf
                <input type="text" id="shop_name" name="shop_name"  required placeholder="Tên cửa hàng"></input>
                <input type="text" id="phone_number" name="phone_number" required placeholder="Số điện thoại">
                <!-- <input type="text" id="address" name="address"  required placeholder="Địa chỉ"></input> -->
                <select id="province" name="province" onchange="fetchDistricts(this.value)">
                    <option value="">Tỉnh/Thành phố</option>
                </select>

                <select id="district" name="district" onchange="fetchWards(this.value)">
                    <option value="">Quận/Huyện</option>
                </select>

                <select id="ward" name="ward">
                    <option value="">Xã/Phường</option>
                </select>



                <button type="submit" class="submit-btn">Gửi yêu cầu</button>
            </form>
        </div>
    </div>

    <br> <br> <br> <br> <br> <br>
    <div class="cart-container">
        <h1 class="cart-title">Cửa hàng của tôi</h1>

        @if($selling_right == 'no')

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Đã bán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="info" colspan="5" class="product" style="text-align: center;">
                        Bạn chưa có quyền bán sản phẩm   <span style="margin-left: 10px;"> <button class="checkout-btn" style="font-size: medium;" onclick="openModal()">Yêu cầu bán sản phẩm</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        @elseif($selling_right == 'waiting')
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Đã bán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="info" colspan="5" class="product" style="text-align: center;">
                        Yêu cầu mở cửa hàng của bạn đang được phê duyệt.
                    </td>
                </tr>
            </tbody>
        </table>
        <br> <br>  <br> <br>  <br> <br>  <br> <br>  <br> <br>  <br> <br>

        @else
            <div id="addProductModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeAddProductModal()">&times;</span>
                    <h2>Thêm sản phẩm mới</h2>
                    <form id="addProductForm" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <!-- Product Name -->
                        <input type="text" id="name" name="name" required placeholder="Tên sản phẩm" />

                        <!-- Product Description -->
                        <textarea id="description" name="description" required placeholder="Mô tả sản phẩm"></textarea>
                        <!-- Product Price -->
                        <input type="number" id="price" name="price" required placeholder="Đơn giá" />

                        <!-- Product Quantity -->
                        <input type="number" id="quantity" name="quantity" required placeholder="Số lượng" />

                        <!-- Product Color (Option Box) -->
                        <select style="width:22%" id="color" name="color" required>
                            <option value="">Chọn màu</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>

                        <!-- Product Button (Option Box) -->
                        <select style="width:22%" id="button" name="button" required>
                            <option value="">Chọn nút</option>
                            @foreach ($buttons as $button)
                                <option value="{{ $button->id }}">{{ $button->name }}</option>
                            @endforeach
                        </select>

                        <!-- Product Type (Option Box) -->
                        <select style="width:23%" id="type" name="type" required>
                            <option value="">Chọn loại</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <select style="width:23%" id="material" name="material" required>
                            <option value="">Chọn chất liệu</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                            @endforeach
                        </select>

                        <!-- Product Representative Image (display after selection) -->
                        <div id="representativePreview" style="width: 50%; float: left;">
                            <img id="representativeImg" style="width: 100%; display: none;" />
                        </div>

                        <!-- Product Detail Images (display after selection) -->
                        <div id="detailPreview" style="width: 100%; float: left; display: flex; flex-wrap: wrap; gap: 10px;">
                            <!-- Images will be displayed here -->
                        </div>

                        <!-- Input for Representative Image -->
                        <input type="file" id="representative_img" name="representative_img" accept="image/*" required onchange="loadImageForCrop(event)" />

                        <!-- Input for Detail Images -->
                        <input type="file" id="detail_imgs" name="detail_imgs[]" accept="image/*" multiple required onchange="previewDetailImages(event)" />

                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn">Thêm sản phẩm</button>
                    </form>
                </div>
            </div>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Đã bán</th>
                        <th>Kho</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                        @forEach($products as $product)
                        <tr>
                            <td class="product">
                                <!-- Wrap product name and image with a link to the product description page -->
                                <a style="text-decoration: none; color: black;" href="{{ url('/product_description/' . $product->id) }}">
                                    <img src="{{ asset( $product->img_path) }}" alt="Product Image">
                                    <span>{{ $product->name }}</span>
                                </a>
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td>
                                <input type="number" name="sold" value="{{ $product->sold }}" min="0" class="quantity-input" required readonly>
                            </td>
                            <td> <input type="number" name="quantity" value="{{ $product->quantity }}" min="1" class="quantity-input" required readonly></td>
                            <td class="action">
                                <!-- Form to remove item -->
                                <form action="{{ route('shop.remove', $product->id) }}" method="POST" class="remove-item-form" onsubmit="confirmDelete(event)" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn">Xóa</button>
                                </form>



                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-summary">
                <p>Thu nhập: <strong id="income"></strong></p>
                <button class="checkout-btn" style="background-color: green;" onclick="openAddProductModal()">Thêm sản phẩm</button>
                <button class="checkout-btn" onclick="requestSelling()">Nhận</button>

            </div>
        @endif
    </div>


<script>
        // Open the modal
    function openAddProductModal() {
        document.getElementById('addProductModal').style.display = 'flex';
    }

    // Close the modal
    function closeAddProductModal() {
        document.getElementById('addProductModal').style.display = 'none';
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target === document.getElementById('addProductModal')) {
            closeAddProductModal();
        }
    }
        // Fetch Provinces
    fetch('https://vapi.vnappmob.com/api/province/')
        .then(res => {
            if (!res.ok) {
                throw new Error("Failed to fetch provinces.");
            }
            return res.json();
        })
        .then(data => {
            const provinceSelect = document.getElementById('province');
            data.results.forEach(province => {
                provinceSelect.innerHTML += `<option value="${province.province_id}">${province.province_name}</option>`;
            });
        })
        .catch(error => {
            console.error("Error fetching provinces:", error);
        });

    // Fetch Districts based on Selected Province
    function fetchDistricts(provinceId) {
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        districtSelect.innerHTML = '<option value="">Quận/Huyện</option>'; // Reset districts
        wardSelect.innerHTML = '<option value="">Xã/Phường</option>'; // Reset wards

        if (!provinceId) return; // Do nothing if no province is selected

        fetch(`https://vapi.vnappmob.com/api/province/district/${provinceId}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error("Failed to fetch districts.");
                }
                return res.json();
            })
            .then(data => {
                data.results.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district.district_id}">${district.district_name}</option>`;
                });
            })
            .catch(error => {
                console.error("Error fetching districts:", error);
            });
    }

    // Fetch Wards based on Selected District
    function fetchWards(districtId) {
        const wardSelect = document.getElementById('ward');
        wardSelect.innerHTML = '<option value="">Xã/Phường</option>'; // Reset wards

        if (!districtId) return; // Do nothing if no district is selected

        fetch(`https://vapi.vnappmob.com/api/province/ward/${districtId}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error("Failed to fetch wards.");
                }
                return res.json();
            })
            .then(data => {
                data.results.forEach(ward => {
                    wardSelect.innerHTML += `<option value="${ward.ward_id}">${ward.ward_name}</option>`;
                });
            })
            .catch(error => {
                console.error("Error fetching wards:", error);
            });
    }




    function openModal() {
        document.getElementById('requestModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('requestModal').style.display = 'none';
    }
    let income = 0;
    const products  = @json($products);
    products.forEach(product => {
        income +=product.price *product.sold;
    });
    function formatPrice(amount) {
        return amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replace('₫', '').trim();
    }

    document.getElementById('income').textContent = formatPrice(income);

    function confirmDelete(event) {
        // Prevent form submission
        event.preventDefault();

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Sản phẩm sẽ bị xóa khỏi cửa hàng!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                event.target.submit();
            }
        });
    }



    // Khi chọn tỉnh -> Lấy huyện
    document.getElementById('province').addEventListener('change', function () {
        const provinceId = this.value;
        fetch(`https://vapi.vnappmob.com/api/province/district/${provinceId}`)
            .then(res => res.json())
            .then(data => {
                const districtSelect = document.getElementById('district');
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                data.results.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district.district_id}">${district.district_name}</option>`;
                });
                districtSelect.disabled = false;
            });
    });

    // Khi chọn huyện -> Lấy xã
    document.getElementById('district').addEventListener('change', function () {
        const districtId = this.value;
        fetch(`https://vapi.vnappmob.com/api/province/ward/${districtId}`)
            .then(res => res.json())
            .then(data => {
                const wardSelect = document.getElementById('ward');
                wardSelect.innerHTML = '<option value="">Chọn Xã/Phường</option>';
                data.results.forEach(ward => {
                    wardSelect.innerHTML += `<option value="${ward.ward_id}">${ward.ward_name}</option>`;
                });
                wardSelect.disabled = false;
            });
    });


    function updateTable(products) {
        const tableBody = document.querySelector('.cart-table tbody');
        tableBody.innerHTML = ''; // Clear the current table content
        if (products)
        {
            products.forEach(product => {
                const row = `
                    <tr>
                        <td class="product">
                            <a style="text-decoration: none; color: black;" href="/product_description/${product.id}">
                                <img src="${product.img_path}" alt="Product Image">
                                <span>${product.name}</span>
                            </a>
                        </td>
                        <td>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.price)}</td>
                        <td>
                            <input type="number" name="sold" value="${product.sold}" min="0" class="quantity-input" readonly>
                        </td>
                        <td>
                            <input type="number" name="quantity" value="${product.quantity}" min="1" class="quantity-input" readonly>
                        </td>
                        <td class="action">
                            <form action="/shop/remove/${product.id}" method="POST" class="remove-item-form" onsubmit="confirmDelete(event)" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

        }
        // Update income
        const income = products.reduce((sum, product) => sum + product.price * product.sold, 0);
        document.getElementById('income').textContent = formatPrice(income);
    }

    function formatPrice(amount) {
        return amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replace('₫', '').trim();
    }
    function requestSelling() {
        fetch('{{ route("resetSold") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ seller_id: {{ auth()->id() }} })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: 'Thu nhập sẽ được chuyển về tài khoản của bạn trong thời gian sớm nhất.',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Update the table data using AJAX
                updateTable(data.products); // Pass the updated products from the response
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể nhận, lỗi không xác định.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Đã xảy ra lỗi khi gửi yêu cầu.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    let cropper;

    function loadImageForCrop(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgElement = document.createElement('img');
            imgElement.src = e.target.result;

            // Chờ ảnh load xong thì tạo Cropper
            imgElement.onload = function() {
                const previewContainer = document.getElementById('representativePreview');
                previewContainer.innerHTML = ''; // Xóa ảnh cũ nếu có
                previewContainer.appendChild(imgElement);

                // Cài đặt cropper với tỷ lệ 3:4
                cropper = new Cropper(imgElement, {
                    aspectRatio: 3 / 4, // Cắt theo tỷ lệ 3:4
                    viewMode: 1, // Cắt ảnh trong vùng xem được
                    autoCropArea: 1,
                    movable: false,
                    scalable: false,
                    crop: function(event) {
                        // Preview ảnh đã cắt trong vùng preview
                        const canvas = cropper.getCroppedCanvas();
                        const croppedImage = canvas.toDataURL(); // Chuyển ảnh đã cắt thành DataURL
                        document.getElementById('representativeImg').src = croppedImage; // Hiển thị ảnh đã cắt trong preview
                        document.getElementById('representativeImg').style.display = 'block'; // Hiển thị ảnh
                    }
                });
            };
        };
        reader.readAsDataURL(file);
    }

    // Lấy ảnh đã cắt và convert sang file trước khi submit


    // Gắn sự kiện submit form để gửi hình ảnh đã cắt

    // Preview the detail images with a 3:4 aspect ratio
    function previewDetailImages(event) {
        const files = event.target.files;
        const detailPreviewContainer = document.getElementById("detailPreview");
        const formData = new FormData(document.getElementById('addProductForm'));

        // Clear previous previews
        detailPreviewContainer.innerHTML = "";

        // Loop through the selected files
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            const file = files[i];

            reader.onload = function (e) {
                const img = new Image();
                img.src = e.target.result;

                // Once the image is loaded, we can adjust the size
                img.onload = function () {
                    const aspectRatio = 3 / 4; // 3:4 aspect ratio
                    const imgWidth = img.width;
                    const imgHeight = img.height;

                    let cropWidth = imgWidth;
                    let cropHeight = cropWidth / aspectRatio;

                    if (cropHeight > imgHeight) {
                        cropHeight = imgHeight;
                        cropWidth = cropHeight * aspectRatio;
                    }

                    const cropX = (imgWidth - cropWidth) / 2;
                    const cropY = (imgHeight - cropHeight) / 2;

                    // Create a canvas to crop the image
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");

                    canvas.width = cropWidth;
                    canvas.height = cropHeight;

                    // Draw the cropped image
                    ctx.drawImage(img, cropX, cropY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

                    // Create an img element to display the cropped image
                    const croppedImg = new Image();
                    croppedImg.src = canvas.toDataURL();

                    // Append the image to the preview container
                    detailPreviewContainer.appendChild(croppedImg);

                    // Convert the cropped image to a Blob and add it to the FormData
                    canvas.toBlob(function(blob) {
                        formData.append('detail_imgs[]', blob, 'detail_img_' + i + '.jpg');
                    });
                };
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Once the form is submitted, the formData will include the cropped detail images
        document.getElementById('addProductForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            const canvas = cropper.getCroppedCanvas();
            canvas.toBlob(function(blob) {
                const formData = new FormData(document.getElementById('addProductForm'));
                formData.set('representative_img', blob, 'representative_img.jpg');

                // Send the form data to the server
                fetch("{{ route('product.store') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to add product.");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: 'Sản phẩm đã được thêm vào cửa hàng!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Close the modal and reset form values
                            closeAddProductModal();  // Close the modal
                            document.getElementById('addProductForm').reset();  // Reset the form
                            document.getElementById('representativePreview').innerHTML = '';  // Clear crop preview
                            document.getElementById('detailPreview').innerHTML = '';  // Clear detail image previews
                            location.reload();
                        });
                    } else {
                        // Show error SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể thêm sản phẩm, vui lòng thử lại.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi gửi yêu cầu.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });
        });
    }



</script>

</body>

<br> <br> <br>

<!-- Include footer -->
@include('header_footer.footer')

</html>
