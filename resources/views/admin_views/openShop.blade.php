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
                                        <td>{{ $user->phone }}</td>
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
                    <button type="button" class="btn btn-success">Approve</button>
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
        function viewUserDetails(userId) {
            const baseUrl = '{{ url('/') }}'; // Dynamically fetch your app's base URL

            $.ajax({
                url: `${baseUrl}/admin/user/${userId}`,
                method: 'GET',
                success: function (data) {
                    $('#userName').text(data.name);
                    $('#userEmail').text(data.email);
                    $('#userPhone').text(data.phone);
                    $('#userAddress').text(data.address || 'N/A');
                    $('#userRequestDate').text(data.created_at);
                },
                error: function () {
                    alert('Error loading user details.');
                }
            });
        }


    </script>
@endpush
   

</body>
</html>


</body>

</html>