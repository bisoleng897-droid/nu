@extends('layouts.admin')

@section('title', 'Kelola Calon Anggota')

@section('content')
    @php
        $tabs = [
            'pending'  => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'inactive' => 'Nonaktif',
            'semua'    => 'Semua',
        ];
        $counts = [
            'pending'  => \App\Models\CalonAnggota::where('status', 'pending')->count(),
            'approved' => \App\Models\CalonAnggota::where('status', 'approved')->count(),
            'rejected' => \App\Models\CalonAnggota::where('status', 'rejected')->count(),
            'inactive' => \App\Models\CalonAnggota::where('status', 'inactive')->count(),
        ];
    @endphp

    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="page-title">Calon Anggota</h1>
            <p class="page-sub">Pendaftar baru berstatus <strong>Menunggu Persetujuan</strong> hingga admin memprosesnya.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error mt-5">{{ session('error') }}</div>
    @endif

    {{-- ======== Filter status ======== --}}
    <div class="flex flex-wrap gap-2 mt-6" role="tablist" aria-label="Filter status">
        @foreach($tabs as $key => $label)
            <a href="{{ route('admin.calon-anggota.index', ['status' => $key, 'q' => request('q')]) }}"
               role="tab"
               aria-selected="{{ $status === $key ? 'true' : 'false' }}"
               class="btn {{ $status === $key ? 'btn-primary' : 'btn-soft' }} btn-sm"
               style="border-radius:999px">
                {{ $label }}
                @if(isset($counts[$key]) && $counts[$key] > 0)
                    <span class="text-xs opacity-70">{{ $counts[$key] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- ======== Pencarian ======== --}}
    <form method="GET" action="{{ route('admin.calon-anggota.index') }}" class="mt-5 flex gap-2 max-w-md">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, nomor pendaftaran, atau NIK..."
               class="flex-1 border rounded-xl px-4 py-2.5 text-sm transition focus:outline-none focus:border-[#2F6B2F] focus:shadow-[0_0_0_3px_rgba(47,107,47,.15)]"
               style="border-color:#E5E2D6; color:#25351F">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>

    {{-- ======== Tabel ======== --}}
    <div class="panel table-card mt-6">
        <div class="table-scroll">
            <table class="min-w-[760px]">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Nama</th>
                        <th>Organisasi</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftar as $p)
                        <tr>
                            <td class="font-mono text-sm">{{ $p->nomor_pendaftaran }}</td>
                            <td>
                                <span class="font-semibold" style="color:#183D22">{{ $p->nama_lengkap }}</span>
                                <span class="block text-xs" style="color:#7D8575">{{ $p->maskedNik() }}</span>
                            </td>
                            <td>{{ $p->organisasiLabel() }}</td>
                            <td class="whitespace-nowrap text-sm" style="color:#7D8575">{{ $p->submitted_at?->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="chip {{ $p->statusBadgeClass() }}">
                                    {{ $p->statusLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('admin.calon-anggota.show', $p) }}" class="btn btn-soft btn-sm justify-center">Lihat Detail</a>
                                    @if($p->isPending())
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('admin.calon-anggota.approve', $p) }}" class="flex-1"
                                                  onsubmit="return confirm('Setujui pendaftaran {{ $p->nama_lengkap }}? Status akan berubah menjadi Anggota Aktif.')">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm justify-center w-full">Setujui</button>
                                            </form>
                                            <a href="{{ route('admin.calon-anggota.show', $p) }}#aksi" class="btn btn-danger-soft btn-sm justify-center flex-1">Tolak</a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10" style="color:#7D8575">Belum ada data pendaftar pada status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $pendaftar->links() }}</div>
@endsection
