@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - NU Rancamaya')

@section('styles')
    <style>
        .field-error-input { border-color: #dc2626 !important; }
    </style>
@endsection

@section('content')
    <section class="my-12 md:my-16 text-center">
        <span class="inline-block px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase" style="background:#EEF4D9; color:#2F6B2F">Verifikasi Status</span>
        <h1 class="mt-6 text-5xl md:text-6xl font-black leading-tight" style="color:#183D22">Cek Status Pendaftaran</h1>
        <p class="mt-7 text-lg max-w-2xl mx-auto" style="color:#5D6F5D">
            Masukkan NIK dan nomor pendaftaran Anda untuk mengetahui status pendaftaran secara real-time.
        </p>
    </section>

    <section class="max-w-2xl mx-auto bg-white rounded-[32px] md:rounded-[40px] shadow-xl p-8 md:p-12 border" style="border-color:rgba(24,61,34,.08)">
        <form method="POST" action="{{ route('cek-status.check') }}" class="grid gap-6 md:gap-7">
            @csrf

            <div>
                <label for="nik" class="block font-black text-base mb-3" style="color:#183D22">NIK</label>
                <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                       inputmode="numeric" pattern="[0-9]{16}" title="NIK harus 16 digit angka" placeholder="16 digit angka"
                       class="w-full border rounded-[16px] px-5 py-3.5 text-base font-medium transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('nik') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                       style="color:#25351F">
                @error('nik')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nomor_pendaftaran" class="block font-black text-base mb-3" style="color:#183D22">Nomor Pendaftaran</label>
                <input id="nomor_pendaftaran" type="text" name="nomor_pendaftaran" value="{{ old('nomor_pendaftaran') }}"
                       required maxlength="50" placeholder="Contoh: NRC/2026/0001"
                       class="w-full border rounded-[16px] px-5 py-3.5 text-base font-medium transition focus:ring-2 focus:ring-opacity-30 {{ $errors->has('nomor_pendaftaran') ? 'field-error-input focus:ring-red-500' : 'border-gray-200 focus:ring-green-500' }}"
                       style="color:#25351F">
                @error('nomor_pendaftaran')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-4 rounded-full font-bold text-white text-lg transition-all hover:shadow-lg hover:scale-105 active:scale-95 shadow-md"
                    style="background:#2F6B2F">
                Cek Status
            </button>
        </form>

        @if($error)
            <div class="mt-8 bg-red-50 border border-red-300 text-red-700 rounded-[20px] p-6">
                <p class="font-bold">❌ Tidak Ditemukan</p>
                <p class="text-sm mt-2">{{ $error }}</p>
            </div>
        @endif

        @if($result)
            <div class="mt-10 border-t border-gray-200 pt-10">
                <div class="flex items-center justify-between gap-3 flex-wrap mb-8">
                    <h2 class="text-3xl font-black" style="color:#183D22">Hasil Pengecekan</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $result->statusBadgeClass() }}">
                        {{ $result->statusLabel() }}
                    </span>
                </div>

                <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-6 text-sm">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">Nama</dt>
                        <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->nama_lengkap }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">Nomor Pendaftaran</dt>
                        <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->nomor_pendaftaran }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">Organisasi</dt>
                        <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->organisasiLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">Tanggal Pendaftaran</dt>
                        <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->submitted_at?->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">NIK</dt>
                        <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->maskedNik() }}</dd>
                    </div>
                    @if($result->status === 'approved' && $result->approved_at)
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">Tanggal Disetujui</dt>
                            <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->approved_at->format('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                    @if($result->status === 'rejected' && $result->rejected_at)
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wide" style="color:#7D8575">Tanggal Penolakan</dt>
                            <dd class="mt-2 font-bold text-base" style="color:#183D22">{{ $result->rejected_at->format('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                </dl>

                @if($result->status === 'pending')
                    <div class="mt-8 bg-yellow-50 border border-yellow-300 text-yellow-900 rounded-[20px] p-6">
                        <p class="font-bold">⏳ Menunggu Persetujuan</p>
                        <p class="text-sm mt-2">Pendaftaran Anda masih <strong>menunggu persetujuan admin</strong>. Anda belum menjadi anggota aktif. Silakan cek kembali beberapa saat lagi.</p>
                    </div>
                @elseif($result->status === 'approved')
                    <div class="mt-8 bg-green-50 border border-green-300 text-green-900 rounded-[20px] p-6">
                        <p class="font-bold">✅ Pendaftaran Disetujui</p>
                        <p class="text-sm mt-2">Selamat! Pendaftaran Anda telah <strong>disetujui</strong> dan Anda resmi menjadi <strong>Anggota Aktif</strong> NU Rancamaya.</p>
                    </div>
                @elseif($result->status === 'rejected')
                    <div class="mt-8 bg-red-50 border border-red-300 text-red-900 rounded-[20px] p-6">
                        <p class="font-bold">❌ Pendaftaran Ditolak</p>
                        <p class="text-sm mt-2">Pendaftaran Anda <strong>ditolak</strong>, sehingga Anda tidak menjadi anggota.
                            @if($result->rejection_reason)
                                <span class="block mt-2"><strong>Alasan:</strong> {{ $result->rejection_reason }}</span>
                            @endif
                        </p>
                    </div>
                @endif

                <p class="mt-6 text-xs" style="color:#7D8575">
                    🔒 Untuk alasan keamanan, NIK hanya ditampilkan 4 digit terakhir. Data pribadi lain hanya dapat dilihat oleh admin.
                </p>
            </div>
        @endif
    </section>

    <section class="mt-12 text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-base font-bold underline underline-offset-4 transition hover:gap-3" style="color:#2F6B2F">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Beranda</span>
        </a>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('nik').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });
    </script>
@endpush
