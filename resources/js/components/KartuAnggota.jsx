import { useEffect, useState } from 'react';
import QRCode from 'qrcode';
import FlipCard from './FlipCard';

const FIELD_LABEL = {
    fontFamily: "'Inter', sans-serif",
    fontSize: 9,
    fontWeight: 700,
    letterSpacing: 1.6,
    textTransform: 'uppercase',
    color: '#7D8575',
    margin: 0,
};

const FIELD_VALUE = {
    fontFamily: "'Inter', sans-serif",
    fontSize: 12.5,
    fontWeight: 600,
    color: '#25351F',
    margin: '3px 0 0',
    lineHeight: 1.45,
    wordBreak: 'break-word',
};

function CorakDaun({ size, style = {} }) {
    return (
        <svg
            width={size}
            height={size}
            viewBox="0 0 24 24"
            fill="none"
            aria-hidden="true"
            style={{ position: 'absolute', pointerEvents: 'none', ...style }}
        >
            <path d="M4 20C4 10 10 4 20 4c0 10-6 16-16 16z" fill="#ffffff" />
            <path
                d="M4 20C8.5 15.5 13 11 20 4"
                stroke="#3E4C1F"
                strokeWidth="0.9"
                strokeLinecap="round"
                opacity="0.35"
            />
        </svg>
    );
}

function Foto({ src, nama }) {
    if (src) {
        return (
            <img
                src={src}
                alt={`Pas foto ${nama || 'anggota'}`}
                style={{
                    width: 84,
                    height: 84,
                    borderRadius: 18,
                    objectFit: 'cover',
                    border: '2px solid #E7E4D8',
                    backgroundColor: '#F5F4EC',
                    flexShrink: 0,
                }}
            />
        );
    }

    return (
        <div
            aria-hidden="true"
            style={{
                width: 84,
                height: 84,
                borderRadius: 18,
                border: '2px dashed #D8D4C2',
                backgroundColor: '#F5F4EC',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0,
            }}
        >
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#A8A693" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </div>
    );
}

function Field({ label, children, style = {} }) {
    return (
        <div style={style}>
            <p style={FIELD_LABEL}>{label}</p>
            <p style={FIELD_VALUE}>{children}</p>
        </div>
    );
}

function KepalaKartu({ eyebrow, judul, sub }) {
    return (
        <div
            style={{
                position: 'relative',
                padding: '20px 22px 18px',
                background: 'linear-gradient(135deg, #5A6E30 0%, #4A5A24 55%, #3C4918 100%)',
                color: '#F5F4EC',
                overflow: 'hidden',
                flexShrink: 0,
            }}
        >
            <CorakDaun size={64} style={{ top: -14, left: -12, opacity: 0.13, transform: 'rotate(-18deg)' }} />
            <CorakDaun size={48} style={{ bottom: -10, right: -8, opacity: 0.11, transform: 'rotate(165deg)' }} />
            <CorakDaun size={26} style={{ top: 14, right: 40, opacity: 0.08, transform: 'rotate(40deg)' }} />

            <p
                style={{
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    fontSize: 9.5,
                    fontWeight: 700,
                    letterSpacing: 3.2,
                    margin: 0,
                    opacity: 0.85,
                }}
            >
                {eyebrow}
            </p>
            <h3
                style={{
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    fontSize: 21,
                    fontWeight: 800,
                    letterSpacing: 0.4,
                    margin: '6px 0 0',
                    lineHeight: 1.2,
                }}
            >
                {judul}
            </h3>
            <p
                style={{
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    fontSize: 9,
                    fontWeight: 700,
                    letterSpacing: 2.6,
                    margin: '7px 0 0',
                    opacity: 0.8,
                }}
            >
                {sub}
            </p>
        </div>
    );
}

