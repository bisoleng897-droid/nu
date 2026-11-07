@extends('layouts.admin')

@section('title', 'Detail Calon Anggota')

@section('content')
    <a href="{{ route('admin.calon-anggota.index', ['status' => $calonAnggota->status]) }}"
       class="text-sm font-semibold" style="color:#2F6B2F">&larr; Kembali ke daftar</a>

    <div class="flex items-center justify-between flex-wrap gap-3 mt-4">
        <h1 class="page-title">Detail Pendaftaran</h1>
        <div class="flex items-center gap-3 flex-wrap">
            <span class="chip text-sm {{ $calonAnggota->statusBadgeClass() }}">
                {{ $calonAnggota->statusLabel() }}
            </span>
            @if($calonAnggota->isApproved())
                <a href="{{ route('admin.calon-anggota.download-kta', $calonAnggota) }}" class="btn btn-soft btn-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh KTA
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error mt-5">{{ session('error') }}</div>
    @endif

    <div class="grid md:grid-cols-3 gap-5 mt-6">
        {{-- ======== Foto ======== --}}
        <div class="panel p-5">
            <p class="text-xs font-bold uppercase tracking-wider" style="color:#7D8575">Pas Foto</p>
            @if($calonAnggota->foto)
                <img src="{{ asset('storage/' . $calonAnggota->foto) }}" alt="Pas foto {{ $calonAnggota->nama_lengkap }}"
                     class="mt-3 w-full aspect-square object-cover rounded-2xl border border-gray-100">
            @else
                <div class="mt-3 w-full aspect-square rounded-2xl flex items-center justify-center text-sm" style="background:#F5F4EC; color:#A8A693">Tidak ada foto</div>
            @endif
        </div>

        {{-- ======== Data pribadi ======== --}}
        <div class="md:col-span-2 panel p-6">
            <p class="text-xs font-bold uppercase tracking-wider" style="color:#7D8575">Data Pribadi</p>
            <dl class="mt-4 grid sm:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Nomor Pendaftaran</dt>
                    <dd class="mt-1 font-mono font-semibold" style="color:#183D22">{{ $calonAnggota->nomor_pendaftaran }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Nama Lengkap</dt>
                    <dd class="mt-1 font-semibold" style="color:#183D22">{{ $calonAnggota->nama_lengkap }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">NIK</dt>
                    <dd class="mt-1 font-mono font-semibold" style="color:#183D22">{{ $calonAnggota->nik }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Nomor HP</dt>
                    <dd class="mt-1 font-semibold" style="color:#183D22">{{ $calonAnggota->nomor_hp }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Jenis Kelamin</dt>
                    <dd class="mt-1 font-semibold" style="color:#183D22">{{ $calonAnggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Organisasi</dt>
                    <dd class="mt-1 font-semibold" style="color:#183D22">{{ $calonAnggota->organisasiLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Tanggal Pendaftaran</dt>
                    <dd class="mt-1 font-semibold" style="color:#183D22">{{ $calonAnggota->submitted_at?->format('d M Y, H:i') }}</dd>
                </div>
            </dl>

            <div class="mt-6">
                <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Alamat Lengkap</dt>
                <dd class="mt-1 text-sm leading-relaxed whitespace-pre-line" style="color:#25351F">{{ $calonAnggota->alamat }}</dd>
            </div>
        </div>
    </div>

    {{-- ======== Riwayat proses ======== --}}
    <div class="panel p-6 mt-5">
        <p class="text-xs font-bold uppercase tracking-wider" style="color:#7D8575">Riwayat Proses</p>
        <dl class="mt-4 grid sm:grid-cols-3 gap-x-8 gap-y-5">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Dikirim</dt>
                <dd class="mt-1 font-semibold" style="color:#183D22">{{ $calonAnggota->submitted_at?->format('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Disetujui</dt>
                <dd class="mt-1 font-semibold" style="color:#183D22">
                    @if($calonAnggota->approved_at)
                        {{ $calonAnggota->approved_at->format('d M Y, H:i') }}
                        @if($calonAnggota->approver)
                            <span class="block text-xs font-normal" style="color:#7D8575">oleh {{ $calonAnggota->approver->name }}</span>
                        @endif
                    @else
                        <span style="color:#D8D4C2">—</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Ditolak</dt>
                <dd class="mt-1 font-semibold" style="color:#183D22">
                    @if($calonAnggota->rejected_at)
                        {{ $calonAnggota->rejected_at->format('d M Y, H:i') }}
                        @if($calonAnggota->rejection_reason)
                            <span class="block mt-1 text-xs font-normal" style="color:#7D8575">Alasan: {{ $calonAnggota->rejection_reason }}</span>
                        @endif
                    @else
                        <span style="color:#D8D4C2">—</span>
                    @endif
                </dd>
            </div>
        </dl>

        @if($calonAnggota->isInactive())
            <dl class="mt-5 grid sm:grid-cols-2 gap-x-8 gap-y-5 pt-5 border-t" style="border-color:#E5E2D6">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Dinonaktifkan</dt>
                    <dd class="mt-1 font-semibold" style="color:#183D22">
                        {{ $calonAnggota->inactivated_at?->format('d M Y, H:i') }}
                        @if($calonAnggota->inactivator)
                            <span class="block text-xs font-normal" style="color:#7D8575">oleh {{ $calonAnggota->inactivator->name }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide" style="color:#A8A693">Alasan Nonaktif</dt>
                    <dd class="mt-1 text-sm" style="color:#25351F; line-height: 1.5;">{{ $calonAnggota->inactive_reason }}</dd>
                </div>
            </dl>
        @endif
    </div>

    {{-- ======== Aksi (hanya untuk yang masih pending) ======== --}}
    @if($calonAnggota->isPending())
        <div id="aksi" class="rounded-3xl p-6 md:p-8 mt-5 scroll-mt-6" style="background:#EEF4D9">
            <h2 class="font-extrabold text-lg" style="color:#183D22">Proses Pendaftaran</h2>
            <p class="text-sm mt-1" style="color:#7D8575">
                Periksa data dengan teliti sebelum memutuskan. Tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="grid sm:grid-cols-2 gap-5 mt-5">
                {{-- Setujui --}}
                <form method="POST" action="{{ route('admin.calon-anggota.approve', $calonAnggota) }}"
                      onsubmit="return confirm('Setujui pendaftaran {{ addslashes($calonAnggota->nama_lengkap) }}?\n\nStatus akan berubah menjadi Disetujui / Anggota Aktif dan pendaftar resmi menjadi anggota.')">
                    @csrf
                    <button type="submit" class="btn btn-primary w-full py-3.5">
                        Setujui &rarr; Anggota Aktif
                    </button>
                </form>

                {{-- Tolak --}}
                <form method="POST" action="{{ route('admin.calon-anggota.reject', $calonAnggota) }}"
                      onsubmit="return confirm('Tolak pendaftaran {{ addslashes($calonAnggota->nama_lengkap) }}?\n\nPendaftar tidak akan menjadi anggota dan akan menerima alasan penolakan.')">
                    @csrf
                    <div class="field {{ $errors->has('rejection_reason') ? 'has-error' : '' }}">
                        <label for="rejection_reason">Alasan Penolakan <span class="text-red-600">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required maxlength="1000"
                                  placeholder="Contoh: Pas foto tidak sesuai ketentuan.">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger w-full py-3.5 mt-3">
                        Tolak Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    @elseif($calonAnggota->isApproved())
        <div id="aksi" class="rounded-3xl p-6 md:p-8 mt-5 scroll-mt-6" style="background:#F0F4E9">
            <h2 class="font-extrabold text-lg" style="color:#183D22">Kelola Status Keanggotaan</h2>
            <p class="text-sm mt-1" style="color:#7D8575">
                Anggota ini sudah aktif. Anda dapat menonaktifkan jika anggota sudah tidak lagi menjadi bagian dari organisasi.
            </p>

            <form method="POST" action="{{ route('admin.calon-anggota.inactivate', $calonAnggota) }}" class="mt-5">
                @csrf
                <div class="field {{ $errors->has('inactive_reason') ? 'has-error' : '' }}">
                    <label for="inactive_reason">Alasan Nonaktif <span class="text-red-600">*</span></label>
                    <select id="inactive_reason" name="inactive_reason" required>
                        <option value="">Pilih alasan...</option>
                        <option value="mengundurkan_diri">Mengundurkan diri</option>
                        <option value="tidak_aktif">Sudah tidak aktif dalam organisasi</option>
                        <option value="masa_berakhir">Masa keanggotaan berakhir</option>
                        <option value="lainnya">Alasan lainnya</option>
                    </select>
                    @error('inactive_reason')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field {{ $errors->has('inactive_reason_custom') ? 'has-error' : '' }}">
                    <label for="inactive_reason_custom">Keterangan Tambahan (opsional)</label>
                    <textarea id="inactive_reason_custom" name="inactive_reason_custom" rows="3" maxlength="500"
                              placeholder="Masukkan detail atau keterangan tambahan jika diperlukan.">{{ old('inactive_reason_custom') }}</textarea>
                    @error('inactive_reason_custom')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger w-full py-3.5 mt-4"
                        onclick="return confirm('Nonaktifkan anggota {{ addslashes($calonAnggota->nama_lengkap) }}?\n\nAnggota akan dihapus dari struktur organisasi tetapi data tetap tersimpan.')">
                    Nonaktifkan Anggota
                </button>
            </form>
        </div>
    @else
        <div class="panel p-6 mt-5">
            <p class="text-sm" style="color:#7D8575">
                Pendaftaran ini sudah diproses
                @if($calonAnggota->status === 'approved')
                    pada {{ $calonAnggota->approved_at?->format('d M Y, H:i') }}.
                @elseif($calonAnggota->status === 'rejected')
                    pada {{ $calonAnggota->rejected_at?->format('d M Y, H:i') }}.
                @elseif($calonAnggota->status === 'inactive')
                    pada {{ $calonAnggota->inactivated_at?->format('d M Y, H:i') }}.
                @endif
                Status tidak dapat diubah lagi.
            </p>
        </div>
    @endif
@endsection
