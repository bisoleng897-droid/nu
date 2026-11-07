<?php

namespace Database\Factories;

use App\Http\Controllers\StrukturController;
use App\Models\CalonAnggota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalonAnggota>
 */
class CalonAnggotaFactory extends Factory
{
    protected $model = CalonAnggota::class;

    public function definition(): array
    {
        static $sequence = 0;

        return [
            'nomor_pendaftaran' => 'NRC/'.now()->year.'/'.str_pad(++$sequence, 4, '0', STR_PAD_LEFT),
            'nama_lengkap' => fake()->name(),
            'alamat' => fake()->address(),
            'nomor_hp' => '+62'.fake()->numerify('8##########'),
            'nik' => fake()->numerify('################'),
            'foto' => 'foto-calon-anggota/placeholder.jpg',
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'organisasi' => fake()->randomElement(array_keys(StrukturController::KATEGORI)),
            'status' => CalonAnggota::STATUS_PENDING,
            'submitted_at' => now(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CalonAnggota::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CalonAnggota::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => 'Data yang diberikan tidak lengkap.',
        ]);
    }
}
