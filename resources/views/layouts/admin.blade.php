<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') &middot; NU Rancamaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #F5F4EC; color: #25351F; }
        h1, h2, h3, h4, h5, .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::selection { background: #EEF4D9; color: #183D22; }

        /* ============ Sidebar ============ */
        .sidebar { width: 264px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 14px;
            font-size: 14px; font-weight: 600;
            color: rgba(238,244,217,.62);
            transition: background .15s ease, color .15s ease;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.07); color: #F5F4EC; }
        .sidebar-link.active { background: #2F6B2F; color: #FFFFFF; }
        .sidebar-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .sidebar-badge {
            margin-left: auto; font-size: 11px; font-weight: 700;
            background: rgba(255,255,255,.16); color: #F5F4EC;
            border-radius: 999px; padding: 2px 9px;
        }
        .sidebar-link.active .sidebar-badge { background: rgba(255,255,255,.25); }

        /* ============ Komponen halaman ============ */
        .page-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: #183D22; letter-spacing: -.01em; }
        @media (min-width: 768px) { .page-title { font-size: 28px; } }
        .page-sub { font-size: 14px; color: #7D8575; margin-top: 4px; }

        .panel {
            background: #FFFFFF;
            border-radius: 24px;
            border: 1px solid rgba(24,61,34,.07);
            box-shadow: 0 1px 3px rgba(24,61,34,.05);
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 14px;
            border-radius: 999px; padding: 11px 22px;
            transition: opacity .15s ease, transform .1s ease, background .15s ease;
            cursor: pointer; white-space: nowrap;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary { background: #2F6B2F; color: #FFFFFF; }
        .btn-primary:hover { opacity: .9; }
        .btn-danger { background: #B91C1C; color: #FFFFFF; }
        .btn-danger:hover { opacity: .9; }
        .btn-soft { background: #EEF4D9; color: #2F6B2F; }
        .btn-soft:hover { background: #E2EDCB; }
        .btn-danger-soft { background: #FDE8E8; color: #B91C1C; }
        .btn-danger-soft:hover { background: #FBDCDC; }
        .btn-ghost { color: #7D8575; padding: 11px 14px; }
        .btn-ghost:hover { color: #183D22; }
        .btn-sm { padding: 8px 16px; font-size: 13px; }

        .alert { border-radius: 16px; padding: 14px 18px; font-size: 14px; font-weight: 500; }
        .alert-success { background: #EEF4D9; color: #183D22; border: 1px solid rgba(47,107,47,.18); }
        .alert-error { background: #FDE8E8; color: #991B1B; border: 1px solid rgba(185,28,28,.18); }

        .field label { display: block; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 600; color: #183D22; margin-bottom: 6px; }
        .field input, .field textarea, .field select {
            width: 100%; border: 1px solid #E5E2D6; border-radius: 12px;
            padding: 11px 14px; font-size: 14px; color: #25351F; background: #FFFFFF;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .field input:focus, .field textarea:focus, .field select:focus {
            outline: none; border-color: #2F6B2F; box-shadow: 0 0 0 3px rgba(47,107,47,.15);
        }
        .field .hint { font-size: 12px; color: #7D8575; margin-top: 6px; }
        .field .error-text { font-size: 13px; color: #DC2626; margin-top: 6px; }
        .field.has-error input, .field.has-error textarea, .field.has-error select { border-color: #DC2626; }

        .table-card { overflow: hidden; }
        .table-card .table-scroll { overflow-x: auto; }
        .table-card table { width: 100%; font-size: 14px; }
        .table-card th {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 11px; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase; color: #7D8575; text-align: left;
            padding: 14px 20px; background: #FAF9F4; border-bottom: 1px solid #EDEAE0; white-space: nowrap;
        }
        .table-card td { padding: 16px 20px; border-bottom: 1px solid #F0EEE4; vertical-align: middle; }
        .table-card tbody tr:last-child td { border-bottom: none; }
        .table-card tbody tr { transition: background .12s ease; }
        .table-card tbody tr:hover { background: #FAF9F4; }

        .stat-card { padding: 24px; }
        .stat-value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 36px; font-weight: 800; color: #183D22; letter-spacing: -.02em; }
        .stat-label { font-size: 14px; color: #7D8575; margin-top: 4px; }

        .chip { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; border-radius: 999px; padding: 4px 12px; }

        /* Kolom Aksi pada tabel */
        .table-card td:last-child { min-width: 280px; }
        .table-card .btn { min-height: 32px; }

        /* Mobile topbar */
        #mobileMenu a, #mobileMenu form { display: block; }
        #mobileMenu a.mob-link {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 14px; border-radius: 14px;
            font-size: 14px; font-weight: 600; color: #25351F;
            transition: background .15s ease;
        }
        #mobileMenu a.mob-link:hover, #mobileMenu a.mob-link:active { background: rgba(47,107,47,.07); }
        #mobileMenu a.mob-link.active { background: #EEF4D9; color: #2F6B2F; }

        /* Hilangkan spinner pada input number di semua browser */
        .field input[type="number"]::-webkit-outer-spin-button,
        .field input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .field input[type="number"] {
            -moz-appearance: textfield;
        }
        @yield('styles')
    </style>
</head>
<body class="min-h-screen">

    {{-- ======== Sidebar (desktop) ======== --}}
    <aside class="sidebar hidden lg:flex fixed inset-y-0 left-0 flex-col z-40" style="background:#183D22" aria-label="Navigasi admin">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-6 pt-7 pb-6">
            <span class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0" style="background:#2F6B2F">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.6H22l-6 4.6 2.3 7.3L12 17l-6.3 4.5L8 14.2 2 9.6h7.6z"/></svg>
            </span>
            <span>
                <span class="block font-extrabold text-white leading-tight">NU Rancamaya</span>
                <span class="block text-xs mt-0.5" style="color:rgba(238,244,217,.55)">Panel Admin</span>
            </span>
        </a>

        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.news.index') }}" class="sidebar-link {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V9"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8z"/></svg>
                Berita
            </a>
            <a href="{{ route('admin.pengurus.index') }}" class="sidebar-link {{ request()->routeIs('admin.pengurus*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Pengurus
            </a>
            <a href="{{ route('admin.dokumentasi.index') }}" class="sidebar-link {{ request()->routeIs('admin.dokumentasi*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                Dokumentasi
            </a>
            <a href="{{ route('admin.infaq.index') }}" class="sidebar-link {{ request()->routeIs('admin.infaq*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM21 14v3M14 21h3M21 21v-1"/></svg>
                Infaq
            </a>
            <a href="{{ route('admin.calon-anggota.index') }}" class="sidebar-link {{ request()->routeIs('admin.calon-anggota*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Calon Anggota
                @if($pendingCount > 0)
                    <span class="sidebar-badge">{{ $pendingCount }}</span>
                @endif
            </a>
        </nav>

        <div class="p-4 border-t" style="border-color:rgba(255,255,255,.1)">
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg>
                Lihat Website
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ======== Topbar (mobile) ======== --}}
    <header class="lg:hidden fixed top-0 inset-x-0 z-40 bg-white border-b" style="border-color:rgba(24,61,34,.08)">
        <div class="flex items-center justify-between h-16 px-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#2F6B2F">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.6H22l-6 4.6 2.3 7.3L12 17l-6.3 4.5L8 14.2 2 9.6h7.6z"/></svg>
                </span>
                <span class="font-extrabold leading-tight" style="color:#183D22">NU Rancamaya</span>
            </a>
            <button id="menuBtn" class="p-2 -mr-2 rounded-full transition hover:bg-black/5" aria-label="Buka menu" aria-expanded="false">
                <svg class="w-6 h-6" style="color:#183D22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobileMenu" class="hidden border-t px-4 pb-4 pt-2" style="border-color:rgba(24,61,34,.08); background:#fff">
            <a href="{{ route('admin.dashboard') }}" class="mob-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.news.index') }}" class="mob-link {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V9"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8z"/></svg>
                Berita
            </a>
            <a href="{{ route('admin.pengurus.index') }}" class="mob-link {{ request()->routeIs('admin.pengurus*') ? 'active' : '' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Pengurus
            </a>
            <a href="{{ route('admin.dokumentasi.index') }}" class="mob-link {{ request()->routeIs('admin.dokumentasi*') ? 'active' : '' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                Dokumentasi
            </a>
            <a href="{{ route('admin.infaq.index') }}" class="mob-link {{ request()->routeIs('admin.infaq*') ? 'active' : '' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM21 14v3M14 21h3M21 21v-1"/></svg>
                Infaq
            </a>
            <a href="{{ route('admin.calon-anggota.index') }}" class="mob-link {{ request()->routeIs('admin.calon-anggota*') ? 'active' : '' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Calon Anggota
                @if($pendingCount > 0)
                    <span class="chip ml-auto" style="background:#FDE8E8; color:#B91C1C">{{ $pendingCount }}</span>
                @endif
            </a>
            <div class="mx-2 my-2 border-t" style="border-color:rgba(24,61,34,.08)"></div>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="mob-link">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg>
                Lihat Website
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="mob-link w-full text-left" style="color:#B91C1C">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    {{-- ======== Konten ======== --}}
    <main class="lg:pl-[264px] pt-16 lg:pt-0 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 md:px-8 py-6 lg:py-10">
            @yield('content')
        </div>
    </main>

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuBtn && mobileMenu) {
            function setMobileMenu(open) {
                mobileMenu.classList.toggle('hidden', !open);
                menuBtn.setAttribute('aria-expanded', String(open));
                menuBtn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
            }
            menuBtn.addEventListener('click', () => setMobileMenu(mobileMenu.classList.contains('hidden')));
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => setMobileMenu(false));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
