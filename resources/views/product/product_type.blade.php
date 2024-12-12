@extends('layout_home')

@section('content_homePage')
<div class="container">
    <div class="product-grid">
        @if($cophuc->count() == 0)
            <p>Không có trang phục nào để hiển thị</p>
        @else
        @foreach ($cophuc as $item)
        <div class="product-card">
            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
            <h2>{{ $item->name }}</h2>
            <p>{{ $item->description }}</p>
            <a href="{{ route('showDetail', $item->id) }}" class="btn">Xem Chi Tiết</a>
        </div>
    @endforeach
@endif
    </div>
</div>
@endsection
