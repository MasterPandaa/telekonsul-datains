<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Dokter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dokter>
 */
class DokterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Dokter::class;
    
    public function definition(): array
    {
        $spesialisasi = fake()->randomElement([
            'Bedah Umum', 'Penyakit Dalam', 'Anak', 'Kandungan dan Kebidanan', 
            'Jantung', 'Saraf', 'Mata', 'THT', 'Kulit dan Kelamin', 'Radiologi'
        ]);
        
        return [
            'nama' => 'dr. ' . fake()->name(),
            'no_sip' => fake()->unique()->numerify('SIP/###/###/####'),
            'no_str' => fake()->unique()->numerify('####.#.#.####.##.#####'),
            'email' => fake()->unique()->safeEmail(),
            'alamat' => fake()->address(),
            'no_hp' => fake()->phoneNumber(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-25 years'),
            'foto' => 'img/dokter/default.jpg',
            'spesialisasi' => $spesialisasi,
            'sub_spesialisasi' => $this->getSubSpesialisasi($spesialisasi),
            'universitas' => fake()->randomElement([
                'Universitas Indonesia', 'Universitas Gadjah Mada', 'Universitas Airlangga',
                'Universitas Padjadjaran', 'Universitas Diponegoro', 'Universitas Hasanuddin'
            ]),
            'tahun_lulus' => fake()->numberBetween(1990, 2022),
            'tempat_praktik' => fake()->company() . ' Medical Center',
            'rumah_sakit' => 'RS ' . fake()->company(),
            'status' => fake()->randomElement(['Aktif', 'Tidak Aktif', 'Cuti']),
            'pengalaman' => fake()->paragraphs(2, true),
            'user_id' => User::factory()->create(['role' => 'dokter'])->id,
        ];
    }
    
    private function getSubSpesialisasi($spesialisasi): ?string
    {
        $subSpesialisasi = [
            'Bedah Umum' => ['Bedah Onkologi', 'Bedah Digestif', 'Bedah Vaskular', 'Bedah Plastik'],
            'Penyakit Dalam' => ['Gastroenterologi', 'Kardiologi', 'Pulmonologi', 'Endokrinologi', 'Nefrologi'],
            'Anak' => ['Neurologi Anak', 'Kardiologi Anak', 'Hematologi Anak', 'Gastroenterologi Anak'],
            'Kandungan dan Kebidanan' => ['Onkologi Ginekologi', 'Fetomaternal', 'Endokrinologi Reproduksi'],
            'Jantung' => ['Intervensi Kardiovaskular', 'Aritmia', 'Gagal Jantung'],
            'Saraf' => ['Stroke', 'Epilepsi', 'Neurodegeneratif', 'Saraf Tepi'],
            'Mata' => ['Retina', 'Glaukoma', 'Kornea', 'Pediatrik Oftalmologi'],
            'THT' => ['Otologi', 'Rinologi', 'Laringologi', 'Bedah Kepala dan Leher'],
            'Kulit dan Kelamin' => ['Dermatologi Kosmetik', 'Alergi Imunologi', 'Infeksi Menular Seksual'],
            'Radiologi' => ['Radiologi Intervensi', 'Neuroradiologi', 'Radiologi Onkologi']
        ];
        
        if (isset($subSpesialisasi[$spesialisasi])) {
            return fake()->randomElement($subSpesialisasi[$spesialisasi]);
        }
        
        return null;
    }
} 