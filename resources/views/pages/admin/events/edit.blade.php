@extends('layouts.admin')

@section('title', 'Edit Event — ' . $event->judul)
@section('breadcrumb', 'Edit Event')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<style>
    .form-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
    .form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); }
    .form-card-header-title { font-size: 16px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.01em; }
    .form-card-header-sub { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

    .ticket-card { background: var(--surface-secondary); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin-bottom: 12px; }
    .ticket-card-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: white; border-bottom: 1px solid var(--border); }
    .ticket-card-body { padding: 16px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }

    .current-image { width: 100%; max-width: 240px; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--radius); border: 1px solid var(--border); }
    .image-preview-container { display: none; margin-top: 10px; }
    .image-preview-container img { width: 100%; max-width: 240px; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--radius); border: 1px solid var(--border); }

    .border-danger { border-color: var(--danger) !important; }
    .ticket-sold-badge { font-size: 10px; font-weight: 600; background: rgba(255,159,10,0.1); color: #a05e00; border: 1px solid rgba(255,159,10,0.2); padding: 2px 7px; border-radius: 100px; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="page-header">
    <div style="display:flex; align-items:center; gap:12px;">
        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost" style="padding:8px;">
            <svg fill="none" viewBox="0 0 24 24" width="16" height="16"><path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <div>
            <h1 class="page-title">Edit Event</h1>
            <p class="page-subtitle">{{ $event->judul }}</p>
        </div>
    </div>
</div>

{{-- ─── Has Sales Warning ─── --}}
@if($hasSales)
<div class="alert alert-warning">
    <svg fill="none" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5"/><line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><line x1="12" y1="17" x2="12.01" y2="17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    <div>
        <strong>Perhatian:</strong> Event ini sudah memiliki penjualan tiket. Tanggal &amp; waktu tidak dapat diubah, dan tiket yang sudah terjual tidak dapat dihapus.
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    <svg fill="none" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    {{ session('error') }}
</div>
@endif

<form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- ─── Event Details Card ─── --}}
<div class="form-card" style="margin-bottom:20px;">
    <div class="form-card-header">
        <div class="form-card-header-title">Detail Event</div>
        <div class="form-card-header-sub">Perbarui informasi utama event konser</div>
    </div>
    <div class="form-card-body" style="padding:24px;">
        <div class="grid-2">
            {{-- Judul --}}
            <div class="form-group">
                <label class="form-label">Judul Event <span class="req">*</span></label>
                <input type="text" name="judul" class="form-control {{ $errors->has('judul') ? 'border-danger' : '' }}" value="{{ old('judul', $event->judul) }}" required>
                @error('judul') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Kategori --}}
            <div class="form-group">
                <label class="form-label">Kategori <span class="req">*</span></label>
                <select name="kategori_id" class="form-control {{ $errors->has('kategori_id') ? 'border-danger' : '' }}" required>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id', $event->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Lokasi --}}
            <div class="form-group">
                <label class="form-label">Lokasi <span class="req">*</span></label>
                <select name="lokasi_id" class="form-control {{ $errors->has('lokasi_id') ? 'border-danger' : '' }}" required>
                    <option value="" disabled {{ old('lokasi_id', $event->lokasi_id) ? '' : 'selected' }}>Pilih lokasi...</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $event->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                            {{ $lokasi->nama_lokasi }} {{ $lokasi->aktif == 'N' ? '(Non-Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('lokasi_id') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Tanggal & Waktu --}}
            <div class="form-group">
                <label class="form-label">
                    Tanggal & Waktu <span class="req">*</span>
                    @if($hasSales) <span class="ticket-sold-badge">Terkunci</span> @endif
                </label>
                <input type="datetime-local" name="tanggal_waktu"
                    class="form-control {{ $errors->has('tanggal_waktu') ? 'border-danger' : '' }} {{ $hasSales ? 'readonly' : '' }}"
                    value="{{ old('tanggal_waktu', \Carbon\Carbon::parse($event->tanggal_waktu)->format('Y-m-d\TH:i')) }}"
                    {{ $hasSales ? 'readonly' : '' }} required>
                @error('tanggal_waktu') <span class="form-error">{{ $message }}</span> @enderror
            </div>



            {{-- Status Publikasi --}}
            <div class="form-group">
                <label class="form-label">Status Publikasi <span class="req">*</span></label>
                <select name="status_publikasi" class="form-control" required>
                    <option value="draft" {{ old('status_publikasi', $event->status_publikasi) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status_publikasi', $event->status_publikasi) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ old('status_publikasi', $event->status_publikasi) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status_publikasi') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Gambar --}}
            <div class="form-group" style="grid-column:1;">
                <label class="form-label">Gambar Event</label>
                @php $imgUrl = $event->image_url; @endphp
                @if($imgUrl && filter_var($imgUrl, FILTER_VALIDATE_URL))
                    <div style="margin-bottom:10px;">
                        <div class="form-hint" style="margin-bottom:6px;">Gambar saat ini:</div>
                        <img src="{{ $imgUrl }}" alt="{{ $event->judul }}" class="current-image">
                    </div>
                @elseif($imgUrl && $imgUrl !== 'konser.jpg')
                    <div style="margin-bottom:10px;">
                        <div class="form-hint" style="margin-bottom:6px;">Gambar saat ini:</div>
                        <img src="{{ Storage::url($imgUrl) }}" alt="{{ $event->judul }}" class="current-image">
                    </div>
                @endif
                <input type="file" id="gambar_file" class="form-control" accept="image/jpg,image/jpeg,image/png">
                <input type="hidden" name="gambar_base64" id="gambar_base64">
                <span class="form-hint">Kosongkan jika tidak ingin mengubah gambar · Maksimal 2MB · JPG, JPEG, PNG</span>
                @error('gambar') <span class="form-error">{{ $message }}</span> @enderror
                
                {{-- Cropper Container --}}
                <div id="cropContainer" style="display:none; margin-top:16px;">
                    <div style="max-width:100%; max-height:400px;">
                        <img id="imageToCrop" src="" style="max-width: 100%; display:block;">
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" id="btnCrop" style="margin-top:8px;">
                        Setuju Crop
                    </button>
                </div>

                <div class="image-preview-container" id="imagePreview">
                    <div class="form-hint" style="margin-bottom:6px;">Preview gambar baru:</div>
                    <img src="" id="finalPreview" alt="Preview Baru">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group col-span-2">
                <label class="form-label">Deskripsi <span class="req">*</span></label>
                <textarea name="deskripsi" class="form-control {{ $errors->has('deskripsi') ? 'border-danger' : '' }}" rows="4" required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                @error('deskripsi') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

{{-- ─── Tickets Card ─── --}}
<div class="form-card" style="margin-bottom:20px;">
    <div class="form-card-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
            <div class="form-card-header-title">Tiket</div>
            <div class="form-card-header-sub">Kelola tipe tiket yang tersedia</div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="addTicket()">
            <svg fill="none" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Tambah Tiket
        </button>
    </div>
    <div class="form-card-body" style="padding:24px;">
        @error('tikets') <div class="alert alert-error" style="margin-bottom:16px;">{{ $message }}</div> @enderror
        <div id="ticketContainer" class="ticket-cards"></div>
    </div>
</div>

{{-- ─── Footer Buttons ─── --}}
<div style="display:flex; justify-content:flex-end; gap:10px; padding-bottom:40px;">
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5"/><polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="1.5"/><polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="1.5"/></svg>
        Simpan Perubahan
    </button>
</div>
</form>

<script>
let ticketCount = 0;
const hasSales = @json($hasSales);

// Existing tickets passed from controller
const existingTickets = @json($event->tikets);

function addTicket(data = {}, sold = false) {
    const n = ++ticketCount;
    const container = document.getElementById('ticketContainer');

    const card = document.createElement('div');
    card.className = 'ticket-card';
    card.id = `ticket-${n}`;
    
    let deleteBtn = '';
    if (!sold) {
        deleteBtn = `
            <button type="button" onclick="removeTicket(${n})" class="btn btn-danger btn-sm">
                <svg fill="none" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Hapus
            </button>
        `;
    } else {
        deleteBtn = `<span class="ticket-sold-badge">Sudah Terjual</span>`;
    }

    card.innerHTML = `
        <input type="hidden" name="tikets[${n}][id]" value="${data.id || ''}">
        <div class="ticket-card-header">
            <span class="ticket-card-title">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" style="display:inline;margin-right:6px;vertical-align:middle;"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><line x1="7" y1="7" x2="7.01" y2="7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Tiket #${n}
            </span>
            ${deleteBtn}
        </div>
        <div class="ticket-card-body">
            <div class="form-group">
                <label class="form-label">Tipe Tiket <span class="req">*</span></label>
                <select name="tikets[${n}][tipe]" class="form-control" required ${sold ? 'readonly style="pointer-events:none;"' : ''}>
                    <option value="reguler" ${(data.tipe === 'reguler' || !data.tipe) ? 'selected' : ''}>🎟 Reguler</option>
                    <option value="premium" ${data.tipe === 'premium' ? 'selected' : ''}>⭐ Premium</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Harga <span class="req">*</span></label>
                <input type="number" name="tikets[${n}][harga]" class="form-control" placeholder="0" min="0" value="${data.harga || ''}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stok <span class="req">*</span></label>
                <input type="number" name="tikets[${n}][stok]" class="form-control" placeholder="0" min="0" value="${data.stok || ''}" required>
            </div>
        </div>
    `;
    container.appendChild(card);
}

function removeTicket(n) {
    const el = document.getElementById(`ticket-${n}`);
    if (el) el.remove();
}

let cropper;
const imageFile = document.getElementById('gambar_file');
const imageToCrop = document.getElementById('imageToCrop');
const cropContainer = document.getElementById('cropContainer');
const btnCrop = document.getElementById('btnCrop');
const imagePreview = document.getElementById('imagePreview');
const finalPreview = document.getElementById('finalPreview');
const gambarBase64 = document.getElementById('gambar_base64');

imageFile.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imageToCrop.src = e.target.result;
            cropContainer.style.display = 'block';
            imagePreview.style.display = 'none';
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 16 / 9,
                viewMode: 1,
            });
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

btnCrop.addEventListener('click', function() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 450,
        });
        const base64 = canvas.toDataURL('image/jpeg');
        gambarBase64.value = base64;
        finalPreview.src = base64;
        imagePreview.style.display = 'block';
        cropContainer.style.display = 'none';
    }
});

// Load existing tickets
existingTickets.forEach(t => {
    // If hasSales, check if each ticket has any orders
    const sold = hasSales;
    addTicket(t, sold);
});
// If no tickets, add one
if (existingTickets.length === 0) addTicket();
</script>
@endsection
