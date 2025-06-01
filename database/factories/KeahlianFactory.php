<?php

namespace Database\Factories;

use App\Models\Keahlian;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Keahlian>
 */
class KeahlianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement([
                'Diagnostik', 'Komunikasi Medis', 'Penanganan Darurat',
                'Kedokteran Keluarga', 'Penelitian Medis', 'Bedah Dasar',
                'Anatomi', 'Farmakologi', 'Imunologi'
            ]),
            'mahasiswa_id' => Mahasiswa::factory(),
        ];
    }
}
