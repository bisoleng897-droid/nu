@extends('layouts.app')

@section('title', 'Infaq & Donasi - NU Rancamaya')

@section('content')
    {{-- ============ HERO (FOCUSED) ============ --}}
    <header class="relative -mx-4 sm:-mx-5 md:-mx-6 rounded-b-[40px] md:rounded-b-[48px] overflow-hidden pt-16 pb-24 md:pt-28 md:pb-32 bg-cover bg-center flex flex-col items-center justify-center min-h-[500px]"
            style="background-image: url('https://images.unsplash.com/photo-1740970185537-2ecd0d9ca30f?q=80&w=1600&auto=format&fit=crop')">
        <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(24,61,34,.92) 0%, rgba(24,61,34,.76) 50%, rgba(24,61,34,.87) 100%)"></div>

        <div class="relative max-w-2xl mx-auto px-6 text-center text-white">
            <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase backdrop-blur-sm" style="background:rgba(255,255,255,.12); color:rgba(255,255,255,.9)">Amal Jariyah Bersama NU Rancamaya</span>
            <h1 class="mt-6 text-5xl md:text-6xl font-black leading-tight tracking-tight">
                Mari Berinfaq
            </h1>
            <p class="mt-7 text-lg md:text-xl leading-relaxed font-light max-w-xl mx-auto" style="color:rgba(255,255,255,.92)">
                Titipkan niat baik Anda. Setiap rupiah menopang kajian, kegiatan sosial, dan dakwah NU Rancamaya dengan transparansi penuh.
            </p>
            
            <a href="#qris" class="mt-10 inline-flex items-center justify-center gap-3 px-8 py-4 rounded-full font-bold text-white transition-all duration-200 hover:shadow-xl hover:scale-105 active:scale-95 shadow-lg" style="background:#2F6B2F">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM21 14v3M14 21h3M21 21h.01"/></svg>
                <span>Scan QRIS Sekarang</span>
            </a>
        </div>
    </header>

    {{-- ============ QRIS + STATS (INTEGRATED) ============ --}}
    <section class="mt-14 md:mt-20">
        <div class="grid lg:grid-cols-2 gap-8 md:gap-10" id="qris">
            {{-- QRIS Card --}}
            <div class="bg-white rounded-[32px] md:rounded-[40px] p-8 md:p-10 shadow-xl border transform hover:-translate-y-1 transition-all duration-300" style="border-color:rgba(24,61,34,.08)">
                <div class="flex items-center gap-4 mb-7">
                    <span class="w-12 h-12 rounded-full flex items-center justify-center shadow-md" style="background:#EEF4D9; color:#2F6B2F">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM21 14v3M14 21h3M21 21h.01"/></svg>
                    </span>
                    <h2 class="text-2xl font-black" style="color:#183D22">Scan & Donasi</h2>
                </div>

                @if($setting->hasQris())
                    <a href="{{ $setting->qrisUrl() }}" target="_blank" rel="noopener" class="block">
                        <div class="rounded-[24px] p-5 flex items-center justify-center shadow-lg" style="background:#FAF9F4">
                            <img src="{{ $setting->qrisUrl() }}" alt="QRIS infaq NU Rancamaya"
                                 class="max-w-[200px] aspect-square object-contain hover:scale-110 transition-transform duration-300">
                        </div>
                    </a>
                    <p class="mt-6 text-sm text-center font-medium" style="color:#5D6F5D">
                        <span class="font-bold" style="color:#2F6B2F">👆 Tap QR</span> untuk membuka di aplikasi bank atau e-wallet Anda
                    </p>
                @else
                    <div class="rounded-[24px] border border-dashed p-10 text-center" style="border-color:#E5E2D6; color:#7D8575">
                        <p class="text-base font-semibold">QRIS belum tersedia</p>
                        <p class="text-sm mt-2">Hubungi pengurus untuk info lebih lanjut</p>
                    </div>
                @endif

                <div class="mt-7 rounded-[20px] p-5 border-l-4" style="background:#F0F4E9; border-color:#2F6B2F">
                    <p class="text-sm font-bold" style="color:#183D22">💡 Tips Keamanan</p>
                    <p class="text-sm mt-3 leading-relaxed" style="color:#5D6F5D">Pastikan nama penerima ialah <strong style="color:#183D22">NU Rancamaya</strong> sebelum konfirmasi pembayaran.</p>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="flex flex-col gap-6 md:gap-7">
                {{-- Ringkasan Kas --}}
                <div class="bg-white rounded-[32px] md:rounded-[40px] p-8 md:p-10 shadow-xl border transform hover:-translate-y-1 transition-all duration-300" style="border-color:rgba(24,61,34,.08)">
                    <p class="text-xs font-bold tracking-widest uppercase" style="color:#7D8575">Status Kas Saat Ini</p>
                    
                    <div class="mt-8">
                        <div class="rounded-[28px] p-8 text-center shadow-lg" style="background:linear-gradient(135deg, #183D22, #0f2c18)">
                            <p class="text-sm font-semibold tracking-wide text-white/70">SALDO KAS INFAQ</p>
                            <p class="mt-4 font-black text-3xl md:text-4xl text-white">Rp {{ number_format($totals['saldo'], 0, ',', '.') }}</p>
                            <p class="mt-3 text-xs text-white/60">Diperbarui berkala · Transparan & Terukur</p>
                        </div>
                    </div>

                    <p class="mt-6 text-sm leading-relaxed" style="color:#5D6F5D">
                        Transparansi kas infaq diperbarui berkala. Semua dana disalurkan untuk program dan kegiatan NU Rancamaya sesuai dengan prinsip syariah.
                    </p>
                </div>

                {{-- Trust Badges --}}
                <div class="bg-gradient-to-br rounded-[32px] md:rounded-[40px] p-8 md:p-10 shadow-xl border transform hover:-translate-y-1 transition-all duration-300" style="background: linear-gradient(135deg, #2F6B2F, #1a4620); border-color:rgba(255,255,255,.1)">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-white/80 mt-1 shadow-lg" style="background:rgba(255,255,255,.15)">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1a11 11 0 100 22 11 11 0 000-22zm5.293 7.293a1 1 0 011.414 0l-8 8-3.293-3.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/></svg>
                        </span>
                        <div>
                            <p class="text-lg font-black text-white">Terpercaya & Terukur</p>
                            <p class="text-sm mt-2 text-white/80">Ahlussunnah wal Jama'ah · Pengelolaan Transparan · Audit Rutin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ 3-STEP FLOW ============ --}}
    <section class="mt-28 md:mt-36 py-16 md:py-20 rounded-[40px] md:rounded-[48px] shadow-xl border" style="background:#F9F9F7; border-color:rgba(24,61,34,.08)">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center">
                <h2 class="text-4xl md:text-5xl font-black" style="color:#183D22">Tiga Langkah Mudah</h2>
                <p class="mt-4 text-lg" style="color:#5D6F5D">Dari niat hingga tersalur hanya dalam hitungan detik</p>
            </div>

            <div class="mt-14 md:mt-16 grid md:grid-cols-3 gap-8 md:gap-6">
                {{-- Step 1 --}}
                <div class="text-center">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto font-black text-2xl md:text-3xl text-white shadow-lg transform hover:scale-110 transition-transform duration-300" style="background:#2F6B2F">
                        1
                    </div>
                    <h3 class="mt-6 font-black text-lg md:text-xl" style="color:#183D22">Buka Aplikasi Bank</h3>
                    <p class="mt-3 text-base leading-relaxed" style="color:#5D6F5D">Siapkan aplikasi bank atau e-wallet favorit Anda untuk melakukan transfer</p>
                </div>

                {{-- Arrow (desktop only) --}}
                <div class="hidden md:flex items-center justify-center">
                    <svg class="w-7 h-7 -mx-2 animate-bounce" style="color:#DCE9BE" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>

                {{-- Step 2 --}}
                <div class="text-center">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto font-black text-2xl md:text-3xl text-white shadow-lg transform hover:scale-110 transition-transform duration-300" style="background:#2F6B2F">
                        2
                    </div>
                    <h3 class="mt-6 font-black text-lg md:text-xl" style="color:#183D22">Scan QR Code</h3>
                    <p class="mt-3 text-base leading-relaxed" style="color:#5D6F5D">Pindai kode QRIS di atas atau di bagian kartu infaq halaman ini</p>
                </div>

                {{-- Arrow (desktop only) --}}
                <div class="hidden md:flex items-center justify-center">
                    <svg class="w-7 h-7 -mx-2 animate-bounce" style="color:#DCE9BE" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto font-black text-2xl md:text-3xl text-white shadow-lg transform hover:scale-110 transition-transform duration-300" style="background:#2F6B2F">
                        3
                    </div>
                    <h3 class="mt-6 font-black text-lg md:text-xl" style="color:#183D22">Konfirmasi & Selesai</h3>
                    <p class="mt-3 text-base leading-relaxed" style="color:#5D6F5D">Terima kasih atas amal jariyah dan kepedulian Anda kepada kami</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ IMPACT / PROGRAMS ============ --}}
    <section class="mt-28 md:mt-36">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-black" style="color:#183D22">Infaq Anda Disalurkan Untuk</h2>
            <p class="mt-5 text-lg" style="color:#5D6F5D">Tiga pilar utama dakwah dan kegiatan NU Rancamaya</p>
        </div>
        
        <div class="mt-16 md:mt-20 grid md:grid-cols-3 gap-7 md:gap-8">
            {{-- Program 1 --}}
            <article class="group rounded-[28px] overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-2" style="border-color:rgba(24,61,34,.08)">
                <div class="overflow-hidden h-56 bg-gradient-to-br" style="background: linear-gradient(135deg, #EEF4D9, #E0EBBC)">
                    <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=600&auto=format&fit=crop"
                         alt="Al-Qur'an" class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-500">
                </div>
                <div class="p-7 md:p-8">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md" style="background:#2F6B2F">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="12 1 22 8 22 16 12 23 2 16 2 8 12 1"></polyline></svg>
                        </span>
                        <h3 class="font-black text-lg" style="color:#183D22">Kajian & Pendidikan</h3>
                    </div>
                    <p class="mt-4 text-base leading-relaxed" style="color:#5D6F5D">Mendukung majelis ilmu, kajian rutin, dan pembinaan generasi muda Islam yang berkualitas</p>
                </div>
            </article>

            {{-- Program 2 --}}
            <article class="group rounded-[28px] overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-2" style="border-color:rgba(24,61,34,.08)">
                <div class="overflow-hidden h-56 bg-gradient-to-br" style="background: linear-gradient(135deg, #FDE8E8, #F5D1D1)">
                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600&auto=format&fit=crop"
                         alt="Kegiatan sosial" class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-500">
                </div>
                <div class="p-7 md:p-8">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md" style="background:#B91C1C">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </span>
                        <h3 class="font-black text-lg" style="color:#183D22">Sosial & Kemanusiaan</h3>
                    </div>
                    <p class="mt-4 text-base leading-relaxed" style="color:#5D6F5D">Membantu warga yang membutuhkan dan kegiatan bakti sosial komunitas dengan komitmen penuh</p>
                </div>
            </article>

            {{-- Program 3 --}}
            <article class="group rounded-[28px] overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-2" style="border-color:rgba(24,61,34,.08)">
                <div class="overflow-hidden h-56 bg-gradient-to-br" style="background: linear-gradient(135deg, #E7F2D3, #D3E8B8)">
                    <img src="https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=600&auto=format&fit=crop"
                         alt="Masjid" class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-500">
                </div>
                <div class="p-7 md:p-8">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md" style="background:#2F6B2F">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L4 6v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V6l-8-4z"/></svg>
                        </span>
                        <h3 class="font-black text-lg" style="color:#183D22">Sarana Ibadah & Dakwah</h3>
                    </div>
                    <p class="mt-4 text-base leading-relaxed" style="color:#5D6F5D">Merawat dan mengembangkan sarana ibadah serta program dakwah NU untuk kemajuan bersama</p>
                </div>
            </article>
        </div>
    </section>

    {{-- ============ CTA PENUTUP ============ --}}
    <section class="mt-28 md:mt-36 rounded-[40px] md:rounded-[48px] p-12 md:p-16 shadow-2xl border" style="background:linear-gradient(135deg, #183D22, #0f2c18); border-color:rgba(255,255,255,.1)">
        <p class="font-arabic text-5xl md:text-6xl font-bold text-center text-white">جَزَاكُمُ اللهُ خَيْرًا</p>
        <h2 class="mt-8 text-4xl md:text-5xl font-black text-center text-white max-w-3xl mx-auto leading-tight">
            Terima kasih atas Kepedulian Anda
        </h2>
        <p class="mt-8 text-lg text-center text-white/88 max-w-2xl mx-auto leading-relaxed">
            Setiap donasi adalah wujud nyata dari komitmen kita bersama dalam memperkuat umat, dakwah, dan kebersamaan di NU Rancamaya.
        </p>
        
        <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-bold text-white transition-all hover:shadow-lg hover:scale-105 active:scale-95" style="background:#2F6B2F">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Beranda</span>
            </a>
            <a href="{{ route('daftar.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-bold border-2 transition-all hover:shadow-lg hover:scale-105 active:scale-95 backdrop-blur-sm" style="border-color:rgba(220,233,190,.6); color:#DCE9BE; background:rgba(255,255,255,.08)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM9 19a7 7 0 1114 0"/></svg>
                <span>Daftar Anggota NU</span>
            </a>
        </div>
    </section>

    <div class="mt-12 md:mt-16"></div>
@endsection
