<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
</head>
<body>
<div class="form-box forgot-password" style="">
    @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message')}}</div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error')}}</div>
    @endif
    @php
        $token = $_GET['token'];
        $email = $_GET['email'];
    @endphp
    <h1>Enter new password</h1>
    <form id="forgotPasswordForm" action="{{ url('/reset_new_pwd') }}" method="post">
        @csrf

        <div class="input-box">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="password" class="form-control" placeholder="New password" name="new_pwd" required>
            <i class='bx bx-envelope'></i>
        </div>
        <button type="submit" id="reset" class="btn1">Submit</button>
    </form>
</div>

</body>
</html>
