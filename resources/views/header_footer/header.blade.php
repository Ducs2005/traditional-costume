<!DOCTYPE html>
<html lang="en">
<?php //session_start();   ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Hurricane&family=Ibarra+Real+Nova:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/header.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Header</title>
</head>
<body>
    <header>
        <input type="checkbox" name="" id="chk1">
        <div class="logo"><a href="{{ url('/') }}"><img src="{{asset('frontend/img/logo.png')}}"></a></div>

        <div class="search-box">
            <form action="#" method="get">
                <input type="text" name="search" id="srch" placeholder="Search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="menubar">
            <ul>
                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                <li class="dropdown">
                    <a href="{{ url('/product_type') }}" class="dropdown-toggle">Cổ phục</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/product_type/ao-giao-linh') }}">Áo giao lĩnh</a></li>
                        <li><a href="{{ url('/product_type/ao-vien-linh') }}">Áo viên lĩnh</a></li>
                        <li><a href="{{ url('/product_type/ao-doi-kham') }}">Áo đối khâm</a></li>
                    </ul>
                </li>

                <li> <a href="{{ url('/product-list') }}" > Cửa hàng </a> </li>
                <li><a href="{{ url('home#aboutus') }}">Về chúng tôi</a></li>
                <li><a href="#">Chính sách</a></li>

                <!-- Check if the user is logged -->
                @if (Auth::check())
                <li>
                    <a href="#" id="chat-toggle">Tin nhắn</a>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-user"></i></a>
                    <ul class="sub-menu">
                        <li><a href="{{ route ('account.profile') }}">Trang cá nhân</a></li>
                        <li><a href="{{ route('account.logout') }}">Đăng xuất</a></li>
                    </ul>
                </li>
                @else
                    <li><a href="{{ route('account.login') }}">Đăng nhập</a></li>
                @endif

                <li>
                    <a href="#"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="#"><i class="fa-solid fa-bell"></i></a>
                </li>
            </ul>
            <div class="menu">
                <label for="chk1">
                    <i class="fa fa-bars"></i>
                </label>
            </div>
        </div>
    </header>

    @include ('chat.chat_window')

    <script>
        // Check if the chat window exists
        var chatWindow = document.getElementById('chat-window');
        console.log(chatWindow); // Debugging: Check if chat window is found

        document.getElementById('chat-toggle').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior
            console.log("Chat toggle clicked!"); // Debugging: Log when clicked
            if (chatWindow.style.display === "none" || chatWindow.style.display === "") {
                chatWindow.style.display = "block"; // Show the chat window
                console.log("Chat window displayed!"); // Debugging: Log when displayed
            } else {
                //chatWindow.style.display = "none"; // Hide the chat window
               // console.log("Chat window hidden!"); // Debugging: Log when hidden
            }
        });
    </script>
</body>
</html>
