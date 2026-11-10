@extends('layouts.app')

@section('title', 'Berita & Kajian - NU Rancamaya')

@section('content')
    {{-- ============ HERO ============ --}}
    <section class="relative -mx-4 sm:-mx-5 md:-mx-6 rounded-b-[28px] md:rounded-b-[36px] overflow-hidden pt-12 pb-16 md:pt-16 md:pb-20 text-center" style="background:#2F6B2F">
        <div class="relative max-w-2xl mx-auto px-6 text-white">
            <p class="text-xs font-bold tracking-widest uppercase" style="color:rgba(255,255,255,.8)">Kabar Terkini</p>
            <h1 class="mt-2 text-3xl md:text-5xl font-extrabold leading-tight">Berita & Kajian</h1>
            <p class="mt-4 text-base" style="color:rgba(255,255,255,.95)">Informasi dan kabar kegiatan NU Rancamaya terdepan</p>
        </div>
    </section>

    {{-- ============ SEARCH ============ --}}
    <section class="mt-10 md:mt-14">
        <form action="{{ route('news.index') }}" method="GET" role="search" class="max-w-2xl mx-auto">
            <div class="relative flex items-center gap-1.5 bg-white rounded-full shadow-lg border p-1.5 min-w-0" style="border-color:rgba(24,61,34,.08)">
                <span class="pl-4 shrink-0" style="color:#2F6B2F">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul berita..."
                       class="flex-1 min-w-0 bg-transparent px-2 py-3 text-base outline-none" aria-label="Pencarian berita">
                <button class="px-6 py-3 rounded-full font-bold text-white transition-all hover:scale-105 active:scale-95 shrink-0" style="background:#2F6B2F">
                    Cari
                </button>
            </div>
            @if(request('q'))
                <p class="text-center text-sm mt-4 break-words" style="color:#7D8575">
                    Hasil untuk <strong style="color:#183D22">{{ request('q') }}</strong>
                    ·
                    <a href="{{ route('news.index') }}" class="font-semibold underline" style="color:#2F6B2F">Reset</a>
                </p>
            @endif
        </form>
    </section>

    {{-- ============ BERITA ============ --}}
    <section class="mt-14 md:mt-18">
        @if($allNews->isEmpty())
            <div class="text-center py-12">
                <p class="text-base" style="color:#7D8575">{{ request('q') ? 'Tidak ada berita yang sesuai.' : 'Belum ada berita.' }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allNews as $item)
                    <a href="{{ route('news.show', $item) }}" class="group flex flex-col bg-white rounded-[20px] overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-200 border h-full" style="border-color:rgba(24,61,34,.07)">
                        <div class="overflow-hidden aspect-video bg-gradient-to-br shrink-0" style="background: linear-gradient(135deg, #EEF4D9, #E0EBBC)">
                            @if($item->image)
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:#F9F9F7">
                                    <svg class="w-12 h-12" style="color:#2F6B2F" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9h4"/><path d="M8 6h12M8 10h12M8 14h8M8 18h8"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex flex-col gap-2 grow w-full overflow-hidden min-w-0">
                            <span class="text-xs font-semibold truncate" style="color:#2F6B2F">{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</span>
                            <h3 class="font-bold text-base line-clamp-2 group-hover:underline w-full" style="color:#183D22; word-break: break-word; overflow-wrap: break-word;">{{ $item->title }}</h3>
                            <p class="text-sm mt-auto line-clamp-2 w-full" style="color:#7D8575; word-break: break-word; overflow-wrap: break-word;">{{ Str::limit($item->content, 90) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <div class="mt-12"></div>
@endsection
