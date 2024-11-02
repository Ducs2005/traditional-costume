$(document).ready(function() {
    // Xử lý xác minh OTP
    $('#submit-otp').click(function(e) {
        e.preventDefault();
        var formData = new FormData($('#twoStepsForm')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('forgot_otp') }}", // Đường dẫn đến route xử lý OTP
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                toastr.success(data.message); // Hiển thị thông báo thành công

                let path = document.querySelector('html').getAttribute('data-path');
                setTimeout(() => {
                    window.location.href = path + 'login'; // Chuyển hướng sau 2 giây để user thấy thông báo
                }, 2000);
            },
            error: function(a) {
                let error = a['responseJSON'].message;
                $('.message-error').remove(); // Xóa thông báo lỗi trước đó

                $('#twoStepsForm .fv-plugins-icon-container').addClass('fv-plugins-bootstrap5-row-invalid');

                if (error) {
                    $('#otp').parent().append('<div class="fv-plugins-message-container invalid-feedback message-error"><div data-field="otp" data-validator="notEmpty">' + error + '</div></div>');
                    toastr.error(error); // Hiển thị thông báo lỗi
                } else {
                    $('#otp').parent().append('<div class="fv-plugins-message-container invalid-feedback message-error"><div data-field="otp" data-validator="notEmpty">' + a['responseJSON'].otp + '</div></div>');
                    toastr.error(a['responseJSON'].otp); // Hiển thị lỗi từ phản hồi của server
                }
            }
        });
    });

    // Xử lý gửi lại OTP
    $('#re-forgot').click(function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('token', "{{ $token }}"); // Thêm token từ server
        formData.append('_token', "{{ csrf_token() }}"); // CSRF token

        $.ajax({
            type: "POST",
            url: '/re_forgot', // Đường dẫn đến route gửi lại OTP
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                toastr.success("OTP has been sent."); // Thông báo khi gửi thành công
            },
            error: function(a) {
                toastr.error("An error occurred while resending OTP."); // Thông báo khi có lỗi
            }
        });
    });
});
