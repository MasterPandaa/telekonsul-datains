<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Dosen;

class SyncUserNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-user-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi nama pengguna dari tabel users ke model terkait (dokter, pasien, dosen)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai sinkronisasi nama pengguna...');
        
        // Sinkronkan nama dokter
        $dokters = Dokter::with('user')->get();
        $this->info('Sinkronisasi ' . count($dokters) . ' data dokter...');
        
        $bar = $this->output->createProgressBar(count($dokters));
        $bar->start();
        
        foreach ($dokters as $dokter) {
            if ($dokter->user) {
                // Tidak perlu lagi mengupdate kolom nama karena sudah dihapus
                // dan diganti dengan accessor getNamaAttribute()
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        // Sinkronkan nama pasien
        $pasiens = Pasien::with('user')->get();
        $this->info('Sinkronisasi ' . count($pasiens) . ' data pasien...');
        
        $bar = $this->output->createProgressBar(count($pasiens));
        $bar->start();
        
        foreach ($pasiens as $pasien) {
            if ($pasien->user) {
                // Tidak perlu lagi mengupdate kolom nama karena sudah dihapus
                // dan diganti dengan accessor getNamaAttribute()
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        // Sinkronkan nama dosen
        $dosens = Dosen::with('user')->get();
        $this->info('Sinkronisasi ' . count($dosens) . ' data dosen...');
        
        $bar = $this->output->createProgressBar(count($dosens));
        $bar->start();
        
        foreach ($dosens as $dosen) {
            if ($dosen->user) {
                // Tidak perlu lagi mengupdate kolom nama karena sudah dihapus
                // dan diganti dengan accessor getNamaAttribute()
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info('Sinkronisasi nama pengguna selesai!');
    }
} 