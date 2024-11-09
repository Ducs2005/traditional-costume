@extends('layouts.admin')

@section('content')
    <h2 class="text-center">Danh sách loại sản phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.types.create') }}" class="btn btn-primary mb-3">Thêm loại sản phẩm</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tên loại sản phẩm</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>
                        <a href="{{ route('admin.types.edit', $type->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại sản phẩm này không?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
