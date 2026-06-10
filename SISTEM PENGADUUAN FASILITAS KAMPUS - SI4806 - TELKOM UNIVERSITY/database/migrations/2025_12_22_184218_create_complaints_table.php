<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            
            // Data Utama
            $table->string('title', 255);
            $table->text('description');
            
            // Lokasi
            $table->string('campus', 100); // <-- TAMBAHAN PENTING (Sesuai Controller Anda)
            $table->string('location', 255); // Detail lokasi (misal: Gedung A Lt 1)
            
            // Data API Wilayah (Boleh nullable jika tidak wajib)
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            
            // Bukti & Status
            $table->string('photo', 255)->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Rejected'])->default('Pending');
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};