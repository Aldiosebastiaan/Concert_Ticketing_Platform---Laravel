<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            ['nama_lokasi' => 'Stadion Utama', 'aktif' => 'Y'],
            ['nama_lokasi' => 'Galeri Seni Kota', 'aktif' => 'Y'],
            ['nama_lokasi' => 'Taman Kota', 'aktif' => 'Y'],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
