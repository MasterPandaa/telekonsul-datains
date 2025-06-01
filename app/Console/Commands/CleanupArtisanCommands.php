<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupArtisanCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan dan menginformasikan command-command yang sudah tidak digunakan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Informasi tentang perubahan sistem:');
        $this->line('');
        
        $this->warn('Command konsultasi:update-status sudah tidak diperlukan untuk dijalankan secara terjadwal.');
        $this->info('Status konsultasi sekarang diperbarui secara otomatis ketika:');
        $this->line('1. Pengguna mengakses halaman konsultasi di panel pasien');
        $this->line('2. Pengguna mengakses halaman konsultasi di panel mahasiswa');
        $this->line('3. Pengguna mengakses chat room konsultasi');
        $this->line('4. Pengguna mengakses dashboard');
        $this->line('');
        
        $this->warn('Command auto:update-konsultasi-status sudah tidak diperlukan lagi.');
        $this->info('Seluruh status konsultasi diperbarui secara otomatis saat mengakses halaman-halaman terkait.');
        $this->line('');
        
        $this->warn('Rute /update-konsultasi-status tersedia untuk pembaruan manual jika diperlukan.');
        $this->info('Dapat diakses melalui browser di: /update-konsultasi-status');
        $this->line('');
        
        $this->info('Perubahan ini memastikan status konsultasi selalu diperbarui secara real-time saat pengguna mengakses aplikasi.');
        
        return Command::SUCCESS;
    }
}
