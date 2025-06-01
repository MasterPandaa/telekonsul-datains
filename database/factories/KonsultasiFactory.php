<?php

namespace Database\Factories;

use App\Models\Konsultasi;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Konsultasi>
 */
class KonsultasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['Menunggu', 'Terkonfirmasi', 'Ditolak', 'Selesai', 'Dibatalkan']);
        $tanggal = fake()->dateTimeBetween('-1 month', '+1 month');
        
        // Jam mulai acak antara jam 8 pagi sampai 3 sore
        $jam_mulai = str_pad(fake()->numberBetween(8, 15), 2, '0', STR_PAD_LEFT) . ':00:00';
        
        // Jam selesai 1 jam setelah jam mulai
        $jam_mulai_obj = \Carbon\Carbon::createFromFormat('H:i:s', $jam_mulai);
        $jam_selesai = $jam_mulai_obj->copy()->addHour()->format('H:i:s');
        
        return [
            'pasien_id' => Pasien::factory(),
            'mahasiswa_id' => User::factory()->create(['role' => 'mahasiswa'])->id,
            'tanggal' => $tanggal,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'keluhan' => fake()->sentence(),
            'keterangan' => fake()->optional(0.7)->paragraph(),
            'diagnosa' => $status === 'Selesai' ? fake()->sentence() : null,
            'catatan' => $status === 'Selesai' ? fake()->paragraph() : null,
            'nilai' => $status === 'Selesai' ? fake()->numberBetween(60, 100) : null,
            'status' => $status,
        ];
    }
    
    /**
     * Menandai konsultasi sebagai selesai
     */
    public function selesai()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Selesai',
                'diagnosa' => fake()->sentence(),
                'catatan' => fake()->paragraph(),
                'nilai' => fake()->numberBetween(60, 100),
            ];
        });
    }
    
    /**
     * Menandai konsultasi sebagai terkonfirmasi
     */
    public function terkonfirmasi()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Terkonfirmasi',
            ];
        });
    }
    
    /**
     * Menandai konsultasi sebagai menunggu
     */
    public function menunggu()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Menunggu',
            ];
        });
    }
} 