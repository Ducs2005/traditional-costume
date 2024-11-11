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
    <a href="" class="avatar-button">Change Avatar</a>

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
        <a href="#" class="change-password-link">Change Password</a>
    </div>

    <div class="cart-summary">
        <p class="cart-text">Bạn đang có  <strong>{{ $cartItemCount }} sản phẩm</strong> trong giỏ hàng.</p>
        <a href="{{ url('/view_cart') }}" class="cart-button">Xem giỏ hàng</a>
    </div>
</div>

</body>

<br> <br><br> <br>
@include('header_footer.footer') 

</html>
