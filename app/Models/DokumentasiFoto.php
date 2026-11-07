<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiFoto extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi_fotos';

    protected $fillable = [
        'dokumentasi_id',
        'foto',
        'is_thumbnail',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'is_thumbnail' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    /**
     * Dokumentasi induk tempat foto ini termasuk.
     */
    public function dokumentasi(): BelongsTo
    {
        return $this->belongsTo(Dokumentasi::class);
    }

    /**
     * URL lengkap foto untuk ditampilkan.
     */
    public function url(): string
    {
        return asset('storage/'.$this->foto);
    }
}
