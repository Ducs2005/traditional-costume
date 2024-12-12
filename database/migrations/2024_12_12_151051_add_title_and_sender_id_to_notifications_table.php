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
        Schema::table('notifications', function (Blueprint $table) {
            // Add the sender_id column to the notifications table
            $table->unsignedBigInteger('sender_id')->nullable();

            // Add foreign key constraint for sender_id (references users table)
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the foreign key and sender_id column
            $table->dropForeign(['sender_id']);
            $table->dropColumn('sender_id');
        });
    }

    
};
