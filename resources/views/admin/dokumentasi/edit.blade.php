@extends('layouts.admin')

@section('title', 'Edit Dokumentasi')

@section('content')
    <h1 class="page-title">Edit Dokumentasi</h1>

    @if($errors->any())
        <div class="alert alert-error mt-5">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.dokumentasi.update', $dokumentasi) }}" enctype="multipart/form-data" class="panel p-6 md:p-8 mt-6">
        @csrf @method('PUT')

        <div class="grid md:grid-cols-2 gap-5">
            <div class="field {{ $errors->has('kategori') ? 'has-error' : '' }}">
                <label for="kategori">Kategori (Folder)</label>
                <input id="kategori" type="text" name="kategori" value="{{ old('kategori', $dokumentasi->kategori) }}" maxlength="100" placeholder="Contoh: Kajian, Musyawarah, Acara">
                <p class="hint">Digunakan untuk mengelompokkan dokumentasi seperti folder.</p>
            </div>

            <div class="field {{ $errors->has('judul') ? 'has-error' : '' }}">
                <label for="judul">Judul <span class="text-red-600">*</span></label>
                <input id="judul" type="text" name="judul" value="{{ old('judul', $dokumentasi->judul) }}" required maxlength="255" placeholder="Contoh: Kajian Keliling Desa">
            </div>
        </div>

        <div class="field {{ $errors->has('tanggal') ? 'has-error' : '' }}">
            <label for="tanggal">Tanggal Publikasi</label>
            <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', $dokumentasi->tanggal?->toDateString() ?? $dokumentasi->created_at->timezone('Asia/Jakarta')->toDateString()) }}">
            <p class="hint">Tanggal publikasi tersimpan. Ubah bila perlu, atau biarkan sesuai tanggal sebelumnya.</p>
        </div>

        <div class="field mt-5 {{ $errors->has('deskripsi') ? 'has-error' : '' }}">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" placeholder="Cerita singkat tentang kegiatan ini (opsional)">{{ old('deskripsi', $dokumentasi->deskripsi) }}</textarea>
            <p class="hint">Teks akan dipotong agar tidak keluar kotak pada halaman galeri.</p>
        </div>

        <div class="field mt-5 {{ $errors->has('fotos') ? 'has-error' : '' }}">
            <label>Foto Saat Ini</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3">
                @if($dokumentasi->fotos->isEmpty())
                    <span class="text-xs text-gray-400 italic">Belum ada foto.</span>
                @else
                    @foreach($dokumentasi->fotos as $foto)
                        <div class="relative group">
                            <img src="{{ $foto->url() }}" alt="{{ $dokumentasi->judul }}" class="w-full h-24 object-cover rounded-xl border border-gray-200">
                            <span class="absolute top-1 left-1 bg-{{ $foto->is_thumbnail ? 'green' : 'gray' }}-700 text-white text-[10px] px-2 py-0.5 rounded">
                                {{ $foto->is_thumbnail ? 'Thumbnail' : 'Foto #' . ($foto->urutan + 1) }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="field mt-3">
                <label for="fotos">Ganti / Tambah Foto (maks. 10 total)</label>
                <input id="fotos" type="file" name="fotos[]" accept="image/jpeg,image/png,image/webp" multiple>
                <p class="hint">Kosongkan jika tidak ingin mengubah foto. Jika diisi, semua foto lama akan diganti. Maksimal 10 foto. Format: JPG / PNG / WebP &middot; Maksimal 2 MB per foto.</p>
            </div>

            <div id="fotoPreview" class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-7">
            <button type="submit" class="btn btn-primary">Perbarui Dokumentasi</button>
            <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>

    <script>
        const input = document.getElementById('fotos');
        const preview = document.getElementById('fotoPreview');

        input.addEventListener('change', function () {
            preview.innerHTML = '';
            const files = Array.from(this.files || []);

            if (files.length > 10) {
                alert('Maksimal 10 foto.');
                this.value = '';
                return;
            }

            files.forEach((file, i) => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative group';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-24 object-cover rounded-xl border border-gray-200';
                    img.alt = 'Preview ' + (i + 1);

                    const badge = document.createElement('div');
                    badge.className = i === 0
                        ? 'absolute top-1 left-1 bg-green-700 text-white text-[10px] px-2 py-0.5 rounded'
                        : 'absolute top-1 left-1 bg-gray-700 text-white text-[10px] px-2 py-0.5 rounded';
                    badge.textContent = i === 0 ? 'Thumbnail' : 'Foto ' + (i + 1);

                    wrap.appendChild(img);
                    wrap.appendChild(badge);
                    preview.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
