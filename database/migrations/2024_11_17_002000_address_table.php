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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assuming you have a users table
            $table->string('province')->nullable();  // Store province as string
            $table->string('district')->nullable();  // Store district as string
            $table->string('ward')->nullable();  // Store ward as string
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing address column if it exists
            $table->dropColumn('address');

            // Add the address_id column, which will store the foreign key reference
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
        Schema::table('users', function (Blueprint $table) {
            // Rollback the changes if needed: remove the address_id column
            $table->dropColumn('address_id');

            // Optionally, you could add back the 'address' column here
            // $table->string('address')->nullable();
        });
    }
};