function QrCode({ nik, nama }) {
    const [qrUrl, setQrUrl] = useState(null);

    useEffect(() => {
        const payload = nik ? `NU-RANCAMAYA|NIK:${nik}|${nama}` : 'NU-RANCAMAYA|KARTU-TANDA-ANGGOTA';
        const timer = setTimeout(() => {
            QRCode.toDataURL(payload, {
                width: 240,
                margin: 0,
                errorCorrectionLevel: 'M',
                color: { dark: '#1E3A1E', light: '#FFFFFF' },
            })
                .then(setQrUrl)
                .catch(() => {});
        }, 250);

        return () => clearTimeout(timer);
    }, [nik, nama]);

    return (
        <div style={{ textAlign: 'center', flexShrink: 0 }}>
            <div
                style={{
                    width: 104,
                    height: 104,
                    borderRadius: 14,
                    border: '1px solid #E7E4D8',
                    backgroundColor: '#FFFFFF',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    overflow: 'hidden',
                }}
            >
                {qrUrl ? (
                    <img src={qrUrl} alt="Kode QR kartu anggota" width="92" height="92" style={{ display: 'block' }} />
                ) : (
                    <span style={{ fontSize: 9, color: '#A8A693', fontFamily: "'Inter', sans-serif" }}>Muat…</span>
                )}
            </div>
            <p
                style={{
                    fontFamily: "'Inter', sans-serif",
                    fontSize: 8.5,
                    fontWeight: 600,
                    color: '#7D8575',
                    margin: '6px 0 0',
                    letterSpacing: 0.3,
                }}
            >
                Pindai untuk verifikasi
            </p>
        </div>
    );
}

