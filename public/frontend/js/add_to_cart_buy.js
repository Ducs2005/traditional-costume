document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll('.payment, .buy, .payment-btn, .buyBtn');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');

            if (this.classList.contains('payment') || this.classList.contains('buy')) {

                Swal.fire ({
                    title: 'Oops! TT',
                    text: 'Bạn phải đăng nhập mới thực hiện được tác vụ này.',
                    icon: 'error',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './account/login';
                    }
                });
            }

            if (this.classList.contains('payment-btn')) {
                const addToCartForm = document.getElementById(`add-to-cart-form-${productId}`);
                const formData = new FormData(addToCartForm);
                const xhr = new XMLHttpRequest();
                xhr.open("POST", addToCartForm.action, true);
                xhr.setRequestHeader('X-CSRF-TOKEN',
                    document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Thêm vào giỏ hàng thành công!",
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (xhr.readyState === 4) {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Lỗi khi thêm vào giỏ hàng!",
                            showConfirmButton: true
                        });
                    }
                };
                xhr.send(formData);
            }
            else if (this.classList.contains('buyBtn')) {
                const addToCartForm = document.getElementById(`add-to-cart-form-${productId}`);
                const paymentForm = document.getElementById(`payment-form-${productId}`);

                const xhr = new XMLHttpRequest();
                xhr.open("POST", addToCartForm.action, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        paymentForm.querySelector('.product_id').value = productId;
                        paymentForm.submit();
                    }
                };

                const formData = new FormData(addToCartForm);
                let encodedData = '';
                for (let pair of formData.entries()) {
                    if (encodedData.length > 0) {
                        encodedData += '&';
                    }
                    encodedData += encodeURIComponent(pair[0]) + '=' + encodeURIComponent(pair[1]);
                }
                xhr.send(encodedData);
            }
        });
    });
});
