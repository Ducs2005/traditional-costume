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
</style>
</head>

@include('header_footer.header')
@include('chat.chat_window') 

<body>
<br> <br> <br> <br><br> <br>
<div class="account-container">
    <h1 class="account-title">{{Auth::user()->name}}</h1>
    <img src="{{ url(Auth::user()->avatar) }}" alt="User Avatar" class="account-avatar">
    <div class="avatar-actions">
    <a href="" class="avatar-button" id="changeAvatar">Change Avatar</a>

    <form action="{{ route('account.logout')}}" style="display: inline;">
        <button type="submit" class="logout-button">Log Out</button>
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
        <a href="#" class="change-password-link" id="changePasswordLink">Change Password</a>
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


<script> 

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
