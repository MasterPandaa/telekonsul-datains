<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Support\Str;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake();

        $dokterUsers = User::where('role', 'dokter')->get();

        foreach ($dokterUsers as $index => $user) {
            Dokter::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'no_sip' => $this->generateSip($index + 1),
                    'no_str' => $this->generateStr($index + 1),
                    'email' => $user->email,
                    'alamat' => $faker->address(),
                    'no_hp' => $faker->phoneNumber(),
                    'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->date('Y-m-d', '-25 years'),
                    'foto' => 'img/dokter/default.jpg',
                    'spesialisasi' => $faker->randomElement([
                        'Kedokteran Umum',
                        'Penyakit Dalam',
                        'Anak',
                        'Bedah Umum',
                        'Jantung',
                    ]),
                    'sub_spesialisasi' => $faker->optional()->word(),
                    'universitas' => $faker->randomElement([
                        'Universitas Indonesia',
                        'Universitas Gadjah Mada',
                        'Universitas Airlangga',
                        'Universitas Padjadjaran',
                    ]),
                    'tahun_lulus' => $faker->numberBetween(2005, 2022),
                    'tempat_praktik' => $faker->company() . ' Klinik',
                    'rumah_sakit' => 'RS ' . Str::title($faker->lastName()),
                    'status' => 'Aktif',
                    'pengalaman' => $faker->paragraph(),
                ]
            );
        }
    }

    private function generateSip(int $sequence): string
    {
        return sprintf('SIP/%03d/%03d/%04d', $sequence, rand(100, 999), now()->year);
    }

    private function generateStr(int $sequence): string
    {
        return sprintf('%04d.%d.%d.%04d.%02d.%05d', rand(1000, 9999), rand(1, 9), rand(1, 9), rand(1000, 9999), rand(10, 99), $sequence * rand(10, 99));
    }
}
 