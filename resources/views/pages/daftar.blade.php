@extends('layouts.app')

@section('title', 'Daftar Menjadi Anggota - NU Rancamaya')

@section('styles')
    <style>
        .field-error-input { border-color: #dc2626 !important; }
        input[type="radio"].peer:checked + div.radio-card { border-color: #2F6B2F; background: #EEF4D9; color: #183D22; }
    </style>
@endsection

@section('content')
    {{-- ============ HERO ============ --}}
    <section class="relative -mx-4 sm:-mx-5 md:-mx-6 rounded-b-[40px] md:rounded-b-[48px] overflow-hidden pt-16 pb-24 md:pt-28 md:pb-32 text-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1600&auto=format&fit=crop')">
        <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(47,107,47,.92) 0%, rgba(47,107,47,.85) 100%)"></div>
        <div class="relative max-w-3xl mx-auto px-6 text-white">
            <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase backdrop-blur-sm" style="background:rgba(255,255,255,.12); color:rgba(255,255,255,.9)">Bergabunglah dengan Komunitas</span>
            <h1 class="mt-6 text-5xl md:text-6xl font-black leading-tight tracking-tight">Daftar Menjadi Anggota</h1>
            <p class="mt-7 text-lg md:text-xl leading-relaxed font-light max-w-2xl mx-auto" style="color:rgba(255,255,255,.92)">Isi formulir di bawah untuk mendaftar sebagai calon anggota NU Rancamaya dan bagian dari keluarga besar kami</p>
        </div>
    </section>

    {{-- ============ PROSES PENDAFTARAN ============ --}}
    <section class="mt-16 md:mt-24 grid sm:grid-cols-3 gap-6 md:gap-7">
        @foreach([
            ['1', 'Isi Formulir', 'Lengkapi data diri dan unggah pas foto Anda dengan benar dan jelas.'],
            ['2', 'Verifikasi Admin', 'Pendaftaran Anda diperiksa secara detail oleh tim admin kami.'],
            ['3', 'Anggota Aktif', 'Jika disetujui, Anda resmi menjadi anggota aktif NU Rancamaya.'],
        ] as $step)
            <div class="bg-white rounded-[28px] p-7 md:p-8 shadow-lg border transition-all hover:shadow-xl hover:-translate-y-1 duration-300" style="border-color:rgba(24,61,34,.08)">
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg text-white shadow-md" style="background:#2F6B2F">{{ $step[0] }}</div>
                <h3 class="font-black text-lg mt-5" style="color:#183D22">{{ $step[1] }}</h3>
                <p class="text-base mt-3" style="color:#5D6F5D">{{ $step[2] }}</p>
            </div>
        @endforeach
    </section>

    {{-- ============ PERHATIAN ============ --}}
    <div class="mt-16 md:mt-24 rounded-[28px] border-l-4 p-8 md:p-10 bg-white shadow-lg transform hover:-translate-y-1 transition-all duration-300" style="border-color:#2F6B2F; color:#183D22">
        <div class="flex items-start gap-4">
            <span class="text-3xl shrink-0">⚠️</span>
            <div>
                <p class="font-black text-lg">Penting untuk Diketahui</p>
                <p class="text-base mt-3 leading-relaxed" style="color:#5D6F5D">
                    Setelah formulir dikirim, status Anda adalah <strong style="color:#183D22">Menunggu Persetujuan</strong>.
                    Anda belum menjadi anggota aktif sampai admin menyetujui. Simpan nomor pendaftaran untuk mengecek status.
                </p>
            </div>
        </div>
    </div>

    {{-- ============ FORM + PREVIEW ============ --}}
    <div class="mt-10 md:mt-14 flex flex-col-reverse gap-6 md:gap-8 lg:grid lg:grid-cols-[1fr_380px] lg:items-start">
        <section class="bg-white rounded-[24px] md:rounded-[32px] shadow-md p-6 md:p-8 lg:p-10 border" style="border-color:rgba(24,61,34,.08)">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-[16px] p-4 md:p-5 mb-6 text-sm">
                    <strong>❌ Terjadi kesalahan.</strong> Silakan periksa kembali kolom yang ditandai.
                </div>
            @endif

            <form id="formPendaftaran" method="POST" action="{{ route('daftar.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-5 md:gap-6">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama_lengkap" class="block font-semibold text-sm mb-2" style="color:#183D22">
                        Nama Lengkap <span class="text-red-600">*</span>
                    </label>
                    <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                           required maxlength="255" placeholder="Contoh: Ahmad Syaifuddin"
                           class="w-full border rounded-[12px] px-4 py-3 text-base transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('nama_lengkap') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                           style="color:#25351F">
                    @error('nama_lengkap')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div>
                    <label for="nomor_hp" class="block font-semibold text-sm mb-2" style="color:#183D22">
                        Nomor HP <span class="text-red-600">*</span>
                    </label>
                    <input id="nomor_hp" type="tel" name="nomor_hp" value="{{ old('nomor_hp') }}"
                           required maxlength="16" inputmode="tel" placeholder="081234567890"
                           class="w-full border rounded-[12px] px-4 py-3 text-base transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('nomor_hp') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                           style="color:#25351F">
                    <p class="text-xs mt-1.5" style="color:#7D8575">Format: 08xx atau +628xx</p>
                    @error('nomor_hp')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block font-semibold text-sm mb-2" style="color:#183D22">
                        NIK <span class="text-red-600">*</span>
                    </label>
                    <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                           required maxlength="16" inputmode="numeric" pattern="[0-9]{16}"
                           title="NIK harus 16 digit angka" placeholder="16 digit angka"
                           class="w-full border rounded-[12px] px-4 py-3 text-base transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('nik') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                           style="color:#25351F">
                    <p class="text-xs mt-1.5" style="color:#7D8575">NIK dari kartu identitas diri Anda (tidak boleh sama dengan pendaftar lain)</p>
                    @error('nik')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pas Foto --}}
                <div>
                    <label for="foto" class="block font-semibold text-sm mb-2" style="color:#183D22">
                        Pas Foto <span class="text-red-600">*</span>
                    </label>
                    <input id="foto" type="file" name="foto" required
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                           class="w-full text-sm {{ $errors->has('foto') ? 'text-red-600' : '' }}"
                           aria-describedby="fotoHint">
                    <p id="fotoHint" class="text-xs mt-1.5" style="color:#7D8575">JPG / JPEG / PNG / WebP · Max 2 MB</p>
                    <div id="fotoPreview" class="hidden mt-3">
                        <img id="fotoPreviewImg" src="#" alt="Pratinjau pas foto" class="w-24 h-24 object-cover rounded-[12px] border" style="border-color:rgba(24,61,34,.08)">
                    </div>
                    <p id="fotoError" class="hidden text-red-600 text-sm mt-1.5"></p>
                    @error('foto')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <span class="block font-semibold text-sm mb-2" style="color:#183D22">
                        Jenis Kelamin <span class="text-red-600">*</span>
                    </span>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="L" class="peer sr-only" {{ old('jenis_kelamin') === 'L' ? 'checked' : '' }} required>
                            <div class="radio-card rounded-[12px] border-2 px-4 py-3 text-center font-semibold transition border-gray-200 duration-200" style="color:#25351F">Laki-laki</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="P" class="peer sr-only" {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }}>
                            <div class="radio-card rounded-[12px] border-2 px-4 py-3 text-center font-semibold transition border-gray-200 duration-200" style="color:#25351F">Perempuan</div>
                        </label>
                    </div>
                    @error('jenis_kelamin')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Organisasi --}}
                <div>
                    <label for="organisasi" class="block font-semibold text-sm mb-2" style="color:#183D22">
                        Organisasi yang Ingin Dipilih <span class="text-red-600">*</span>
                    </label>
                    <select id="organisasi" name="organisasi" required
                            class="w-full border rounded-[12px] px-4 py-3 text-base transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('organisasi') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                            style="color:#25351F">
                        <option value="" disabled {{ old('organisasi') === null ? 'selected' : '' }}>— Pilih organisasi —</option>
                        @foreach($kategori as $slug => $info)
                            <option value="{{ $slug }}" @selected(old('organisasi') === $slug)>{{ $info['label'] }} — {{ $info['desc'] }}</option>
                        @endforeach
                    </select>
                    @error('organisasi')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label for="alamat" class="block font-semibold text-sm mb-2" style="color:#183D22">
                        Alamat Lengkap <span class="text-red-600">*</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="4" required maxlength="1000"
                              placeholder="Jl. ..., RT/RW, Kelurahan, Kecamatan, Kabupaten, Kode Pos"
                              class="w-full border rounded-[12px] px-4 py-3 text-base transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('alamat') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                              style="color:#25351F">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="md:col-span-2 flex flex-col sm:flex-row items-center gap-4 pt-2">
                    <button id="submitBtn" type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full font-bold text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed shadow-md"
                            style="background:#2F6B2F">
                        <svg id="btnSpinner" class="hidden animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span id="btnLabel">Kirim Pendaftaran</span>
                    </button>
                    <a href="{{ route('cek-status') }}" class="text-sm font-semibold underline underline-offset-4" style="color:#2F6B2F">Sudah mendaftar? Cek status</a>
                </div>
            </form>
        </section>

        {{-- Pratinjau Kartu Tanda Anggota --}}
        <aside class="bg-white rounded-[24px] md:rounded-[32px] shadow-md p-6 md:p-8 border lg:sticky lg:top-28" style="border-color:rgba(24,61,34,.08)">
            <div class="text-center">
                <p class="text-xs font-bold tracking-widest uppercase" style="color:#7D8575">Pratinjau</p>
                <h3 class="mt-2 text-lg font-extrabold" style="color:#183D22">Kartu Anggota</h3>
                <p class="mt-2 text-sm leading-relaxed" style="color:#7D8575">
                    Pratinjau ini diperbarui otomatis saat Anda mengisi formulir.
                </p>
            </div>
            <div class="flex justify-center mt-6">
                <div id="kartu-anggota-preview" data-ranting="NU Rancamaya"></div>
            </div>
            <p class="text-center text-xs mt-5" style="color:#7D8575">
                Tap kartu untuk membaliknya
            </p>
        </aside>
    </div>

    <div class="mt-12"></div>
