<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Tanda Anggota - {{ $calonAnggota->nama_lengkap }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .page {
            width: 340px;
            height: 540px;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            page-break-after: always;
            margin: 0 auto;
            page-break-inside: avoid;
        }

        /* ===== DEPAN ===== */
        .kta-header {
            position: relative;
            padding: 20px 22px 18px;
            background: linear-gradient(135deg, #5A6E30 0%, #4A5A24 55%, #3C4918 100%);
            color: #F5F4EC;
            overflow: hidden;
            flex-shrink: 0;
        }

        .kta-header-eyebrow {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 3.2px;
            margin: 0;
            opacity: 0.85;
            position: relative;
            z-index: 1;
        }

        .kta-header-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: 0.4px;
            margin: 6px 0 0;
            line-height: 1.2;
            position: relative;
            z-index: 1;
        }

        .kta-header-subtitle {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2.6px;
            margin: 7px 0 0;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }

        .kta-body {
            flex: 1;
            padding: 16px 20px 18px;
            display: flex;
            flex-direction: column;
        }

        .kta-member {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .kta-photo {
            width: 84px;
            height: 84px;
            border-radius: 18px;
            object-fit: cover;
            border: 2px solid #E7E4D8;
            background-color: #F5F4EC;
            flex-shrink: 0;
        }

        .kta-photo-placeholder {
            width: 84px;
            height: 84px;
            border-radius: 18px;
            border: 2px dashed #D8D4C2;
            background-color: #F5F4EC;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 40px;
        }

        .kta-member-info {
            min-width: 0;
            flex: 1;
        }

        .kta-member-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16.5px;
            font-weight: 800;
            color: #183D22;
            margin: 0;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .kta-organisasi-badge {
            display: inline-block;
            margin-top: 7px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #2F6B2F;
            background-color: #EEF4D9;
            border-radius: 999px;
            padding: 4px 10px;
        }

        .kta-divider {
            height: 1px;
            background-color: #EDEAE0;
            margin: 14px 0;
        }

        .kta-fields {
            display: grid;
            gap: 10px;
        }

        .kta-field {
            display: block;
        }

        .kta-field-label {
            font-family: 'Inter', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: #7D8575;
            margin: 0;
        }

        .kta-field-value {
            font-family: 'Inter', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: #25351F;
            margin: 3px 0 0;
            line-height: 1.45;
            word-break: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .kta-field-value.numeric {
            font-variant-numeric: tabular-nums;
            letter-spacing: 1.2px;
        }

        .kta-footer {
            flex: 1;
        }

        .kta-status-qr {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-top: 12px;
        }

        .kta-status {
            min-width: 0;
        }

        .kta-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 5px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #8A6D1D;
            background-color: #FBF3D9;
            border-radius: 999px;
            padding: 5px 11px;
        }

        .kta-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background-color: #C9A227;
            flex-shrink: 0;
        }

        .kta-qrcode {
            text-align: center;
            flex-shrink: 0;
        }

        .kta-qr-container {
            width: 104px;
            height: 104px;
            border-radius: 14px;
            border: 1px solid #E7E4D8;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .kta-qr-container img {
            width: 92px;
            height: 92px;
            display: block;
        }

        .kta-qr-label {
            font-family: 'Inter', sans-serif;
            font-size: 8.5px;
            font-weight: 600;
            color: #7D8575;
            margin: 6px 0 0;
            letter-spacing: 0.3px;
        }

        /* ===== BELAKANG ===== */
        .kta-back .kta-body {
            padding: 18px 22px;
        }

        .kta-back .kta-fields {
            gap: 12px;
        }

        .kta-warning-box {
            background-color: #FBF3D9;
            border: 1px solid #EFE3BC;
            border-radius: 14px;
            padding: 12px 14px;
        }

        .kta-warning-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #8A6D1D;
            margin: 0;
        }

        .kta-warning-text {
            font-size: 11px;
            line-height: 1.55;
            color: #5C5A4A;
            margin: 6px 0 0;
        }

        .corak-daun {
            position: absolute;
            pointer-events: none;
        }
    </style>
</head>
<body>

