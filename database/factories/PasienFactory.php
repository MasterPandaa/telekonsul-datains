<?php

namespace Database\Factories;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pasien>
 */
class PasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenis_kelamin = fake()->randomElement(['Laki-laki', 'Perempuan']);
        
        return [
            'nama' => fake()->name($jenis_kelamin == 'Laki-laki' ? 'male' : 'female'),
            'nik' => fake()->unique()->numerify('################'),
            'email' => fake()->unique()->safeEmail(),
            'alamat' => fake()->address(),
            'no_hp' => fake()->phoneNumber(),
            'jenis_kelamin' => $jenis_kelamin,
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-80 years', '-15 years'),
            'foto' => null,
            
            // Informasi medis
            'tinggi_badan' => fake()->numberBetween(150, 190),
            'berat_badan' => fake()->numberBetween(45, 100),
            'tekanan_darah' => fake()->randomElement(['120/80', '130/85', '110/70', '140/90', '100/60']),
            'alergi' => fake()->optional(0.3)->randomElement([
                'Tidak ada', 'Debu', 'Seafood', 'Kacang', 'Gluten', 'Susu', 'Telur', 
                'Obat penisilin', 'Serbuk bunga', 'Bulu hewan'
            ]),
            'riwayat_penyakit' => fake()->optional(0.4)->randomElement([
                'Tidak ada riwayat penyakit kronis',
                'Diabetes Melitus Tipe 2',
                'Hipertensi',
                'Asma',
                'Penyakit jantung koroner',
                'Gastritis',
                'Migrain',
                'Osteoporosis',
                'Artritis',
                'Kolesterol tinggi'
            ]),
        ];
    }
    
    /**
     * Menambahkan relasi dengan user
     */
    public function withUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory()->create(['role' => 'pasien'])->id,
            ];
        });
    }
} 