<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Dosen;

class CheckUserNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa nama pengguna dari tabel users dan model terkait';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa data nama pengguna...');
        
        // Periksa data dokter
        $dokter = Dokter::with('user')->first();
        if ($dokter) {
            $this->info('Data dokter:');
            $this->table(
                ['ID', 'User ID', 'Nama (dari accessor)', 'User Name'],
                [[$dokter->id, $dokter->user_id, $dokter->nama, $dokter->user ? $dokter->user->name : 'null']]
            );
        } else {
            $this->warn('Tidak ada data dokter.');
        }
        
        // Periksa data pasien
        $pasien = Pasien::with('user')->first();
        if ($pasien) {
            $this->info('Data pasien:');
            $this->table(
                ['ID', 'User ID', 'Nama (dari accessor)', 'User Name'],
                [[$pasien->id, $pasien->user_id, $pasien->nama, $pasien->user ? $pasien->user->name : 'null']]
            );
        } else {
            $this->warn('Tidak ada data pasien.');
        }
        
        // Periksa data dosen
        $dosen = Dosen::with('user')->first();
        if ($dosen) {
            $this->info('Data dosen:');
            $this->table(
                ['ID', 'User ID', 'Nama (dari accessor)', 'User Name'],
                [[$dosen->id, $dosen->user_id, $dosen->nama, $dosen->user ? $dosen->user->name : 'null']]
            );
        } else {
            $this->warn('Tidak ada data dosen.');
        }
    }
} 