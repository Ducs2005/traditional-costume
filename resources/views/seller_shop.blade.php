@extends('layout_home')
@section('content_homePage')
<br> <br> <br> <br> <br> <br>

<div id="requestModal" class="modal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Nhập thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="requestForm" method="POST" action="{{ route('seller.request') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="shop_name" class="form-label">Tên cửa hàng</label>
                        <input type="text" id="shop_name" name="shop_name" class="form-control" required placeholder="Tên cửa hàng">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Số điện thoại</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" required placeholder="Số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label for="province" class="form-label">Tỉnh/Thành phố</label>
                        <select id="province" name="province" class="form-select" onchange="fetchDistricts(this.value)">
                            <option value="">Chọn Tỉnh/Thành phố</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">Quận/Huyện</label>
                        <select id="district" name="district" class="form-select" onchange="fetchWards(this.value)">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ward" class="form-label">Xã/Phường</label>
                        <select id="ward" name="ward" class="form-select">
                            <option value="">Chọn Xã/Phường</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h1 class="text-center cart-title">Cửa hàng của tôi</h1>

    @if($selling_right == 'no')
        <div class="alert alert-warning text-center" role="alert">
            Bạn chưa có quyền bán sản phẩm.
            <button class="btn btn-primary mt-2" onclick="openModal()">Yêu cầu bán sản phẩm</button>
        </div>
    @elseif($selling_right == 'waiting')
        <div class="alert alert-info text-center" role="alert">
            Yêu cầu mở cửa hàng của bạn đang được phê duyệt.
        </div>
    @else
    <div id="addProductModal" class="modal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 80%; width: 80%; margin-left: 25%; margin-right: 20%;"> <!-- Add modal-dialog-centered to center the modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <form id="addProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="text" id="name" name="name" class="form-control" style="width: 94%" required placeholder="Tên sản phẩm">
                    </div>
                    <div class="mb-3">
                        <textarea id="description" name="description" class="form-control" style="width: 94%"  required placeholder="Mô tả sản phẩm"></textarea>
                    </div>
                    <div class="mb-3 d-flex">
                        <input type="number" id="price" name="price" class="form-control me-3" style="width: 46%" required placeholder="Đơn giá">
                        <input type="number" id="quantity" name="quantity" class="form-control" style="width: 46%" required placeholder="Số lượng">
                    </div>
                    <div class="mb-3">
                        <select id="color" name="color" class="form-select"  style="width: 94%" required>
                            <option value="">Chọn màu</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select id="button" name="button" class="form-select"  style="width: 94%" required>
                            <option value="">Chọn nút</option>
                            @foreach ($buttons as $button)
                                <option value="{{ $button->id }}">{{ $button->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select id="type" name="type" class="form-select"  style="width: 94%" required>
                            <option value="">Chọn loại</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select id="material" name="material" class="form-select"  style="width: 94%" required>
                            <option value="">Chọn chất liệu</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="representativePreview" style="width: 50%; float: left;">
                            <img id="representativeImg" style="width: 100%; display: none;" />
                        </div>

                        <!-- Product Detail Images (display after selection) -->
                    <div id="detailPreview" style="width: 100%; float: left; display: flex; flex-wrap: wrap; gap: 10px;">
                        <!-- Images will be displayed here -->
                    </div>
                    <div class="mb-3">
                        <label for="representative_img" class="form-label">Hình đại diện</label>
                        <input type="file" id="representative_img" name="representative_img" class="form-control" accept="image/*" required onchange="loadImageForCrop(event)">
                    </div>
                    <div class="mb-3">
                        <label for="detail_imgs" class="form-label">Hình chi tiết</label>
                        <input type="file" id="detail_imgs" name="detail_imgs[]" class="form-control" accept="image/*" multiple required onchange="previewDetailImages(event)">
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
</div>


        <table class="table table-striped">
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
                @foreach($products as $product)
                    <tr>
                        <td>
                            <a href="{{ url('/product_description/' . $product->id) }}" style="text-decoration: none; color: black;">
                                <img src="{{ asset($product->img_path) }}" alt="Product Image" class="img-thumbnail" style="width: 50px; height: 50px;">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                        <td>
                            <input type="number" name="sold" value="{{ $product->sold }}" min="0" class="form-control" required readonly>
                        </td>
                        <td>
                            <input type="number" name="quantity" value="{{ $product->quantity }}" min="1" class="form-control" required readonly>
                        </td>
                        <td style="display: flex;">
                        <form action="{{ route('shop.remove', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                        <!-- Button to open the modal -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">Xem chi tiết</button>
                    </td>

    <!-- Modal for editing product -->
    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action={{ route('seller.update', ['id' => $product->id]) }} method="POST" id="updateProductForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="text" id="name{{ $product->id }}" name="name" class="form-control" style="width: 94%" value="{{ $product->name }}" required>
                        </div>
                        <div class="mb-3">
                            <textarea id="description{{ $product->id }}" name="description" class="form-control" style="width: 94%" required>{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-3" style="display:flex;">
                            <input type="number" id="price{{ $product->id }}" name="price" class="form-control" style="width: 46% ; margin-right: 1%;" value="{{ $product->price }}" required>
                        
                            <input type="number" id="quantity{{ $product->id }}" name="quantity" class="form-control" style="width: 46%" value="{{ $product->quantity }}" required>
                        </div>
                        <div class="mb-3">
                            <select id="color{{ $product->id }}" name="color" class="form-select" style="width: 94%" required>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}" {{ $product->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select id="button{{ $product->id }}" name="button" class="form-select" style="width: 94%" required>
                                @foreach ($buttons as $button)
                                    <option value="{{ $button->id }}" {{ $product->button_id == $button->id ? 'selected' : '' }}>{{ $button->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select id="type{{ $product->id }}" name="type" class="form-select" style="width: 94%" required>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" {{ $product->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select id="material{{ $product->id }}" name="material" class="form-select" style="width: 94%" required>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" {{ $product->material_id == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="representativePreview" style="width: 50%; float: left;">
                            <img id="representativeImg" style="width: 100%; display: none;" />
                        </div>
                        <div class="mb-3">
                            <input type="file" id="representative_img{{ $product->id }}" name="representative_img" class="form-control" accept="image/*" onchange="loadImageForCrop(event)">
                        </div>
                        <div class="mb-3">
                            <input type="file" id="detail_imgs{{ $product->id }}" name="detail_imgs[]" class="form-control" accept="image/*" multiple onchange="previewDetailImages(event)">
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <p><strong>Thu nhập:</strong> <span id="income"></span></p>
            <div>
                <button class="btn btn-success" onclick="openAddProductModal()">Thêm sản phẩm</button>
                <button class="btn btn-info" onclick="requestSelling()">Nhận</button>
            </div>
        </div>
        <td style="display: flex;">
   


    @endif
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

<!-- Thêm script của Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
        // Open the modal
    function openAddProductModal() {
        document.getElementById('addProductModal').style.display = 'flex';
        document.getElementById('addProductModal').setAttribute('aria-hidden', 'false');
        document.body.setAttribute('aria-hidden', 'true');  
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

@endsection