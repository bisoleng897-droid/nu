@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="page-title">Kelola Berita</h1>
            <p class="page-sub">Berita, kajian, dan pengumuman kegiatan untuk website.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Berita
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
    @endif

    <div class="panel table-card mt-6">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        <tr>
                            <td>
                                <span class="font-semibold" style="color:#183D22">{{ $item->title }}</span>
                                @if($item->alamat)
                                    <span class="block text-xs mt-1" style="color:#7D8575">
                                        <svg class="w-3 h-3 inline -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $item->alamat }}
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap" style="color:#7D8575">{{ ($item->published_at ?? $item->created_at)->format('d M Y') }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-soft btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.news.destroy', $item) }}" onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-10" style="color:#7D8575">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $news->links() }}</div>
@endsection
