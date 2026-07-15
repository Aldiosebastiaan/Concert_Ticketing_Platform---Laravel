@extends('layouts.admin')

@section('title', 'Manajemen Event')
@section('breadcrumb', 'Manajemen Event')

@section('content')

{{-- ─── Page Header ─── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Event</h1>
        <p class="page-subtitle">Kelola semua event dan konser yang tersedia</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Tambah Event
    </a>
</div>

{{-- ─── Alerts ─── --}}
@if(session('success'))
<div class="alert alert-success">
    <svg fill="none" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-error">
    <svg fill="none" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- ─── Filters ─── --}}
<form method="GET" action="{{ route('admin.events.index') }}">
<div class="filter-bar">
    <div class="filter-group" style="flex:1; min-width:180px;">
        <label>Cari Event</label>
        <input type="text" name="search" class="filter-input" placeholder="Judul atau lokasi..." value="{{ request('search') }}">
    </div>
    <div class="filter-group">
        <label>Kategori</label>
        <select name="kategori_id" class="filter-input">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>Urutan</label>
        <select name="sort" class="filter-input">
            <option value="asc" {{ request('sort', 'asc') == 'asc' ? 'selected' : '' }}>Terlama</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru</option>
        </select>
    </div>
    <div style="display:flex; gap:8px; align-items:flex-end;">
        <button type="submit" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.5"/><path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Filter
        </button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</div>
</form>

{{-- ─── Table ─── --}}
<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width:72px">Gambar</th>
                    <th>Event</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($events as $event)
                <tr>
                    {{-- Thumbnail --}}
                    <td>
                        @php $imgUrl = $event->image_url; @endphp
                        @if(filter_var($imgUrl, FILTER_VALIDATE_URL) || str_starts_with($imgUrl, '/storage'))
                            <img src="{{ $imgUrl }}" alt="{{ $event->judul }}" class="event-thumb">
                        @else
                            <div class="event-thumb-placeholder">
                                <svg fill="none" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        @endif
                    </td>

                    {{-- Judul --}}
                    <td>
                        <div class="event-title">{{ $event->judul }}</div>
                        <div class="event-meta">{{ $event->tikets->count() }} tipe tiket</div>
                    </td>

                    {{-- Kategori --}}
                    <td><span style="font-size:13px; color: var(--text-secondary);">{{ $event->kategori->nama ?? '-' }}</span></td>

                    {{-- Tanggal --}}
                    <td>
                        <span style="font-size:13px; font-weight:500;">{{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('d M Y') }}</span>
                        <div class="event-meta">{{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('H:i') }} WIB</div>
                    </td>

                    {{-- Lokasi --}}
                    <td>
                        <span style="font-size:13px; max-width:160px; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $event->lokasi }}</span>
                    </td>

                    {{-- Status --}}
                    <td>
                        @php $status = $event->status; @endphp
                        <span class="badge badge-{{ strtolower($status) }}">{{ $status }}</span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-ghost btn-sm" title="Lihat">
                                <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                            </a>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-ghost btn-sm" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus event ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </div>
                            <div class="empty-state-title">Belum Ada Event</div>
                            <div class="empty-state-desc">Mulai tambahkan event konser pertama Anda</div>
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary" style="margin-top:8px">Tambah Event</a>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($events->hasPages())
    <div class="pagination-wrapper">
        {{ $events->appends(request()->except('page'))->links() }}
    </div>
    @endif
</div>

@endsection
