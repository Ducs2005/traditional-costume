<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Chạy migration để tạo bảng order_items.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();  // Mã sản phẩm trong đơn hàng tự tăng
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Liên kết với bảng orders
            $table->unsignedBigInteger('product_id'); // Refers to products in the product tablephp 
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->integer('quantity');  // Số lượng sản phẩm
            $table->decimal('price', 10, 2);  // Giá sản phẩm
            $table->timestamps();  // Thời gian tạo và cập nhật

        });
    }

    /**
     * Đảo ngược migration (xóa bảng order_items).
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
