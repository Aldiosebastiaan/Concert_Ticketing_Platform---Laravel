<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Pop'],
            ['nama_kategori' => 'Rock'],
            ['nama_kategori' => 'K-Pop'],
            ['nama_kategori' => 'EDM'],
            ['nama_kategori' => 'Festival'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create(['nama' => $kategori['nama_kategori']]);
        }
    }
}
