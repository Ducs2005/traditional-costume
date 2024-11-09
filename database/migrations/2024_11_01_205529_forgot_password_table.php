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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'confirm_otp')) {
                $table->string('confirm_otp')->nullable();
            }

            if (!Schema::hasColumn('users', 'token_forgot')) {
                $table->string('token_forgot')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('confirm_otp');
            $table->dropColumn('token_forgot');
        });
    }
};

