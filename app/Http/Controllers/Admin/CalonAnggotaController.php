<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalonAnggota;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class CalonAnggotaController extends Controller
{
    /**
     * Daftar calon anggota. Default menampilkan yang masih pending.
     */
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', CalonAnggota::STATUS_PENDING);

        if (! in_array($status, [CalonAnggota::STATUS_PENDING, CalonAnggota::STATUS_APPROVED, CalonAnggota::STATUS_REJECTED, CalonAnggota::STATUS_INACTIVE, 'semua'], true)) {
            $status = CalonAnggota::STATUS_PENDING;
        }

        $search = (string) $request->query('q', '');

        $query = CalonAnggota::query();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                    ->orWhere('nomor_pendaftaran', 'like', '%'.$search.'%')
                    ->orWhere('nik', 'like', '%'.$search.'%');
            });
        }

        $pendaftar = $query->orderByDesc('submitted_at')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.calon-anggota.index', compact('pendaftar', 'status', 'search'));
    }

    /**
     * Detail calon anggota (khusus admin).
     */
    public function show(CalonAnggota $calonAnggota): View
    {
        return view('admin.calon-anggota.show', compact('calonAnggota'));
    }

    /**
     * Unduh KTA (Kartu Tanda Anggota) dalam format PDF.
     * Hanya tersedia untuk anggota yang sudah disetujui.
     */
    public function downloadKta(CalonAnggota $calonAnggota): Response
    {
        if (! $calonAnggota->isApproved()) {
            abort(403, 'KTA hanya dapat diunduh untuk anggota yang sudah disetujui.');
        }

        // Generate QR Code
        $qrPayload = 'NU-RANCAMAYA|NIK:'.$calonAnggota->nik.'|'.$calonAnggota->nama_lengkap;
        $qrCode = QrCode::create($qrPayload)
            ->setSize(240)
            ->setMargin(0)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Medium);
        $result = new PngWriter;
        $pngData = $result->write($qrCode)->getString();
        $qrCodeBase64 = base64_encode($pngData);

        // Convert foto to base64
        $fotoBase64 = null;
        if ($calonAnggota->foto && \Storage::disk('public')->exists($calonAnggota->foto)) {
            $fotoContent = \Storage::disk('public')->get($calonAnggota->foto);
            $fotoMime = \Storage::disk('public')->mimeType($calonAnggota->foto);
            $fotoBase64 = 'data:'.$fotoMime.';base64,'.base64_encode($fotoContent);
        }

        // Render HTML
        $html = view('admin.calon-anggota.kta-pdf', [
            'calonAnggota' => $calonAnggota,
            'qrCode' => $qrCodeBase64,
            'foto' => $fotoBase64,
        ])->render();

        // Generate PDF menggunakan Browsershot (Chrome)
        $pdf = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome')
            ->setNodeModulePath(base_path('node_modules'))
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->windowSize(340, 540)
            ->emulateMedia('screen')
            ->showBackground()
            ->waitForFunction('document.fonts.ready')
            ->format('A5')
            ->margins(0, 0, 0, 0)
            ->pdf();

        $filename = 'KTA_'.str_replace('/', '_', $calonAnggota->nomor_pendaftaran).'.pdf';

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Setujui pendaftaran: status menjadi approved (Anggota Aktif)
     * sekaligus mendaftarkan pendaftar ke susunan pengurus
     * sehingga langsung tampil di halaman struktur organisasi.
     */
    public function approve(CalonAnggota $calonAnggota): RedirectResponse
    {
        if (! $calonAnggota->isPending()) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya dan tidak dapat disetujui lagi.');
        }

        DB::transaction(function () use ($calonAnggota): void {
            $calonAnggota->update([
                'status' => CalonAnggota::STATUS_APPROVED,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'rejection_reason' => null,
            ]);

            $calonAnggota->pengurus()->create($calonAnggota->pengurusFromPendaftaran());
        });

        return redirect()->route('admin.calon-anggota.index')
            ->with('success', 'Pendaftaran '.$calonAnggota->nama_lengkap.' disetujui dan masuk ke struktur organisasi.');
    }

    /**
     * Tolak pendaftaran: status menjadi rejected, simpan alasan penolakan.
     */
    public function reject(Request $request, CalonAnggota $calonAnggota): RedirectResponse
    {
        if (! $calonAnggota->isPending()) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya dan tidak dapat ditolak lagi.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $calonAnggota->update([
            'status' => CalonAnggota::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.calon-anggota.index')
            ->with('success', 'Pendaftaran '.$calonAnggota->nama_lengkap.' ditolak.');
    }

    /**
     * Nonaktifkan anggota: status menjadi inactive, simpan alasan nonaktif.
     * Hanya dapat dilakukan untuk anggota yang sudah disetujui.
     */
    public function inactivate(Request $request, CalonAnggota $calonAnggota): RedirectResponse
    {
        if (! $calonAnggota->isApproved()) {
            return back()->with('error', 'Hanya anggota yang sudah disetujui yang dapat dinonaktifkan.');
        }

        $validated = $request->validate([
            'inactive_reason' => ['required', 'string', 'max:1000'],
            'inactive_reason_custom' => ['nullable', 'string', 'max:500'],
        ], [
            'inactive_reason.required' => 'Alasan nonaktif wajib dipilih.',
            'inactive_reason.max' => 'Alasan nonaktif maksimal 1000 karakter.',
            'inactive_reason_custom.max' => 'Keterangan tambahan maksimal 500 karakter.',
        ]);

        // Gabungkan alasan dengan keterangan tambahan jika ada
        $finalReason = $validated['inactive_reason'];
        if ($validated['inactive_reason_custom']) {
            $finalReason .= "\n\n".$validated['inactive_reason_custom'];
        }

        DB::transaction(function () use ($calonAnggota, $finalReason): void {
            $calonAnggota->update([
                'status' => CalonAnggota::STATUS_INACTIVE,
                'inactivated_at' => now(),
                'inactivated_by' => Auth::id(),
                'inactive_reason' => $finalReason,
            ]);

            // Hapus dari pengurus jika ada
            $calonAnggota->pengurus()?->delete();
        });

        return redirect()->route('admin.calon-anggota.index', ['status' => CalonAnggota::STATUS_INACTIVE])
            ->with('success', 'Anggota '.$calonAnggota->nama_lengkap.' berhasil dinonaktifkan dan dihapus dari struktur organisasi.');
    }
}
