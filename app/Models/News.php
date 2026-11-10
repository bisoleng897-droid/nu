<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'alamat',
        'lat',
        'lng',
        'image',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'lat' => 'float',
            'lng' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (News $news) {
            if (blank($news->published_at)) {
                $news->published_at = now('Asia/Jakarta')->toDateString();
            }
        });
    }

    public function imageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    /**
     * Lokasi kegiatan sudah dipilih lewat peta (punya titik koordinat).
     */
    public function hasCoordinates(): bool
    {
        return $this->lat !== null && $this->lng !== null;
    }

    /**
     * Peta yang ditampilkan di halaman berita.
     *
     * Kalau admin memilih lokasi lewat peta, pakai titik koordinat supaya
     * pin-nya presisi. Kalau hanya mengetik alamat teks, Google Maps
     * mencari sendiri berdasarkan teks tersebut.
     */
    public function mapsEmbedUrl(): string
    {
        $query = $this->hasCoordinates()
            ? $this->lat.','.$this->lng
            : $this->alamat;

        return 'https://www.google.com/maps?q='.urlencode((string) $query).'&output=embed';
    }

    /**
     * Tautan "Buka di Google Maps" untuk admin.
     */
    public function googleMapsUrl(): string
    {
        $query = $this->hasCoordinates()
            ? $this->lat.','.$this->lng
            : $this->alamat;

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode((string) $query);
    }
}
