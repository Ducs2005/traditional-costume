@extends('layouts.admin') <!-- Assuming layouts.admin is your layout file -->
<link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}?v={{ time() }}">
<style> .image-number {
    position: absolute;
    top: 10px; 
    left: 10px; 
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    font-size: 24px;
    padding: 5px 10px;
    border-radius: 50%;
    font-weight: bold;
    z-index: 10;
}
</style>
@section('content')
<div id="wrapper">
   <div class="banner" style="background-color: white;">
    <div class="slider" style="--quantity: 8; background-color:white;">
        <!-- Spinning Images -->
        <div class="item" style="--position: 1">
        <div class="image-number">1</div>

            <img src="{{ asset('frontend/img/home/trangphuc1.jpg') }}?v={{ time() }}" alt="Trang Phuc 1" class="img-fluid">
        </div>
        <div class="item" style="--position: 2">
             <div class="image-number">2</div>

            <img src="{{ asset('frontend/img/home/trangphuc2.jpg') }}?v={{ time() }}" alt="Trang Phuc 2" class="img-fluid">
        </div>
        <div class="item" style="--position: 3">
             <div class="image-number">3</div>

            <img src="{{ asset('frontend/img/home/trangphuc3.jpg') }}?v={{ time() }}" alt="Trang Phuc 3" class="img-fluid">
        </div>
        <div class="item" style="--position: 4">
            <div class="image-number">4</div>

            <img src="{{ asset('frontend/img/home/trangphuc4.jpg') }}?v={{ time() }}" alt="Trang Phuc 4" class="img-fluid">
        </div>
        <div class="item" style="--position: 5">
         <div class="image-number">5</div>

            <img src="{{ asset('frontend/img/home/trangphuc5.jpg') }}?v={{ time() }}" alt="Trang Phuc 5" class="img-fluid">
        </div>
        <div class="item" style="--position: 6">
            <div class="image-number">6</div>

            <img src="{{ asset('frontend/img/home/trangphuc6.jpg') }}?v={{ time() }}" alt="Trang Phuc 6" class="img-fluid">
        </div>
        <div class="item" style="--position: 7">
        <div class="image-number">7</div>

            <img src="{{ asset('frontend/img/home/trangphuc7.jpg') }}?v={{ time() }}" alt="Trang Phuc 7" class="img-fluid">
        </div>
        <div class="item" style="--position: 8">
            <div class="image-number">8</div>

            <img src="{{ asset('frontend/img/home/trangphuc8.jpg') }}" alt="Trang Phuc 8" class="img-fluid">
        </div>
    </div>
</div>

    </div>
    <div class="container mt-5">
    <h3 class="text-center">Update Banner Images</h3>

    <form id="updateImagesForm" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <!-- Image 1 -->
        <div class="col-md-4 mb-3">
            <label for="img1Input" class="form-label">Update Image 1:</label>
            <input type="file" class="form-control" id="img1Input" name="img1" accept=".jpg">
        </div>

        <!-- Image 2 -->
        <div class="col-md-4 mb-3">
            <label for="img2Input" class="form-label">Update Image 2:</label>
            <input type="file" class="form-control" id="img2Input" name="img2" accept=".jpg">
        </div>

        <!-- Image 3 -->
        <div class="col-md-4 mb-3">
            <label for="img3Input" class="form-label">Update Image 3:</label>
            <input type="file" class="form-control" id="img3Input" name="img3" accept=".jpg">
        </div>
    </div>

    <div class="row">
        <!-- Image 4 -->
        <div class="col-md-4 mb-3">
            <label for="img4Input" class="form-label">Update Image 4:</label>
            <input type="file" class="form-control" id="img4Input" name="img4" accept=".jpg">
        </div>

        <!-- Image 5 -->
        <div class="col-md-4 mb-3">
            <label for="img5Input" class="form-label">Update Image 5:</label>
            <input type="file" class="form-control" id="img5Input" name="img5" accept=".jpg">
        </div>

        <!-- Image 6 -->
        <div class="col-md-4 mb-3">
            <label for="img6Input" class="form-label">Update Image 6:</label>
            <input type="file" class="form-control" id="img6Input" name="img6" accept=".jpg">
        </div>
    </div>

    <div class="row">
        <!-- Image 7 -->
        <div class="col-md-4 mb-3">
            <label for="img7Input" class="form-label">Update Image 7:</label>
            <input type="file" class="form-control" id="img7Input" name="img7" accept=".jpg">
        </div>

        <!-- Image 8 -->
        <div class="col-md-4 mb-3">
            <label for="img8Input" class="form-label">Update Image 8:</label>
            <input type="file" class="form-control" id="img8Input" name="img8" accept=".jpg">
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Update Images</button>
    </div>
</form>

</div>

</s>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
    // Handle image preview before upload
// Handle image preview before upload
function previewImage(inputId, imgId) {
    const file = document.getElementById(inputId).files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById(imgId).src = e.target.result; // Update the image in the carousel
    };
    if (file) {
        reader.readAsDataURL(file);
    }
}

// Add event listeners for each input
document.getElementById('img1Input').addEventListener('change', function() {
    previewImage('img1Input', 'img1');
});
document.getElementById('img2Input').addEventListener('change', function() {
    previewImage('img2Input', 'img2');
});
document.getElementById('img3Input').addEventListener('change', function() {
    previewImage('img3Input', 'img3');
});
document.getElementById('img4Input').addEventListener('change', function() {
    previewImage('img4Input', 'img4');
});
document.getElementById('img5Input').addEventListener('change', function() {
    previewImage('img5Input', 'img5');
});
document.getElementById('img6Input').addEventListener('change', function() {
    previewImage('img6Input', 'img6');
});
document.getElementById('img7Input').addEventListener('change', function() {
    previewImage('img7Input', 'img7');
});
document.getElementById('img8Input').addEventListener('change', function() {
    previewImage('img8Input', 'img8');
});

// Handle form submission to update images
document.getElementById('updateImagesForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Collect form data (image files)
    const formData = new FormData(this);

    // You can send this data to the server (e.g., via AJAX or a form submit)
    // Example AJAX request to upload the images
    fetch('{{route('updateDecorationImages')}}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Images updated successfully!');
            location.reload();
        } else {
            alert('Error updating images');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating images');
    });
});

</script>
@endpush
