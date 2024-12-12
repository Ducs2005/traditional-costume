<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('button', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('buttons', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};

