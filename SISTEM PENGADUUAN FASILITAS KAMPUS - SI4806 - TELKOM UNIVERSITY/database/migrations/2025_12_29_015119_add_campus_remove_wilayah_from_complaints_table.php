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
        Schema::table('complaints', function (Blueprint $table) {
            // Tambah kolom campus
            $table->string('campus')->nullable()->after('facility_id');
            
            // Hapus kolom province, city, district (jika ada)
            if (Schema::hasColumn('complaints', 'province')) {
                $table->dropColumn('province');
            }
            if (Schema::hasColumn('complaints', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('complaints', 'district')) {
                $table->dropColumn('district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            
            // Hapus kolom campus
            $table->dropColumn('campus');
        });
    }
};