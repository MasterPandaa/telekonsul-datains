<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat pasien untuk user dengan role pasien yang sudah ada
        $user = User::where('email', 'pasien@example.com')->first();
        
        if ($user) {
            Pasien::create([
                'nama' => 'Budi Santoso',
                'nik' => '3175012345678901',
                'email' => 'pasien@example.com',
                'alamat' => 'Jl. Kesehatan No. 123, Jakarta Selatan',
                'no_hp' => '081234567890',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-05-15',
                'tinggi_badan' => 175,
                'berat_badan' => 70,
                'tekanan_darah' => '120/80',
                'alergi' => 'Seafood',
                'riwayat_penyakit' => 'Tidak ada riwayat penyakit kronis',
                'user_id' => $user->id
            ]);
        }
        
        // Buat 15 pasien dummy dengan factory
        Pasien::factory(15)->create();
        
        // Buat 5 pasien dummy dengan relasi user
        Pasien::factory(5)->withUser()->create();
    }
} 