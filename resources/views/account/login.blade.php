<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('frontend/css/login_register.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <title>Login</title>

</head>
<body>

    <!-- wrapper -->
    <div class="wrapper">

        <span class="icon-close">
            <a href="{{ url('/') }}"><i class="fa-solid fa-xmark" style="color: #fff"></i></a>
        </span>

        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success')}}</div>
        @endif

        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message')}}</div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error')}}</div>
        @endif

        <!-- login -->
        <div class="form-box login">
            <form action="{{ route('account.authenticate') }}" method="POST">
                @csrf
                <img src="{{asset('frontend/img/logo.png')}}" alt="logo">
                <h1>WELCOME TO OUR PAGE</h1>
                <!-- input -->
                <div class="input-box">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email@example.com" name="email" value="{{ old('email') }}">
                    <i class='bx bx-user' ></i>
                    @error('email')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-box">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                    <i class='bx bx-lock-alt' ></i>
                    @error('password')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <!-- forgot pwd -->
                <div class="remember-forgot">
                    <label><input type="checkbox" name="remember">Remember me</label>
                    <a href="{{ url('/forgot_password') }}" id="forgot">Quên mật khẩu?</a>
                </div>

                <!-- submit -->
                <button type="submit" class="btn1" name="submit">Login</button>

                <!-- auto login -->
                <div class="authentication-social">
                    <div class="separator">
                        <span></span>
                        <span class="paragraph">OR</span>
                        <span></span>
                    </div>
                    <!-- Google -->
                    <a href="#" class="google" rel="nofollow">
                        <i class='bx bxl-google'></i>
                        <span>Login with Google</span>
                    </a>
                    <br>
                    <!-- Facebook -->
                    <a href="#" class="fb" rel="nofollow">
                        <i class='bx bxl-facebook-circle' ></i>
                        <span>Login with Facebook</span>
                    </a>
                </div>

                <!-- register link -->
                <div class="register-link">
                    <p>Don't have an account? <a href="{{ route('account.register') }}">Register</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/forgot_password/input_email.js') }}"></script>
</body>
</html>
