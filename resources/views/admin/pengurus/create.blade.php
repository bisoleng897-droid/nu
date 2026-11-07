@extends('layouts.admin')

@section('title', 'Tambah Pengurus')

@section('content')
    <h1 class="page-title">Tambah Pengurus</h1>

    @if($errors->any())
        <div class="alert alert-error mt-5">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.pengurus.store') }}" enctype="multipart/form-data" class="panel p-6 md:p-8 mt-6">
        @csrf

        <div class="grid md:grid-cols-2 gap-5">
            <div class="field {{ $errors->has('nama') ? 'has-error' : '' }}">
                <label for="nama">Nama <span class="text-red-600">*</span></label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required maxlength="255" placeholder="Contoh: Ustadz Ahmad Syaifuddin">
            </div>

            <div class="field {{ $errors->has('jabatan') ? 'has-error' : '' }}">
                <label for="jabatan">Jabatan <span class="text-red-600">*</span></label>
                <input id="jabatan" type="text" name="jabatan" value="{{ old('jabatan') }}" required maxlength="255" placeholder="Contoh: Ketua">
            </div>

            <div class="field {{ $errors->has('kategori') ? 'has-error' : '' }}">
                <label for="kategori">Kategori <span class="text-red-600">*</span></label>
                <select id="kategori" name="kategori" required>
                    @foreach($kategoriList as $key => $label)
                        <option value="{{ $key }}" @selected(old('kategori') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field {{ $errors->has('urutan') ? 'has-error' : '' }}">
                <label for="urutan">Urutan Tampil</label>
                <input id="urutan" type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0">
                <p class="hint">Angka lebih kecil ditampilkan lebih dulu.</p>
            </div>
        </div>

        <div class="field mt-5 {{ $errors->has('foto') ? 'has-error' : '' }}">
            <label for="foto">Foto</label>
            <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp">
            <p class="hint">Format: JPG / PNG / WebP &middot; Maksimal 2 MB</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-7">
            <button type="submit" class="btn btn-primary">Simpan Pengurus</button>
            <a href="{{ route('admin.pengurus.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
@endsection
