# Sistem Pengaduan Fasilitas Kampus

Sistem informasi berbasis web untuk mengelola pengaduan fasilitas kampus menggunakan Laravel 11.

## Fitur Utama

1. **Manajemen Pengaduan** - Membuat, melihat, dan mengelola pengaduan fasilitas
2. **Data Fasilitas** - Mengelola data fasilitas dan kategori fasilitas
3. **Monitoring & Status** - Memantau status pengaduan dan penanganan
4. **Laporan & Rekap** - Menampilkan laporan dan rekap pengaduan
5. **Manajemen User** - Mengelola pengguna sistem (Mahasiswa, Dosen, Admin)
6. **Validasi Data** - Validasi pengaduan sebelum diproses

## Teknologi

- **Framework**: Laravel 11
- **Database**: SQLite / MySQL
- **Frontend**: Bootstrap 5
- **PDF Export**: DomPDF
- **Excel Export**: Maatwebsite Excel
- **API**: API Wilayah Indonesia (https://github.com/farizdotid/DAFTAR-API-LOKAL-INDONESIA)

## Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- MySQL (opsional jika menggunakan SQLite)
- Node.js & NPM (opsional)

### Langkah Instalasi

1. Clone repository atau extract project
   ```bash
   cd tubes-laravel
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Copy file environment
   ```bash
   cp .env.example .env
   ```

4. Generate application key
   ```bash
   php artisan key:generate
   ```

5. Konfigurasi database di file `.env`

   **Opsi 1: SQLite (Default)**
   Pastikan konfigurasi berikut ada di file `.env`:
   ```env
   DB_CONNECTION=sqlite
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=laravel
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```
   *File database akan otomatis dibuat di `database/database.sqlite` saat menjalankan migrasi.*

   **Opsi 2: MySQL**
   Jika ingin menggunakan MySQL, ubah konfigurasi menjadi:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

6. Jalankan migrasi dan seeder
   ```bash
   php artisan migrate --seed
   ```

7. Buat symbolic link untuk storage
   ```bash
  
   ```

8. Jalankan server development
   ```bash
   php artisan serve
   ```

9. Akses aplikasi di browser
   ```
   http://localhost:8000
   ```

## Akun Default

Setelah menjalankan seeder, akun default yang tersedia:

- **Admin**
  - Email: admin@example.com
  - Password: password

- **Mahasiswa**
  - Email: surya@example.com
  - Password: password

- **Dosen**
  - Email: dosen@example.com
  - Password: password

## Struktur Database

### Tabel Utama

- `users` - Data pengguna (Mahasiswa, Dosen, Admin)
- `facility_categories` - Kategori fasilitas
- `facilities` - Data fasilitas kampus
- `complaints` - Data pengaduan
- `tindak_lanjut` - Riwayat penanganan pengaduan

## Penggunaan

### Untuk Mahasiswa/Dosen

1. Login dengan akun yang sesuai
2. Buat pengaduan baru melalui menu "Buat Pengaduan Baru"
3. Isi form pengaduan lengkap dengan foto kerusakan
4. Pantau status pengaduan di halaman "Daftar Pengaduan"

### Untuk Admin

1. Login dengan akun admin
2. Kelola fasilitas dan kategori di menu "Fasilitas"
3. Validasi pengaduan di menu "Validasi"
4. Monitor pengaduan di menu "Monitoring"
5. Lihat laporan di menu "Laporan"
6. Kelola user di menu "User"

## Export Data

Sistem menyediakan fitur export data dalam format:

- **PDF**: Laporan pengaduan individual, daftar fasilitas, laporan monitoring, rekap pengaduan, data user
- **Excel/CSV**: Rekap pengaduan, data fasilitas, data user

## API Wilayah Indonesia

Sistem mengintegrasikan API Wilayah Indonesia untuk:
- Dropdown Provinsi
- Dropdown Kota/Kabupaten (berdasarkan Provinsi)
- Dropdown Kecamatan (berdasarkan Kota/Kabupaten)

API endpoint tersedia di:
- `/api/wilayah/provinces`
- `/api/wilayah/regencies/{provinceId}`
- `/api/wilayah/districts/{regencyId}`

## Tim Pengembang

1. Surya Darmawan (Ketua) - Manajemen Pengaduan & Security
2. Queen Naomi Liklikwatil - Data Fasilitas & Database Design
3. Tania Syifa Fadillah - Monitoring & Status Pengaduan
4. Khalifa Almaira Setia - Laporan & Rekap Data
5. Rifka Aisyah Safitri - Validasi Data & Manajemen User

## Lisensi

Proyek ini dibuat untuk keperluan akademik.
