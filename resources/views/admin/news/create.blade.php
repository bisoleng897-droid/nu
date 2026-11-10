@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h1 class="page-title">Tambah Berita</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-error mt-5">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="panel p-6 md:p-8 mt-6">
        @csrf

        <div class="field {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">Judul <span class="text-red-600">*</span></label>
            <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Kajian Bulanan Oktober">
        </div>

        <div class="field mt-5 {{ $errors->has('published_at') ? 'has-error' : '' }}">
            <label for="published_at">Tanggal Publikasi</label>
            <input id="published_at" type="date" name="published_at" value="{{ old('published_at', now('Asia/Jakarta')->toDateString()) }}">
            <p class="hint">Otomatis terisi tanggal hari ini (WIB). Anda tetap dapat mengubahnya.</p>
        </div>

        <div class="field mt-5 {{ $errors->has('content') ? 'has-error' : '' }}">
            <label for="content">Isi Berita <span class="text-red-600">*</span></label>
            <textarea id="content" name="content" rows="8" required placeholder="Tulis isi berita, kajian, atau pengumuman di sini...">{{ old('content') }}</textarea>
        </div>

        @include('admin.news._peta', ['alamat' => null, 'lat' => null, 'lng' => null])

        <div class="field mt-5 {{ $errors->has('image') ? 'has-error' : '' }}">
            <label for="image">Gambar</label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <p class="hint">Format: JPG / PNG / WebP &middot; Maksimal 2 MB</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-7">
            <button type="submit" class="btn btn-primary">Simpan Berita</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
@endsection
