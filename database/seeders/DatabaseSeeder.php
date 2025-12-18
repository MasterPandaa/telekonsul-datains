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
            ['Admin PSTI', 'adminpsti@gmail.com', 'adminpsti', 'admin'],
            ['Admin Datains', 'admindatains@gmail.com', 'admindatains', 'admin'],
            ['Admin Adit', 'adminadit@gmail.com', 'adminadit', 'admin'],
            ['Admin Yesa', 'adminyesa@gmail.com', 'adminyesa', 'admin'],
            ['Admin Susi', 'adminsusi@gmail.com', 'adminsusi', 'admin'],
            ['Admin Lutpi', 'adminlutpi@gmail.com', 'adminlutpi', 'admin'],
            
            ['Dokter PSTI', 'dokterpsti@gmail.com', 'dokterpsti', 'dokter'],
            ['Dokter Datains', 'dokterdatains@gmail.com', 'dokterdatains', 'dokter'],
            ['Dokter Adit', 'dokteradit@gmail.com', 'dokteradit', 'dokter'],
            ['Dokter Yesa', 'dokteryesa@gmail.com', 'dokteryesa', 'dokter'],
            ['Dokter Susi', 'doktersusi@gmail.com', 'doktersusi', 'dokter'],
            ['Dokter Lutpi', 'dokterlutpi@gmail.com', 'dokterlutpi', 'dokter'],
            
            ['Dosen PSTI', 'dosenpsti@gmail.com', 'dosenpsti', 'dosen'],
            ['Dosen Datains', 'dosendatains@gmail.com', 'dosendatains', 'dosen'],
            ['Dosen Adit', 'dosenadit@gmail.com', 'dosenadit', 'dosen'],
            ['Dosen Yesa', 'dosenyesa@gmail.com', 'dosenyesa', 'dosen'],
            ['Dosen Susi', 'dosensusi@gmail.com', 'dosensusi', 'dosen'],
            ['Dosen Lutpi', 'dosenlutpi@gmail.com', 'dosenlutpi', 'dosen'],
            
            ['Pasien PSTI', 'pasienpsti@gmail.com', 'pasienpsti', 'pasien'],
            ['Pasien Datains', 'pasiendatains@gmail.com', 'pasiendatains', 'pasien'],
            ['Pasien Adit', 'pasienadit@gmail.com', 'pasienadit', 'pasien'],
            ['Pasien Yesa', 'pasienyesa@gmail.com', 'pasienyesa', 'pasien'],
            ['Pasien Susi', 'pasiensusi@gmail.com', 'pasiensusi', 'pasien'],
            ['Pasien Lutpi', 'pasienlutpi@gmail.com', 'pasienlutpi', 'pasien'],
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
        // No additional users needed - all users are created in createMandatoryUsers
    }
}

