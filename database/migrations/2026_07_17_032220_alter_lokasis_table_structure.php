<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah struktur
        Schema::table('lokasis', function (Blueprint $table) {
            $table->renameColumn('nama', 'nama_lokasi');
        });

        Schema::table('lokasis', function (Blueprint $table) {
            $table->char('aktif', 1)->default('Y')->after('nama_lokasi');
        });

        // 2. Data cleaning
        // Ambil ID untuk "Stadion Utama", "Galeri Seni Kota", "Taman Kota"
        $stadionUtama = DB::table('lokasis')->where('nama_lokasi', 'Stadion Utama')->first();
        if ($stadionUtama) {
            // Pindahkan semua event ke Stadion Utama
            DB::table('events')->update(['lokasi_id' => $stadionUtama->id]);
            
            // Hapus lokasi selain 3 lokasi tersebut
            $validLokasi = ['Stadion Utama', 'Galeri Seni Kota', 'Taman Kota'];
            DB::table('lokasis')->whereNotIn('nama_lokasi', $validLokasi)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasis', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });

        Schema::table('lokasis', function (Blueprint $table) {
            $table->renameColumn('nama_lokasi', 'nama');
        });
    }
};
