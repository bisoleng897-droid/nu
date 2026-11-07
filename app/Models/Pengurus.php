<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus';

    protected $fillable = ['nama', 'jabatan', 'kategori', 'foto', 'urutan', 'calon_anggota_id'];

    /**
     * Pendaftaran asal keanggotaan ini. Null untuk pengurus yang
     * ditambahkan langsung oleh admin lewat menu Pengurus.
     */
    public function calonAnggota(): BelongsTo
    {
        return $this->belongsTo(CalonAnggota::class);
    }

    /**
     * Apakah pengurus ini dibuat otomatis dari pendaftaran anggota?
     */
    public function dariPendaftaran(): bool
    {
        return $this->calon_anggota_id !== null;
    }
}
