<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Tạo tài khoản admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@email',  
            'password' => bcrypt('12345678'),
            'role' => 'admin', 
            'confirm_otp' => null,  // Không cần OTP cho admin
            'token_forgot' => '',  // Cung cấp giá trị mặc định cho cột 'token_forgot'
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
