<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name');
            $table->text('description')->default('')->nullable();
            $table->timestamps(); // Adds created_at and updated_at
        });
        Schema::create('subtype', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('type_id')->constrained('type')->onDelete('cascade');
            $table->text('description')->default('')->nullable();
            $table->string('img_path')->nullable();
            $table->timestamps(); // Adds created_at ancd updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('types');
        Schema::dropIfExists('type_details');

        
    }
};
