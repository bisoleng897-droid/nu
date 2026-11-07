@extends('layouts.admin')

@section('title', 'Kelola Pengurus')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="page-title">Kelola Pengurus</h1>
            <p class="page-sub">Susunan pengurus NU Rancamaya yang ditampilkan di halaman struktur.</p>
        </div>
        <a href="{{ route('admin.pengurus.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Pengurus
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
    @endif

    <div class="panel table-card mt-6">
        <div class="table-scroll">
            <table class="min-w-[760px]">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Kategori</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengurus as $p)
                        <tr>
                            <td>
                                @if($p->foto)
                                    <img src="{{ asset('storage/'.$p->foto) }}" alt="{{ $p->nama }}" class="w-11 h-11 rounded-full object-cover border border-gray-100">
                                @else
                                    <span class="w-11 h-11 rounded-full flex items-center justify-center" style="background:#F5F4EC; color:#A8A693">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </span>
                                @endif
                            </td>
                            <td class="font-semibold" style="color:#183D22">{{ $p->nama }}</td>
                            <td>{{ $p->jabatan }}</td>
                            <td>
                                <span class="chip" style="background:#EEF4D9; color:#2F6B2F">{{ \App\Http\Controllers\Admin\PengurusController::KATEGORI[$p->kategori] ?? $p->kategori }}</span>
                            </td>
                            <td>{{ $p->urutan }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.pengurus.edit', $p) }}" class="btn btn-soft btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.pengurus.destroy', $p) }}" onsubmit="return confirm('Hapus pengurus ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10" style="color:#7D8575">Belum ada pengurus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
