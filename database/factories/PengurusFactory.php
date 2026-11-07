<?php

namespace Database\Factories;

use App\Http\Controllers\Admin\PengurusController;
use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengurus>
 */
class PengurusFactory extends Factory
{
    protected $model = Pengurus::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'jabatan' => 'Anggota',
            'kategori' => fake()->randomElement(array_keys(PengurusController::KATEGORI)),
            'foto' => null,
            'urutan' => fake()->numberBetween(1, 10),
        ];
    }
}
