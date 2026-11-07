<?php

namespace Database\Factories;

use App\Models\InfaqHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InfaqHistory>
 */
class InfaqHistoryFactory extends Factory
{
    protected $model = InfaqHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipe' => fake()->randomElement([InfaqHistory::TIPE_MASUK, InfaqHistory::TIPE_KELUAR]),
            'keterangan' => fake()->sentence(4),
            'nominal' => fake()->numberBetween(10, 5000) * 1000,
            'tanggal' => now()->subDays(fake()->numberBetween(0, 30))->toDateString(),
        ];
    }

    /**
     * Histori berupa infaq yang masuk.
     */
    public function masuk(): static
    {
        return $this->state(fn (array $attributes) => ['tipe' => InfaqHistory::TIPE_MASUK]);
    }

    /**
     * Histori berupa pengeluaran kas.
     */
    public function keluar(): static
    {
        return $this->state(fn (array $attributes) => ['tipe' => InfaqHistory::TIPE_KELUAR]);
    }
}
