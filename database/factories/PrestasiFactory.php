<?php

namespace Database\Factories;

use App\Models\Prestasi;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestasi>
 */
class PrestasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => 'Prestasi ' . fake()->sentence(4),
            'tahun' => (string) fake()->numberBetween(2018, 2023),
            'mahasiswa_id' => Mahasiswa::factory(),
        ];
    }
}
