@extends('layouts.admin') <!-- Extend the base layout -->

@section('content')
    <!-- Page Wrapper -->
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="align-items: center; text-align: center; margin-left: 20px;">Thêm Cổ Phục</h1>
                </div>

                <br><br>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Nhập Thông Tin Cổ Phục</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('cophuc.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Tên Cổ Phục -->
                                <div class="form-group">
                                    <label for="name">Tên Cổ Phục</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên cổ phục" required>
                                </div>

                                <!-- Mô Tả -->
                                <div class="form-group">
                                    <label for="description">Mô Tả</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Nhập mô tả" rows="3"></textarea>
                                </div>

                                <!-- Chi Tiết -->
                                <div class="form-group">
                                    <label for="detail">Chi Tiết</label>
                                    <textarea name="detail" id="detail" class="form-control" placeholder="Nhập chi tiết" rows="5"></textarea>
                                </div>

                                <!-- Hình Ảnh -->
                                <div class="form-group">
                                    <label for="image">Hình Ảnh</label>
                                    <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <a href="{{ route('table.cophuc') }}" class="btn btn-secondary">Hủy</a>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
@endsection
