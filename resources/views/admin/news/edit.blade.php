@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h1 class="page-title">Edit Berita</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-error mt-5">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" class="panel p-6 md:p-8 mt-6">
        @csrf @method('PUT')

        <div class="grid md:grid-cols-2 gap-5">
            <div class="field {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">Judul <span class="text-red-600">*</span></label>
                <input id="title" type="text" name="title" value="{{ old('title', $news->title) }}" required maxlength="255" placeholder="Contoh: Kajian Bulanan Oktober">
            </div>

            <div class="field {{ $errors->has('published_at') ? 'has-error' : '' }}">
                <label for="published_at">Tanggal Publikasi</label>
                <input id="published_at" type="date" name="published_at" value="{{ old('published_at', optional($news->published_at)->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="field mt-5 {{ $errors->has('content') ? 'has-error' : '' }}">
            <label for="content">Isi Berita <span class="text-red-600">*</span></label>
            <textarea id="content" name="content" rows="8" required placeholder="Tulis isi berita, kajian, atau pengumuman di sini...">{{ old('content', $news->content) }}</textarea>
        </div>

        @include('admin.news._peta', ['alamat' => $news->alamat, 'lat' => $news->lat, 'lng' => $news->lng])

        <div class="field mt-5 {{ $errors->has('image') ? 'has-error' : '' }}">
            <label for="image">Gambar</label>
            @if($news->image)
                <img src="{{ asset('storage/'.$news->image) }}" alt="{{ $news->title }}" class="w-40 rounded-2xl mb-3">
            @endif
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <p class="hint">Format: JPG / PNG / WebP &middot; Maksimal 2 MB &middot; Kosongkan untuk mempertahankan gambar lama</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-7">
            <button type="submit" class="btn btn-primary">Perbarui Berita</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
@endsection
