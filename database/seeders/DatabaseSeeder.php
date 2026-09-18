<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản Admin tự động
        User::create([
            'name' => 'Quản Trị Viên',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), // Mật khẩu của admin
            'phone' => '0900000000',
            'role' => 'admin', // Gán quyền admin tại đây
<<<<<<< HEAD
            'email_verified_at' => now(),
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        ]);
    }
}