<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
