<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public const KATEGORI = [
        'ipnu' => 'IPNU (Putra)',
        'ippnu' => 'IPPNU (Putri)',
        'muslimat' => 'Muslimat',
        'fatayat' => 'Fatayat',
        'ansor' => 'Ansor',
    ];

    public function index()
    {
        $pengurus = Pengurus::orderBy('urutan')->orderBy('id')->get();

        return view('admin.pengurus.index', compact('pengurus'));
    }

    public function create()
    {
        return view('admin.pengurus.create', ['kategoriList' => self::KATEGORI]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'jabatan' => 'required|max:255',
            'kategori' => 'required|in:'.implode(',', array_keys(self::KATEGORI)),
            'urutan' => 'nullable|integer',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengurus', 'public');
        }

        Pengurus::create($validated);

        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function edit(Pengurus $pengurus)
    {
        return view('admin.pengurus.edit', ['pengurus' => $pengurus, 'kategoriList' => self::KATEGORI]);
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'jabatan' => 'required|max:255',
            'kategori' => 'required|in:'.implode(',', array_keys(self::KATEGORI)),
            'urutan' => 'nullable|integer',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengurus', 'public');
        }

        $pengurus->update($validated);

        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $pengurus)
    {
        $pengurus->delete();

        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil dihapus.');
    }
}
