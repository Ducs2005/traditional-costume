@extends('layouts.admin')

@section('content')
    <!-- Nội dung quản trị viên -->
    <div class="content-header mt-4">
        <h2>Danh sách Quản trị viên</h2>

        <!-- Hiển thị thông báo thành công nếu có -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    
    <!-- Bảng hiển thị danh sách quản trị viên -->
    <div class="table-responsive mt-3">
        <table class="table table-striped">
            <thead class="table-header">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.administrators.edit', $admin->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.administrators.destroy', $admin->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
