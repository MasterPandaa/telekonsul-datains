<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Konsultasi;
use App\Models\ChatRoom;
use Carbon\Carbon;

class UpdateKonsultasiStatus extends Command
{
    protected $signature = 'konsultasi:update-status';
    protected $description = 'Memperbarui status konsultasi berdasarkan waktu dan aktivitas';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $this->info('Memulai pembaruan status konsultasi pada: ' . $now->format('Y-m-d H:i:s'));

        // 1. Update konsultasi yang terkonfirmasi menjadi berlangsung saat waktunya tiba
        $this->updateBerlangsung($now);
        
        // 2. Cek konsultasi yang terkonfirmasi tapi sudah lewat waktunya (setelah jam selesai)
        // dan belum memiliki chat room (belum dimulai) -> ubah status menjadi 'Terlambat'
        $this->updateTerlambat($now);

        // 3. Cek konsultasi yang terkonfirmasi atau berlangsung dan sudah lewat jam selesai + grace period
        // -> ubah status menjadi 'Selesai'
        $this->updateSelesai($now);

        $this->info('Pembaruan status konsultasi selesai.');
        return Command::SUCCESS;
    }

    private function updateBerlangsung($now)
    {
        $konsultasiTerkonfirmasi = Konsultasi::where('status', 'Terkonfirmasi')
            ->whereDate('tanggal', '=', $now->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($konsultasiTerkonfirmasi as $konsultasi) {
            $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
            $jamMulai = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_mulai);
            $jamSelesai = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_selesai);

            // Jika sudah memasuki waktu konsultasi dan belum melewati waktu selesai
            if ($now->gte($jamMulai) && $now->lt($jamSelesai)) {
                $konsultasi->update(['status' => 'Berlangsung']);
                $this->info("Konsultasi ID: {$konsultasi->id} diubah menjadi Berlangsung");
                $count++;
            }
        }

        $this->info("Total {$count} konsultasi diubah menjadi Berlangsung");
    }

    private function updateTerlambat($now)
    {
        $konsultasiAktif = Konsultasi::whereIn('status', ['Terkonfirmasi', 'Berlangsung'])
            ->whereDate('tanggal', '<=', $now->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($konsultasiAktif as $konsultasi) {
            $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
            $konsultasiEndTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_selesai);

            // Jika sudah lewat jam selesai dan belum ada chat room
            if ($now->gt($konsultasiEndTime) && !$konsultasi->chatRoom) {
                $konsultasi->update(['status' => 'Terlambat']);
                $this->info("Konsultasi ID: {$konsultasi->id} diubah menjadi Terlambat");
                $count++;
            }
        }

        $this->info("Total {$count} konsultasi diubah menjadi Terlambat");
    }

    private function updateSelesai($now)
    {
        $konsultasiAktif = Konsultasi::whereIn('status', ['Terkonfirmasi', 'Terlambat', 'Berlangsung'])
            ->get();

        $count = 0;
        foreach ($konsultasiAktif as $konsultasi) {
            $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
            $konsultasiEndTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_selesai);
            
            // Tambahkan grace period 30 menit setelah jam selesai
            $graceEndTime = $konsultasiEndTime->copy()->addMinutes(30);

            // Jika sudah lewat jam selesai + grace period
            if ($now->gt($graceEndTime)) {
                $konsultasi->update(['status' => 'Selesai']);
                
                // Update juga status chat room menjadi tidak aktif
                if ($konsultasi->chatRoom && $konsultasi->chatRoom->is_active) {
                    $konsultasi->chatRoom->update([
                        'is_active' => false,
                        'ended_at' => $now
                    ]);
                }
                
                $this->info("Konsultasi ID: {$konsultasi->id} diubah menjadi Selesai");
                $count++;
            }
        }

        $this->info("Total {$count} konsultasi diubah menjadi Selesai");
    }
} 