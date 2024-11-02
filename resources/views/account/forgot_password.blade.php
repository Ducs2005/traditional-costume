<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
<div class="form-box forgot-password" style="">
    @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message')}}</div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error')}}</div>
    @endif

    <h1>Enter your email</h1>
    <form id="forgotPasswordForm" action="{{ url('/reset_pwd') }}" method="post">
        @csrf
        <div class="input-box">
            <input type="email" class="form-control" placeholder="Email@example.com" name="email_reset" required>
            <i class='bx bx-envelope'></i>
        </div>
        <button type="submit" id="forgot" class="btn1">Enter code</button>
    </form>
</div>

</body>
</html>
