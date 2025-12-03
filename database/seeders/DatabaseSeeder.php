<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createMandatoryUsers();
        $this->createAdditionalUsers();

        $this->call([
            DokterSeeder::class,
            DosenSeeder::class,
            PasienSeeder::class,
            DummyDataSeeder::class,
        ]);
    }

    private function createMandatoryUsers(): void
    {
        $users = [
            ['Admin Wajib', 'admin@example.com', 'admin', 'admin'],
            ['Dokter Wajib', 'dokter@example.com', 'dokter', 'dokter'],
            ['Dosen Wajib', 'dosen@example.com', 'dosen', 'dosen'],
            ['Pasien Wajib', 'pasien@example.com', 'pasien', 'pasien'],
        ];

        foreach ($users as [$name, $email, $password, $role]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role' => $role,
                ]
            );
        }
    }

    private function createAdditionalUsers(): void
    {
        $counts = 5;
        $roles = [
            'admin' => 'Admin',
            'dokter' => 'Dokter',
            'dosen' => 'Dosen',
            'pasien' => 'Pasien',
        ];

        foreach ($roles as $role => $label) {
            for ($i = 2; $i <= $counts + 1; $i++) {
                $email = strtolower($role) . $i . '@example.com';
                $password = strtolower($role) . $i;

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $label . ' ' . $i,
                        'password' => Hash::make($password),
                        'role' => $role,
                    ]
                );
            }
        }
    }
}

