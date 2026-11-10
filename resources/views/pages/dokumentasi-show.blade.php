@extends('layouts.app')

@section('title', '{{ $dokumentasi->judul }} - Dokumentasi - NU Rancamaya')

@section('styles')
    <style>
        .show-thumb {
            width: 100%;
            height: 420px;
            overflow: hidden;
            border-radius: 28px;
            background: linear-gradient(135deg, #EEF4D9, #E0EBBC);
        }
        .show-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .show-thumb:hover img {
            transform: scale(1.03);
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }
        .gallery-item {
            height: 200px;
            overflow: hidden;
            border-radius: 20px;
            background: #F3F4F6;
            position: relative;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .gallery-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(24,61,34,0.85);
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <section class="text-center my-10 md:my-14">
        <a href="{{ route('dokumentasi') }}" class="inline-flex items-center gap-2 text-sm font-medium text-green-700 hover:text-green-900 transition mb-4">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Galeri
        </a>
        <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3" style="color:#183D22">{{ $dokumentasi->judul }}</h1>
        <div class="flex flex-wrap items-center justify-center gap-3 text-sm">
            @if($dokumentasi->kategori)
                <span class="px-3 py-1 rounded-full font-bold text-xs uppercase tracking-wider" style="background:#EEF4D9; color:#2F6B2F">{{ strtoupper($dokumentasi->kategori) }}</span>
            @endif
            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 font-medium text-xs">{{ $dokumentasi->tanggal?->format('d M Y') ?? $dokumentasi->created_at->format('d M Y') }}</span>
            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 font-medium text-xs">{{ $dokumentasi->fotos->count() }} foto</span>
        </div>
    </section>

    @if($dokumentasi->deskripsi)
        <section class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8 mb-12">
            <p class="text-lg text-gray-700 leading-relaxed">{{ $dokumentasi->deskripsi }}</p>
        </section>
    @endif

    @if($dokumentasi->fotos->isNotEmpty())
        <section class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 mb-16">
            <h2 class="text-2xl md:text-3xl font-bold mb-8" style="color:#183D22">Foto Kegiatan</h2>
            <div class="gallery-grid">
                @foreach($dokumentasi->fotos as $foto)
                    <div class="gallery-item group">
                        <img src="{{ $foto->url() }}" alt="{{ $dokumentasi->judul }} - Foto {{ $loop->index }}" loading="lazy">
                        @if($foto->is_thumbnail)
                            <span class="gallery-badge">Sampul</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 text-center mb-16">
        <a href="{{ route('dokumentasi') }}" class="btn btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Galeri
        </a>
    </section>
@endsection
