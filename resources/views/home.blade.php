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
                <a href="#intro" class="down"><i class="fa-solid fa-angle-down"></i></a>
            </div>
    </section>
    <div class="banner">
        <div class="slider" style="--quantity: 8">
        <div class="item" style="--position: 1">
                <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 2">
                <img src="{{ asset('frontend/img/home/trangphuc2.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 3">
                <img src="{{ asset('frontend/img/home/trangphuc3.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 4">
                <img src="{{ asset('frontend/img/home/trangphuc4.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 5">
                <img src="{{ asset('frontend/img/home/trangphuc5.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 6">
                <img src="{{ asset('frontend/img/home/trangphuc6.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 7">
                <img src="{{ asset('frontend/img/home/trangphuc7.jpg') }}" alt="">
            </div>
            <div class="item" style="--position: 8">
                <img src="{{ asset('frontend/img/home/trangphuc8.jpg') }}" alt="">
            </div>
        </div>
    </div>

    <!-- section brand -->
    <section class="brand my-3" id="brand">
        <div class="brand_container">
            <img src="{{ asset('frontend/img/banner/brand1.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand2.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand1.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand2.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand1.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand2.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand1.png') }}" alt="brand">
            <img src="{{ asset('frontend/img/banner/brand2.png') }}" alt="brand">
        </div>
    </section>

    {{-- section intro --}}
    <section class="section_container intro_container" id="intro">
        <h2 class="intro_header">TÌM HIỂU THÊM CÁC LOẠI TRANG PHỤC CỔ</h2>
        <div class="intro_grid">
            @foreach($prd as $prds)
                <div class="intro_card">
                    <div class="intro_img">
                        <img src="{{ $prds->image }}" alt="intro">
                    </div>
                    <div class="intro_content">
                        <div>
                            <h4>{{ $prds->name }}</h4>
                            <div class="span">
                                <a href="{{ route('showDetail', $prds->id) }}" class="btn">Xem Chi Tiết</a>
                                <span><i class='bx bx-chevrons-right' style='color:#181818'  ></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- section shop --}}
    <section class="shop my-2 py-5 text-center" id="shop">
        <div class="overlay_shop">
        <div class="content">
            <h4>Shop name</h4>
            <h1>Mở cửa hàng cá nhân
                <br>NHIỀU ƯU ĐÃI HẤP DẪN
            </h1>
            <button class="text-uppercase">Truy cập nhanh</button>
        </div>
    </section>

    {{-- section feature --}}
    <section class="feature" id="feature" class="my-5 pb-5">
        <div class="feature_container text-center">
            <h3>Sản phẩm mới</h3>
            <hr class="mx-auto">
            <p>Bạn có thể tìm và mua sản phẩm <a href="{{ URL::to('/product-list') }}">tại đây</a></p>
        </div>
        <div class="product_feature">
            @foreach($featureProducts as $feature)
                <div class="product">
                    <div class="image">
                        <img class="img-fluid mb-3" src="{{ $feature->img_path }}" alt="">
                    </div>
                    <div class="feature_content">
                        <div class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="p-name">{{ $feature->name }}</h5>
                        <h4 class="p-price">{{ number_format($feature->price, 0, ',', '.')}}</h4>
                        @if(Auth::check())
                        <form id="add-to-cart-form-{{ $feature->id }}" action="{{ route('cart.add') }}" method="POST" class="cart_home">
                            @csrf
                            <input type="hidden" name="product_id" class="product_id" value="{{ $feature->id }}">
                            <button type="button" class="payment-btn buy-btn btn btn-primary mt-3" data-id="{{ $feature->id }}">Thêm vào giỏ hàng</button>
                        </form>
                        <form id="payment-form-{{ $feature->id }}" action="{{ route('payment.process') }}" method="POST" class="cart_home payment_form">
                            @csrf
                            <input type="hidden" id="cartTotal" name="cartTotal" value="{{ floor($feature->price) }}">
                            <div hidden class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay" required checked>
                                <label class="form-check-label" for="vnpay">
                                    Thanh toán qua VNPay
                                </label>
                            </div>
                            <input type="hidden" name="product_id" class="product_id" value="{{ $feature->id }}">
                            <button type="button" class="buyBtn buy-btn btn btn-primary mt-3" data-id="{{ $feature->id }}">Mua ngay</button>
                        </form>
                        @else
                        <button type="button" class="payment buy-btn btn btn-primary mt-3" data-id="{{ $feature->id }}">Thêm vào giỏ hàng</button>
                        <button type="button" class="buy buy-btn btn btn-primary mt-3" data-id="{{ $feature->id }}">Mua ngay</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    {{-- section gallery --}}
    <section class="gallery" id="gallery">
        <h1><span>Thu vien anh</span>
        </h1>
        <div class="gallery_image_box">
            @foreach($gallery as $gallery)
            <div class="gallery_image">
                <img src="{{ $gallery->img_path }}" alt="">
            </div>
            @endforeach
        </div>
    </section>

    <!-- section about us -->
    <section class="team" id="team">
        <h1>Nhóm 10</h1>
        <div class="team_box">
            <div class="profile">
                <img src="https://i.pinimg.com/736x/a0/a8/46/a0a846db2c036d3a8fcf739bb5707e43.jpg" alt="">
                <div class="info">
                    <h2 class="name">Pham Ngoc Duc</h2>
                    <p class="info_p">23IT059_Nhóm trưởng</p>
                </div>
            </div>
            <div class="profile">
                <img src="https://i.pinimg.com/736x/80/b2/1f/80b21fc7cb615e1a3b23bbd41e5dc455.jpg" alt="">
                <div class="info">
                    <h2 class="name">Dam Thanh Phuong</h2>
                    <p class="info_p">23IT219_Thành viên</p>
                </div>
            </div>
            <div class="profile">
                <img src="https://i.pinimg.com/736x/78/9e/28/789e281493d6f7fce03e194589952c27.jpg" alt="">
                <div class="info">
                    <h2 class="name">Pham Hoang Nguyen</h2>
                    <p class="info_p">23IT.EB06EB065_Thành viên</p>
                </div>
            </div>
        </div>
    </section>
@endsection
