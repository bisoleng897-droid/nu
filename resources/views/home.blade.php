@extends('layouts.app')

@section('title', 'NU Rancamaya - Beranda')

@section('content')
    {{-- ============ HERO ============ --}}
    <header class="relative -mx-4 sm:-mx-5 md:-mx-6 rounded-b-[40px] md:rounded-b-[48px] overflow-hidden pt-20 pb-24 md:pt-32 md:pb-40 bg-cover bg-center flex flex-col items-center justify-center min-h-[560px]"
            style="background-image: url('https://images.unsplash.com/photo-1740970185537-2ecd0d9ca30f?q=80&w=1600&auto=format&fit=crop')">
        <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(24,61,34,.92) 0%, rgba(24,61,34,.75) 50%, rgba(24,61,34,.85) 100%)"></div>

        <div class="relative max-w-3xl mx-auto px-6 text-center text-white">
            <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase backdrop-blur-sm" style="background:rgba(255,255,255,.12); color:rgba(255,255,255,.9)">Organisasi Sosial Keagamaan</span>
            <h1 class="mt-6 text-5xl md:text-7xl font-black leading-tight tracking-tight">
                Menyatu dalam Dakwah,<br>Berkah untuk Desa
            </h1>
            <p class="mt-8 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto font-light" style="color:rgba(255,255,255,.92)">
                Portal digital NU Rancamaya — tempat berbagi kabar, kajian, dan dokumentasi kegiatan komunitas kami dengan transparansi penuh.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('news.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-semibold text-white transition-all hover:shadow-2xl hover:scale-105 active:scale-95 shadow-xl" style="background:#2F6B2F">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Jelajahi Kabar
                </a>
                <a href="{{ route('infaq') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-semibold transition-all hover:shadow-xl hover:scale-105 active:scale-95 border-2 backdrop-blur-sm" style="border-color:rgba(220,233,190,.6); color:#DCE9BE; background:rgba(255,255,255,.08)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Berinfaq Sekarang
                </a>
            </div>
        </div>
    </header>

    {{-- ============ SAMBUT ============ --}}
    <section class="mt-24 md:mt-32 grid md:grid-cols-2 gap-12 md:gap-16 items-center">
        <div class="rounded-[32px] overflow-hidden shadow-2xl border" style="border-color:rgba(24,61,34,.08)">
            <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=1000&auto=format&fit=crop"
                 alt="Al-Qur'an dan tradisi kajian NU" class="w-full aspect-[4/3] object-cover">
        </div>

        <div>
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Tentang Kami</span>
            <h2 class="mt-6 text-4xl md:text-5xl font-black leading-tight" style="color:#183D22">Rumah Digital NU Rancamaya</h2>
            <p class="mt-6 text-lg leading-relaxed" style="color:#5D6F5D">
                Kami membangun komunitas yang kuat melalui berbagi ilmu, kegiatan sosial, dan dakwah Ahlussunnah wal Jama'ah. Platform ini adalah wadah untuk saling terhubung, berbagi informasi, dan berkontribusi pada kemajuan bersama.
            </p>
            <ul class="mt-8 space-y-4">
                <li class="flex items-start gap-4">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white text-sm font-bold mt-0.5" style="background:#2F6B2F">✓</span>
                    <span class="text-base" style="color:#25351F">Kajian rutin dan pembinaan generasi muda NU</span>
                </li>
                <li class="flex items-start gap-4">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white text-sm font-bold mt-0.5" style="background:#2F6B2F">✓</span>
                    <span class="text-base" style="color:#25351F">Program kegiatan sosial dan kemanusiaan yang berdampak</span>
                </li>
                <li class="flex items-start gap-4">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white text-sm font-bold mt-0.5" style="background:#2F6B2F">✓</span>
                    <span class="text-base" style="color:#25351F">Transparansi dan akuntabilitas penuh dalam setiap kegiatan</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- ============ BERITA TERBARU ============ --}}
    <section class="mt-28 md:mt-36">
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Update Terkini</span>
            <h2 class="mt-5 text-4xl md:text-5xl font-black leading-tight" style="color:#183D22">Berita & Kajian Terbaru</h2>
            <p class="mt-5 text-lg" style="color:#5D6F5D">Informasi dan kabar kegiatan NU Rancamaya yang perlu Anda ketahui</p>
        </div>

        @if($news->isEmpty())
            <p class="text-center mt-16" style="color:#7D8575">Belum ada berita. Kunjungi <a href="{{ route('news.index') }}" class="font-semibold underline" style="color:#2F6B2F">halaman berita</a> untuk informasi lebih lengkap.</p>
        @else
            <div class="mt-14 grid md:grid-cols-3 gap-7">
                @foreach($news->take(3) as $item)
                    <a href="{{ route('news.show', $item) }}" class="group bg-white rounded-[28px] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-1" style="border-color:rgba(24,61,34,.08)">
                        <div class="overflow-hidden h-56 bg-gradient-to-br" style="background: linear-gradient(135deg, #EEF4D9, #E0EBBC)">
                            @if($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:#F9F9F7">
                                    <svg class="w-16 h-16" style="color:#2F6B2F" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9h4"/><path d="M8 6h12M8 10h12M8 14h8M8 18h8"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <span class="text-xs font-bold tracking-widest uppercase" style="color:#2F6B2F">{{ ($item->published_at ?? $item->created_at)->format('d M Y') }}</span>
                            <h3 class="font-black text-lg mt-3 line-clamp-2 group-hover:underline transition" style="color:#183D22">{{ $item->title }}</h3>
                            <p class="text-sm mt-3 line-clamp-2" style="color:#5D6F5D">{{ Str::limit($item->content, 80) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-14 text-center">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 font-bold px-8 py-4 rounded-full border-2 transition-all hover:shadow-lg hover:scale-105 active:scale-95" style="border-color:#2F6B2F; color:#2F6B2F">
                    <span>Lihat Semua Berita</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        @endif
    </section>

    {{-- ============ DOKUMENTASI ============ --}}
    <section class="mt-28 md:mt-36">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Galeri</span>
                <h2 class="mt-4 text-4xl md:text-5xl font-black leading-tight" style="color:#183D22">Dokumentasi Kegiatan</h2>
            </div>
            <a href="{{ route('dokumentasi') }}" class="shrink-0 text-sm font-bold inline-flex items-center gap-2 transition hover:gap-3" style="color:#2F6B2F">
                <span>Lihat Semua</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>

        @if($dokumentasi->isEmpty())
            <p class="mt-14 text-center text-lg" style="color:#7D8575">Belum ada dokumentasi kegiatan.</p>
        @else
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($dokumentasi as $d)
                    <a href="{{ route('dokumentasi') }}" class="group rounded-[24px] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="overflow-hidden aspect-square bg-gradient-to-br" style="background: linear-gradient(135deg, #EEF4D9, #E0EBBC)">
                            <img src="{{ asset('storage/'.$d->foto) }}" alt="{{ $d->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-500">
                        </div>
                        <div class="p-4 bg-white border-t" style="border-color:rgba(24,61,34,.08)">
                            <p class="text-sm font-bold line-clamp-2" style="color:#183D22">{{ $d->judul }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ============ DAFTAR ============ --}}
    <section class="mt-28 md:mt-36 rounded-[40px] md:rounded-[48px] p-10 md:p-16 md:p-20 shadow-2xl border" style="background:linear-gradient(135deg, #2F6B2F, #1a4620); border-color:rgba(255,255,255,.1)">
        <div class="max-w-2xl mx-auto text-center text-white">
            <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase backdrop-blur-sm" style="background:rgba(255,255,255,.15)">Bergabunglah</span>
            <h2 class="mt-6 text-4xl md:text-5xl font-black leading-tight">Menjadi Bagian dari NU Rancamaya</h2>
            <p class="mt-6 text-lg" style="color:rgba(255,255,255,.92)">
                Daftarkan diri Anda sebagai anggota komunitas kami dan bersama membangun masa depan yang lebih baik untuk desa kita.
            </p>
            <a href="{{ route('daftar.create') }}" class="mt-8 inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-bold text-white transition-all hover:shadow-xl hover:scale-105 active:scale-95" style="background:#DCE9BE; color:#183D22">
                <span>Mulai Pendaftaran</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>

    {{-- ============ INFAQ ============ --}}
    <section class="mt-28 md:mt-36">
        <div class="grid md:grid-cols-2 gap-12 md:gap-16 items-center">
            <div>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Amal Jariyah</span>
                <h2 class="mt-6 text-4xl md:text-5xl font-black leading-tight" style="color:#183D22">Mari Berinfaq</h2>
                <p class="mt-6 text-lg leading-relaxed" style="color:#5D6F5D">
                    Titipkan niat baik Anda untuk menopang kajian, kegiatan sosial, dan dakwah NU Rancamaya. Setiap rupiah disalurkan dengan transparan dan tepat sasaran untuk kemajuan bersama.
                </p>

                <div class="mt-8">
                    <div class="rounded-[24px] p-6 text-center shadow-lg border" style="background:#183D22; border-color:rgba(255,255,255,.1)">
                        <p class="text-sm font-semibold tracking-wide text-white/70">SALDO KAS INFAQ</p>
                        <p class="mt-4 font-black text-3xl md:text-4xl text-white">Rp {{ number_format($infaqTotals['saldo'], 0, ',', '.') }}</p>
                        <p class="mt-2 text-xs text-white/60">Diperbarui berkala • Transparan & Terukur</p>
                    </div>
                </div>

                <a href="{{ route('infaq') }}" class="mt-8 inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-bold text-white transition-all hover:shadow-lg hover:scale-105 active:scale-95" style="background:#2F6B2F">
                    <span>Lihat Detail & Scan QRIS</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>

            <div class="rounded-[32px] overflow-hidden shadow-2xl border" style="border-color:rgba(24,61,34,.08)">
                <img src="https://images.unsplash.com/photo-1771536145202-aa022f6ef7b2?q=80&w=1000&auto=format&fit=crop"
                     alt="Jamaah salat berjamaah" class="w-full aspect-[4/3] object-cover">
            </div>
        </div>
    </section>

    {{-- ============ CTA AKHIR ============ --}}
    <section class="mt-28 md:mt-36 py-16 md:py-20 rounded-[40px] md:rounded-[48px] shadow-2xl border" style="background:linear-gradient(135deg, #183D22, #0f2c18); border-color:rgba(255,255,255,.1)">
        <div class="max-w-2xl mx-auto text-center text-white">
            <p class="font-arabic text-4xl md:text-5xl font-bold">جَزَاكُمُ اللهُ خَيْرًا</p>
            <h2 class="mt-8 text-3xl md:text-4xl font-black leading-tight">
                Bersama Membangun Masa Depan yang Lebih Baik
            </h2>
            <p class="mt-6 text-lg" style="color:rgba(255,255,255,.88)">
                Terima kasih telah menjadi bagian dari keluarga besar NU Rancamaya. Dukungan Anda sangat berarti bagi perjalanan kami.
            </p>
        </div>
    </section>

    <div class="mt-12 md:mt-16"></div>
@endsection
