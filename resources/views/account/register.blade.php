<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('frontend/css/login_register.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <title>Register</title>
</head>

<body>

    <!-- wrapper -->
    <div class="wrapper">

        <span class="icon-close">
            <a href="{{ url('/') }}"><i class="fa-solid fa-xmark" style="color: #fff"></i></a>
        </span>

        <!-- registration -->
        <div class="form-box register">
            <form action="{{ route('account.processRegister') }}" method="post">
                @csrf
                <img src="{{asset('frontend/img/logo.png')}}" alt="logo">
                <h1>WELCOME TO OUR PAGE</h1>
                <!-- input -->
                <div class="input-box">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Username" name="name"  value="{{ old('name') }}">
                    <i class='bx bx-user' ></i>
                    @error('name')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-box">
                    <input type="text"  class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                    <i class='bx bx-envelope'></i>
                    @error('email')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-box">
                    <input type="password"  class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                    <i class='bx bx-lock-alt' ></i>
                    @error('password')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <!-- forgot pwd -->
                <div class="agree">
                    <label><input type="checkbox">I agree to the <a href="#">Service</a> and <a href="#">Conditions</a></label>
                </div>

                <!-- submit -->
                <button type="submit" name="submit" class="btn2">Register</button>

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
                        <span>Register with Google</span>
                    </a>
                    <br>
                    <!-- Facebook -->
                    <a href="#" class="fb" rel="nofollow">
                        <i class='bx bxl-facebook-circle' ></i>
                        <span>Register with Facebook</span>
                    </a>
                </div>

                <!-- register link -->
                <div class="loginLink">
                    <p>Already have an account? <a href="{{ route('account.login') }}">Login</a></p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
