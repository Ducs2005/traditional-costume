@extends('layout_home')

@section('content_homePage')
<div class="container py-5">
    <div class="product-info shadow-lg p-5 bg-light border border-dark rounded" style="font-family: 'Times New Roman', serif; max-width: 900px; margin: 50px auto 0 auto; box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1); position: relative;">
        <h2 class="text-center text-dark font-weight-bold mb-4" style="font-family: 'Georgia', serif; color: #333;">{{ $cophuc->name }}</h2>
        <div class="row">
            <div class="col-md-6 text-center mb-4 mb-md-0">
                <img src="{{ asset('storage/' . $cophuc->image) }}" alt="{{ $cophuc->name }}" class="product-image img-fluid rounded shadow-sm" style="max-width: 100%; height: auto; border: 2px solid #333; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            </div>
            <div class="col-md-6">
                <div class="product-description mb-4" style="color: #333;">
                    <p><strong class="text-uppercase" style="color: #000;">Mô tả:</strong> {{ $cophuc->description }}</p>
                    <p><strong class="text-uppercase" style="color: #000;">Chi tiết:</strong> {{ $cophuc->detail }}</p>
                </div>
                <a href="{{ route('product.product_type') }}" class="btn" style="position: absolute; bottom: 20px; right: 20px; border-radius: 12px; padding: 12px 20px; font-family: 'Georgia', serif; border: 1px solid #b58900; background-color: #f7e5a3; color: #8b5d33; transition: background-color 0.3s, transform 0.2s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
