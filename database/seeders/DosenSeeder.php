<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user untuk dosen
        $user = User::create([
            'name' => 'Dosen',
            'email' => 'dosen@example.com',
            'password' => Hash::make('dosen'),
            'role' => 'dosen',
        ]);

        // Buat data dosen
        Dosen::create([
            'user_id' => $user->id,
            'nama' => 'Dosen Supervisor',
            'nip' => '1234567890',
            'email' => 'dosen@example.com',
            'alamat' => 'Jl. Pendidikan No. 1',
            'no_hp' => '081234567890',
        ]);
    }
} 