@endsection

@push('scripts')
    @vite('resources/js/kartu-anggota.jsx')
    <script>
        (function () {
            var form = document.getElementById('formPendaftaran');
            var nikInput = document.getElementById('nik');
            var fotoInput = document.getElementById('foto');
            var fotoPreview = document.getElementById('fotoPreview');
            var fotoPreviewImg = document.getElementById('fotoPreviewImg');
            var fotoError = document.getElementById('fotoError');
            var submitBtn = document.getElementById('submitBtn');
            var btnLabel = document.getElementById('btnLabel');
            var btnSpinner = document.getElementById('btnSpinner');
            var submitting = false;

            nikInput.addEventListener('input', function () {
                nikInput.value = nikInput.value.replace(/\D/g, '').slice(0, 16);
            });

            fotoInput.addEventListener('change', function () {
                fotoError.classList.add('hidden');
                fotoPreview.classList.add('hidden');
                var file = fotoInput.files && fotoInput.files[0];
                if (!file) {
                    return;
                }
                var allowed = ['image/jpeg', 'image/png', 'image/webp'];
                if (allowed.indexOf(file.type) === -1) {
                    fotoError.textContent = 'Format foto tidak valid. Hanya JPG, JPEG, PNG, atau WebP.';
                    fotoError.classList.remove('hidden');
                    fotoInput.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    fotoError.textContent = 'Ukuran foto melebihi 2 MB.';
                    fotoError.classList.remove('hidden');
                    fotoInput.value = '';
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    fotoPreviewImg.src = e.target.result;
                    fotoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });

            form.addEventListener('submit', function (e) {
                if (submitting) {
                    e.preventDefault();
                    return;
                }
                submitting = true;
                submitBtn.disabled = true;
                btnLabel.textContent = 'Mengirim...';
                btnSpinner.classList.remove('hidden');
            });
        })();
    </script>
@endpush
