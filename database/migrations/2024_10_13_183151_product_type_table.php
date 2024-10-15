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
        Schema::create('product_type', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('type')->onDelete('cascade');
            $table->primary(['product_id', 'type_id']); // Composite primary key
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_type');
    }
};
