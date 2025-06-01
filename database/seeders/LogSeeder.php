<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan sudah ada user admin
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->info('Tidak ada user admin. Seeder tidak dapat dijalankan.');
            return;
        }
        
        // Data logs
        $logData = [
            [
                'user_id' => $admin->id,
                'action' => 'login',
                'description' => 'Admin telah login ke sistem',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'create_mahasiswa',
                'description' => 'Admin menambahkan data mahasiswa baru',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'create_dosen',
                'description' => 'Admin menambahkan data dosen baru',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subDays(2)->addHours(2),
                'updated_at' => Carbon::now()->subDays(2)->addHours(2),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'update_mahasiswa',
                'description' => 'Admin memperbarui data mahasiswa',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'create_pasien',
                'description' => 'Admin menambahkan data pasien baru',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subHours(12),
                'updated_at' => Carbon::now()->subHours(12),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'login',
                'description' => 'Admin telah login ke sistem',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
        ];
        
        foreach ($logData as $log) {
            Log::create($log);
        }
        
        $this->command->info('Log seeder telah dijalankan! ' . count($logData) . ' data logs telah ditambahkan.');
    }
}
