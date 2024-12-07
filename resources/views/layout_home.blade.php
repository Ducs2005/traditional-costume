<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Hurricane&family=Ibarra+Real+Nova:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/layout_home.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/view_cart.css') }}">
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
                <li class="dropdown">
                     <a href="#"><i class="fa-solid fa-bell"></i> Thông báo</a>
                     <ul class="sub-menu">
                        @if (Auth::check() && auth()->user()->selling_right === 'yes')
                        <li> <a href="{{route('seller.viewOrder')}}">Đơn hàng </a>  </li>
                        @endif
                        <li> <a href="{{route('viewOrder')}}">Thông báo </a>  </li>
                     </ul>
                </li>

                <!-- Check if the user is logged -->
                @if (Auth::check())
                <li>
                    <a href="#" id="chat-toggle">Tin nhắn</a>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fa-solid fa-user"></i></a>
                    <ul class="sub-menu">
                        <li><a href="{{ route ('account.profile') }}">Trang cá nhân</a></li>
                        <li>
                            <a href="{{ url('/view_order') }}"></i> Đơn mua</a>
                         </li>
                        <li><a href="{{ route('account.logout') }}">Đăng xuất</a></li>
                    </ul>
                </li>
                @else
                    <li><a href="{{ route('account.login') }}">Đăng nhập</a></li>
                @endif

                <li>
                    <a href="{{ url('/view_cart') }}"><i class="fa-solid fa-cart-shopping"></i></a>
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

    <main>
        @yield('content_homePage')
    </main>

    <footer>
        <div class="row">
            <div class="col">
                <img src="{{asset('frontend/img/logo.png')}}" class="logo1">
                <p> Những trang phục cổ Việt Nam không chỉ dừng lại ở việc là di tích quý báu của lịch sử nước nhà
                mà còn đại diện những nét đặc trưng của nền văn hoá dân tộc. </p>
            </div>
            <div class="col">
                <h3>Get in touch <div class="underline"><span></span></div> </h3>
                <p>470 Tran Dai Nghia, Ngu Hanh Son, Da Nang, Viet Nam</p>
                <p class="email-id">CPV@gmail.com</p>
                <p>(+84) 123456789</p>
            </div>
            <div class="col">
                <h3 class="contact">Contact <div class="underline"><span></span></div> </h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Contacts</a></li>
                </ul>
            </div>
            <div class="col">
                <h3>Newletter <div class="underline"><span></span></div> </h3>
                <form class="mail">
                    <i class='bx bx-envelope'></i>
                    <input type="email" placeholder="Enter your email to receive notifications." class="email">
                    <button class="submit"><i class="fas fa-arrow-right"></i></button>
                </form>
                <div class="social-icons">
                    <i class="fab fa-facebook"></i>
                    <i class="fab fa-tiktok"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <hr>
        <p class="copyright">
            &copy;<?php echo date("Y"); ?>CoPhucViet | DucNguyenPhuong
        </p>

    </footer>

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
