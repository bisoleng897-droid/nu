@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-sub">Ringkasan aktivitas NU Rancamaya hari ini.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-soft">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg>
            Lihat Website
        </a>
    </div>

    {{-- ======== Statistik ======== --}}
    <div class="grid sm:grid-cols-2 gap-5 mt-8">
        <div class="panel stat-card">
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0" style="background:#EEF4D9; color:#2F6B2F">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V9"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8z"/></svg>
                </span>
                <div>
                    <p class="stat-value">{{ $totalNews }}</p>
                    <p class="stat-label">Total Berita</p>
                </div>
            </div>
            <a href="{{ route('admin.news.index') }}" class="btn btn-ghost btn-sm mt-4 -ml-2">Kelola berita &rarr;</a>
        </div>

        <a href="{{ route('admin.calon-anggota.index') }}" class="panel stat-card card-hover block">
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0" style="background:#FBF3D9; color:#8A6D1D">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                </span>
                <div>
                    <p class="stat-value" style="{{ $pendingCount > 0 ? 'color:#8A6D1D' : '' }}">{{ $pendingCount }}</p>
                    <p class="stat-label">Calon Anggota Menunggu</p>
                </div>
            </div>
            <span class="btn btn-ghost btn-sm mt-4 -ml-2">Proses pendaftaran &rarr;</span>
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mt-6">
        {{-- ======== Pendaftar terbaru ======== --}}
        <div class="panel p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-extrabold text-lg" style="color:#183D22">Pendaftar Terbaru</h2>
                <a href="{{ route('admin.calon-anggota.index') }}" class="text-sm font-semibold" style="color:#2F6B2F">Lihat semua</a>
            </div>

            @if($latestPending->isNotEmpty())
                <ul class="mt-4 divide-y" style="--tw-divide-y:#F0EEE4">
                    @foreach($latestPending as $item)
                        <li class="py-3.5 flex items-center justify-between gap-3">
                            <span class="min-w-0">
                                <span class="block font-semibold truncate" style="color:#183D22">{{ $item->nama_lengkap }}</span>
                                <span class="block text-xs mt-0.5" style="color:#7D8575">{{ $item->organisasiLabel() }}</span>
                            </span>
                            <span class="flex items-center gap-3 shrink-0">
                                <span class="text-xs hidden sm:block" style="color:#7D8575">{{ $item->submitted_at?->format('d M Y, H:i') }}</span>
                                <a href="{{ route('admin.calon-anggota.show', $item) }}" class="btn btn-soft btn-sm">Proses</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm mt-4" style="color:#7D8575">Belum ada pendaftar yang menunggu persetujuan.</p>
            @endif
        </div>

        {{-- ======== Berita terbaru ======== --}}
        <div class="panel p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-extrabold text-lg" style="color:#183D22">Berita Terbaru</h2>
                <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold" style="color:#2F6B2F">Lihat semua</a>
            </div>

            @if($latestNews->isNotEmpty())
                <ul class="mt-4 divide-y" style="--tw-divide-y:#F0EEE4">
                    @foreach($latestNews as $item)
                        <li class="py-3.5 flex items-center justify-between gap-3">
                            <span class="font-semibold truncate min-w-0" style="color:#183D22">{{ $item->title }}</span>
                            <span class="text-xs shrink-0" style="color:#7D8575">{{ ($item->published_at ?? $item->created_at)->format('d M Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm mt-4" style="color:#7D8575">Belum ada berita.</p>
            @endif
        </div>
    </div>
@endsection
