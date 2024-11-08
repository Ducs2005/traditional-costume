@extends('layouts.admin')
            
@section('content')

<div class="content-header mt-4">
    <h2>Sửa Quản Trị Viên</h2>
    </div>

    <form action="{{ route('admin.administrators.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="editName" class="form-label">Tên</label>
            <input type="text" class="form-control" name="name" id="editName" value="{{ old('name', $admin->name) }}" required>
        </div>
        
        <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="editEmail" value="{{ old('email', $admin->email) }}" required>
        </div>
        
        <div class="mb-3">
            <label for="editPassword" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" id="editPassword">
            <small class="form-text text-muted">Để trống nếu không thay đổi mật khẩu</small>
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật Quản trị viên</button>
    </form>
@endsection
