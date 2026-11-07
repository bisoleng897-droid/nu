@extends('layouts.admin')

@section('title', 'Kelola Dokumentasi')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="page-title">Kelola Dokumentasi</h1>
            <p class="page-sub">Foto dan dokumentasi kegiatan NU Rancamaya, dikelola per folder (kategori).</p>
        </div>
        <a href="{{ route('admin.dokumentasi.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Dokumentasi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
    @endif

    <div class="panel table-card mt-6">
        <div class="table-scroll">
            <table class="min-w-[720px]">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Folder</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumentasi as $d)
                        <tr>
                            <td>
                                @if($d->coverUrl())
                                    <img src="{{ $d->coverUrl() }}" alt="{{ $d->judul }}" class="w-16 h-12 object-cover rounded-xl border border-gray-100">
                                @else
                                    <div class="w-16 h-12 rounded-xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center text-[10px] text-gray-400">Tanpa Foto</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap" style="color:#7D8575">
                                {{ $d->kategori ? strtoupper($d->kategori) : 'Tanpa Kategori' }}
                            </td>
                            <td>
                                <span class="font-semibold block" style="color:#183D22">{{ $d->judul }}</span>
                            </td>
                            <td class="max-w-[220px]">
                                @if($d->deskripsi)
                                    <p class="text-xs text-gray-600 line-clamp-2" style="color:#5D6F5D; overflow-hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical">{{ $d->deskripsi }}</p>
                                @else
                                    <span class="text-xs text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap" style="color:#7D8575">{{ $d->tanggal?->format('d M Y') }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.dokumentasi.edit', $d) }}" class="btn btn-soft btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.dokumentasi.destroy', $d) }}" onsubmit="return confirm('Hapus dokumentasi ini beserta semua fotonya?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10" style="color:#7D8575">Belum ada dokumentasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($kategori->isNotEmpty())
        <div class="mt-6 panel p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Filter Folder</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-ghost btn-sm {{ request('kategori') === null ? 'btn-primary' : '' }}">Semua</a>
                @foreach($kategori as $kat)
                    <a href="{{ route('admin.dokumentasi.index') }}?kategori={{ urlencode($kat) }}" class="btn btn-soft btn-sm {{ request('kategori') === $kat ? 'btn-primary' : '' }}">{{ strtoupper($kat) }}</a>
                @endforeach
            </div>
        </div>
    @endif
@endsection
