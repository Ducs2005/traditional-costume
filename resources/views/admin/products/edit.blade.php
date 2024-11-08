@extends('layouts.admin')

@section('content')
    <h2 class="text-center">Chỉnh sửa sản phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="details" class="form-label">Mô tả</label>
            <textarea class="form-control" id="details" name="details">{{ old('details', $product->details) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="mb-3">
            <label for="type_id" class="form-label">Loại sản phẩm</label>
            <select class="form-select" id="type_id" name="type_id" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ $product->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Hình ảnh</label>
            <input type="file" class="form-control" id="img" name="img">
            @if($product->img_path)
                <img src="{{ asset('storage/' . $product->img_path) }}" alt="Hình ảnh sản phẩm" style="width: 100px; height: auto;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
@endsection
