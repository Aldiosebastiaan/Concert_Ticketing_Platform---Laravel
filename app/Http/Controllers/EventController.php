<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{

    /**
     * Public landing page — all events
     */
    public function publicIndex(Request $request)
    {
        $query = Event::with(['kategori', 'lokasi', 'tikets'])->where('status_publikasi', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('lokasi', function ($q2) use ($search) {
                      $q2->where('nama_lokasi', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $events    = $query->orderBy('tanggal_waktu', 'asc')->paginate(12);
        $kategoris = Kategori::all();
        $featuredEvent = Event::with(['kategori', 'tikets'])->where('status_publikasi', 'published')->orderBy('tanggal_waktu', 'desc')->first();

        return view('pages.public.events.index', compact('events', 'kategoris', 'featuredEvent'));
    }


    public function index(Request $request)
    {
        // 1. Load events dengan relationships: kategori, lokasi dan tikets
        $query = Event::with(['kategori', 'lokasi', 'tikets']);

        // 2. Filter by kategori_id jika parameter ada
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // 3. Search by judul atau lokasi jika parameter search ada
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('lokasi', function($q2) use ($search) {
                      $q2->where('nama_lokasi', 'like', "%{$search}%");
                  });
            });
        }

        // 4. Sort by tanggal_waktu (asc/desc)
        $sort = $request->get('sort', 'asc');
        $query->orderBy('tanggal_waktu', $sort);

        // 5. Paginate dengan limit items per page
        $limit = $request->get('limit', 10);
        $events = $query->paginate($limit);
        $kategoris = Kategori::all();

        return view('pages.admin.events.index', compact('events', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::where('aktif', 'Y')->orderBy('nama_lokasi', 'asc')->get();
        return view('pages.admin.events.create', compact('kategoris', 'lokasis'));
    }

    // 1. Validasi input menggunakan EventFormRequest
    public function store(EventFormRequest $request)
    {
        DB::beginTransaction();
        try {
            // 2. Handle image upload (with cropper base64 support)
            $gambarPath = 'konser.jpg'; // fallback
            if ($request->has('gambar_base64') && !empty($request->gambar_base64)) {
                $imageParts = explode(";base64,", $request->gambar_base64);
                if (count($imageParts) == 2) {
                    $imageTypeAux = explode("image/", $imageParts[0]);
                    $imageType = $imageTypeAux[1];
                    $imageBase64 = base64_decode($imageParts[1]);
                    $fileName = 'events/' . uniqid() . '.' . $imageType;
                    Storage::disk('public')->put($fileName, $imageBase64);
                    $gambarPath = $fileName;
                }
            } elseif ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('events', 'public');
            }

            // 3. Create event dengan data dari form
            $event = Event::create([
                'user_id' => auth()->id() ?? 1,
                'kategori_id' => $request->kategori_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'lokasi_id' => $request->lokasi_id,
                'gambar' => $gambarPath,
                'tanggal_waktu' => $request->tanggal_waktu,
                'status_publikasi' => $request->status_publikasi ?? 'published',
            ]);

            // Catat history
            $event->statusHistories()->create([
                'user_id' => auth()->id() ?? 1,
                'status_sebelumnya' => null,
                'status_baru' => $event->status_publikasi,
                'catatan' => 'Event dibuat',
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
        $event->load(['kategori', 'lokasi', 'tikets']);
        $kategoris = Kategori::all();
        // Ambil semua lokasi yg aktif, tapi kalau lokasi yg tersimpan di event ini kebetulan sedang non-aktif,
        // kita perlu includekan juga supaya ga error/hilang dari select dropdown.
        $lokasis = Lokasi::where('aktif', 'Y')->orWhere('id', $event->lokasi_id)->orderBy('nama_lokasi', 'asc')->get();
        
        // Cek $event->hasSales() dan pass ke view
        $hasSales = $event->hasSales();
        
        return view('pages.admin.events.edit', compact('event', 'kategoris', 'lokasis', 'hasSales'));
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

            // 3. Handle image update (with cropper base64 support)
            $gambarPath = $event->gambar;
            $newImageUploaded = false;

            if ($request->has('gambar_base64') && !empty($request->gambar_base64)) {
                $imageParts = explode(";base64,", $request->gambar_base64);
                if (count($imageParts) == 2) {
                    $imageTypeAux = explode("image/", $imageParts[0]);
                    $imageType = $imageTypeAux[1];
                    $imageBase64 = base64_decode($imageParts[1]);
                    $fileName = 'events/' . uniqid() . '.' . $imageType;
                    Storage::disk('public')->put($fileName, $imageBase64);
                    $gambarPath = $fileName;
                    $newImageUploaded = true;
                }
            } elseif ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('events', 'public');
                $newImageUploaded = true;
            }

            // Hapus old image jika ada gambar baru
            if ($newImageUploaded) {
                if ($event->gambar && $event->gambar !== 'konser.jpg' && !filter_var($event->gambar, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($event->gambar);
                }
            }

            // 4. Update event data
            $oldStatus = $event->status_publikasi;
            $event->update([
                'kategori_id' => $request->kategori_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'lokasi_id' => $request->lokasi_id,
                'gambar' => $gambarPath,
                'tanggal_waktu' => $request->tanggal_waktu,
                'status_publikasi' => $request->status_publikasi ?? $event->status_publikasi,
            ]);

            if ($oldStatus !== $event->status_publikasi) {
                $event->statusHistories()->create([
                    'user_id' => auth()->id() ?? 1,
                    'status_sebelumnya' => $oldStatus,
                    'status_baru' => $event->status_publikasi,
                    'catatan' => 'Status diubah via edit event',
                ]);
            }

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
        // 1. Cek apakah event memiliki penjualan, kecuali jika event sudah 'Completed'
        if ($event->status !== 'Completed') {
            if ($event->hasSales()) {
                return back()->with('error', 'Event tidak dapat dihapus karena sudah memiliki penjualan tiket.');
            }
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
        $event->load(['kategori', 'lokasi', 'tikets', 'user']);

        // Related events (kategori sama, tanggal > now, max 4 events)
        $relatedEvents = Event::where('kategori_id', $event->kategori_id)
            ->where('id', '!=', $event->id)
            ->upcoming()
            ->take(4)
            ->get();

        return view('pages.public.events.show', compact('event', 'relatedEvents'));
    }

    public function export()
    {
        return Excel::download(new EventsExport, 'events.xlsx');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada event yang dipilih.');
        }

        $events = Event::whereIn('id', $ids)->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($events as $event) {
            if ($event->status !== 'Completed' && $event->hasSales()) {
                $skipped++;
            } else {
                if ($event->gambar && $event->gambar !== 'konser.jpg' && !filter_var($event->gambar, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($event->gambar);
                }
                $event->delete();
                $deleted++;
            }
        }

        if ($skipped > 0) {
            return back()->with('warning', "$deleted event berhasil dihapus. $skipped event dilewati karena sudah memiliki penjualan tiket dan belum selesai (Completed).");
        }

        return back()->with('success', "$deleted event berhasil dihapus.");
    }

    public function clone(Event $event)
    {
        DB::beginTransaction();
        try {
            $newEvent = $event->replicate();
            $newEvent->judul = $event->judul . ' (Copy)';
            $newEvent->save();

            foreach ($event->tikets as $tiket) {
                $newTiket = $tiket->replicate();
                $newTiket->event_id = $newEvent->id;
                $newTiket->save();
            }

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diduplikasi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menduplikasi event: ' . $e->getMessage());
        }
    }
}
