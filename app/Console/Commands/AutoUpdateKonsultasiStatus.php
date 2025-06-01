<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AutoUpdateKonsultasiStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:update-konsultasi-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status konsultasi secara otomatis dan terus menerus';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai auto update status konsultasi pada: ' . now()->format('Y-m-d H:i:s'));
        
        // Loop terus menerus setiap 5 menit
        while (true) {
            try {
                // Jalankan command update status konsultasi
                $this->info('Menjalankan update status konsultasi pada: ' . now()->format('Y-m-d H:i:s'));
                Artisan::call('konsultasi:update-status');
                
                // Tampilkan output dari command
                $output = Artisan::output();
                $this->info($output);
                
                // Log ke file log
                Log::info('Auto Update Konsultasi Status: ' . $output);
                
                // Tunggu 5 menit (300 detik)
                $this->info('Menunggu 5 menit...');
                sleep(300);
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
                Log::error('Auto Update Konsultasi Status Error: ' . $e->getMessage());
                
                // Tunggu 1 menit sebelum mencoba lagi jika terjadi error
                sleep(60);
            }
        }
    }
}
