@extends('layouts.admin')
            <!-- Main Content -->
@section('content')
    <div class="content-header mt-4">
        <h2>Thêm quản trị viên mới</h2>
                    
                    <!-- Hiển thị lỗi nếu có -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

                    <!-- Hiển thị thông báo thành công nếu có -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
                
    <form class="mt-3" method="POST" action="{{ route('admin.administrators.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="admin_name" class="form-label">Tên quản trị viên</label>
            <input type="text" class="form-control" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="admin_email" class="form-label">Thư điện tử</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" required>
        </div>
        <div class="mb-3">
            <label for="admin_password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm quản trị viên</button>
    </form>           
@endsection