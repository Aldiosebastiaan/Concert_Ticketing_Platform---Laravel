<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_lokasi', 'like', '%' . $request->search . '%');
        }

        if ($request->has('aktif') && $request->aktif != '') {
            $query->where('aktif', $request->aktif);
        }

        $sort = $request->get('sort', 'asc');
        $query->orderBy('nama_lokasi', $sort);

        $limit = $request->get('limit', 10);
        $lokasis = $query->paginate($limit);

        return view('pages.admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('pages.admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255|unique:lokasis,nama_lokasi',
            'aktif' => 'required|in:Y,N',
        ], [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi.',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada.',
            'aktif.required' => 'Status aktif wajib diisi.',
        ]);

        Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'aktif' => $request->aktif,
        ]);

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('pages.admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255|unique:lokasis,nama_lokasi,' . $lokasi->id,
            'aktif' => 'required|in:Y,N',
        ], [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi.',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada.',
            'aktif.required' => 'Status aktif wajib diisi.',
        ]);

        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'aktif' => $request->aktif,
        ]);

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->events()->exists()) {
            return back()->with('error', 'Lokasi tidak dapat dihapus karena masih digunakan pada event.');
        }

        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada lokasi yang dipilih.');
        }

        $lokasis = Lokasi::whereIn('id', $ids)->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($lokasis as $lokasi) {
            if ($lokasi->events()->exists()) {
                $skipped++;
            } else {
                $lokasi->delete();
                $deleted++;
            }
        }

        if ($skipped > 0) {
            return back()->with('warning', "$deleted lokasi berhasil dihapus. $skipped lokasi dilewati karena masih digunakan pada event.");
        }

        return back()->with('success', "$deleted lokasi berhasil dihapus.");
    }
}
