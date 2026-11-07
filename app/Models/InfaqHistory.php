<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfaqHistory extends Model
{
    use HasFactory;

    public const TIPE_MASUK = 'masuk';

    public const TIPE_KELUAR = 'keluar';

    protected $fillable = [
        'tipe',
        'keterangan',
        'nominal',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'integer',
        ];
    }

    /**
     * Ringkasan kas infaq: total pemasukan, total pengeluaran, dan sisa saldo.
     *
     * @return array{masuk: int, keluar: int, saldo: int}
     */
    public static function totals(): array
    {
        $masuk = (int) static::where('tipe', self::TIPE_MASUK)->sum('nominal');
        $keluar = (int) static::where('tipe', self::TIPE_KELUAR)->sum('nominal');

        return [
            'masuk' => $masuk,
            'keluar' => $keluar,
            'saldo' => $masuk - $keluar,
        ];
    }

    /**
     * Histori ini adalah pengeluaran kas (bukan infaq yang masuk).
     */
    public function isPengeluaran(): bool
    {
        return $this->tipe === self::TIPE_KELUAR;
    }

    public function tipeLabel(): string
    {
        return $this->isPengeluaran() ? 'Pengeluaran' : 'Pemasukan';
    }

    /**
     * Nominal dalam format rupiah tanpa simbol, mis. 150.000.
     */
    public function nominalRupiah(): string
    {
        return number_format((float) $this->nominal, 0, ',', '.');
    }
}
