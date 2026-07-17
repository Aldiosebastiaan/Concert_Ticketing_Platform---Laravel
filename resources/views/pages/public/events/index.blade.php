<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>ConcertTix - Temukan Konser Impianmu</title>
    <meta name="description" content="Platform resmi untuk tiket konser artis favorit Anda. Aman, cepat, dan terpercaya.">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "surface-container": "#edeeef",
                "surface-dim": "#d9dadb",
                "inverse-surface": "#2e3132",
                "on-tertiary-fixed": "#002106",
                "on-tertiary-container": "#f7fff2",
                "on-tertiary": "#ffffff",
                "surface-container-lowest": "#ffffff",
                "on-primary": "#ffffff",
                "on-secondary": "#ffffff",
                "surface-bright": "#f8f9fa",
                "on-surface-variant": "#414755",
                "tertiary-fixed-dim": "#66df75",
                "on-surface": "#191c1d",
                "surface": "#f8f9fa",
                "on-secondary-container": "#5b646b",
                "outline": "#717786",
                "background": "#f8f9fa",
                "primary-fixed": "#d8e2ff",
                "on-error-container": "#93000a",
                "surface-variant": "#e1e3e4",
                "tertiary-container": "#008730",
                "surface-container-high": "#e7e8e9",
                "tertiary": "#006b24",
                "on-primary-fixed": "#001a41",
                "inverse-on-surface": "#f0f1f2",
                "tertiary-fixed": "#83fc8e",
                "on-error": "#ffffff",
                "surface-container-highest": "#e1e3e4",
                "secondary-fixed-dim": "#bfc8d0",
                "on-secondary-fixed": "#141d23",
                "on-background": "#191c1d",
                "secondary-container": "#d8e1ea",
                "secondary": "#575f67",
                "error": "#ba1a1a",
                "on-primary-fixed-variant": "#004493",
                "on-secondary-fixed-variant": "#3f484f",
                "secondary-fixed": "#dbe4ed",
                "outline-variant": "#c1c6d7",
                "on-primary-container": "#fefcff",
                "surface-tint": "#005bc1",
                "on-tertiary-fixed-variant": "#00531a",
                "error-container": "#ffdad6",
                "primary-fixed-dim": "#adc6ff",
                "inverse-primary": "#adc6ff",
                "primary": "#0058bc",
                "primary-container": "#0070eb",
                "surface-container-low": "#f3f4f5"
              },
              borderRadius: {
                DEFAULT: "0.25rem",
                lg: "0.5rem",
                xl: "0.75rem",
                full: "9999px"
              },
              spacing: {
                "max-width": "1440px",
                "section-gap": "32px",
                "container-padding": "24px",
                "element-gap": "8px",
                "gutter-md": "16px"
              },
              fontFamily: {
                "headline-lg": ["Inter"],
                "headline-md": ["Inter"],
                "body-lg": ["Inter"],
                "label-caps": ["Inter"],
                "body-md": ["Inter"],
                "body-sm": ["Inter"],
                "button-text": ["Inter"]
              },
              fontSize: {
                "headline-lg": ["24px", {lineHeight: "32px", fontWeight: "700"}],
                "headline-md": ["20px", {lineHeight: "28px", fontWeight: "600"}],
                "body-lg": ["16px", {lineHeight: "24px", fontWeight: "500"}],
                "label-caps": ["11px", {lineHeight: "14px", letterSpacing: "0.05em", fontWeight: "700"}],
                "body-md": ["14px", {lineHeight: "20px", fontWeight: "400"}],
                "body-sm": ["12px", {lineHeight: "16px", fontWeight: "400"}],
                "button-text": ["14px", {lineHeight: "20px", fontWeight: "600"}]
              }
            },
          },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-header { backdrop-filter: blur(8px); background-color: rgba(255,255,255,0.8); }
        .concert-shadow { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .hero-bg-scale { transform-origin: center; animation: heroZoom 12s ease-in-out infinite alternate; }
        @keyframes heroZoom { from { transform: scale(1); } to { transform: scale(1.06); } }

        /* Pagination styling */
        nav[aria-label] { display: flex; gap: 4px; }
        nav[aria-label] span, nav[aria-label] a {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 38px; height: 38px; padding: 0 10px;
            border-radius: 8px; font-size: 14px; font-weight: 500;
            border: 1px solid #c1c6d7; color: #414755; text-decoration: none;
            transition: all .15s;
        }
        nav[aria-label] a:hover { background: #e7e8e9; border-color: #717786; }
        nav[aria-label] span[aria-current] { background: #0058bc; border-color: #0058bc; color: white; }
        nav[aria-label] span.disabled { color: #c1c6d7; cursor: default; }
    </style>
</head>
<body class="text-on-surface">

{{-- ─── Top Navigation Bar ─── --}}
<header class="sticky top-0 w-full z-50 bg-surface border-b border-outline-variant">
    <div class="flex justify-between items-center h-16 px-container-padding max-w-max-width mx-auto">
        {{-- Logo (kiri) --}}
        <a href="{{ route('home') }}" class="font-headline-md text-headline-md text-primary no-underline flex-shrink-0">ConcertTix</a>

        {{-- Nav (tengah) --}}
        <nav class="hidden md:flex items-center gap-6 absolute left-1/2 -translate-x-1/2">
            <a class="{{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }} transition-colors font-body-md text-body-md" href="{{ route('home') }}">Home</a>
            <a class="{{ request()->routeIs('events.index') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }} transition-colors font-body-md text-body-md" href="{{ route('events.index') }}">Events</a>
        </nav>

        {{-- Auth buttons (kanan) --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="#" class="px-4 py-2 rounded-lg border border-primary text-primary font-button-text text-button-text hover:bg-primary-fixed transition-all text-sm">
                Masuk
            </a>
            <a href="#" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-button-text text-button-text hover:bg-primary-container transition-all active:opacity-80 text-sm">
                Daftar
            </a>
        </div>
    </div>
</header>


<main>
{{-- ─── Hero Section ─── --}}
<section class="relative h-[500px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        @if($featuredEvent && filter_var($featuredEvent->image_url, FILTER_VALIDATE_URL))
            <div class="w-full h-full bg-cover bg-center hero-bg-scale"
                 style="background-image: url('{{ $featuredEvent->image_url }}')"></div>
        @else
            <div class="w-full h-full bg-cover bg-center hero-bg-scale"
                 style="background-image: url('https://images.unsplash.com/photo-1540039155733-d7696d4eb98b?auto=format&fit=crop&w=1600&q=80')"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-on-surface/80 to-transparent"></div>
    </div>

    <div class="relative z-10 px-container-padding max-w-max-width mx-auto w-full">
        <div class="max-w-2xl">
            <h1 class="font-headline-lg text-[48px] leading-[56px] text-white mb-6">Temukan Konser Impianmu</h1>
            <p class="text-white/90 text-lg mb-8 font-body-lg">Platform resmi untuk tiket konser artis favorit Anda. Aman, cepat, dan terpercaya.</p>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('events.index') }}" id="heroSearch">
            <div class="bg-white p-2 rounded-xl flex flex-col md:flex-row gap-2 shadow-xl" id="searchContainer">
                <div class="flex-1 flex items-center px-4 gap-3 border-b md:border-b-0 md:border-r border-outline-variant">
                    <span class="material-symbols-outlined text-secondary">search</span>
                    <input name="search" class="w-full border-none focus:ring-0 font-body-md text-body-md outline-none"
                           placeholder="Cari konser, artis, atau lokasi..."
                           value="{{ request('search') }}" type="text">
                </div>
                <div class="flex-1 flex items-center px-4 gap-3">
                    <span class="material-symbols-outlined text-secondary">category</span>
                    <select name="kategori_id" class="w-full border-none focus:ring-0 font-body-md text-body-md outline-none bg-transparent">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-button-text text-button-text hover:bg-primary-container transition-all">
                    Cari
                </button>
            </div>
            </form>
        </div>
    </div>
</section>

{{-- ─── Categories & Filters ─── --}}
<section class="py-10 px-container-padding max-w-max-width mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="font-headline-md text-headline-md">Kategori Populer</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('events.index', ['search' => request('search')]) }}"
               class="px-4 py-2 rounded-full font-body-md text-body-md transition-colors
               {{ !request('kategori_id') ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                Semua
            </a>
            @foreach($kategoris as $k)
            <a href="{{ route('events.index', ['kategori_id' => $k->id, 'search' => request('search')]) }}"
               class="px-4 py-2 rounded-full font-body-md text-body-md transition-colors
               {{ request('kategori_id') == $k->id ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                {{ $k->nama }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── Events Grid ─── --}}
<section class="pb-section-gap px-container-padding max-w-max-width mx-auto">

    {{-- Result info --}}
    @if(request('search') || request('kategori_id'))
    <div class="flex items-center justify-between mb-6">
        <p class="text-on-surface-variant font-body-md">
            Menampilkan <strong class="text-on-surface">{{ $events->total() }}</strong> event
            @if(request('search')) untuk "<strong class="text-on-surface">{{ request('search') }}</strong>" @endif
        </p>
        <a href="{{ route('events.index') }}" class="text-primary hover:underline font-body-md text-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">close</span> Reset filter
        </a>
    </div>
    @endif

    @if($events->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($events as $event)
        @php
            $img = $event->image_url;
            $status = $event->status;
            $minPrice = $event->tikets->min('harga');
            $fallbackGrads = [
                'linear-gradient(135deg,#1a1a2e,#16213e)',
                'linear-gradient(135deg,#0d1b2a,#415a77)',
                'linear-gradient(135deg,#1a0533,#4a0e6e)',
                'linear-gradient(135deg,#100c1c,#2d1b69)',
                'linear-gradient(135deg,#0a1628,#1a3a5c)',
            ];
            $grad = $fallbackGrads[$event->id % 5];
        @endphp
        <a href="{{ route('events.show', $event) }}"
           class="bg-white rounded-xl border border-outline-variant overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300 no-underline text-inherit">

            {{-- Image --}}
            <div class="relative h-48 overflow-hidden">
                @if($img && (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, '/storage')))
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         src="{{ $img }}" alt="{{ $event->judul }}">
                @else
                    <div class="w-full h-full flex items-center justify-center group-hover:scale-105 transition-transform duration-500"
                         style="background: {{ $grad }}">
                        <span class="material-symbols-outlined text-white/20" style="font-size:48px;">music_note</span>
                    </div>
                @endif


                {{-- Category badge --}}
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider text-primary">
                    {{ $event->kategori->nama ?? '—' }}
                </div>

                {{-- Status badge --}}
                @php
                    $statusClasses = [
                        'Upcoming' => 'bg-blue-500/90 text-white',
                        'Ongoing'  => 'bg-green-500/90 text-white',
                        'Completed'=> 'bg-gray-500/90 text-white',
                    ];
                @endphp
                <div class="absolute top-3 right-3 px-2 py-1 rounded-md text-[10px] font-bold {{ $statusClasses[$status] ?? 'bg-gray-500/90 text-white' }}">
                    {{ $status }}
                </div>
            </div>

            {{-- Body --}}
            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-headline-md text-on-surface text-base font-semibold mb-3 line-clamp-2 leading-snug">{{ $event->judul }}</h3>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined" style="font-size:16px;">calendar_month</span>
                        <span class="text-body-sm font-body-sm">{{ \Carbon\Carbon::parse($event->tanggal_waktu)->isoFormat('D MMM YYYY · HH:mm') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined" style="font-size:16px;">location_on</span>
                        <span class="text-body-sm font-body-sm line-clamp-1">{{ $event->lokasi->nama_lokasi ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-auto flex items-center justify-between pt-3 border-t border-outline-variant">
                    @if($minPrice)
                        <span class="text-primary font-semibold text-base">Rp {{ number_format($minPrice, 0, ',', '.') }}+</span>
                    @else
                        <span class="text-secondary text-sm">Cek tiket</span>
                    @endif
                    <span class="bg-primary text-on-primary px-4 py-2 rounded-lg font-button-text text-button-text text-sm group-hover:bg-primary-container transition-colors">
                        Beli Tiket
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Load More / Pagination --}}
    @if($events->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $events->appends(request()->except('page'))->links() }}
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="flex flex-col items-center text-center py-24 gap-4">
        <div class="w-20 h-20 rounded-2xl bg-surface-container flex items-center justify-center">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:40px;">event_busy</span>
        </div>
        <h3 class="font-headline-md text-headline-md">Event Tidak Ditemukan</h3>
        <p class="text-on-surface-variant font-body-md max-w-sm">
            Coba ubah filter atau kata kunci pencarian kamu. Atau lihat semua event yang tersedia.
        </p>
        <a href="{{ route('events.index') }}" class="mt-2 px-6 py-3 rounded-lg bg-primary text-on-primary font-button-text text-button-text hover:bg-primary-container transition-all">
            Lihat Semua Event
        </a>
    </div>
    @endif
</section>

{{-- ─── Why ConcertTix Section ─── --}}
<section class="bg-surface-container-low py-20">
    <div class="px-container-padding max-w-max-width mx-auto">
        <div class="text-center mb-16">
            <h2 class="font-headline-lg text-headline-lg mb-4">Kenapa Memilih ConcertTix?</h2>
            <p class="text-on-surface-variant font-body-lg max-w-2xl mx-auto">
                Kami memberikan pengalaman pemesanan tiket yang terbaik untuk kenyamanan Anda menonton konser.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-6">
                    <span class="material-symbols-outlined text-4xl">verified</span>
                </div>
                <h3 class="font-headline-md text-headline-md mb-3">Tiket Resmi</h3>
                <p class="text-on-surface-variant font-body-md">Bekerjasama langsung dengan promotor resmi untuk menjamin keaslian tiket Anda.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-6">
                    <span class="material-symbols-outlined text-4xl">payments</span>
                </div>
                <h3 class="font-headline-md text-headline-md mb-3">Pembayaran Aman</h3>
                <p class="text-on-surface-variant font-body-md">Enkripsi tingkat tinggi untuk semua transaksi pembayaran Anda.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-6">
                    <span class="material-symbols-outlined text-4xl">support_agent</span>
                </div>
                <h3 class="font-headline-md text-headline-md mb-3">Layanan 24/7</h3>
                <p class="text-on-surface-variant font-body-md">Tim support kami siap membantu kendala Anda kapan saja, 24 jam sehari.</p>
            </div>
        </div>
    </div>
</section>

{{-- ─── Stats Bar ─── --}}
<section class="py-16 px-container-padding max-w-max-width mx-auto">
    <div class="grid grid-cols-3 divide-x divide-outline-variant border border-outline-variant rounded-xl overflow-hidden bg-white">
        <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
            <span class="text-[36px] font-bold text-primary leading-none">{{ \App\Models\Event::count() }}</span>
            <span class="text-on-surface-variant font-body-md mt-2">Total Event</span>
        </div>
        <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
            <span class="text-[36px] font-bold text-primary leading-none">{{ \App\Models\Event::upcoming()->count() }}</span>
            <span class="text-on-surface-variant font-body-md mt-2">Event Mendatang</span>
        </div>
        <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
            <span class="text-[36px] font-bold text-primary leading-none">{{ \App\Models\Kategori::count() }}</span>
            <span class="text-on-surface-variant font-body-md mt-2">Genre Musik</span>
        </div>
    </div>
</section>

{{-- ─── Call To Action Section ─── --}}
<section class="py-16 px-container-padding">
    <div class="max-w-4xl mx-auto bg-primary rounded-[2rem] p-12 relative overflow-hidden text-center">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.4)_0%,transparent_70%)]"></div>
        </div>
        <div class="relative z-10">
            <h2 class="font-headline-lg text-[32px] leading-tight text-white mb-6">Dapatkan Akses Tiket Lebih Awal!</h2>
            <p class="text-white/80 font-body-lg mb-10 max-w-xl mx-auto">
                Daftar sekarang dan jadilah yang pertama menerima notifikasi presale konser artis favoritmu langsung di email.
            </p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                <input class="flex-1 px-6 py-4 rounded-xl border-none focus:ring-2 focus:ring-white/50 text-on-surface"
                       placeholder="Masukkan alamat email Anda" type="email">
                <button type="button" class="bg-white text-primary font-button-text text-button-text px-8 py-4 rounded-xl hover:bg-surface-bright transition-all shadow-lg active:scale-95">
                    Daftar Sekarang
                </button>
            </form>
            <p class="mt-6 text-white/60 text-sm font-body-sm italic">Gratis! Tidak dipungut biaya pendaftaran.</p>
        </div>
    </div>
</section>

</main>

{{-- ─── Footer ─── --}}
<footer class="bg-surface-container text-primary w-full py-12 px-container-padding">
    <div class="max-w-max-width mx-auto grid grid-cols-1 md:grid-cols-4 gap-section-gap">
        <div class="col-span-1 md:col-span-1">
            <a href="{{ route('home') }}" class="font-headline-md text-headline-md text-on-surface mb-6 block no-underline">ConcertTix</a>
            <p class="text-on-surface-variant font-body-sm mb-6 pr-4">
                Destinasi utama Anda untuk mendapatkan tiket konser berkualitas di Indonesia dan sekitarnya.
            </p>
        </div>
        <div>
            <h4 class="font-bold text-on-surface mb-6 font-body-md">Navigasi</h4>
            <ul class="space-y-4">
                <li><a class="text-on-surface-variant font-body-sm hover:underline hover:text-primary transition-all" href="{{ route('home') }}">Beranda</a></li>
                <li><a class="text-on-surface-variant font-body-sm hover:underline hover:text-primary transition-all" href="{{ route('events.index') }}">Semua Event</a></li>
                <li><a class="text-on-surface-variant font-body-sm hover:underline hover:text-primary transition-all" href="{{ route('admin.events.index') }}">Admin Panel</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-on-surface mb-6 font-body-md">Kategori</h4>
            <ul class="space-y-4">
                @foreach($kategoris->take(4) as $k)
                <li>
                    <a class="text-on-surface-variant font-body-sm hover:underline hover:text-primary transition-all"
                       href="{{ route('events.index', ['kategori_id' => $k->id]) }}">{{ $k->nama }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-on-surface mb-6 font-body-md">Ikuti Kami</h4>
            <div class="flex gap-4">
                <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined">public</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined">alternate_email</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined">share</span>
                </a>
            </div>
        </div>
    </div>
    <div class="max-w-max-width mx-auto mt-12 pt-8 border-t border-outline-variant flex items-center justify-between">
        <p class="text-on-surface-variant font-body-sm">© {{ date('Y') }} ConcertTix. All rights reserved.</p>
        <a href="{{ route('admin.events.index') }}" class="text-primary font-body-sm hover:underline">Admin Panel →</a>
    </div>
</footer>

<script>
    // Search bar focus micro-interaction
    const searchContainer = document.getElementById('searchContainer');
    if (searchContainer) {
        const inputs = searchContainer.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                searchContainer.classList.add('ring-2', 'ring-primary/20');
                searchContainer.style.outline = '2px solid rgba(0,88,188,0.2)';
            });
            input.addEventListener('blur', () => {
                searchContainer.style.outline = 'none';
            });
        });
    }
</script>
</body>
</html>
