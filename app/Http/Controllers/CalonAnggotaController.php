<?php

namespace App\Http\Controllers;

use App\Models\CalonAnggota;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalonAnggotaController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran anggota untuk publik.
     */
    public function create(): View
    {
        return view('pages.daftar', [
            'kategori' => StrukturController::KATEGORI,
        ]);
    }

    /**
     * Simpan pendaftaran baru dengan status pending.
     *
     * Pendaftar TIDAK dibuatkan akun pengguna dan TIDAK mendapatkan
     * akses anggota sebelum admin menyetujui pendaftarannya.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'nik' => preg_replace('/\D/', '', (string) $request->input('nik')),
            'nomor_hp' => $this->normalizePhoneNumber((string) $request->input('nomor_hp')),
        ]);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:1000'],
            'nomor_hp' => ['required', 'string', 'max:16', 'regex:/^\+628\d{7,11}$/'],
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/', 'unique:calon_anggotas,nik'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'organisasi' => ['required', 'in:'.implode(',', array_keys(StrukturController::KATEGORI))],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Format nomor HP tidak valid. Gunakan format seperti 081234567890 atau +6281234567890.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus terdiri dari 16 digit.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
            'nik.unique' => 'NIK sudah terdaftar pada pendaftaran lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'organisasi.required' => 'Organisasi wajib dipilih.',
            'organisasi.in' => 'Organisasi tidak valid.',
            'foto.required' => 'Pas foto wajib diunggah.',
            'foto.image' => 'Pas foto harus berupa gambar.',
            'foto.mimes' => 'Pas foto hanya boleh berformat JPG, JPEG, PNG, atau WebP.',
            'foto.max' => 'Ukuran pas foto maksimal 2 MB.',
        ]);

        $validated['status'] = CalonAnggota::STATUS_PENDING;
        $validated['submitted_at'] = now();
        $validated['foto'] = $request->file('foto')->store('foto-calon-anggota', 'public');

        // Pendaftaran IPNU & IPPNU disesuaikan otomatis dengan jenis kelamin:
        // laki-laki menjadi IPNU, perempuan menjadi IPPNU.
        if ($validated['organisasi'] === 'ipnu-ippnu') {
            $validated['organisasi'] = $validated['jenis_kelamin'] === 'P' ? 'ippnu' : 'ipnu';
        }

        $pendaftar = CalonAnggota::create($validated);
        $pendaftar->update([
            'nomor_pendaftaran' => $this->generateNomorPendaftaran($pendaftar->id),
        ]);

        return redirect()->route('daftar.success')->with([
            'nama_lengkap' => $pendaftar->nama_lengkap,
            'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
        ]);
    }

    /**
     * Halaman konfirmasi setelah formulir dikirim.
     *
     * Hanya dapat diakses melalui redirect setelah submit (data ada di session).
     */
    public function success(): View|RedirectResponse
    {
        if (session('nomor_pendaftaran') === null) {
            return redirect()->route('home');
        }

        return view('pages.daftar-success');
    }

    /**
     * Formulir dan hasil pengecekan status pendaftaran.
     *
     * Pencarian memerlukan NIK DAN nomor pendaftaran agar data pendaftar
     * lain tidak dapat diambil. NIK tidak pernah ditampilkan secara penuh.
     */
    public function cekStatus(Request $request): View
    {
        $result = null;
        $error = null;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
                'nomor_pendaftaran' => ['required', 'string', 'max:50'],
            ], [
                'nik.required' => 'NIK wajib diisi.',
                'nik.size' => 'NIK harus terdiri dari 16 digit.',
                'nik.regex' => 'NIK hanya boleh berisi angka.',
                'nomor_pendaftaran.required' => 'Nomor pendaftaran wajib diisi.',
            ]);

            $result = CalonAnggota::where('nik', $validated['nik'])
                ->where('nomor_pendaftaran', $validated['nomor_pendaftaran'])
                ->first();

            if ($result === null) {
                $error = 'Data pendaftaran tidak ditemukan. Periksa kembali NIK dan nomor pendaftaran Anda.';
            }
        }

        return view('pages.cek-status', compact('result', 'error'));
    }

    /**
     * Normalisasi nomor HP ke format +62xxxxxxxxxx.
     */
    private function normalizePhoneNumber(string $value): string
    {
        $normalized = preg_replace('/[^0-9+]/', '', $value);

        if (str_starts_with($normalized, '0')) {
            return '+62'.substr($normalized, 1);
        }

        if (str_starts_with($normalized, '62')) {
            return '+'.$normalized;
        }

        return $normalized;
    }

    private function generateNomorPendaftaran(int $id): string
    {
        return 'NRC/'.now()->year.'/'.str_pad($id, 4, '0', STR_PAD_LEFT);
    }
}
