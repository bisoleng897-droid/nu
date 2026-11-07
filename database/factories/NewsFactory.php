<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'slug' => Str::slug(fake()->unique()->sentence(4)).'-'.fake()->unique()->numberBetween(1, 999999),
            'content' => fake()->paragraphs(3, true),
            'alamat' => null,
            'lat' => null,
            'lng' => null,
            'image' => null,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }

    /**
     * Berita kegiatan yang lokasinya sudah dipilih lewat peta.
     */
    public function withLocation(): static
    {
        return $this->state(fn (array $attributes) => [
            'alamat' => 'Masjid NU Rancamaya, Jl. Raya Bogor No. 12, Rancamaya',
            'lat' => -6.615,
            'lng' => 106.9,
        ]);
    }

    /**
     * Berita yang hanya punya teks alamat, tanpa titik peta.
     */
    public function withAddressOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'alamat' => 'Aula RT 04, Rancamaya, Bogor',
        ]);
    }

    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'news/'.fake()->uuid().'.jpg',
        ]);
    }
}
