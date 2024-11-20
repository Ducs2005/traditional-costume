<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
    
        Schema::table('product', function (Blueprint $table) {
            $table->integer('quantity')->default(0); // Number of items in stock
            $table->integer('sold')->default(0);     // Number of items sold
        });
    
        Schema::table('users', function (Blueprint $table) {
            $table->enum('selling_right', ['yes', 'no', 'waiting'])->default('no')->after('role');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'sold']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('selling_right');
        });
    }
};
