<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita mengubah kolom role agar menyertakan Staff dan Teknisi
            $table->enum('role', ['Admin', 'Dosen', 'Mahasiswa', 'Staff', 'Teknisi'])
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke pilihan semula jika migration di-rollback
            $table->enum('role', ['Admin', 'Dosen', 'Mahasiswa'])
                  ->change();
        });
    }
};