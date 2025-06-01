<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Keahlian;
use App\Models\Prestasi;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user untuk mahasiswa contoh
        $user = User::where('email', 'mahasiswa@example.com')->first();
        
        // Buat mahasiswa dari user yang sudah ada
        $mahasiswa = Mahasiswa::create([
            'nama' => 'Riska Amalia',
            'nim' => '18120510023',
            'email' => $user->email,
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1999-05-15',
            'foto' => 'img/mahasiswa/default.jpg',
            'spesialisasi' => 'Kedokteran Umum',
            'semester' => 8,
            'tahun_masuk' => 2018,
            'ipk' => 3.85,
            'status' => 'Aktif',
            'pembimbing' => 'dr. Budi Santoso, Sp.PD.',
            'user_id' => $user->id,
        ]);
        
        // Tambahkan keahlian untuk mahasiswa
        $keahlian = [
            'Diagnostik',
            'Komunikasi Medis',
            'Penanganan Darurat',
            'Kedokteran Keluarga'
        ];
        
        foreach ($keahlian as $k) {
            Keahlian::create([
                'nama' => $k,
                'mahasiswa_id' => $mahasiswa->id
            ]);
        }
        
        // Tambahkan prestasi untuk mahasiswa
        Prestasi::create([
            'nama' => 'Juara 1 Lomba Karya Tulis Ilmiah Kedokteran',
            'tahun' => '2022',
            'mahasiswa_id' => $mahasiswa->id
        ]);
        
        Prestasi::create([
            'nama' => 'Delegasi Indonesia pada ASEAN Medical Student Conference',
            'tahun' => '2021',
            'mahasiswa_id' => $mahasiswa->id
        ]);
        
        // Buat 5 mahasiswa lainnya dengan factory
        Mahasiswa::factory(5)
            ->has(
                Keahlian::factory()->count(3)->state(function () {
                    return [
                        'nama' => fake()->randomElement([
                            'Diagnostik', 'Komunikasi Medis', 'Penanganan Darurat',
                            'Kedokteran Keluarga', 'Penelitian Medis', 'Bedah Dasar',
                            'Anatomi', 'Farmakologi', 'Imunologi'
                        ])
                    ];
                })
            )
            ->has(
                Prestasi::factory()->count(2)->state(function () {
                    return [
                        'nama' => 'Prestasi ' . fake()->sentence(4),
                        'tahun' => (string) fake()->numberBetween(2018, 2023)
                    ];
                })
            )
            ->create();
    }
}
