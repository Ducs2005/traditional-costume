<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('rating')->unsigned()->checkBetween(1, 5); // Rating scale 1 to 5
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('product_ratings');
    }
}
