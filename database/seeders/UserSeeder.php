<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'Admin',
        //     'level' => 'adminpengelola',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('password'),
        //     'status' => 1, // tambahkan ini agar bisa login

        // ]);

        User::create([
            'name' => 'Admin Posyandu',
            'level' => 'admin',
            'email' => 'adminposyandu@gmail.com',
            'password' => bcrypt('admin123'),
            'status' => 1, // <-- ini status aktif
            'type' => 'warga', // <-- tambahkan ini
        ]);

       
    }
}
