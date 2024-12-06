@extends('layout_home')

@section('content_homePage')

    <!-- section home -->
    <section class="main-home">
        <div class="main-text">
            <h1>TRANG PHỤC CỔ VIỆT NAM</h1>
             <p>
                Những trang phục cổ Việt Nam không chỉ dừng lại ở việc là di tích quý báu của lịch sử nước nhà
                mà còn đại diện những nét đặc trưng của nền văn hoá dân tộc.
            </p>
            <a href="#" class="main-btn">Tìm hiểu <i class="fa-solid fa-right-long"></i></a>
        </div>

        <div class="down-arrow">
            <a href="#product" class="down"><i class="fa-solid fa-angle-down"></i></a>
        </div>
    </section>

    <div class="banner">
        <div class="slider" style="--quantity: 8">
            <div class="item" style="--position: 1">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 2">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 3">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 4">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 5">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 6">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 7">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 8">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
        </div>
    </div>

    <!-- section product -->
    {{-- <section class="product" id="product">
        <div class="center-text">
            <h2>Các dạng cổ phục</h2>
        </div>
        <div class="img">
            @include ('product.product_type')
        </div>
    </section> --}}

    <!-- section about us -->
    {{-- <section class="about-us" id="aboutus">
        <div class="introduce">
            <h3 class="intro">Introduction</h3>
            <div class="member">
                <div class="DTP">
                    <img src="{{ asset('frontend/img/member/DTP.jpg') }}" alt="">
                    <h3>Dam Thanh Phuong</h3>
                    <h2>23IT219</h2>
                    <h2>23JIT</h2>
                    <p>Member</p>
                </div>
                <div class="PND">
                    <img src="{{ asset('frontend/img/member/PND.png') }}" alt="">
                    <h3>Pham Ngoc Duc</h3>
                    <h2>23IT</h2>
                    <h2>23JIT</h2>
                    <p>Leader</p>
                </div>

                <div class="PHN">
                    <img src="{{ asset('frontend/img/member/PHN.png') }}" alt="">
                    <h3>Pham Hoang Nguyen</h3>
                    <h2>23IT</h2>
                    <h2>23JIT</h2>
                    <p>Member</p>
                </div>
            </div>
        </div>
    </section> --}}
@endsection