{{-- ===== HALAMAN 1: KTA DEPAN ===== --}}
<div class="page">
    {{-- Header dengan dekorasi daun --}}
    <div class="kta-header">
        {{-- Daun kiri atas --}}
        <svg class="corak-daun" style="top: -14px; left: -12px; opacity: 0.13; transform: rotate(-18deg);" width="64" height="64" viewBox="0 0 24 24" fill="none">
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path d="M4 20C8.5 15.5 13 11 20 4" stroke="#3E4C1F" stroke-width="0.9" stroke-linecap="round" opacity="0.35" />
        </svg>

        {{-- Daun kanan bawah --}}
        <svg class="corak-daun" style="bottom: -10px; right: -8px; opacity: 0.11; transform: rotate(165deg);" width="48" height="48" viewBox="0 0 24 24" fill="none">
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path d="M4 20C8.5 15.5 13 11 20 4" stroke="#3E4C1F" stroke-width="0.9" stroke-linecap="round" opacity="0.35" />
        </svg>

        {{-- Daun kecil di tengah atas --}}
        <svg class="corak-daun" style="top: 14px; right: 40px; opacity: 0.08; transform: rotate(40deg);" width="26" height="26" viewBox="0 0 24 24" fill="none">
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path d="M4 20C8.5 15.5 13 11 20 4" stroke="#3E4C1F" stroke-width="0.9" stroke-linecap="round" opacity="0.35" />
        </svg>

        {{-- Teks header --}}
        <p class="kta-header-eyebrow">NAHDLATUL ULAMA</p>
        <h3 class="kta-header-title">NU Rancamaya</h3>
        <p class="kta-header-subtitle">KARTU TANDA ANGGOTA</p>
    </div>

    {{-- Body depan --}}
    <div class="kta-body">
        {{-- Foto + nama --}}
        <div class="kta-member">
            @if($foto)
                <img src="{{ $foto }}" alt="Foto anggota" class="kta-photo">
            @else
                <div class="kta-photo-placeholder">👤</div>
            @endif
            <div class="kta-member-info">
                <p class="kta-member-name">{{ $calonAnggota->nama_lengkap }}</p>
                @if($calonAnggota->organisasiLabel())
                    <span class="kta-organisasi-badge">{{ $calonAnggota->organisasiLabel() }}</span>
                @endif
            </div>
        </div>

        {{-- Divider --}}
        <div class="kta-divider"></div>

        {{-- Fields: NIK, Alamat, HP --}}
        <div class="kta-fields">
            <div class="kta-field">
                <p class="kta-field-label">NIK</p>
                <p class="kta-field-value numeric">{{ $calonAnggota->nik ?: '—' }}</p>
            </div>
            <div class="kta-field">
                <p class="kta-field-label">Alamat</p>
                <p class="kta-field-value">{{ $calonAnggota->alamat ?: '—' }}</p>
            </div>
            <div class="kta-field">
                <p class="kta-field-label">Nomor HP</p>
                <p class="kta-field-value">{{ $calonAnggota->nomor_hp ?: '—' }}</p>
            </div>
        </div>

        {{-- Spacer --}}
        <div class="kta-footer"></div>

        {{-- Status + QR --}}
        <div class="kta-status-qr">
            <div class="kta-status">
                <p class="kta-field-label">Status</p>
                <span class="kta-status-badge">
                    <span class="kta-status-dot"></span>
                    Anggota Aktif
                </span>
            </div>
            <div class="kta-qrcode">
                <div class="kta-qr-container">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="Kode QR">
                </div>
                <p class="kta-qr-label">Pindai untuk verifikasi</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== HALAMAN 2: KTA BELAKANG ===== --}}
<div class="page kta-back">
    {{-- Header dengan dekorasi daun --}}
    <div class="kta-header">
        {{-- Daun kiri atas --}}
        <svg class="corak-daun" style="top: -14px; left: -12px; opacity: 0.13; transform: rotate(-18deg);" width="64" height="64" viewBox="0 0 24 24" fill="none">
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path d="M4 20C8.5 15.5 13 11 20 4" stroke="#3E4C1F" stroke-width="0.9" stroke-linecap="round" opacity="0.35" />
        </svg>

        {{-- Daun kanan bawah --}}
        <svg class="corak-daun" style="bottom: -10px; right: -8px; opacity: 0.11; transform: rotate(165deg);" width="48" height="48" viewBox="0 0 24 24" fill="none">
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path d="M4 20C8.5 15.5 13 11 20 4" stroke="#3E4C1F" stroke-width="0.9" stroke-linecap="round" opacity="0.35" />
        </svg>

        {{-- Daun kecil di tengah atas --}}
        <svg class="corak-daun" style="top: 14px; right: 40px; opacity: 0.08; transform: rotate(40deg);" width="26" height="26" viewBox="0 0 24 24" fill="none">
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path d="M4 20C8.5 15.5 13 11 20 4" stroke="#3E4C1F" stroke-width="0.9" stroke-linecap="round" opacity="0.35" />
        </svg>

        {{-- Teks header --}}
        <p class="kta-header-eyebrow">PENGAJUAN</p>
        <h3 class="kta-header-title">Kartu Tanda Anggota</h3>
        <p class="kta-header-subtitle">NU Rancamaya</p>
    </div>

    {{-- Body belakang --}}
    <div class="kta-body">
        {{-- Fields lengkap --}}
        <div class="kta-fields">
            <div class="kta-field">
                <p class="kta-field-label">Nama Lengkap</p>
                <p class="kta-field-value">{{ $calonAnggota->nama_lengkap ?: '—' }}</p>
            </div>
            <div class="kta-field">
                <p class="kta-field-label">NIK</p>
                <p class="kta-field-value numeric">{{ $calonAnggota->nik ?: '—' }}</p>
            </div>
            <div class="kta-field">
                <p class="kta-field-label">Nomor HP</p>
                <p class="kta-field-value">{{ $calonAnggota->nomor_hp ?: '—' }}</p>
            </div>
            <div class="kta-field">
                <p class="kta-field-label">Organisasi</p>
                <p class="kta-field-value">{{ $calonAnggota->organisasiLabel() ?: '—' }}</p>
            </div>
            <div class="kta-field">
                <p class="kta-field-label">Alamat Lengkap</p>
                <p class="kta-field-value">{{ $calonAnggota->alamat ?: '—' }}</p>
            </div>
        </div>

        {{-- Spacer --}}
        <div style="flex: 1;"></div>

        {{-- Warning box --}}
        <div class="kta-warning-box">
            <p class="kta-warning-title">Anggota Aktif</p>
            <p class="kta-warning-text">
                Kartu tanda anggota ini berlaku selama anggota masih aktif di organisasi NU Rancamaya.
            </p>
        </div>
    </div>
</div>

</body>
</html>
