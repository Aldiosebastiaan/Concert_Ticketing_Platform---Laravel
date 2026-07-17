@extends('layouts.admin')

@section('title', 'Tambah Lokasi')
@section('breadcrumb', 'Tambah Lokasi')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Lokasi Baru</h1>
        <p class="page-subtitle">Masukkan detail lokasi untuk disimpan sebagai master data</p>
    </div>
    <div>
        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" width="14" height="14"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Kembali
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-error">
    <svg fill="none" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    {{ session('error') }}
</div>
@endif

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.lokasi.store') }}" method="POST">
        @csrf
        <div style="padding: 24px;">
            <div class="form-group">
                <label class="form-label">Nama Lokasi <span class="req">*</span></label>
                <input type="text" name="nama_lokasi" class="form-control {{ $errors->has('nama_lokasi') ? 'border-danger' : '' }}" placeholder="Contoh: Stadion Utama" value="{{ old('nama_lokasi') }}" required>
                @error('nama_lokasi') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Status <span class="req">*</span></label>
                <select name="aktif" class="form-control {{ $errors->has('aktif') ? 'border-danger' : '' }}" required>
                    <option value="Y" {{ old('aktif') == 'Y' ? 'selected' : '' }}>Aktif</option>
                    <option value="N" {{ old('aktif') == 'N' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                @error('aktif') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="padding: 16px 24px; background: var(--surface-secondary); border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Simpan Lokasi
            </button>
        </div>
    </form>
</div>

@endsection
