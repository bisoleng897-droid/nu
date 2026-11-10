@extends('layouts.admin')

@section('title', $news->title)

@section('content')
    <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold" style="color:#2F6B2F">&larr; Kembali ke daftar</a>

    <div class="panel p-6 md:p-8 mt-4">
        <h1 class="page-title">{{ $news->title }}</h1>
        <p class="mt-2 text-sm" style="color:#7D8575">{{ $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y') }}</p>

        @if($news->image)
            <img src="{{ asset('storage/'.$news->image) }}" alt="{{ $news->title }}" class="rounded-3xl my-6 w-full max-w-2xl">
        @endif

        <div class="text-sm leading-relaxed max-w-3xl" style="color:#25351F">{!! nl2br(e($news->content)) !!}</div>

        @if($news->alamat)
            <div class="mt-8">
                <p class="text-xs font-bold uppercase tracking-wider" style="color:#7D8575">Lokasi Kegiatan</p>
                <p class="mt-2 font-semibold flex items-center gap-2" style="color:#183D22">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $news->alamat }}
                </p>
                @if($news->hasCoordinates())
                    <p class="mt-1 font-mono text-xs" style="color:#7D8575">
                        {{ number_format($news->lat, 5) }}, {{ number_format($news->lng, 5) }}
                    </p>
                @else
                    <p class="mt-1 text-xs" style="color:#A8A693">
                        Belum ada titik peta — lokasi dicari dari teks alamat saat berita dibuka.
                    </p>
                @endif
                <div class="mt-3 max-w-2xl rounded-2xl overflow-hidden border" style="border-color:#EDEAE0">
                    <iframe title="Pratinjau peta: {{ $news->alamat }}" src="{{ $news->mapsEmbedUrl() }}"
                            class="w-full h-64 border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen></iframe>
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 mt-8 pt-6 border-t" style="border-color:#EDEAE0">
            <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-soft btn-sm">Edit Berita</a>
            <a href="{{ route('admin.news.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
        </div>
    </div>
@endsection
