import { createRoot } from 'react-dom/client';
import KartuAnggota from './components/KartuAnggota';

const container = document.getElementById('kartu-anggota-preview');

if (container) {
    const bacaNilai = id => {
        const el = document.getElementById(id);
        return el ? el.value : '';
    };

    const dataAwal = {
        nama: bacaNilai('nama_lengkap'),
        nik: bacaNilai('nik'),
        alamat: bacaNilai('alamat'),
        hp: bacaNilai('nomor_hp'),
        organisasi: '',
        foto: null,
    };

    const organisasi = document.getElementById('organisasi');
    if (organisasi && organisasi.selectedIndex > 0) {
        dataAwal.organisasi = organisasi.options[organisasi.selectedIndex].text.split(' — ')[0];
    }

    createRoot(container).render(
        <KartuAnggota ranting={container.dataset.ranting || 'NU Rancamaya'} initial={dataAwal} />
    );
}
