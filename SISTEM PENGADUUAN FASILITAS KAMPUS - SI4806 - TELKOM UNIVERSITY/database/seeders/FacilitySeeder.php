// database/seeders/FacilitySeeder.php
public function run(): void
{
    // Pastikan kategori 'Fasilitas Umum' ada
    DB::table('facility_categories')->updateOrInsert(
        ['id' => 1],
        ['category_name' => 'Fasilitas Umum', 'created_at' => now(), 'updated_at' => now()]
    );

    $facilities = [
        'Ruang Kelas',
        'Laboratorium',
        'Toilet',
        'Perpustakaan',
        'Kantin',
        'Masjid/Musholla',
        'Lainnya'
    ];

    foreach ($facilities as $name) {
        DB::table('facilities')->insert([
            'facility_name' => $name,
            'category_id' => 1,
            'description' => 'Fasilitas ' . $name . ' umum',
            'location' => 'Seluruh Area Kampus',
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}