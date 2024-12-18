@extends('layouts.admin')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('backend/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <!-- Custom styles for this template-->
    <link href="{{asset ('backend/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

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
              
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($waitingUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#userModal" 
                                                    onclick="viewUserDetails({{ $user->id }})">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <!-- End of Main Content -->

          
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="userName"></span></p>
                    <p><strong>Email:</strong> <span id="userEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="userPhone"></span></p>
                    <p><strong>Address:</strong> <span id="userAddress"></span></p>
                    <p><strong>Request Date:</strong> <span id="userRequestDate"></span></p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="rejectButton">Từ chối</button>
                <button type="button" class="btn btn-success" id="acceptButton">Chấp nhận</button>

                </div>
            </div>
        </div>
                <!-- Rejection Reason Modal -->
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Enter Rejection Reason</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea id="rejectReason" class="form-control" rows="4" placeholder="Nhập nguyên nhân yêu cầu bị từ chối"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" id="confirmRejectButton">Từ chối</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    @endsection

    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
@push('scripts')  
    <!-- Bootstrap core JavaScript-->
    
    
    <!-- Page level plugins -->
    <!-- <script src="{{asset('backend/vendor/chart.js/Chart.min.js')}}"></script> -->

    <!--
    <script src="{{asset('backend/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('backend/js/demo/chart-pie-demo.js')}}"></script> -- Page level custom scripts -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable();
        });
    </script>

    <script>

        let userId = 0;
        async function viewUserDetails(userPara) {
           userId = userPara;
            const baseUrl = '{{ url('/') }}'; // Dynamically fetch your app's base URL

            $.ajax({
                url: `${baseUrl}/admin/user/${userPara}`,
                method: 'GET',
                success: function (data) {
                    if (!data.error)
                    {
                        $('#userName').text(data.user.name);
                        $('#userEmail').text(data.user.email);
                        $('#userPhone').text(data.user.phone_number);
                        $('#userRequestDate').text(data.user.updated_at);
                        const { province, district, ward } = data.address;

                        Promise.all([
                            getProvince(province),
                            getDistrict(province, district),
                            getWard(district, ward)
                        ])
                        .then(([provinceName, districtName, wardName]) => {
                            const fullAddress = ` ${wardName || ''}, ${districtName || ''}, ${provinceName || ''}`.replace(/, ,/g, ',').trim();
                            $('#userAddress').text(fullAddress || 'N/A');
                        })
                        .catch(error => {
                            console.error("Error fetching address details:", error);
                            $('#userAddress').text('N/A');
                        });
                    }
                    else{
                        alert(data.error);
                    }
                },
                error: function () {
                    alert('Error loading user details.');
                }
            });
        }

            // Fetch Province Name by ID
        // Fetch Province Name by ID
    async function getProvince(provinceID) {
        try {
            const response = await fetch('https://vapi.vnappmob.com/api/province/');
            if (!response.ok) {
                throw new Error('Failed to fetch provinces.');
            }
            const data = await response.json();
            const province = data.results.find(p => p.province_id === provinceID);
            return province ? province.province_name : 'Unknown Province';
        } catch (error) {
            console.error('Error fetching province:', error);
            return 'Error fetching province';
        }
    }

    // Fetch District Name by Province ID and District ID
    async function getDistrict(provinceID, districtID) {
        try {
            const response = await fetch(`https://vapi.vnappmob.com/api/province/district/${provinceID}`);
            if (!response.ok) {
                throw new Error('Failed to fetch districts.');
            }
            const data = await response.json();
            console.log(districtID);
            const district = data.results.find(d => d.district_id === districtID);
            console.log(data.results);
            return district ? district.district_name : 'Unknown District';
        } catch (error) {
            console.error('Error fetching district:', error);
            return 'Error fetching district';
        }
    }

    // Fetch Ward Name by District ID and Ward ID
    async function getWard(districtID, wardID) {
        try {
            const response = await fetch(`https://vapi.vnappmob.com/api/province/ward/${districtID}`);
            if (!response.ok) {
                throw new Error('Failed to fetch wards.');
            }
            const data = await response.json();
            const ward = data.results.find(w => w.ward_id === wardID);
            return ward ? ward.ward_name : 'Unknown Ward';
        } catch (error) {
            console.error('Error fetching ward:', error);
            return 'Error fetching ward';
        }
    }
    const baseUrl = '{{ env("APP_URL") }}'; 
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $('#acceptButton').click(function () {
        $.ajax({
            url: `${baseUrl}/admin/user/${userId}/accept-selling-right`,  // Use the base URL
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,  // Include CSRF token
            },
            success: function (data) {
                alert(data.message);  // Show success message
                // Optionally, close the modal here
                $('#exampleModal').modal('hide');
                location.reload();
            },
            error: function () {
                alert('Lỗi khi chấp nhận.');
            }
        });
    });

    // Handle Reject action (Từ chối)
    $('#rejectButton').click(function () {
        // Show the rejection reason modal
        $('#rejectReasonModal').modal('show');
    });

    $('#confirmRejectButton').click(function () {
        var rejectReason = $('#rejectReason').val().trim();  // Get the reason input

        // Check if the reason is provided
        if (rejectReason === '') {
            alert('Hãy điền lý do từ chối yêu cầu này.');
            return;
        }

        // Proceed with the AJAX request to reject the selling right
        $.ajax({
            url: `${baseUrl}/admin/user/${userId}/reject-selling-right`,  // Define route in web.php
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,  // Include CSRF token
            },
            data: {
                reason: rejectReason  // Send the reason in the request body
            },
            success: function (data) {
                alert(data.message);  // Show success message
                $('#rejectReasonModal').modal('hide');  // Hide the reason modal
                $('#exampleModal').modal('hide');  // Optionally, hide the user details modal
                location.reload();  // Reload the page to reflect the changes
            },
            error: function () {
                alert('Lỗi không xác định. Vui lòng liên hệ nhà phát triển.');
            }
        });
    });



    </script>
@endpush
   

</body>
</html>


</body>

</html>