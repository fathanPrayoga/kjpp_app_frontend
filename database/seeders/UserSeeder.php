<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Akun Karyawan
    \App\Models\User::create([
        'name' => 'Aditya Karyawan',
        'email' => 'karyawan@test.com',
        'password' => bcrypt('password'),
        'role' => 'karyawan',
    ]);

    // Akun Client
    \App\Models\User::create([
        'name' => 'Asri Client',
        'email' => 'client@test.com',
        'password' => bcrypt('password'),
        'role' => 'client',
    ]);

    // Akun Pekerja Tambahan
    \App\Models\User::create([
        'name' => 'Anto Pekerja',
        'email' => 'pekerja@test.com',
        'password' => bcrypt('password'),
        'role' => 'pekerjaTambahan',
    ]);
}
}
