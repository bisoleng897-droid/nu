<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;

class StrukturController extends Controller
{
    public const KATEGORI = [
        'ipnu-ippnu' => ['label' => 'IPNU & IPPNU', 'desc' => 'Ikatan Pelajar Nahdlatul Ulama (putra) & Ikatan Pelajar Putri Nahdlatul Ulama (putri)'],
        'muslimat' => ['label' => 'Muslimat', 'desc' => 'Persatuan Muslimin Indonesia'],
        'fatayat' => ['label' => 'Fatayat', 'desc' => 'Fatayat Nahdlatul Ulama'],
        'ansor' => ['label' => 'Ansor', 'desc' => 'Barisan Ansor Nahdlatul Ulama'],
    ];

    public function index()
    {
        return view('pages.struktur', ['kategori' => self::KATEGORI]);
    }

    public function show(string $kategori)
    {
        if (! isset(self::KATEGORI[$kategori])) {
            abort(404);
        }

        $kategoriPengurus = $kategori === 'ipnu-ippnu'
            ? ['ipnu', 'ippnu']
            : [$kategori];

        $pengurus = Pengurus::whereIn('kategori', $kategoriPengurus)
            ->orderBy('urutan')->orderBy('id')->get();

        return view('pages.struktur-detail', [
            'kategori' => $kategori,
            'info' => self::KATEGORI[$kategori],
            'pengurus' => $pengurus,
            'ipnu' => $pengurus->where('kategori', 'ipnu'),
            'ippnu' => $pengurus->where('kategori', 'ippnu'),
        ]);
    }
}
