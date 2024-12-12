<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <link rel="stylesheet" href="{{asset('frontend/css/account.css')}}"> <!-- Link to the CSS file -->
    <style> /* Modal container */
#imageModal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* On top of other elements */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.6); /* Black with opacity */
}

/* Modal content box */
.modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* Center vertically */
    padding: 20px;
    border-radius: 8px;
    width: 80%; /* 80% of the screen width */
    max-width: 500px; /* Maximum width */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease-in-out; /* Fade-in animation */
}

/* Close button */
.close-btn {
    color: #aaa;
    float: right;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close-btn:hover {
    color: #000;
}

/* Modal title */
.modal-content h2 {
    margin-top: 0;
    font-size: 20px;
    color: #333;
}

/* File input styling */
#imageInput {
    margin: 10px 0;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
}

/* Preview container */
#selectedImagePreview {
    margin: 10px 0;
    text-align: center;
}

#selectedImagePreview img {
    width: 100px; /* Fixed width */
    height: 100px;
    border-radius: 50%;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Submit button */
#submitImageBtn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#submitImageBtn:hover {
    background-color: #0056b3;
}

/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
#changePasswordForm {
    display: none; /* Đảm bảo form ẩn ban đầu */
    margin-top: 20px;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Tạo bóng đổ */
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    border: 1px solid #ddd; /* Viền mờ cho form */
    animation: fadeIn 0.3s ease-in-out;
}

.form-group-change {
    margin-bottom: 20px;
}

.form-group-change label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    display: block;
    margin-bottom: 8px;
}

.form-group-change input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
    box-sizing: border-box;
    transition: border 0.3s ease;
}

.form-group-change input:focus {
    border-color: #007bff; /* Thay đổi màu viền khi focus */
    outline: none;
}

#changePasswordForm .btn-primary {
    background-color: #500000;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%; /* Đảm bảo nút bấm chiếm đầy chiều rộng */
}

#changePasswordForm .btn-primary:hover {
    background-color: #b50202;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}


</style>
</head>

@include('header_footer.header')
@include('chat.chat_window')

<body>
<br> <br> <br> <br><br> <br>

<div class="account-container">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h1 class="account-title">{{Auth::user()->name}}</h1>
    <img src="{{ url(Auth::user()->avatar) }}" alt="User Avatar" class="account-avatar">
    <div class="avatar-actions">
    <a href="" class="avatar-button" id="changeAvatar">Đổi ảnh đại diệndiện</a>

    <form action="{{ route('account.logout')}}" style="display: inline;">
        <button type="submit" class="logout-button">Đăng xuất</button>
    </form>
</div>

    <div class="account-info">
        <label class="info-label">Name:</label>
        <p class="info-value">{{Auth::user()->name}}</p>
    </div>

    <div class="account-info">
            <label class="info-label">Email:</label>
            <p class="info-value">{{Auth::user()->email}}</p>
        </div>
        <div class="account-info">
        <label class="info-label">Password:</label>
        <p class="info-value">********</p>
        <a href="#" class="change-password-link" id="changePasswordLink">Đổi mật khẩu</a>
    </div>
    <div id="changePasswordForm" style="display: none;">
        <form action="{{ route('change.password.submit') }}" method="POST">
            @csrf
            <div class="form-group-change">
                <label for="current_password">Mật khẩu cũ</label>
                <input type="password" name="current_password" id="current_password" required class="form-control">
            </div>

            <!-- Mật khẩu mới -->
            <div class="form-group-change">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" name="new_password" id="new_password" required class="form-control">
            </div>

            <!-- Xác nhận mật khẩu mới -->
            <div class="form-group-change">
                <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
        </form>
    </div>
    <!-- Modal for Image Selection -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h2>Select an Image</h2>
            <input type="file" id="imageInput" accept="image/*">
            <div id="selectedImagePreview"></div>
            <button id="submitImageBtn">Submit</button>
        </div>
    </div>



    <div class="cart-summary">
        <p class="cart-text">Bạn đang bán  <strong>{{ $shopItemCount }} sản phẩm</strong> trong cửa hàng của mình.</p>
        <a href="{{ url('/own_shop') }}" class="cart-button">Xem cửa hàng</a>
    </div>
</div>


</body>

<!-- Link to FilePond CSS -->
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />

<!-- FilePond JavaScript -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<script>
document.getElementById("changePasswordLink").addEventListener("click", function (e) {
    e.preventDefault();
    const changePasswordForm = document.getElementById("changePasswordForm");
    if (changePasswordForm.style.display === "none") {
        changePasswordForm.style.display = "block";
    } else {
        changePasswordForm.style.display = "none";
    }
});

    // Get modal and elements
const modal = document.getElementById("imageModal");
const changeAvatar = document.getElementById("changeAvatar");
const closeModalBtn = document.getElementById("closeModalBtn");
const imageInput = document.getElementById("imageInput");
const selectedImagePreview = document.getElementById("selectedImagePreview");
const submitImageBtn = document.getElementById("submitImageBtn");

// Open the modal when the "Change Password" link is clicked
changeAvatar.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent default link behavior
    modal.style.display = "block"; // Show the modal
});

// Close the modal when the close button is clicked
closeModalBtn.addEventListener("click", function () {
    modal.style.display = "none"; // Hide the modal
});

// Close the modal if the user clicks outside of the modal content
window.addEventListener("click", function (e) {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});

// Handle image selection
imageInput.addEventListener("change", function () {
    const file = imageInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            selectedImagePreview.innerHTML = `<img src="${e.target.result}" alt="Selected Image">`; // Show image preview
        };
        reader.readAsDataURL(file);
    }
});

submitImageBtn.addEventListener("click", function () {
    const file = imageInput.files[0];
    if (!file) {
        alert("Please select an image before submitting.");
        return;
    }

    const formData = new FormData();
    formData.append("avatar", file);

    fetch("{{ route('update-avatar') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content, // Laravel CSRF Token
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Avatar updated successfully!");
                modal.style.display = "none";
                // Update the image source to use the returned file path
                document.querySelector(".account-avatar").src = `/${data.avatar}`;
                location.reload();
            } else {
                alert("Error updating avatar: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An unexpected error occurred.");
        });
});



 </script>

<br> <br><br> <br>
@include('header_footer.footer')

</html>
