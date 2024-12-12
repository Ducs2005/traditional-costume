<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cophuc', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên trang phục
            $table->text('description'); // Mô tả
            $table->text('detail'); //Xem chi tiết
            $table->string('image')->nullable(); // Link ảnh
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cophuc');
    }
};
