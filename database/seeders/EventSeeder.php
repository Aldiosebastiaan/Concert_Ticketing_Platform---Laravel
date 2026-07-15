<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'user_id' => 1,
                'judul' => 'The Eras Tour - Taylor Swift',
                'deskripsi' => 'Konser spektakuler Taylor Swift yang membawa kita melintasi semua era musiknya. Jangan lewatkan pengalaman magis ini.',
                'tanggal_waktu' => '2024-11-20 19:00:00',
                'lokasi' => 'Stadion Utama Gelora Bung Karno, Jakarta',
                'kategori_id' => 1,
                'gambar' => 'https://images.unsplash.com/photo-1540039155733-d7696d4eb98b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'user_id' => 1,
                'judul' => 'Music of the Spheres - Coldplay',
                'deskripsi' => 'Pengalaman visual dan audio luar biasa dari band rock legendaris Coldplay. Tur ramah lingkungan yang menggebrak dunia.',
                'tanggal_waktu' => '2024-12-05 20:00:00',
                'lokasi' => 'Jakarta International Stadium',
                'kategori_id' => 2,
                'gambar' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'user_id' => 1,
                'judul' => 'BORN PINK World Tour',
                'deskripsi' => 'Konser girl group K-Pop paling hits saat ini dengan penampilan energik, tata panggung spektakuler, dan lagu-lagu viral.',
                'tanggal_waktu' => '2024-10-15 18:30:00',
                'lokasi' => 'ICE BSD, Tangerang',
                'kategori_id' => 3,
                'gambar' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'user_id' => 1,
                'judul' => 'Tomorrowland Asia 2024',
                'deskripsi' => 'Festival musik EDM terbesar di dunia kini hadir dengan panggung megah di Asia. Mari berdansa di bawah bintang.',
                'tanggal_waktu' => '2024-12-29 15:00:00',
                'lokasi' => 'Garuda Wisnu Kencana, Bali',
                'kategori_id' => 4,
                'gambar' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'user_id' => 1,
                'judul' => 'Java Jazz Festival 2025',
                'deskripsi' => 'Festival musik jazz tahunan terbesar yang menampilkan artis lokal dan internasional terbaik dari berbagai genre musik terkait.',
                'tanggal_waktu' => '2025-03-01 14:00:00',
                'lokasi' => 'JIExpo Kemayoran, Jakarta',
                'kategori_id' => 5,
                'gambar' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
        ];
        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
