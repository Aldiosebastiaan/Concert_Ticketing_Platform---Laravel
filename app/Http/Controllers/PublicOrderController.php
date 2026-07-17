<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicOrderController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'tiket_id' => 'required|exists:tikets,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $tiket = Tiket::where('event_id', $event->id)->findOrFail($request->tiket_id);
        $jumlah = (int) $request->jumlah;

        if ($tiket->stok < $jumlah) {
            return back()->with('error', 'Stok tiket tidak mencukupi.');
        }

        try {
            DB::transaction(function () use ($event, $tiket, $jumlah) {
                // Kurangi stok
                $tiket->stok -= $jumlah;
                $tiket->save();

                $subtotal = $tiket->harga * $jumlah;

                // Buat Order (Hardcode user_id = 1 untuk simulasi)
                Order::unguard();
                $order = Order::create([
                    'user_id' => 1,
                    'event_id' => $event->id,
                    'order_date' => now(),
                    'total_harga' => $subtotal,
                ]);
                Order::reguard();

                // Buat Detail Order
                DetailOrder::create([
                    'order_id' => $order->id,
                    'tiket_id' => $tiket->id,
                    'jumlah' => $jumlah,
                    'subtotal_harga' => $subtotal,
                ]);
            });

            return back()->with('success', "Berhasil membeli $jumlah tiket {$tiket->nama}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda.');
        }
    }
}
