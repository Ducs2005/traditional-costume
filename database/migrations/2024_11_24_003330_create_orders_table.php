<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Chạy migration để tạo bảng orders.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();  // Mã đơn hàng tự tăng
            $table->string('user');  // Người nhận đơn hàng
            $table->enum('status', ['pending', 'delivering', 'delivered', 'cancelled'])->default('pending');  // Trạng thái đơn hàng
            $table->enum('payment_method', ['credit_card', 'paypal', 'cash_on_delivery'])->default('cash_on_delivery');  // Phương thức thanh toán
            $table->timestamps();  // Tạo trường created_at và updated_at
        });
    }

    /**
     * Đảo ngược migration (xóa bảng orders).
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
