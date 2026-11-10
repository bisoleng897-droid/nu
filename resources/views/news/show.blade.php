@extends('layouts.app')

@section('title', $news->title . ' - NU Rancamaya')

@section('content')
    <article class="max-w-3xl mx-auto bg-white rounded-[28px] p-6 md:p-10 my-10 shadow-sm overflow-hidden">
        <a href="{{ route('home') }}" class="text-sm font-semibold inline-block" style="color:#2F6B2F">&larr; Kembali ke berita</a>
        <h1 class="text-3xl md:text-4xl font-extrabold mt-4 break-words" style="color:#183D22; word-break: break-word; overflow-wrap: break-word;">{{ $news->title }}</h1>
        <p class="mt-2 text-sm" style="color:#7D8575">{{ $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y') }}</p>
        @if($news->image)
            <img src="{{ $news->imageUrl() }}" class="rounded-3xl my-6 w-full max-w-full h-auto" alt="{{ $news->title }}" loading="lazy">
        @endif
        <div class="leading-relaxed min-w-0" style="color:#25351F; word-break: break-word; overflow-wrap: break-word; word-wrap: break-word;">{!! nl2br(e($news->content)) !!}</div>

        @if($news->alamat)
            <div class="mt-8 min-w-0">
                <p class="text-xs font-bold tracking-[0.2em] uppercase" style="color:#7D8575">Lokasi Kegiatan</p>
                <p class="mt-2 font-semibold flex items-center gap-2 break-words" style="color:#183D22; word-break: break-word; overflow-wrap: break-word;">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span class="break-words">{{ $news->alamat }}</span>
                </p>
                <div class="mt-4 rounded-3xl overflow-hidden border w-full" style="border-color:#EDEAE0">
                    <iframe
                        title="Peta lokasi kegiatan: {{ $news->alamat }}"
                        src="{{ $news->mapsEmbedUrl() }}"
                        class="w-full h-72 md:h-80 border-0 block"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
                </div>
                <a href="{{ $news->googleMapsUrl() }}" target="_blank" rel="noopener"
                   class="mt-3 inline-flex items-center gap-2 text-sm font-semibold" style="color:#2F6B2F">
                    Buka di Google Maps &rarr;
                </a>
            </div>
        @endif
    </article>
@endsection
