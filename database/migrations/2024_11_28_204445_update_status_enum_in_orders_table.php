<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Sử dụng raw SQL để thay đổi ENUM
            \DB::statement("ALTER TABLE orders MODIFY status ENUM('Chờ xác nhận', 'Đang giao', 'Đã nhận', 'Đã hủy') NOT NULL");
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Khôi phục trạng thái cũ nếu cần
            \DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'completed', 'canceled') NOT NULL");
        });
    }
}
