<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->enum('sender', ['Quản trị viên', 'Hệ thống'])->default('Hệ thống');
            $table->text('content');
            $table->unsignedBigInteger('receiver_id')->nullable(); // Receiver ID
            $table->enum('receiver_type', ['all', 'one'])->default('all'); // Receiver type
            $table->timestamps(); // Created at and updated at

            // Foreign key constraint for receiver_id
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
