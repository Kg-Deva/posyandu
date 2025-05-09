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
        User::create([
            'name' => 'Admin',
            'level' => 'adminpengelola',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Admin',
            'level' => 'adminpengelola',
            'email' => 'adminlpq@gmail.com',
            'password' => bcrypt('adminlpq123'),
        ]);

        User::create([
            'name' => 'coba',
            'level' => 'adminkonten',
            'email' => 'coba@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
