<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nim' => fake()->unique()->numerify('18########'),
            'email' => fake()->unique()->safeEmail(),
            'alamat' => fake()->address(),
            'no_hp' => fake()->phoneNumber(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-30 years', '-18 years'),
            'foto' => null,
            'spesialisasi' => fake()->randomElement(['Kedokteran Umum', 'Bedah', 'Penyakit Dalam', 'Anak', 'Saraf']),
            'semester' => fake()->numberBetween(1, 12),
            'tahun_masuk' => fake()->numberBetween(2018, 2023),
            'ipk' => fake()->randomFloat(2, 2.5, 4.0),
            'status' => fake()->randomElement(['Aktif', 'Cuti', 'Lulus']),
            'pembimbing' => 'dr. ' . fake()->name() . ', ' . fake()->randomElement(['Sp.PD', 'Sp.B', 'Sp.A', 'Sp.S']),
            'user_id' => User::factory()->create(['role' => 'mahasiswa'])->id,
        ];
    }
}
