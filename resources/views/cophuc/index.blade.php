@extends('layouts.admin') <!-- Extend the base layout -->

@section('content')
    <!-- Page Wrapper -->
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="align-items: center; text-align: center; margin-left: 20px;">Cổ Phục</h1>
                </div>

                <br><br>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Add Button -->
                    <div class="mb-4">
                        <a href="{{ route('cophuc.create') }}" class="btn btn-success">Thêm Cổ Phục</a>
                    </div>

                    <!-- Page Heading -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Cổ Phục</th>
                                    <th>Mô Tả</th>
                                    <th>Chi Tiết</th>
                                    <th>Hình Ảnh</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cophuc as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description ?? 'Không có mô tả' }}</td>
                                        <td>
                                            <a href="{{ route('showDetail', $item->id) }}" class="btn btn-info btn-sm">Chi Tiết</a>
                                        </td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 100px; height: auto;">
                                            @else
                                                <span>Không có hình ảnh</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('cophuc.edit', $item->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                            <form action="{{ route('cophuc.destroy', $item->id) }}" method="POST" style="display: inline-block;">
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

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
