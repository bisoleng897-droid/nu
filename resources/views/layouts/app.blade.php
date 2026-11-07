<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NU Rancamaya')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Amiri&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #F5F4EC; color: #25351F; overflow-x: hidden; }
        h1, h2, h3, h4, h5 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-arabic { font-family: 'Amiri', serif; }
        .card-hover { transition: transform .3s ease, box-shadow .3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 18px 36px rgba(24,61,34,.12); }
        .card-hover .card-img { transition: transform .35s ease; }
        .card-hover:hover .card-img { transform: scale(1.03); }
        input:focus, textarea:focus, select:focus { outline: none; box-shadow: 0 0 0 3px rgba(47,107,47,.18); }

        /* Navbar: floating capsule -> full-width fixed bar on scroll.
           Lebar diatur via JS dalam px agar transisi selalu smooth (px -> px).
           Background SELALU solid putih agar teks tetap terbaca di semua layar,
           termasuk saat di atas hero berwarna gelap. */
        #navbar {
            position: fixed;
            top: 24px;
            left: 0;
            right: 0;
            margin: 0 auto;
            width: min(1180px, calc(100% - 40px));
            height: 68px;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid rgba(24,61,34,.08);
            box-shadow: 0 10px 30px rgba(24,61,34,.08);
            z-index: 50;
            transition: width 300ms ease, top 300ms ease, height 300ms ease,
                        border-radius 300ms ease, box-shadow 300ms ease;
        }
        #navbar.scrolled {
            top: 0;
            height: 64px;
            border-radius: 0;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(24,61,34,.08);
        }
        /* Menu mobile terbuka: navbar membungkus menu */
        #navbar.menu-open { height: auto; border-radius: 28px; }
        #navbar.scrolled.menu-open { border-radius: 0; }
        /* Layar kecil: navbar lebih ringkas agar tidak terasa sesak.
           Pengecualian .menu-open dipakai supaya tinggi auto saat menu terbuka
           tidak tertimpa aturan di bawah ini. */
        @media (max-width: 640px) {
            #navbar:not(.menu-open) { top: 12px; height: 56px; }
            #navbar.scrolled:not(.menu-open) { height: 54px; }
            #navbar .nav-inner { padding: 0 14px; }
        }
        #navbar .nav-inner {
            max-width: 1180px;
            margin: 0 auto;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        /* Menu mobile: buka/tutup dikontrol penuh oleh CSS ini lewat kelas
           .is-open, bukan oleh utilitas Tailwind CDN — tombol strip-3 selalu
           berhasil membuka menu berapa pun urutan kelas yang dihasilkan CDN. */
        #mobileMenu {
            display: none;
            flex-direction: column;
            gap: 4px;
            border-top: 1px solid rgba(24,61,34,.08);
            margin-top: 2px;
            padding-top: 8px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }
        #mobileMenu.is-open { display: flex; }
        @media (min-width: 1024px) {
            #mobileMenu, #mobileMenu.is-open { display: none; }
        }
        #mobileMenu a.mob-link {
            display: block;
            padding: 11px 14px;
            border-radius: 14px;
            color: #25351F;
            transition: background 180ms ease;
        }
        #mobileMenu a.mob-link:hover,
        #mobileMenu a.mob-link:active {
            background: rgba(47,107,47,.07);
        }
        @yield('styles')
    </style>
