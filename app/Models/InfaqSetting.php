<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfaqSetting extends Model
{
    protected $fillable = [
        'qris_image',
    ];

    /**
     * Satu-satunya baris pengaturan infaq, dibuat otomatis saat pertama dipakai.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }

    /**
     * Foto QRIS sudah pernah diunggah admin.
     */
    public function hasQris(): bool
    {
        return $this->qris_image !== null;
    }

    /**
     * URL foto QRIS untuk ditampilkan ke pengunjung, null bila belum ada.
     */
    public function qrisUrl(): ?string
    {
        return $this->hasQris() ? asset('storage/'.$this->qris_image) : null;
    }
}
