<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun untuk Anda (Ketua/Admin)
        User::create([
            'name' => 'Surya Darmawan',
            'email' => 'surya@admin.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
            'nim_nip' => '102022400338',
            'status' => 'Aktif',
        ]);

        // Akun contoh untuk Mahasiswa
        User::create([
            'name' => 'Mahasiswa Contoh',
            'email' => 'mhs@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Mahasiswa',
            'nim_nip' => '1234567890',
            'status' => 'Aktif',
        ]);
    }
}