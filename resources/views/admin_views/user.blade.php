@extends('layouts.admin')  <!-- Extend the base layout -->

@section('content')
    <!-- Page Wrapper -->
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">
            <br>
            <!-- Main Content -->
            <div id="content">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" style="align-items: center; text-align: center; margin-left: 20px;">Dashboard</h1>
                </div>

                <br><br>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Quyền</th>
                                    <th>Created At</th>
                                    <th>Updated At </th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role ?? 'N/A' }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $user->updated_at->format('Y-m-d') }}</td>
                                        <td>
                                            <!-- View Detail Button -->
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewDetailModal" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}" data-selling_right="{{ $user->selling_right }}">
                                                View Detail
                                            </button>
                                            <!-- Delete Button -->
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
    <!-- End of Page Wrapper -->

    <!-- View Detail Modal -->
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailModalLabel">Edit User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="viewEditForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user-name">Name</label>
                            <input type="text" class="form-control" id="user-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="user-email">Email</label>
                            <input type="email" class="form-control" id="user-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="user-role">Role</label>
                            <select class="form-control" id="user-role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user-selling_right">Selling Right</label>
                            <input type="text" class="form-control" id="user-selling_right" name="selling_right">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
        console.log('fsaoifnd');
        // Handle View Detail button click to populate modal fields
        $('#viewDetailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userId = button.data('id');
            var userName = button.data('name');
            var userEmail = button.data('email');
            var userRole = button.data('role');
            var userSellingRight = button.data('selling_right');
           
                    // Set form action to edit user
            var formAction = "{{ route('user.update', ':id') }}";
            formAction = formAction.replace(':id', userId);
            $('#viewEditForm').attr('action', formAction);

            // Populate modal fields
            $('#user-name').val(userName);
            $('#user-email').val(userEmail);
            // Set the selected role in the dropdown
            $('#user-role option').each(function () {
                if ($(this).val() == userRole) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });
            $('#user-selling_right').val(userSellingRight);
        });
    </script>
@endpush
