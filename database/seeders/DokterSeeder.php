<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dokter;
use App\Models\User;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user untuk dokter contoh
        $user = User::where('email', 'dokter@example.com')->first();
        
        // Buat dokter dari user yang sudah ada
        $dokter = Dokter::create([
            'nama' => 'Riska Amalia',
            'no_sip' => 'SIP/123/456/2023',
            'no_str' => '1234.5.6.7890.12.34567',
            'email' => $user->email,
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1999-05-15',
            'foto' => 'img/dokter/default.jpg',
            'spesialisasi' => 'Kedokteran Umum',
            'sub_spesialisasi' => 'Kedokteran Keluarga',
            'universitas' => 'Universitas Indonesia',
            'tahun_lulus' => 2022,
            'tempat_praktik' => 'Klinik Sehat Sentosa',
            'rumah_sakit' => 'RS Umum Jakarta',
            'status' => 'Aktif',
            'pengalaman' => 'Memiliki pengalaman di bidang kedokteran keluarga dan telah menangani berbagai kasus umum.',
            'user_id' => $user->id,
        ]);
        
        // Buat 5 dokter lainnya dengan factory
        Dokter::factory(5)->create();
    }
} 