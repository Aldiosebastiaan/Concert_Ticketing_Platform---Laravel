<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Public landing page — all events
     */
    public function publicIndex(Request $request)
    {
        $query = Event::with(['kategori', 'tikets']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $events    = $query->orderBy('tanggal_waktu', 'asc')->paginate(12);
        $kategoris = Kategori::all();
        $featuredEvent = Event::with(['kategori', 'tikets'])->orderBy('tanggal_waktu', 'desc')->first();

        return view('pages.public.events.index', compact('events', 'kategoris', 'featuredEvent'));
    }

    public function index(Request $request)
    {
        // 1. Load events dengan relationships: kategori dan tikets
        $query = Event::with(['kategori', 'tikets']);

        // 2. Filter by kategori_id jika parameter ada
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // 3. Search by judul atau lokasi jika parameter search ada
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // 4. Sort by tanggal_waktu (asc/desc)
        $sort = $request->get('sort', 'asc');
        $query->orderBy('tanggal_waktu', $sort);

        // 5. Paginate dengan 10 items per page
        $events = $query->paginate(10);
        $kategoris = Kategori::all();

        return view('pages.admin.events.index', compact('events', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('pages.admin.events.create', compact('kategoris'));
    }

    // 1. Validasi input menggunakan EventFormRequest
    public function store(EventFormRequest $request)
    {
        DB::beginTransaction();
        try {
            // 2. Handle image upload
            $gambarPath = 'konser.jpg'; // fallback
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('events', 'public');
            }

            // 3. Create event dengan data dari form
            $event = Event::create([
                'user_id' => auth()->id(),
                'kategori_id' => $request->kategori_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'lokasi' => $request->lokasi,
                'gambar' => $gambarPath,
                'tanggal_waktu' => $request->tanggal_waktu,
            ]);

            // 4. Create tickets (loop through $request->tikets)
            foreach ($request->tikets as $tiketData) {
                $event->tikets()->create([
                    'tipe' => $tiketData['tipe'],
                    'harga' => $tiketData['harga'],
                    'stok' => $tiketData['stok'],
                ]);
            }

            DB::commit();
            // 5. Redirect ke index dengan success message
            return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Event $event)
    {
        // Load event dan kategoris, Load tikets dari event
        $event->load(['kategori', 'tikets']);
        $kategoris = Kategori::all();
        
        // Cek $event->hasSales() dan pass ke view
        $hasSales = $event->hasSales();
        
        return view('pages.admin.events.edit', compact('event', 'kategoris', 'hasSales'));
    }


    public function update(EventFormRequest $request, Event $event)
    {
        DB::beginTransaction();
        try {
            $hasSales = $event->hasSales();

            // 2. Jika event sudah terjual (hasSales()): Tampilkan error jika tanggal_waktu berubah
            if ($hasSales && $event->tanggal_waktu->format('Y-m-d H:i:s') !== date('Y-m-d H:i:s', strtotime($request->tanggal_waktu))) {
                return back()->with('error', 'Tanggal dan waktu tidak dapat diubah karena event sudah memiliki penjualan tiket.')->withInput();
            }

            // 3. Handle image update (hapus old image jika ada)
            $gambarPath = $event->gambar;
            if ($request->hasFile('gambar')) {
                if ($gambarPath && $gambarPath !== 'konser.jpg' && !filter_var($gambarPath, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($gambarPath);
                }
                $gambarPath = $request->file('gambar')->store('events', 'public');
            }

            // 4. Update event data
            $event->update([
                'kategori_id' => $request->kategori_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'lokasi' => $request->lokasi,
                'gambar' => $gambarPath,
                'tanggal_waktu' => $request->tanggal_waktu,
            ]);

            // 5. Handle tickets
            $requestedTicketIds = collect($request->tikets)->pluck('id')->filter()->toArray();
            
            // Delete removed tickets (hanya jika belum ada penjualan)
            if (!$hasSales) {
                $event->tikets()->whereNotIn('id', $requestedTicketIds)->delete();
            }

            // Update existing or create new tickets
            foreach ($request->tikets as $tiketData) {
                if (isset($tiketData['id']) && $tiketData['id']) {
                    // Update existing tickets
                    $tiket = Tiket::find($tiketData['id']);
                    if ($tiket && $tiket->event_id === $event->id) {
                        $tiket->update([
                            'tipe' => $tiketData['tipe'],
                            'harga' => $tiketData['harga'],
                            'stok' => $tiketData['stok'],
                        ]);
                    }
                } else {
                    // Create new tickets
                    $event->tikets()->create([
                        'tipe' => $tiketData['tipe'],
                        'harga' => $tiketData['harga'],
                        'stok' => $tiketData['stok'],
                    ]);
                }
            }

            DB::commit();
            // 6. Redirect dengan success message
            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Event $event)
    {
        // 1. Cek apakah event memiliki penjualan
        if ($event->hasSales()) {
            // 2. Jika ya, return dengan error message
            return back()->with('error', 'Event tidak dapat dihapus karena sudah memiliki penjualan tiket.');
        }

        // 3. Jika tidak: Hapus image dari storage (jika bukan default)
        if ($event->gambar && $event->gambar !== 'konser.jpg' && !filter_var($event->gambar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($event->gambar);
        }
        
        // Delete event
        $event->delete();

        // 4. Redirect dengan success message
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function show(Event $event)
    {
        // Detail event dengan relationships
        $event->load(['kategori', 'tikets', 'user']);

        // Related events (kategori sama, tanggal > now, max 4 events)
        $relatedEvents = Event::where('kategori_id', $event->kategori_id)
            ->where('id', '!=', $event->id)
            ->upcoming()
            ->take(4)
            ->get();

        return view('pages.public.events.show', compact('event', 'relatedEvents'));
    }
}
