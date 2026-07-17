<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->judul }} — ConcertTix</title>
    <meta name="description" content="{{ Str::limit($event->deskripsi, 160) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f5f5f7;
            --surface: #fff;
            --border: rgba(0,0,0,0.08);
            --text-primary: #1d1d1f;
            --text-secondary: #6e6e73;
            --text-tertiary: #86868b;
            --accent: #0071e3;
            --radius: 12px;
        }
        body { font-family: 'Inter', -apple-system, sans-serif; background: var(--bg); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

        nav { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); padding: 0 48px; height: 52px; display: flex; align-items: center; gap: 16px; position: sticky; top: 0; z-index: 100; }
        nav a { font-size: 14px; color: var(--text-secondary); text-decoration: none; transition: color 0.15s; }
        nav a:hover { color: var(--text-primary); }
        nav .logo { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-right: auto; }

        .hero {
            width: 100%;
            height: 480px;
            position: relative;
            overflow: hidden;
        }
        .hero img { width: 100%; height: 100%; object-fit: cover; }
        .hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
        }
        .hero-meta {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 40px 48px;
            color: white;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
            padding: 4px 10px; border-radius: 100px;
            font-size: 12px; font-weight: 600;
            margin-bottom: 10px;
        }
        .hero-title { font-size: 38px; font-weight: 800; letter-spacing: -0.03em; line-height: 1.1; margin-bottom: 8px; }
        .hero-sub { font-size: 15px; opacity: 0.8; }

        .container { max-width: 1100px; margin: 0 auto; padding: 0 48px; }
        .content-grid { display: grid; grid-template-columns: 1fr 360px; gap: 32px; margin-top: 40px; padding-bottom: 80px; }

        .detail-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .detail-card-section { padding: 24px; }
        .detail-card-section + .detail-card-section { border-top: 1px solid var(--border); }
        .detail-title { font-size: 18px; font-weight: 700; margin-bottom: 12px; letter-spacing: -0.01em; }
        .detail-text { font-size: 15px; line-height: 1.7; color: var(--text-secondary); }

        .meta-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .meta-icon { width: 32px; height: 32px; background: var(--bg); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .meta-icon svg { width: 14px; height: 14px; color: var(--text-secondary); }
        .meta-label { font-size: 11px; color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .meta-value { font-size: 14px; font-weight: 600; color: var(--text-primary); margin-top: 1px; }

        .sticky-sidebar { position: sticky; top: 72px; }
        .ticket-widget { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .ticket-widget-header { padding: 20px; border-bottom: 1px solid var(--border); }
        .ticket-widget-title { font-size: 16px; font-weight: 700; }
        .ticket-widget-sub { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

        .ticket-option {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .ticket-option:last-of-type { border-bottom: none; }
        .ticket-option:hover { background: var(--bg); }
        .ticket-type { font-size: 14px; font-weight: 600; }
        .ticket-stock { font-size: 12px; color: var(--text-tertiary); margin-top: 2px; }
        .ticket-price { font-size: 18px; font-weight: 800; color: var(--accent); letter-spacing: -0.02em; }

        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 8px; border-radius: 100px;
            font-size: 11px; font-weight: 600;
        }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
        .badge-upcoming { background: rgba(0,113,227,0.08); color: #0055b3; }
        .badge-upcoming::before { background: #0071e3; }
        .badge-on-going { background: rgba(52,199,89,0.1); color: #1a7a3a; }
        .badge-on-going::before { background: #34c759; animation: pulse 1.5s infinite; }
        .badge-completed { background: rgba(0,0,0,0.05); color: var(--text-secondary); }
        .badge-completed::before { background: #86868b; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        .related-section { margin-top: 56px; padding-bottom: 80px; }
        .related-title { font-size: 22px; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 24px; }
        .related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }

        .event-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; text-decoration: none; color: inherit; transition: all 0.2s ease; }
        .event-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.1); }
        .event-card-image { width: 100%; aspect-ratio: 16/9; object-fit: cover; background: var(--bg); }
        .event-card-image-placeholder {
            width: 100%; aspect-ratio: 16/9;
            background: linear-gradient(135deg, #0071e3, #5856d6);
            display: flex; align-items: center; justify-content: center;
        }
        .event-card-image-placeholder svg { width: 28px; height: 28px; color: rgba(255,255,255,0.6); }
        .event-card-body { padding: 14px; }
        .event-card-category { font-size: 11px; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px; }
        .event-card-title { font-size: 14px; font-weight: 700; line-height: 1.3; margin-bottom: 6px; }
        .event-card-date { font-size: 12px; color: var(--text-tertiary); }
    </style>
</head>
<body>
    <nav>
        <a href="/" class="logo">🎵 ConcertTix</a>
        <a href="{{ route('admin.events.index') }}">Admin</a>
    </nav>

    {{-- ─── Hero ─── --}}
    <div class="hero">
        @php $imgUrl = $event->image_url; @endphp
        @if(filter_var($imgUrl, FILTER_VALIDATE_URL) || str_starts_with($imgUrl, '/storage'))
            <img src="{{ $imgUrl }}" alt="{{ $event->judul }}">
        @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#0071e3 0%,#5856d6 100%);display:flex;align-items:center;justify-content:center;">
                <svg width="80" height="80" fill="none" viewBox="0 0 24 24" style="color:rgba(255,255,255,0.3)"><path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
        @endif
        <div class="hero-overlay"></div>
        <div class="hero-meta">
            <div class="hero-badge">
                <svg width="10" height="10" fill="none" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.5"/></svg>
                {{ $event->kategori->nama ?? 'Konser' }}
            </div>
            <h1 class="hero-title">{{ $event->judul }}</h1>
            <p class="hero-sub">{{ $event->lokasi->nama_lokasi ?? '-' }}</p>
        </div>
    </div>

    {{-- ─── Content ─── --}}
    <div class="container">
        <div class="content-grid">
            {{-- Left: Detail --}}
            <div>
                <div class="detail-card">
                    {{-- Info --}}
                    <div class="detail-card-section">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                            <h2 class="detail-title" style="margin-bottom:0;">Informasi Event</h2>
                            @php $status = $event->status; @endphp
                            <span class="badge badge-{{ str_replace(' ', '-', strtolower($status)) }}">{{ $status }}</span>
                        </div>

                        <div class="meta-row">
                            <div class="meta-icon"><svg fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>
                            <div><div class="meta-label">Tanggal & Waktu</div><div class="meta-value">{{ \Carbon\Carbon::parse($event->tanggal_waktu)->isoFormat('dddd, D MMMM YYYY · HH:mm') }} WIB</div></div>
                        </div>
                        <div class="meta-row">
                            <div class="meta-icon"><svg fill="none" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.5"/></svg></div>
                            <div><div class="meta-label">Lokasi</div><div class="meta-value">{{ $event->lokasi->nama_lokasi ?? '-' }}</div></div>
                        </div>
                        <div class="meta-row">
                            <div class="meta-icon"><svg fill="none" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>

                        <div class="meta-row">
                            <div class="meta-icon"><svg fill="none" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.5"/><line x1="7" y1="7" x2="7.01" y2="7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></div>
                            <div><div class="meta-label">Kategori</div><div class="meta-value">{{ $event->kategori->nama ?? '-' }}</div></div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="detail-card-section">
                        <h2 class="detail-title">Tentang Event</h2>
                        <p class="detail-text">{{ $event->deskripsi }}</p>
                    </div>
                </div>
            </div>

            {{-- Right: Ticket Widget --}}
            <div class="sticky-sidebar">
                @if(session('success'))
                    <div style="background:#f0faf3; border:1px solid #34c759; color:#1a7a3a; padding:12px; border-radius:12px; margin-bottom:16px; font-size:14px; font-weight:500;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background:#fff0ef; border:1px solid #ff3b30; color:#d92a20; padding:12px; border-radius:12px; margin-bottom:16px; font-size:14px; font-weight:500;">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('events.buy', $event) }}" method="POST" class="ticket-widget">
                    @csrf
                    <div class="ticket-widget-header">
                        <div class="ticket-widget-title">Pilih Tiket</div>
                        <div class="ticket-widget-sub">{{ $event->tikets->count() }} tipe tiket tersedia</div>
                    </div>

                    @forelse($event->tikets as $tiket)
                    @php $terjual = \App\Models\DetailOrder::where('tiket_id', $tiket->id)->sum('jumlah'); @endphp
                    <label class="ticket-option" style="cursor:pointer;">
                        <div style="display:flex; gap:12px; align-items:flex-start;">
                            <input type="radio" name="tiket_id" value="{{ $tiket->id }}" style="margin-top:4px;" required {{ $tiket->stok <= 0 ? 'disabled' : '' }}>
                            <div>
                                <div class="ticket-type">
                                    {{ $tiket->tipe === 'premium' ? '⭐ ' : '🎟 ' }}{{ ucfirst($tiket->tipe) }}
                                </div>
                                <div class="ticket-stock" style="margin-top:4px;">
                                    <strong>Sisa: {{ number_format($tiket->stok) }}</strong> <span style="color:var(--border-strong);">|</span> Terjual: {{ number_format($terjual) }}
                                </div>
                            </div>
                        </div>
                        <div class="ticket-price" style="margin-left:auto;">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</div>
                    </label>
                    @empty
                    <div style="padding:24px; text-align:center; color:var(--text-tertiary); font-size:14px;">Belum ada tiket tersedia</div>
                    @endforelse

                    <div style="padding:16px;">
                        <div style="margin-bottom:12px;">
                            <label style="font-size:13px;font-weight:600;margin-bottom:6px;display:block;">Jumlah Tiket</label>
                            <input type="number" name="jumlah" value="1" min="1" max="10" required style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:14px;">
                        </div>
                        <button type="submit" style="width:100%;padding:12px;background:#0071e3;color:white;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;transition:all 0.15s;font-family:inherit;" onmouseover="this.style.background='#0077ed'" onmouseout="this.style.background='#0071e3'">
                            Beli Tiket
                        </button>
                    </div>
                </form>

                {{-- Organizer --}}
                <div style="margin-top:16px; background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:16px;">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:var(--text-tertiary);margin-bottom:10px;">Penyelenggara</div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#0071e3,#5856d6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">
                            {{ strtoupper(substr($event->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:600;">{{ $event->user->name ?? 'ConcertTix' }}</div>
                            <div style="font-size:12px;color:var(--text-tertiary);">Penyelenggara Resmi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Related Events ─── --}}
        @if($relatedEvents->count() > 0)
        <div class="related-section">
            <h2 class="related-title">Event Terkait</h2>
            <div class="related-grid">
                @foreach($relatedEvents as $related)
                <a href="{{ route('admin.events.show', $related) }}" class="event-card">
                    @php $relImg = $related->image_url; @endphp
                    @if(filter_var($relImg, FILTER_VALIDATE_URL) || str_starts_with($relImg, '/storage'))
                        <img src="{{ $relImg }}" alt="{{ $related->judul }}" class="event-card-image">
                    @else
                        <div class="event-card-image-placeholder">
                            <svg fill="none" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </div>
                    @endif
                    <div class="event-card-body">
                        <div class="event-card-category">{{ $related->kategori->nama ?? '' }}</div>
                        <div class="event-card-title">{{ $related->judul }}</div>
                        <div class="event-card-date">{{ \Carbon\Carbon::parse($related->tanggal_waktu)->isoFormat('D MMM YYYY') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>
