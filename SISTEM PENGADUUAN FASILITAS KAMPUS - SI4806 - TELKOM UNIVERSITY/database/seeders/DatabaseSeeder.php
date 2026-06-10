<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FacilityCategory;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'nim_nip' => 'ADMIN001',
                'status' => 'Aktif',
            ]
        );

        // Create Sample Users
        User::firstOrCreate(
            ['email' => 'surya@example.com'],
            [
                'name' => 'Surya Darmawan',
                'password' => Hash::make('password'),
                'role' => 'Mahasiswa',
                'nim_nip' => '102022400338',
                'status' => 'Aktif',
            ]
        );

        User::firstOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Dr. John Doe',
                'password' => Hash::make('password'),
                'role' => 'Dosen',
                'nim_nip' => 'DOSEN001',
                'status' => 'Aktif',
            ]
        );

        // Create Facility Categories
        $categories = [
            ['category_name' => 'Ruang Kelas'],
            ['category_name' => 'Laboratorium'],
            ['category_name' => 'Sarana Umum'],
            ['category_name' => 'Toilet'],
            ['category_name' => 'Perpustakaan'],
            ['category_name' => 'Kantin'],
        ];

        foreach ($categories as $category) {
            FacilityCategory::firstOrCreate($category);
        }

        // Create Facilities
        $facilities = [
            [
                'facility_name' => 'Ruang Kelas A101',
                'category_id' => 1,
                'description' => 'Ruang kelas untuk mata kuliah teori',
                'location' => 'Gedung A, Lantai 1',
                'status' => 'Aktif',
            ],
            [
                'facility_name' => 'Laboratorium Komputer 1',
                'category_id' => 2,
                'description' => 'Laboratorium untuk praktikum komputer',
                'location' => 'Gedung B, Lantai 2',
                'status' => 'Aktif',
            ],
            [
                'facility_name' => 'Toilet Lantai 1',
                'category_id' => 4,
                'description' => 'Toilet umum lantai 1',
                'location' => 'Gedung A, Lantai 1',
                'status' => 'Aktif',
            ],
            [
                'facility_name' => 'Perpustakaan Pusat',
                'category_id' => 5,
                'description' => 'Perpustakaan utama kampus',
                'location' => 'Gedung C, Lantai 1-3',
                'status' => 'Aktif',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['facility_name' => $facility['facility_name']],
                $facility
            );
        }
    }
}
