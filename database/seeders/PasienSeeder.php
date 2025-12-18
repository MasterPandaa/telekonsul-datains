<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');

        $pasienUsers = User::where('role', 'pasien')->get();

        foreach ($pasienUsers as $index => $user) {
            $gender = ($index % 2 == 0) ? 'Laki-laki' : 'Perempuan';
            
            Pasien::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik' => $this->generateNik($index + 1),
                    'email' => $user->email,
                    'alamat' => $faker->address(),
                    'no_hp' => $this->generatePhoneNumber($index + 1),
                    'jenis_kelamin' => $gender,
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->date('Y-m-d', '-20 years'),
                    'foto' => null,
                    'tinggi_badan' => $faker->numberBetween(150, 185),
                    'berat_badan' => $faker->numberBetween(45, 90),
                    'tekanan_darah' => $faker->randomElement(['110/70', '120/80', '130/85']),
                    'alergi' => $faker->optional()->sentence(),
                    'riwayat_penyakit' => $faker->optional()->sentence(),
                ]
            );
        }
    }

    private function generateNik(int $sequence): string
    {
        return sprintf('31750%02d%07d', now()->format('y'), 1000000 + $sequence);
    }

    private function generatePhoneNumber(int $sequence): string
    {
        return sprintf('08%d%08d', rand(1, 9), $sequence * rand(10000, 99999));
    }
}