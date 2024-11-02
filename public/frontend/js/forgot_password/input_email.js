$(document).ready(function(){
    $('#forgot').click(function(e) {
        e.preventDefault();

        var formData = new FormData($('#forgotPasswordForm')[0]); // Đổi đúng ID form
        $.ajax({
            type: "POST",
            url: "{{ route('forgot-password') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Thêm CSRF token
            },
            success: function(data) {
                let path = document.querySelector('html').getAttribute('data-path');
                window.location.href = path + 'forgot_otp/' + data.token;
            },
            error: function(a) {
                $('.message-error').remove();
                let error = a['responseJSON'].message;

                if (error) {
                    $('input[name="email"]').parent().append('<div class="alert alert-danger message-error" role="alert">' + error + '</div>');
                } else {
                    $('input[name="email"]').parent().append('<div class="alert alert-danger message-error" role="alert">' + a['responseJSON'].email + '</div>');
                }
            }
        });
    });
});
