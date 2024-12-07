<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('frontend/css/pwd/forgotPwd.css') }}">
    <title>Forgot Password</title>
</head>
<body>
    <div class="wrapper"> <!-- Added wrapper container -->
        <h1>Enter your email<div class="underline"><span></span></div> </h1>
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message')}}</div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error')}}</div>
        @endif
        <form id="forgotPasswordForm" action="{{ url('/reset_pwd') }}" method="post">
            @csrf
            <div class="group">
                <i class='bx bx-envelope'></i>
                <input type="email" class="form-control" placeholder="Email@example.com" name="email_reset" required>
            </div>
            <button type="submit" id="forgot" class="btn1">Enter code</button>

            <div class="email-instruction">
                <p >Please check your email for a reset code after submitting.</p>
            </div>
        </form>
    </div> <!-- End wrapper -->
</body>
</html>
