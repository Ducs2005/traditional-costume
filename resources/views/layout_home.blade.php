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
    <link rel="stylesheet" href="{{ asset('frontend/css/productList.css') }}">
    <script src="{{ asset('frontend/js/add_to_cart_buy.js') }}"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('frontend/js/brand.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Header</title>
</head>


<body>
    @include('header_footer.header')

    <main>
        @yield('content_homePage')
    </main>

    @include('header_footer.footer')

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
