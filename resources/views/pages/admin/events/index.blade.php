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
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.events.export') }}" class="btn btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export Excel
        </a>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Tambah Event
        </a>
    </div>
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

<form action="{{ route('admin.events.bulkDelete') }}" method="POST" id="bulkDeleteForm" onsubmit="return confirm('Hapus semua event yang dipilih?')">
@csrf
<div class="card">
    <div style="padding: 12px 16px; border-bottom: 1px solid var(--border); display: none;" id="bulkDeleteContainer">
        <button type="submit" class="btn btn-danger btn-sm">
            <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Hapus Terpilih (<span id="selectedCount">0</span>)
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width:40px"><input type="checkbox" id="selectAll"></th>
                    <th style="width:72px">Gambar</th>
                    <th>Event</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Masa Penjualan</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($events as $event)
                <tr>
                    {{-- Checkbox --}}
                    @php
                        $cannotDelete = false;
                        $deleteReason = '';
                        if ($event->hasSales()) {
                            $cannotDelete = true;
                            $deleteReason = 'Event ini tidak dapat dihapus karena sudah memiliki penjualan tiket. Menghapus event ini akan menghilangkan rekam jejak transaksi penjualan yang sudah terjadi.';
                        } elseif ($event->isDalamRentangPenjualan()) {
                            $cannotDelete = true;
                            $deleteReason = 'Event ini tidak dapat dihapus karena sedang dalam masa aktif penjualan tiket. Silakan ubah rentang masa penjualan terlebih dahulu jika ingin menghapusnya.';
                        }
                    @endphp
                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $event->id }}" class="row-checkbox" {{ $cannotDelete ? 'disabled title="'.$deleteReason.'"' : '' }}>
                    </td>
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

                    <td>
                        @if($event->tanggal_mulai_penjualan && $event->tanggal_selesai_penjualan)
                            <div style="font-size:12px;">{{ $event->tanggal_mulai_penjualan->format('d M, H:i') }}</div>
                            <div style="font-size:12px; color:var(--text-secondary);">s/d {{ $event->tanggal_selesai_penjualan->format('d M, H:i') }}</div>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Lokasi --}}
                    <td>
                        <span style="font-size:13px; max-width:160px; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $event->lokasi }}</span>
                    </td>

                    {{-- Status --}}
                    <td>
                        @php $status = $event->status; @endphp
                        <span class="badge badge-{{ strtolower($status) }}">{{ $status }}</span>
                        <div style="margin-top: 4px; font-size: 11px; text-transform: uppercase; color: var(--text-secondary);">
                            {{ $event->status_publikasi }}
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-ghost btn-sm" title="Lihat">
                                <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                            </a>
                            <form action="{{ route('admin.events.clone', $event) }}" method="POST" style="display:inline;" onsubmit="return confirm('Duplikasi event ini?')">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm" title="Clone">
                                    <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><rect x="9" y="9" width="13" height="13" rx="2" ry="2" stroke="currentColor" stroke-width="1.5"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="1.5"/></svg>
                                </button>
                            </form>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-ghost btn-sm" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display:inline;" class="form-delete">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete-action" title="Hapus" data-cannot-delete="{{ $cannotDelete ? 'true' : 'false' }}" data-reason="{{ $deleteReason }}" style="{{ $cannotDelete ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                                    <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
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
</form>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox:not([disabled])');
    const bulkContainer = document.getElementById('bulkDeleteContainer');
    const selectedCount = document.getElementById('selectedCount');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    function updateBulkUI() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkContainer.style.display = 'block';
        } else {
            bulkContainer.style.display = 'none';
            selectAll.checked = false;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkUI();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
            selectAll.checked = allChecked;
            updateBulkUI();
        });
    });

    // SweetAlert untuk single delete
    const deleteButtons = document.querySelectorAll('.btn-delete-action');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.dataset.cannotDelete === 'true') {
                Swal.fire({
                    title: 'Tidak Dapat Dihapus',
                    text: this.dataset.reason,
                    icon: 'error',
                    confirmButtonColor: '#0071e3',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            const form = this.closest('form.form-delete');
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Event ini akan dihapus permanen dan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3b30',
                cancelButtonColor: '#6e6e73',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert untuk bulk delete
    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Massal',
                text: `Apakah Anda yakin ingin menghapus ${selectedCount.textContent} event terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3b30',
                cancelButtonColor: '#6e6e73',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    }
});
</script>
@endsection

@endsection