export default function KartuAnggota({ ranting = 'NU Rancamaya', initial = {} }) {
    const [data, setData] = useState({
        nama: initial.nama || '',
        nik: initial.nik || '',
        alamat: initial.alamat || '',
        hp: initial.hp || '',
        organisasi: initial.organisasi || '',
        foto: initial.foto || null,
    });

    // Perbarui kartu secara langsung saat formulir diisi.
    useEffect(() => {
        const cleanups = [];

        const bind = (id, key) => {
            const el = document.getElementById(id);
            if (!el) {
                return;
            }
            const update = () => setData(d => ({ ...d, [key]: el.value }));
            el.addEventListener('input', update);
            el.addEventListener('change', update);
            cleanups.push(() => {
                el.removeEventListener('input', update);
                el.removeEventListener('change', update);
            });
        };

        bind('nama_lengkap', 'nama');
        bind('nik', 'nik');
        bind('alamat', 'alamat');
        bind('nomor_hp', 'hp');

        const organisasi = document.getElementById('organisasi');
        if (organisasi) {
            const updateOrganisasi = () => {
                const teks = organisasi.options[organisasi.selectedIndex]?.text || '';
                setData(d => ({ ...d, organisasi: teks.split(' — ')[0] }));
            };
            organisasi.addEventListener('change', updateOrganisasi);
            cleanups.push(() => organisasi.removeEventListener('change', updateOrganisasi));
        }

        const foto = document.getElementById('foto');
        if (foto) {
            const updateFoto = () => {
                const file = foto.files && foto.files[0];
                if (!file) {
                    setData(d => ({ ...d, foto: null }));
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => setData(d => ({ ...d, foto: e.target.result }));
                reader.readAsDataURL(file);
            };
            foto.addEventListener('change', updateFoto);
            cleanups.push(() => foto.removeEventListener('change', updateFoto));
        }

        return () => cleanups.forEach(fn => fn());
    }, []);

    const kosong = '—';

    const depan = (
        <div
            style={{
                width: '100%',
                height: '100%',
                display: 'flex',
                flexDirection: 'column',
                background: '#FFFFFF',
                fontFamily: "'Inter', sans-serif",
            }}
        >
            <KepalaKartu eyebrow="NAHDLATUL ULAMA" judul={ranting} sub="KARTU TANDA ANGGOTA" />

            <div style={{ flex: 1, padding: '16px 20px 18px', display: 'flex', flexDirection: 'column' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                    <Foto src={data.foto} nama={data.nama} />
                    <div style={{ minWidth: 0, flex: 1 }}>
                        <p
                            style={{
                                fontFamily: "'Plus Jakarta Sans', sans-serif",
                                fontSize: 16.5,
                                fontWeight: 800,
                                color: '#183D22',
                                margin: 0,
                                lineHeight: 1.3,
                                display: '-webkit-box',
                                WebkitLineClamp: 2,
                                WebkitBoxOrient: 'vertical',
                                overflow: 'hidden',
                            }}
                        >
                            {data.nama || kosong}
                        </p>
                        {data.organisasi && (
                            <span
                                style={{
                                    display: 'inline-block',
                                    marginTop: 7,
                                    fontSize: 9.5,
                                    fontWeight: 700,
                                    letterSpacing: 1.2,
                                    textTransform: 'uppercase',
                                    color: '#2F6B2F',
                                    backgroundColor: '#EEF4D9',
                                    borderRadius: 999,
                                    padding: '4px 10px',
                                }}
                            >
                                {data.organisasi}
                            </span>
                        )}
                    </div>
                </div>

                <div style={{ height: 1, backgroundColor: '#EDEAE0', margin: '14px 0' }} />

                <div style={{ display: 'grid', gap: 10 }}>
                    <Field label="NIK">
                        <span style={{ fontVariantNumeric: 'tabular-nums', letterSpacing: 1.2 }}>
                            {data.nik || kosong}
                        </span>
                    </Field>
                    <Field label="Alamat">
                        <span
                            style={{
                                display: '-webkit-box',
                                WebkitLineClamp: 2,
                                WebkitBoxOrient: 'vertical',
                                overflow: 'hidden',
                            }}
                        >
                            {data.alamat || kosong}
                        </span>
                    </Field>
                    <Field label="Nomor HP">{data.hp || kosong}</Field>
                </div>

                <div style={{ flex: 1 }} />

                <div
                    style={{
                        display: 'flex',
                        alignItems: 'flex-end',
                        justifyContent: 'space-between',
                        gap: 12,
                        marginTop: 12,
                    }}
                >
                    <div style={{ minWidth: 0 }}>
                        <p style={FIELD_LABEL}>Status</p>
                        <span
                            style={{
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: 6,
                                marginTop: 5,
                                fontSize: 10,
                                fontWeight: 700,
                                letterSpacing: 1,
                                textTransform: 'uppercase',
                                color: '#8A6D1D',
                                backgroundColor: '#FBF3D9',
                                borderRadius: 999,
                                padding: '5px 11px',
                            }}
                        >
                            <span
                                aria-hidden="true"
                                style={{ width: 6, height: 6, borderRadius: 999, backgroundColor: '#C9A227' }}
                            />
                            Calon Anggota
                        </span>
                    </div>
                    <QrCode nik={data.nik} nama={data.nama} />
                </div>
            </div>
        </div>
    );

    const belakang = (
        <div
            style={{
                width: '100%',
                height: '100%',
                display: 'flex',
                flexDirection: 'column',
                background: '#FFFFFF',
                fontFamily: "'Inter', sans-serif",
            }}
        >
            <KepalaKartu eyebrow="PENGAJUAN" judul="Kartu Tanda Anggota" sub={ranting} />

            <div style={{ flex: 1, padding: '18px 22px', display: 'flex', flexDirection: 'column' }}>
                <div style={{ display: 'grid', gap: 12 }}>
                    <Field label="Nama Lengkap">{data.nama || kosong}</Field>
                    <Field label="NIK">
                        <span style={{ fontVariantNumeric: 'tabular-nums', letterSpacing: 1.2 }}>
                            {data.nik || kosong}
                        </span>
                    </Field>
                    <Field label="Nomor HP">{data.hp || kosong}</Field>
                    <Field label="Organisasi">{data.organisasi || kosong}</Field>
                    <Field label="Alamat Lengkap">{data.alamat || kosong}</Field>
                </div>

                <div style={{ flex: 1 }} />

                <div
                    style={{
                        backgroundColor: '#FBF3D9',
                        border: '1px solid #EFE3BC',
                        borderRadius: 14,
                        padding: '12px 14px',
                    }}
                >
                    <p
                        style={{
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            fontSize: 10.5,
                            fontWeight: 800,
                            letterSpacing: 1.4,
                            textTransform: 'uppercase',
                            color: '#8A6D1D',
                            margin: 0,
                        }}
                    >
                        Menunggu Persetujuan
                    </p>
                    <p
                        style={{
                            fontSize: 11,
                            lineHeight: 1.55,
                            color: '#5C5A4A',
                            margin: '6px 0 0',
                        }}
                    >
                        Pengajuan kartu tanda anggota akan diproses setelah admin menyetujui
                        pendaftaran Anda.
                    </p>
                </div>
            </div>
        </div>
    );

    return (
        <FlipCard
            front={depan}
            back={belakang}
            axis="y"
            flipOnClick
            draggable
            dragDistance={0}
            tilt
            tiltMax={10}
            glare={false}
            hoverScale={1.02}
            perspective={1100}
            stiffness={170}
            damping={22}
            width={340}
            height={540}
            radius={24}
            background="#ffffff"
            color="#25351F"
            shadow
            shadowColor="#183D22"
            shadowOpacity={0.35}
            ariaLabel="Kartu tanda anggota — ketuk atau seret untuk membalik"
        />
    );
}
