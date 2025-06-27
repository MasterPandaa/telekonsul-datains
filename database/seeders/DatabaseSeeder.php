<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Dokter',
            'email' => 'dokter@example.com',
            'password' => bcrypt('dokter'),
            'role' => 'dokter',
        ]);
        User::create([
            'name' => 'Pasien',
            'email' => 'pasien@example.com',
            'password' => bcrypt('pasien'),
            'role' => 'pasien',
        ]);
        
        // Panggil seeder lainnya
        $this->call([
            LogSeeder::class,
            DokterSeeder::class,
            PasienSeeder::class,
            KonsultasiSeeder::class,
            DosenSeeder::class,
            DummyDataSeeder::class,
        ]);
    }
}
