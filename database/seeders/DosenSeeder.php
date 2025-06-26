<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Create dosen for default dosen user
        $dosenUser = User::where('email', 'dosen@example.com')->first();
        if ($dosenUser) {
            Dosen::create([
                'nama' => 'Dosen',
                'nip' => '198501012010011001',
                'email' => 'dosen@example.com',
                'alamat' => 'Jl. Pendidikan No. 1',
                'no_hp' => '081234567890',
            ]);
        }

        // Create 5 random dosen
        Dosen::factory(5)->create()->each(function ($dosen) {
            User::create([
                'name' => $dosen->nama,
                'email' => $dosen->email,
                'password' => bcrypt('password'),
                'role' => 'dosen',
            ]);
        });
    }
} 