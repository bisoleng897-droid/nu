@extends('layouts.app')

@section('title', $info['label'] . ' - Struktur Organisasi')

@section('content')
    <section class="text-center my-12 md:my-16">
        <a href="{{ route('struktur') }}" class="text-base font-bold inline-flex items-center gap-2 transition-all hover:gap-3" style="color:#2F6B2F">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            <span>Semua Kategori</span>
        </a>
        <h1 class="text-4xl md:text-5xl font-black mt-6 leading-tight" style="color:#183D22">Struktur Organisasi {{ $info['label'] }}</h1>
        <p class="text-lg mt-5" style="color:#5D6F5D">{{ $info['desc'] }}</p>
    </section>

    @php
        $sections = $kategori === 'ipnu-ippnu'
            ? [
                ['label' => 'IPNU — Laki-laki', 'items' => $ipnu],
                ['label' => 'IPPNU — Perempuan', 'items' => $ippnu],
              ]
            : [['label' => $info['label'], 'items' => $pengurus]];
    @endphp

    @foreach($sections as $section)
        <div class="mb-16 md:mb-20">
            <div class="flex items-center gap-4 mb-8 md:mb-10">
                <span class="w-3 h-3 rounded-full" style="background:#2F6B2F"></span>
                <h2 class="text-3xl md:text-4xl font-black" style="color:#183D22">{{ $section['label'] }}</h2>
            </div>

            @if($section['items']->isEmpty())
                <p class="text-lg" style="color:#7D8575">Data pengurus belum tersedia.</p>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7 md:gap-8">
                    @foreach($section['items'] as $p)
                        <div class="bg-white rounded-[28px] p-8 text-center shadow-lg hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-2" style="border-color:rgba(24,61,34,.08)">
                            @if($p->foto)
                                <img src="{{ asset('storage/'.$p->foto) }}" class="w-32 h-32 mx-auto rounded-[20px] object-cover shadow-md">
                            @else
                                <div class="w-32 h-32 mx-auto rounded-[20px] bg-green-50 flex items-center justify-center shadow-md" style="color:#2F6B2F">
                                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                            @endif
                            <h3 class="font-black text-lg mt-6" style="color:#183D22">{{ $p->nama }}</h3>
                            <p class="text-base mt-2 font-semibold" style="color:#2F6B2F">{{ $p->jabatan }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
@endsection
