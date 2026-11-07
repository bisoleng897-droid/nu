<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';

    protected $fillable = [
        'kategori',
        'judul',
        'deskripsi',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Semua foto yang termasuk dokumentasi ini, diurutkan.
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(DokumentasiFoto::class)->orderBy('urutan')->orderBy('id');
    }

    /**
     * Foto yang ditetapkan sebagai thumbnail (sampul).
     */
    public function thumbnail(): HasMany
    {
        return $this->hasMany(DokumentasiFoto::class)->where('is_thumbnail', true);
    }

    /**
     * URL foto sampul: prioritas thumbnail, lalu foto pertama, null kalau kosong.
     */
    public function coverUrl(): ?string
    {
        $cover = $this->fotos->where('is_thumbnail', true)->first()
            ?? $this->fotos->first();

        return $cover?->url();
    }

    /**
     * Apakah dokumentasi ini sudah memiliki foto minimal satu.
     */
    public function hasFoto(): bool
    {
        return $this->fotos->count() > 0;
    }

    /**
     * Scope: filter berdasarkan kategori (folder).
     */
    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
