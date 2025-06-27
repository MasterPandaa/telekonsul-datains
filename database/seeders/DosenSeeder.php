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
        // Cek apakah user dosen sudah ada
        $user = User::where('email', 'dosen@example.com')->first();
        
        if (!$user) {
            // Buat user untuk dosen jika belum ada
            $user = User::create([
                'name' => 'Dosen Supervisor',
                'email' => 'dosen@example.com',
                'password' => Hash::make('dosen'),
                'role' => 'dosen',
            ]);
        }

        // Cek apakah data dosen sudah ada
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            // Buat data dosen jika belum ada
            Dosen::create([
                'user_id' => $user->id,
                'nip' => '1234567890',
                'email' => 'dosen@example.com',
                'alamat' => 'Jl. Pendidikan No. 1',
                'no_hp' => '081234567890',
            ]);
        }
    }
} 