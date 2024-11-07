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
        Schema::create('colors', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name')->unique(); // Color name, unique to avoid duplicates
            $table->timestamps(); // Adds created_at and updated_at
        });
        Schema::create('materials', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name')->unique(); // Material name, unique to avoid duplicates
            $table->timestamps(); // Adds created_at and updated_at
        });
        Schema::create('button', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name')->unique(); // Size name, unique to avoid duplicates
            $table->timestamps(); // Adds created_at and updated_at
        });
            
        Schema::create('product', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('img_path')->nullable();
            $table->string('button')->nullable();
            $table->decimal('price', 10, 2);
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('set null');
            $table->foreignId('material_id')->nullable()->constrained('materials')->onDelete('set null');
            $table->foreignId('size_id')->nullable()->constrained('sizes')->onDelete('set null');
            $table->timestamps(); // Adds created_at and updated_at
        });
        Schema::create('product_image', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('img_path');
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }

    
};
