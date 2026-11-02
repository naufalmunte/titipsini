<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // jangan lupa diubah di production
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mitra
        DB::table('users')->insert([
            'name' => 'Mitra User',
            'email' => 'mitra@example.com',
            'password' => Hash::make('password123'),
            'role' => 'mitra',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Finance
        DB::table('users')->insert([
            'name' => 'Finance User',
            'email' => 'finance@example.com',
            'password' => Hash::make('password123'),
            'role' => 'finance',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pelanggan
        DB::table('users')->insert([
            'name' => 'Pelanggan User',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}