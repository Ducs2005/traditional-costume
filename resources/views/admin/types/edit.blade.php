@extends('layouts.admin')

@section('content')
    <h2 class="text-center">Sửa loại sản phẩm</h2>
    
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

    <form action="{{ route('admin.types.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Tên loại sản phẩm</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $type->name }}" required>
        </div>
        <div class="form-group">
            <label for="name">Mô tả</label>
            <input type="text" id="description" name="description" class="form-control" value="{{ $type->description }}">
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật loại sản phẩm</button>
    </form>
@endsection
