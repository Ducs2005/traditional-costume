@extends('layouts.admin') <!-- Assuming layouts.admin is your layout file -->

@section('content')
<div id="wrapper">
    <div class="container mt-4">
        <h2>Send Notification</h2>

        <form action="{{ route('notifications.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="receiver_type">To</label>
                <select class="form-control" id="receiver_type" name="receiver_type" onchange="toggleUserSelection()" required>
                    <option value="all">All Users</option>
                    <option value="one">Specific User</option>
                </select>
            </div>

            <div class="form-group" id="user_select" style="display: none;">
                <label for="user_id">Select User</label>
                <select class="form-control" id="user_id" name="user_id">
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Send</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
    // Show or hide user selection based on "To" dropdown
    function toggleUserSelection() {
        var receiverType = document.getElementById('receiver_type').value;
        var userSelectDiv = document.getElementById('user_select');

        if (receiverType === 'one') {
            userSelectDiv.style.display = 'block';
        } else {
            userSelectDiv.style.display = 'none';
        }
    }
</script>
@endpush
