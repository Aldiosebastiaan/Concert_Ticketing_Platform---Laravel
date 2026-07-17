@extends('layouts.admin')

@section('title', 'Manajemen Lokasi')
@section('breadcrumb', 'Manajemen Lokasi')

@section('content')

{{-- ─── Page Header ─── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Lokasi</h1>
        <p class="page-subtitle">Kelola master data lokasi untuk event</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Tambah Lokasi
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
@if(session('warning'))
<div class="alert alert-warning">
    <svg fill="none" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    {{ session('warning') }}
</div>
@endif

{{-- ─── Filters ─── --}}
<form method="GET" action="{{ route('admin.lokasi.index') }}">
<div class="filter-bar">
    <div class="filter-group" style="flex:1; min-width:180px;">
        <label>Cari Lokasi</label>
        <input type="text" name="search" class="filter-input" placeholder="Nama lokasi..." value="{{ request('search') }}">
    </div>
    <div class="filter-group">
        <label>Status</label>
        <select name="aktif" class="filter-input">
            <option value="">Semua</option>
            <option value="Y" {{ request('aktif') == 'Y' ? 'selected' : '' }}>Aktif</option>
            <option value="N" {{ request('aktif') == 'N' ? 'selected' : '' }}>Non-Aktif</option>
        </select>
    </div>
    <div class="filter-group">
        <label>Urutan</label>
        <select name="sort" class="filter-input">
            <option value="asc" {{ request('sort', 'asc') == 'asc' ? 'selected' : '' }}>A - Z</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z - A</option>
        </select>
    </div>
    <div style="display:flex; gap:8px; align-items:flex-end;">
        <button type="submit" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.5"/><path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Filter
        </button>
        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</div>
</form>

<form action="{{ route('admin.lokasi.bulkDelete') }}" method="POST" id="bulkDeleteForm" onsubmit="return confirm('Hapus semua lokasi yang dipilih?')">
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
                    <th>ID</th>
                    <th>Nama Lokasi</th>
                    <th>Status</th>
                    <th>Jumlah Event</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($lokasis as $lokasi)
                <tr>
                    {{-- Checkbox --}}
                    @php
                        $cannotDelete = $lokasi->events()->exists();
                        $deleteReason = $cannotDelete ? 'Lokasi ini tidak dapat dihapus karena masih digunakan oleh event.' : '';
                    @endphp
                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $lokasi->id }}" class="row-checkbox" {{ $cannotDelete ? 'disabled title="'.$deleteReason.'"' : '' }}>
                    </td>
                    <td>{{ $lokasi->id }}</td>
                    <td>
                        <div class="event-title">{{ $lokasi->nama_lokasi }}</div>
                    </td>
                    <td>
                        @if($lokasi->aktif == 'Y')
                            <span class="badge badge-premium">Aktif</span>
                        @else
                            <span class="badge badge-reguler">Non-Aktif</span>
                        @endif
                    </td>
                    <td><span class="badge badge-reguler" style="background:var(--surface-secondary); color:var(--text-primary)">{{ $lokasi->events()->count() }} event</span></td>

                    {{-- Actions --}}
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="btn btn-ghost btn-sm" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('admin.lokasi.destroy', $lokasi) }}" method="POST" style="display:inline;" class="form-delete">
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
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </div>
                            <div class="empty-state-title">Belum Ada Lokasi</div>
                            <div class="empty-state-desc">Mulai tambahkan lokasi event pertama Anda</div>
                            <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary" style="margin-top:8px">Tambah Lokasi</a>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper" style="display: flex; align-items: center; gap: 16px;">
        @if($lokasis->hasPages())
            {{ $lokasis->appends(request()->except('page'))->links('vendor.pagination.admin') }}
        @endif
        
        <select class="filter-input" style="padding: 4px 28px 4px 12px; font-size: 13px; height: 32px;" onchange="window.location.href=this.value">
            @foreach([10, 25, 50, 100] as $lmt)
                <option value="{{ request()->fullUrlWithQuery(['limit' => $lmt, 'page' => 1]) }}" {{ request('limit', 10) == $lmt ? 'selected' : '' }}>
                    {{ $lmt }}
                </option>
            @endforeach
        </select>

        <span style="font-size: 14px; color: var(--text-secondary);">
            Showing {{ $lokasis->firstItem() ?? 0 }} to {{ $lokasis->lastItem() ?? 0 }} of {{ $lokasis->total() }} entries
        </span>
    </div>
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
                text: "Lokasi ini akan dihapus permanen dan tidak dapat dikembalikan!",
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

    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Massal',
                text: `Apakah Anda yakin ingin menghapus ${selectedCount.textContent} lokasi terpilih?`,
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
