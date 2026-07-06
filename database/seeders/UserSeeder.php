<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Zahra Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Akun Kasir
        User::create([
            'name' => 'Zahra Kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        // 3. Akun Staff Gudang
        User::create([
            'name' => 'Budi Gudang',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
    }
}