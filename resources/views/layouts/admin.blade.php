<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — Concert Ticketing</title>
    <meta name="description" content="Admin Panel — Concert Ticketing Platform">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #f5f5f7;
            --surface: #ffffff;
            --surface-secondary: #f5f5f7;
            --border: rgba(0,0,0,0.08);
            --border-strong: rgba(0,0,0,0.15);
            --text-primary: #1d1d1f;
            --text-secondary: #6e6e73;
            --text-tertiary: #86868b;
            --accent: #0071e3;
            --accent-hover: #0077ed;
            --accent-active: #006edb;
            --danger: #ff3b30;
            --danger-bg: #fff0ef;
            --success: #34c759;
            --success-bg: #f0faf3;
            --warning: #ff9f0a;
            --warning-bg: #fff8ed;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow: 0 4px 16px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.06);
            --sidebar-width: 240px;
            --header-height: 56px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            padding: 0 0 24px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 12px;
        }

        .sidebar-logo-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #0071e3 0%, #5856d6 100%);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }

        .sidebar-logo-icon svg { color: white; }

        .sidebar-logo-text {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .sidebar-logo-sub {
            font-size: 11px;
            color: var(--text-tertiary);
            margin-top: 1px;
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-tertiary);
            padding: 8px 20px 4px;
        }

        .sidebar-nav { padding: 0 12px; flex: 1; }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.15s ease;
            margin-bottom: 2px;
        }

        .sidebar-nav-item:hover {
            background: var(--surface-secondary);
            color: var(--text-primary);
        }

        .sidebar-nav-item.active {
            background: rgba(0, 113, 227, 0.08);
            color: var(--accent);
        }

        .sidebar-nav-item svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
        .sidebar-nav-item.active svg { opacity: 1; }

        .sidebar-user {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 4px;
        }

        .sidebar-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #0071e3, #5856d6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; color: white;
            flex-shrink: 0;
        }

        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 11px; color: var(--text-tertiary); }

        /* ─── Main ─── */
        .main { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; }

        .topbar {
            height: var(--header-height);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 32px;
            position: sticky; top: 0; z-index: 50;
            gap: 16px;
        }

        .topbar-breadcrumb {
            display: flex; align-items: center; gap: 8px;
            font-size: 14px; color: var(--text-secondary);
        }
        .topbar-breadcrumb-sep { color: var(--border-strong); }
        .topbar-breadcrumb strong { color: var(--text-primary); font-weight: 600; }

        .topbar-spacer { flex: 1; }

        /* ─── Content ─── */
        .content { padding: 32px; flex: 1; }

        /* ─── Page Header ─── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title { font-size: 22px; font-weight: 700; letter-spacing: -0.02em; color: var(--text-primary); }
        .page-subtitle { font-size: 14px; color: var(--text-secondary); margin-top: 2px; }

        /* ─── Cards ─── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 500;
            border: none; cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            line-height: 1;
            white-space: nowrap;
        }

        .btn svg { width: 14px; height: 14px; }

        .btn-primary {
            background: var(--accent);
            color: white;
        }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-0.5px); box-shadow: 0 4px 12px rgba(0,113,227,0.3); }
        .btn-primary:active { background: var(--accent-active); transform: none; }

        .btn-secondary {
            background: var(--surface-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: #ebebec; border-color: var(--border-strong); }

        .btn-danger {
            background: transparent;
            color: var(--danger);
            border: 1px solid rgba(255,59,48,0.2);
        }
        .btn-danger:hover { background: var(--danger-bg); border-color: rgba(255,59,48,0.4); }

        .btn-ghost {
            background: transparent; color: var(--text-secondary); border: none;
            padding: 8px 12px;
        }
        .btn-ghost:hover { background: var(--surface-secondary); color: var(--text-primary); }

        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-sm svg { width: 12px; height: 12px; }

        /* ─── Alerts ─── */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
        .alert-success { background: var(--success-bg); color: #1a7a3a; border: 1px solid rgba(52,199,89,0.2); }
        .alert-error { background: var(--danger-bg); color: #c0261f; border: 1px solid rgba(255,59,48,0.2); }
        .alert-warning { background: var(--warning-bg); color: #a05e00; border: 1px solid rgba(255,159,10,0.2); }

        /* ─── Form Elements ─── */
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 13px; font-weight: 500; color: var(--text-primary); }
        .form-label .req { color: var(--danger); margin-left: 2px; }
        .form-hint { font-size: 12px; color: var(--text-tertiary); }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            background: var(--surface-secondary);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--text-primary);
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
            -webkit-appearance: none;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,113,227,0.12);
            background: white;
        }

        .form-control::placeholder { color: var(--text-tertiary); }
        .form-control.readonly, .form-control[readonly] { opacity: 0.6; cursor: not-allowed; }

        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 96px; }

        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }

        /* ─── Badges ─── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 8px;
            border-radius: 100px;
            font-size: 11px; font-weight: 600;
        }

        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }

        .badge-upcoming { background: rgba(0,113,227,0.08); color: #0055b3; }
        .badge-upcoming::before { background: var(--accent); }

        .badge-ongoing { background: rgba(52,199,89,0.1); color: #1a7a3a; }
        .badge-ongoing::before { background: var(--success); animation: pulse 1.5s infinite; }

        .badge-completed { background: rgba(0,0,0,0.05); color: var(--text-secondary); }
        .badge-completed::before { background: var(--text-tertiary); }

        .badge-premium { background: linear-gradient(135deg, rgba(88,86,214,0.1), rgba(0,113,227,0.1)); color: #3634a3; border: 1px solid rgba(88,86,214,0.2); }
        .badge-reguler { background: rgba(0,0,0,0.04); color: var(--text-secondary); border: 1px solid var(--border); }

        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* ─── Table ─── */
        .table-container { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { border-bottom: 1px solid var(--border); }
        thead th {
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-tertiary);
            white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface-secondary); }
        tbody td { padding: 14px 16px; font-size: 14px; vertical-align: middle; }

        .event-thumb {
            width: 56px; height: 56px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            background: var(--surface-secondary);
            flex-shrink: 0;
        }

        .event-thumb-placeholder {
            width: 56px; height: 56px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, #0071e3 0%, #5856d6 100%);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .event-thumb-placeholder svg { color: white; width: 20px; height: 20px; }

        .event-title { font-weight: 600; font-size: 14px; color: var(--text-primary); }
        .event-meta { font-size: 12px; color: var(--text-tertiary); margin-top: 2px; }

        .actions-cell { display: flex; align-items: center; gap: 4px; }

        /* ─── Filters ─── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group { display: flex; flex-direction: column; gap: 4px; min-width: 140px; }
        .filter-group label { font-size: 11px; font-weight: 600; letter-spacing: 0.03em; text-transform: uppercase; color: var(--text-tertiary); }

        .filter-input {
            padding: 7px 10px;
            background: var(--surface-secondary);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            font-size: 13px;
            color: var(--text-primary);
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .filter-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,113,227,0.1); }

        /* ─── Ticket Cards ─── */
        .ticket-cards { display: flex; flex-direction: column; gap: 12px; }

        .ticket-card {
            background: var(--surface-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: box-shadow 0.15s;
        }
        .ticket-card:hover { box-shadow: var(--shadow-sm); }

        .ticket-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px;
            background: white;
            border-bottom: 1px solid var(--border);
        }

        .ticket-card-title { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .ticket-card-body { padding: 16px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }

        /* ─── Image Preview ─── */
        .image-preview {
            width: 100%; max-width: 300px;
            aspect-ratio: 16/9;
            border-radius: var(--radius);
            object-fit: cover;
            margin-top: 8px;
            border: 1px solid var(--border);
        }

        /* ─── Grid ─── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .col-span-2 { grid-column: span 2; }

        /* ─── Divider ─── */
        .divider { height: 1px; background: var(--border); margin: 24px 0; }

        /* ─── Pagination ─── */
        .pagination-wrapper { padding: 16px 20px; border-top: 1px solid var(--border); }
        .pagination-wrapper .pagination { display: flex; gap: 4px; align-items: center; }
        .pagination-wrapper .pagination span, .pagination-wrapper .pagination a {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }
        .pagination-wrapper .pagination span[aria-current] {
            background: var(--accent); color: white;
        }
        .pagination-wrapper .pagination a { color: var(--text-secondary); }
        .pagination-wrapper .pagination a:hover { background: var(--surface-secondary); color: var(--text-primary); }
        .pagination-wrapper .pagination span.disabled { color: var(--border-strong); cursor: default; }

        /* ─── Misc ─── */
        .empty-state {
            text-align: center; padding: 64px 24px;
            display: flex; flex-direction: column; align-items: center; gap: 12px;
        }
        .empty-state-icon {
            width: 56px; height: 56px;
            background: var(--surface-secondary);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 4px;
        }
        .empty-state-icon svg { width: 24px; height: 24px; color: var(--text-tertiary); }
        .empty-state-title { font-size: 16px; font-weight: 600; color: var(--text-primary); }
        .empty-state-desc { font-size: 14px; color: var(--text-secondary); }

        .section-title {
            font-size: 13px; font-weight: 600;
            color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.04em;
            margin-bottom: 12px;
        }
    </style>
    @yield('styles')
</head>
<body>
    {{-- ─── Sidebar ─── --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
                <div class="sidebar-logo-text">ConcertTix</div>
                <div class="sidebar-logo-sub">Admin Panel</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="sidebar-section-label">Menu</div>
            <a href="{{ route('admin.events.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Manajemen Event
            </a>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</div>
                <div class="sidebar-user-role">{{ auth()->check() ? ucfirst(auth()->user()->role ?? 'admin') : 'Admin' }}</div>
            </div>
        </div>
    </aside>

    {{-- ─── Main ─── --}}
    <main class="main">
        <div class="topbar">
            <div class="topbar-breadcrumb">
                <span>Admin</span>
                <span class="topbar-breadcrumb-sep">/</span>
                <strong>@yield('breadcrumb', 'Dashboard')</strong>
            </div>
            <div class="topbar-spacer"></div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </main>
</body>
</html>
