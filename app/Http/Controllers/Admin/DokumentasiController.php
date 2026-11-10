<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\DokumentasiFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumentasi::with(['fotos' => function ($query) {
            $query->orderBy('urutan')->orderBy('id');
        }])->orderByDesc('created_at')->orderByDesc('id');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $dokumentasi = $query->get();

        $kategori = Dokumentasi::whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori')
            ->unique()
            ->sort()
            ->values();

        return view('admin.dokumentasi.index', compact('dokumentasi', 'kategori'));
    }

    public function create()
    {
        return view('admin.dokumentasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'nullable|string|max:100',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'fotos' => 'required|array|min:1|max:10',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $dokumentasi = Dokumentasi::create([
                'kategori' => $validated['kategori'] ?? null,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => now('Asia/Jakarta')->toDateString(),
            ]);

            $urutan = 0;
            foreach ($request->file('fotos') as $index => $file) {
                DokumentasiFoto::create([
                    'dokumentasi_id' => $dokumentasi->id,
                    'foto' => $file->store('dokumentasi', 'public'),
                    'is_thumbnail' => $index === 0,
                    'urutan' => $urutan++,
                ]);
            }
        });

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function edit(Dokumentasi $dokumentasi)
    {
        $dokumentasi->load('fotos');

        return view('admin.dokumentasi.edit', compact('dokumentasi'));
    }

    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $validated = $request->validate([
            'kategori' => 'nullable|string|max:100',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'fotos' => 'nullable|array|max:10',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $dokumentasi->update([
            'kategori' => $validated['kategori'] ?? null,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        if ($request->hasFile('fotos')) {
            // Hapus foto lama dan ganti dengan yang baru.
            $dokumentasi->fotos->each(fn ($foto) => $foto->delete());

            $urutan = 0;
            foreach ($request->file('fotos') as $index => $file) {
                DokumentasiFoto::create([
                    'dokumentasi_id' => $dokumentasi->id,
                    'foto' => $file->store('dokumentasi', 'public'),
                    'is_thumbnail' => $index === 0,
                    'urutan' => $urutan++,
                ]);
            }
        }

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        $dokumentasi->delete();

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