</head>
<body>
    <!-- Navbar: floating capsule sebelum scroll, full-width fixed sesudah scroll -->
    <nav id="navbar" aria-label="Navigasi utama">
        <div class="nav-inner">
            <div class="flex items-center gap-3">
                @unless(request()->routeIs('home'))
                <button onclick="goBack()" aria-label="Kembali" class="w-9 h-9 rounded-full flex items-center justify-center transition hover:bg-black/10 shrink-0" style="color:#183D22">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </button>
                @endunless
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-lg" style="color:#2F6B2F">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.6H22l-6 4.6 2.3 7.3L12 17l-6.3 4.5L8 14.2 2 9.6h7.6z"/></svg>
                    NU Rancamaya
                </a>
            </div>

            <button id="menuBtn" class="lg:hidden p-2 -mr-2 rounded-full transition hover:bg-black/5" aria-label="Buka menu" aria-expanded="false">
                <svg class="w-6 h-6" style="color:#183D22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div class="hidden lg:flex gap-5 items-center text-sm font-medium">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'font-bold' : '' }}" style="color:{{ request()->routeIs('home') ? '#2F6B2F' : '#25351F' }}">Beranda</a>
                <a href="{{ route('news.index') }}" class="hover:text-green-800 transition">Berita</a>
                <a href="{{ route('struktur') }}" class="hover:text-green-800 transition">Struktur</a>
                <a href="{{ route('dokumentasi') }}" class="hover:text-green-800 transition">Dokumentasi</a>
                <a href="{{ route('infaq') }}" class="hover:text-green-800 transition">Infaq</a>
                
                <a href="{{ route('daftar.create') }}" class="text-white px-5 py-2 rounded-full font-semibold transition hover:opacity-90 flex items-center gap-2" style="background:#2F6B2F">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    Daftar Anggota
                </a>
            </div>
        </div>

        <div id="mobileMenu" class="px-4 pb-4 gap-1 text-sm font-medium">
            <a href="{{ route('home') }}" class="mob-link">Beranda</a>
            <a href="{{ route('news.index') }}" class="mob-link">Berita</a>
            <a href="{{ route('struktur') }}" class="mob-link">Struktur</a>
            <a href="{{ route('dokumentasi') }}" class="mob-link">Dokumentasi</a>
            <a href="{{ route('infaq') }}" class="mob-link">Infaq</a>
            <div class="mx-2 my-2 border-t" style="border-color:rgba(24,61,34,.08)"></div>
            <a href="{{ route('daftar.create') }}" class="block font-semibold text-white px-4 py-2.5 rounded-2xl text-center mt-1" style="background:#2F6B2F">Daftar Anggota</a>
        </div>
    </nav>

    <main class="mx-auto w-full max-w-[1280px] px-4 sm:px-5 md:px-6 pb-14 md:pb-16 {{ request()->routeIs('home') ? '' : 'pt-24 md:pt-28' }}">
        @yield('content')
    </main>

    <footer style="background:#183D22">
        <div class="max-w-[1180px] mx-auto px-5 md:px-6 py-10 md:py-12 grid gap-8 md:gap-10 md:grid-cols-3 items-start">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl text-white">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.6H22l-6 4.6 2.3 7.3L12 17l-6.3 4.5L8 14.2 2 9.6h7.6z"/></svg>
                    NU Rancamaya
                </a>
                <p class="text-white/60 text-sm mt-3 leading-relaxed">Portal digital NU Rancamaya — berita, kajian, dan dokumentasi kegiatan dalam satu tempat.</p>
            </div>
            <div>
                <h3 class="text-white font-bold">Navigasi</h3>
                <div class="flex flex-col gap-2 mt-3 text-sm text-white/70">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                    <a href="{{ route('news.index') }}" class="hover:text-white transition">Berita</a>
                    <a href="{{ route('struktur') }}" class="hover:text-white transition">Struktur</a>
                    <a href="{{ route('dokumentasi') }}" class="hover:text-white transition">Dokumentasi</a>
                    <a href="{{ route('infaq') }}" class="hover:text-white transition">Infaq</a>
                    <a href="{{ route('daftar.create') }}" class="hover:text-white transition">Daftar Anggota</a>
                    <a href="{{ route('cek-status') }}" class="hover:text-white transition">Cek Status Pendaftaran</a>
                </div>
            </div>
            <div>
                <h3 class="text-white font-bold">Tautan</h3>
                <div class="flex gap-3 mt-3">
                    <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition">IG</a>
                    <a href="#" aria-label="YouTube" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition">YT</a>
                    <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition">FB</a>
                </div>
                <p class="font-arabic text-2xl text-green-100 mt-6">جَزَاكُمُ اللهُ خَيْرًا</p>
            </div>
        </div>
        <div class="border-t border-white/10">
            <p class="text-center text-white/50 text-sm py-5">&copy; {{ date('Y') }} NU Rancamaya. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        function goBack() {
            const path = window.location.pathname;
            const struktur = @json(route('struktur'));
            const home = @json(route('home'));

            if (path.startsWith(struktur + '/')) {
                window.location.href = struktur;
                return;
            }
            if (path === struktur) {
                window.location.href = home;
                return;
            }
            if (path === home) return;

            if (history.length > 1) {
                history.back();
            } else {
                window.location.href = home;
            }
        }

        const navbar = document.getElementById('navbar');
        let isScrolled = false;

        function applyNavWidth(scrolled) {
            const gutter = window.innerWidth < 640 ? 20 : 40;
            navbar.style.width = scrolled
                ? window.innerWidth + 'px'
                : Math.max(0, Math.min(1180, window.innerWidth - gutter)) + 'px';
        }

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY > 40;
            if (scrolled !== isScrolled) {
                isScrolled = scrolled;
                navbar.classList.toggle('scrolled', scrolled);
                applyNavWidth(scrolled);
            }
        }, { passive: true });

        // Rentang lebar diubah: sesuaikan lebar navbar dan tutup menu mobile
        // begitu tampilan desktop muncul.
        window.addEventListener('resize', () => {
            applyNavWidth(isScrolled);

            if (window.innerWidth >= 1024) {
                setMobileMenu(false);
            }
        });

        applyNavWidth(false);

        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        function setMobileMenu(open) {
            mobileMenu.classList.toggle('is-open', open);
            navbar.classList.toggle('menu-open', open);
            menuBtn.setAttribute('aria-expanded', String(open));
            menuBtn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        }

        function isMobileMenuOpen() {
            return mobileMenu.classList.contains('is-open');
        }

        menuBtn.addEventListener('click', () => setMobileMenu(!isMobileMenuOpen()));

        // Tutup menu mobile begitu sebuah tautan di dalamnya diklik,
        // agar halaman berikutnya tidak ditumpuki menu yang masih terbuka.
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => setMobileMenu(false));
        });

        // Tutup menu saat beralih ke layar lebar atau saat tombol Esc ditekan.
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                setMobileMenu(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && isMobileMenuOpen()) {
                setMobileMenu(false);
                menuBtn.focus();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
