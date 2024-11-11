@extends('layouts.admin')

@section('content')
    <h2 class="text-center">Danh sách sản phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <label for="typeFilter" class="form-label">Chọn loại sản phẩm:</label>
        <select id="typeFilter" class="form-select" onchange="filterProducts()">
            <option value="">Tất cả</option>
            @foreach($type as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="productList">
            @foreach($products as $product)
                <tr class="product" data-type="{{ $product->type->id }}">
                    <td>
                        <img src="{{ asset('frontend/img/product/' . $product->img_path) }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->details }}</td>
                    <td>{{ $product->price }}đ</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function filterProducts() {
            const selectedType = document.getElementById('typeFilter').value;
            const products = document.querySelectorAll('.product');
            products.forEach(product => {
                if (selectedType === "" || product.getAttribute('data-type') == selectedType) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        }
    </script>
@endsection
