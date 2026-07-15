<?php

namespace Database\Seeders;

use App\Models\Tiket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            // Event 1 - The Eras Tour
            ['event_id' => 1, 'tipe' => 'premium', 'harga' => 3500000, 'stok' => 500],
            ['event_id' => 1, 'tipe' => 'reguler', 'harga' => 1500000, 'stok' => 1500],
            // Event 2 - Coldplay
            ['event_id' => 2, 'tipe' => 'premium', 'harga' => 4000000, 'stok' => 300],
            ['event_id' => 2, 'tipe' => 'reguler', 'harga' => 2000000, 'stok' => 2000],
            // Event 3 - BORN PINK
            ['event_id' => 3, 'tipe' => 'premium', 'harga' => 2800000, 'stok' => 800],
            ['event_id' => 3, 'tipe' => 'reguler', 'harga' => 1200000, 'stok' => 1200],
            // Event 4 - Tomorrowland
            ['event_id' => 4, 'tipe' => 'premium', 'harga' => 5000000, 'stok' => 200],
            ['event_id' => 4, 'tipe' => 'reguler', 'harga' => 2500000, 'stok' => 1000],
            // Event 5 - Java Jazz
            ['event_id' => 5, 'tipe' => 'premium', 'harga' => 1500000, 'stok' => 600],
            ['event_id' => 5, 'tipe' => 'reguler', 'harga' => 800000, 'stok' => 2000],
        ];
        foreach ($tickets as $ticket) {
            Tiket::create($ticket);
        }
    }
}
