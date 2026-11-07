@extends('layouts.app')

@section('title', 'Struktur Organisasi - NU Rancamaya')

@section('content')
    <section class="text-center my-12 md:my-16">
        <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Organisasi NU</span>
        <h1 class="mt-6 text-5xl md:text-6xl font-black leading-tight" style="color:#183D22">Struktur Organisasi</h1>
        <p class="text-lg mt-5" style="color:#5D6F5D">Pilih kategori badan otonom untuk melihat susunan pengurus dan struktur kami</p>
    </section>

    <div class="grid sm:grid-cols-2 gap-7 md:gap-8">
        @foreach($kategori as $slug => $k)
            <a href="{{ route('struktur.kategori', $slug) }}" class="group bg-white rounded-[28px] p-8 md:p-10 shadow-lg hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-2" style="border-color:rgba(24,61,34,.08)">
                <div class="w-16 h-16 rounded-[20px] flex items-center justify-center shadow-md transform group-hover:scale-110 transition-transform duration-300" style="background:#2F6B2F">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-black mt-6" style="color:#183D22">{{ $k['label'] }}</h3>
                <p class="text-base mt-3" style="color:#5D6F5D">{{ $k['desc'] }}</p>
                <span class="inline-flex items-center gap-2 mt-6 font-bold text-base" style="color:#2F6B2F">
                    <span>Lihat Struktur</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </a>
        @endforeach
    </div>
@endsection
