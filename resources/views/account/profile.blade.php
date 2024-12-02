<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <link rel="stylesheet" href="{{asset('frontend/css/account.css')}}"> <!-- Link to the CSS file -->
</head>

@include('header_footer.header')
@include('chat.chat_window') 

<body>
<br> <br> <br> <br><br> <br>
<div class="account-container">
    <h1 class="account-title">{{Auth::user()->name}}</h1>
    <img src="{{asset('frontend/img/background/background.jpg')}}" alt="User Avatar" class="account-avatar">
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

// Handle submit image
submitImageBtn.addEventListener("click", function () {
    // You can add logic to send the image to the server or update the user profile here
    alert("Image selected! You can now save the changes.");
    modal.style.display = "none"; // Close the modal
});


 </script>

<br> <br><br> <br>
@include('header_footer.footer') 

</html>
