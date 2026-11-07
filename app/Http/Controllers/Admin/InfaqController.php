<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfaqHistory;
use App\Models\InfaqSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InfaqController extends Controller
{
    /**
     * Halaman admin: pengaturan QRIS, pencatatan transaksi, dan histori infaq.
     */
    public function index()
    {
        $setting = InfaqSetting::current();
        $histories = InfaqHistory::orderByDesc('tanggal')->orderByDesc('id')->get();
        $totals = InfaqHistory::totals();

        return view('admin.infaq.index', compact('setting', 'histories', 'totals'));
    }

    /**
     * Unggah atau ganti foto QRIS yang ditampilkan di halaman utama.
     */
    public function updateQris(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'qris' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $setting = InfaqSetting::current();

        if ($setting->qris_image !== null) {
            Storage::disk('public')->delete($setting->qris_image);
        }

        $setting->update([
            'qris_image' => $request->file('qris')->store('infaq', 'public'),
        ]);

        return redirect()->route('admin.infaq.index')->with('success', 'Foto QRIS berhasil diperbarui.');
    }

    /**
     * Lepas foto QRIS dari halaman utama.
     */
    public function removeQris(): RedirectResponse
    {
        $setting = InfaqSetting::current();

        if ($setting->qris_image !== null) {
            Storage::disk('public')->delete($setting->qris_image);
            $setting->update(['qris_image' => null]);
        }

        return redirect()->route('admin.infaq.index')->with('success', 'Foto QRIS berhasil dihapus.');
    }

    /**
     * Catat pemasukan atau pengeluaran kas infaq ke dalam histori.
     */
    public function storeHistory(Request $request): RedirectResponse
    {
        $nominal = $request->input('nominal');

        // Bersihkan nominal: hapus semua karakter non-digit
        $nominal = preg_replace('/\D/', '', $nominal);

        $validated = $request->validate([
            'tipe' => ['required', Rule::in([InfaqHistory::TIPE_MASUK, InfaqHistory::TIPE_KELUAR])],
            'keterangan' => 'required|string|max:500',
            'tanggal' => 'required|date',
        ]);

        // Validasi nominal setelah pembersihan
        if (! $nominal || (int) $nominal < 1 || (int) $nominal > 10000000000000) {
            return redirect()->back()->withInput()->withErrors([
                'nominal' => 'Nominal harus berupa angka antara 1 hingga 10 triliun.',
            ]);
        }

        $validated['nominal'] = (int) $nominal;

        InfaqHistory::create($validated);

        return redirect()->route('admin.infaq.index')->with('success', 'Transaksi infaq berhasil dicatat.');
    }

    /**
     * Hapus satu baris histori infaq.
     */
    public function destroyHistory(InfaqHistory $infaqHistory): RedirectResponse
    {
        $infaqHistory->delete();

        return redirect()->route('admin.infaq.index')->with('success', 'Histori infaq berhasil dihapus.');
    }
}
