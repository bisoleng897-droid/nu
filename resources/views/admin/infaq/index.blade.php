@extends('layouts.admin')

@section('title', 'Infaq & Kas')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="page-title">Infaq &amp; Kas</h1>
            <p class="page-sub">Foto QRIS donasi dan histori pemasukan serta pengeluaran kas infaq.</p>
        </div>
        <a href="{{ route('infaq') }}" target="_blank" rel="noopener" class="btn btn-soft">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg>
            Lihat Halaman Infaq
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error mt-5">{{ $errors->first() }}</div>
    @endif

    {{-- ======== Ringkasan kas ======== --}}
    <div class="grid sm:grid-cols-3 gap-5 mt-6">
        <div class="panel stat-card">
            <p class="stat-value" style="font-size:26px">Rp {{ number_format($totals['masuk'], 0, ',', '.') }}</p>
            <p class="stat-label">Total Infaq Masuk</p>
        </div>
        <div class="panel stat-card">
            <p class="stat-value" style="font-size:26px; color:#B91C1C">Rp {{ number_format($totals['keluar'], 0, ',', '.') }}</p>
            <p class="stat-label">Total Pengeluaran</p>
        </div>
        <div class="panel stat-card">
            <p class="stat-value" style="font-size:26px">Rp {{ number_format($totals['saldo'], 0, ',', '.') }}</p>
            <p class="stat-label">Sisa Saldo Kas</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mt-6 items-start">
        {{-- ======== Foto QRIS ======== --}}
        <div class="panel p-6">
            <h2 class="font-extrabold text-lg" style="color:#183D22">Foto QRIS</h2>
            <p class="text-sm mt-1" style="color:#7D8575">Foto ini yang tampil di halaman utama untuk donasi via QRIS.</p>

            @if($setting->hasQris())
                <div class="mt-4">
                    <img src="{{ $setting->qrisUrl() }}" alt="QRIS infaq NU Rancamaya"
                         class="w-full max-w-[260px] aspect-square object-contain rounded-2xl border" style="border-color:#EDEAE0">
                    <form method="POST" action="{{ route('admin.infaq.qris.destroy') }}" class="mt-3"
                          onsubmit="return confirm('Hapus foto QRIS dari halaman utama?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger-soft btn-sm">Hapus Foto QRIS</button>
                    </form>
                </div>
            @else
                <div class="mt-4 rounded-2xl border border-dashed p-6 text-center text-sm" style="border-color:#E5E2D6; color:#7D8575">
                    Belum ada foto QRIS. Unggah lewat form di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.infaq.qris.update') }}" enctype="multipart/form-data" class="mt-5">
                @csrf
                <div class="field {{ $errors->has('qris') ? 'has-error' : '' }}">
                    <label for="qris">Unggah / Ganti Foto QRIS <span class="text-red-600">*</span></label>
                    <input id="qris" type="file" name="qris" accept="image/jpeg,image/png,image/webp" {{ $setting->hasQris() ? '' : 'required' }}>
                    <p class="hint">Format: JPG / PNG / WebP &middot; Maksimal 4 MB</p>
                    @error('qris')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-4">Simpan Foto QRIS</button>
            </form>
        </div>

        {{-- ======== Catat transaksi ======== --}}
        <div class="panel p-6">
            <h2 class="font-extrabold text-lg" style="color:#183D22">Catat Transaksi</h2>
            <p class="text-sm mt-1" style="color:#7D8575">Pengeluaran kas akan langsung masuk ke histori infaq.</p>

            <form method="POST" action="{{ route('admin.infaq.histori.store') }}" class="mt-4 grid gap-4">
                @csrf

                <div class="field {{ $errors->has('tipe') ? 'has-error' : '' }}">
                    <label for="tipe">Jenis <span class="text-red-600">*</span></label>
                    <select id="tipe" name="tipe" required>
                        <option value="keluar" @selected(old('tipe', \App\Models\InfaqHistory::TIPE_KELUAR) === \App\Models\InfaqHistory::TIPE_KELUAR)>Pengeluaran (kas keluar)</option>
                        <option value="masuk" @selected(old('tipe') === \App\Models\InfaqHistory::TIPE_MASUK)>Pemasukan (infaq masuk)</option>
                    </select>
                    @error('tipe')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field {{ $errors->has('keterangan') ? 'has-error' : '' }}">
                    <label for="keterangan">Keterangan <span class="text-red-600">*</span></label>
                    <input id="keterangan" type="text" name="keterangan" value="{{ old('keterangan') }}" maxlength="500"
                           placeholder="Contoh: Beli sound system untuk kajian" required>
                    @error('keterangan')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="field {{ $errors->has('nominal') ? 'has-error' : '' }}">
                        <label for="nominal">Nominal (Rp) <span class="text-red-600">*</span></label>
                        <input id="nominal" type="text" name="nominal" value="{{ old('nominal') }}"
                               inputmode="numeric" placeholder="15.000.000" required>
                        <input id="nominal-hidden" type="hidden" name="nominal">
                        @error('nominal')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field {{ $errors->has('tanggal') ? 'has-error' : '' }}">
                        <label for="tanggal">Tanggal <span class="text-red-600">*</span></label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary justify-self-start">Simpan Transaksi</button>
            </form>

            <script>
                // Format nominal dengan pemisah ribuan otomatis
                const nominalInput = document.getElementById('nominal');
                const nominalHidden = document.getElementById('nominal-hidden');
                const form = nominalInput.closest('form');

                // Format saat input
                nominalInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, ''); // Hapus semua karakter non-digit
                    if (value) {
                        // Format dengan pemisah ribuan titik
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }
                    this.value = value;
                });

                // Bersihkan titik sebelum submit
                form.addEventListener('submit', function(e) {
                    const cleanedValue = nominalInput.value.replace(/\D/g, '');
                    if (!cleanedValue) {
                        e.preventDefault();
                        nominalInput.focus();
                        return false;
                    }
                    nominalHidden.value = cleanedValue;
                    nominalInput.value = cleanedValue;
                });

                // Restore format setelah focus hilang
                nominalInput.addEventListener('blur', function() {
                    if (this.value) {
                        let value = this.value.replace(/\D/g, '');
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        this.value = value;
                    }
                });
            </script>
        </div>
    </div>

    {{-- ======== Histori infaq ======== --}}
    <div class="panel table-card mt-6">
        <div class="p-6 pb-0">
            <h2 class="font-extrabold text-lg" style="color:#183D22">Histori Infaq</h2>
        </div>
        <div class="table-scroll mt-4">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
                        <th class="text-right" style="text-align:right">Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $item)
                        <tr>
                            <td class="whitespace-nowrap" style="color:#7D8575">{{ $item->tanggal->format('d M Y') }}</td>
                            <td>
                                <span class="font-semibold" style="color:#183D22">{{ $item->keterangan }}</span>
                            </td>
                            <td>
                                <span class="chip" style="{{ $item->isPengeluaran() ? 'background:#FDE8E8; color:#B91C1C' : 'background:#EEF4D9; color:#2F6B2F' }}">
                                    {{ $item->tipeLabel() }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap text-right font-semibold" style="color:{{ $item->isPengeluaran() ? '#B91C1C' : '#2F6B2F' }}">
                                {{ $item->isPengeluaran() ? '-' : '+' }} Rp {{ $item->nominalRupiah() }}
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.infaq.histori.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus transaksi ini dari histori?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10" style="color:#7D8575">Belum ada transaksi infaq yang dicatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
