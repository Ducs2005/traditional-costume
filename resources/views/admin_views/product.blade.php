@extends('layouts.admin') <!-- Assuming layouts.app is your layout file -->

@section('content')
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="text-align: center; margin-left: 20px;">Dashboard</h1>
                </div>
                
                <br><br>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableProducts" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ number_format($product->price) }} VND</td>
                                        <td>
                                            <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}" style="width: auto; height: 75px;">
                                        </td>
                                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewDetailModal"
                                                data-id="{{ $product->id }}" 
                                                data-name="{{ $product->name }}" 
                                                data-price="{{ number_format($product->price) }} VND"
                                                data-img="{{ asset($product->img_path) }}" 
                                                data-created-at="{{ $product->created_at->format('Y-m-d') }}"
                                                data-description="{{ $product->description }}">View Detail</button>

                                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailModalLabel">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateProductForm" action="{{ route('product.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Hidden Input for Product ID -->
                        <input type="hidden" name="product_id" id="product-id">
                        
                        <!-- Image Preview -->
                        <div class="text-center">
                            <img id="product-image" src="" alt="Product Image" style="max-width: 100%; height: auto; margin-bottom: 15px;">
                        </div>
                        
                        <!-- Fields for Update -->
                        <div class="form-group">
                            <label for="product-name">Name</label>
                            <input type="text" name="name" id="product-name-input" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="product-price">Price (VND)</label>
                            <input type="number" name="price" id="product-price-input" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="product-description">Description</label>
                            <textarea name="description" id="product-description-input" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product-image-input">Image</label>
                            <input type="file" name="image" id="product-image-input" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTableProducts').DataTable();
        });

        $(document).ready(function () {
            $('#viewDetailModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var price = button.data('price').replace(' VND', '').replace(/,/g, '');
                var img = button.data('img');
                var description = button.data('description');
                $('#product-id').val(id);
                $('#product-name-input').val(name);
                $('#product-price-input').val(price);
                $('#product-description-input').val(description);
                $('#product-image').attr('src', img).attr('alt', name);
            });
        });
    </script>
@endpush
