@extends('layouts.admin')

@section('content')
    <h2 class="text-center">Thêm Sản Phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="details" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="details" name="details" required></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
            <label for="type_id" class="form-label">Chọn Loại Sản Phẩm</label>
            <select id="type_id" class="form-select" name="type_id" required>
                <option value="">Chọn loại</option>
                <option value="all">Tất cả</option>
                @foreach($type as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình Ảnh Sản Phẩm</label>
            <input type="file" class="form-control" id="image" name="img" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
@endsection
