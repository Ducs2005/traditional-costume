@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Gallery</h2>

        <!-- Select box to choose table to display -->
        <div class="form-group">
            <label for="tableSelect">Choose a Table</label>
            <select id="tableSelect" class="form-control">
                <option value="colors">Colors</option>
                <option value="buttons">Buttons</option>
                <option value="materials">Materials</option>
                <option value="types">Types</option>
            </select>
        </div>

        <!-- Colors Table -->
        <div class="row mb-4" id="colorsTable" style="display: none;">
            <div class="col-md-12">
                <h4>Colors</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colors as $color)
                            <tr>
                                <td>{{ $color->name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editColorModal" data-id="{{ $color->id }}" data-name="{{ $color->name }}">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success" data-toggle="modal" data-target="#addColorModal">Add New</button>
            </div>
        </div>

        <!-- Buttons Table -->
        <div class="row mb-4" id="buttonsTable" style="display: none;">
            <div class="col-md-12">
                <h4>Buttons</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buttons as $button)
                            <tr>
                                <td>{{ $button->name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editButtonModal" data-id="{{ $button->id }}" data-name="{{ $button->name }}">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success" data-toggle="modal" data-target="#addButtonModal">Add New</button>
            </div>
        </div>

        <!-- Materials Table -->
        <div class="row mb-4" id="materialsTable" style="display: none;">
            <div class="col-md-12">
                <h4>Materials</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            <tr>
                                <td>{{ $material->name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editMaterialModal" data-id="{{ $material->id }}" data-name="{{ $material->name }}">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success" data-toggle="modal" data-target="#addMaterialModal">Add New</button>
            </div>
        </div>

        <!-- Types Table -->
        <div class="row mb-4" id="typesTable" style="display: none;">
            <div class="col-md-12">
                <h4>Types</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($types as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editTypeModal" data-id="{{ $type->id }}" data-name="{{ $type->name }}">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success" data-toggle="modal" data-target="#addTypeModal">Add New</button>
            </div>
        </div>

    </div>

    <!-- Modal Templates for Adding New Records -->
    <!-- Add Color Modal -->
    <div class="modal fade" id="addColorModal" tabindex="-1" role="dialog" aria-labelledby="addColorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addColorModalLabel">Add New Color</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('color.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="color-name">Color Name</label>
                            <input type="text" class="form-control" id="color-name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Button Modal -->
    <div class="modal fade" id="addButtonModal" tabindex="-1" role="dialog" aria-labelledby="addButtonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addButtonModalLabel">Add New Button</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('button.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="button-name">Button Name</label>
                            <input type="text" class="form-control" id="button-name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Color Modal -->
    <div class="modal fade" id="editColorModal" tabindex="-1" role="dialog" aria-labelledby="editColorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editColorModalLabel">Edit Color</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('color.update', '') }}" method="POST" id="editColorForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-color-name">Color Name</label>
                            <input type="text" class="form-control" id="edit-color-name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Repeat similar modals for Buttons, Materials, and Types Edit -->

</div>


@push('scripts')
<script>
    // Edit Color Modal
    $('#editColorModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var colorId = button.data('id');
        var colorName = button.data('name');

        var modal = $(this);
        modal.find('.modal-title').text('Edit Color');
        modal.find('.modal-body #edit-color-name').val(colorName);
        modal.find('form').attr('action', '{{ route("color.update", "") }}/' + colorId);
    });

    // Repeat similar script for Buttons, Materials, and Types Edit Modal
</script>
@endpush
@endsection
