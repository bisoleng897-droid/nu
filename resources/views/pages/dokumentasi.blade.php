@extends('layouts.app')

@section('title', 'Dokumentasi - NU Rancamaya')

@section('styles')
    <style>
        .dokumen-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .dokumen-thumb {
            width: 100%;
            height: 220px;
            overflow: hidden;
            border-radius: 28px;
            background: linear-gradient(135deg, #EEF4D9, #E0EBBC);
        }
        .dokumen-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }
        .dokumen-card:hover .dokumen-thumb img {
            transform: scale(1.05);
        }
        .dokumen-judul {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .dokumen-deskripsi {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .folder-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 10px;
            border-radius: 999px;
            background: #EEF4D9;
            color: #2F6B2F;
        }
    </style>
@endsection

@section('content')
    <section class="text-center my-12 md:my-16">
        <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Galeri</span>
        <h1 class="mt-6 text-5xl md:text-6xl font-black leading-tight" style="color:#183D22">Dokumentasi Kegiatan</h1>
        <p class="text-lg mt-5" style="color:#5D6F5D">Galeri kegiatan dan acara NU Rancamaya yang menginspirasi</p>
    </section>

    @if($dokumentasi->isEmpty())
        <p class="text-center text-lg mt-16" style="color:#7D8575">Belum ada dokumentasi.</p>
    @else
        <?php
            $dokumenByKategori = $dokumentasi->groupBy(function ($d) {
                return $d->kategori ?? 'Tanpa Kategori';
            });
        ?>

        <div class="mt-14 space-y-12">
            @foreach($dokumenByKategori as $namaKategori => $koleksi)
                <div>
                    <h2 class="mb-6 text-2xl md:text-3xl font-bold" style="color:#183D22">
                        <span class="folder-badge">{{ strtoupper($namaKategori) }}</span>
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-7">
                        @foreach($koleksi as $d)
                            <article class="dokumen-card group border border-gray-100 rounded-[28px] bg-white shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="dokumen-thumb">
                                    @if($d->coverUrl())
                                        <img src="{{ $d->coverUrl() }}" alt="{{ $d->judul }}" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[11px] text-gray-400 bg-gray-50">Tanpa Foto</div>
                                    @endif
                                </div>
                                <div class="p-5 md:p-6 flex flex-col">
                                    <span class="folder-badge text-xs mb-2 block inline-flex" style="background:#F3F4F6; color:#4B5563">{{ $d->tanggal?->format('d M Y') }}</span>
                                    <h3 class="dokumen-judul font-bold text-base leading-tight mb-3" style="color:#183D22">{{ $d->judul }}</h3>
                                    @if($d->deskripsi)
                                        <p class="dokumen-deskripsi text-sm text-gray-600 leading-relaxed mb-4">{{ $d->deskripsi }}</p>
                                    @endif
                                    <div class="mt-auto text-xs text-green-700 font-semibold">{{ $d->fotos->count() }} foto</div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
