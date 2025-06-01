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

        // 1. Cek konsultasi yang terkonfirmasi tapi sudah lewat waktunya (15 menit setelah jam mulai)
        // dan belum memiliki chat room (belum dimulai) -> ubah status menjadi 'Terlambat'
        $this->updateTerlambat($now);

        // 2. Cek konsultasi yang terkonfirmasi dan sudah memiliki chat room (sudah dimulai)
        // tapi sudah lewat jam selesai -> ubah status menjadi 'Selesai'
        $this->updateSelesai($now);

        $this->info('Pembaruan status konsultasi selesai.');
        return Command::SUCCESS;
    }

    private function updateTerlambat($now)
    {
        $konsultasiTerlambat = Konsultasi::where('status', 'Terkonfirmasi')
            ->whereDate('tanggal', '<=', $now->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($konsultasiTerlambat as $konsultasi) {
            $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
            $konsultasiDateTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_mulai);
            $terlambatDateTime = $konsultasiDateTime->copy()->addMinutes(15);

            // Jika sudah lewat 15 menit dari waktu mulai dan belum ada chat room
            if ($now->gt($terlambatDateTime) && !$konsultasi->chatRoom) {
                $konsultasi->update(['status' => 'Terlambat']);
                $this->info("Konsultasi ID: {$konsultasi->id} diubah menjadi Terlambat");
                $count++;
            }
        }

        $this->info("Total {$count} konsultasi diubah menjadi Terlambat");
    }

    private function updateSelesai($now)
    {
        $konsultasiAktif = Konsultasi::whereIn('status', ['Terkonfirmasi', 'Terlambat'])
            ->whereHas('chatRoom') // Sudah memiliki chat room (konsultasi sudah dimulai)
            ->get();

        $count = 0;
        foreach ($konsultasiAktif as $konsultasi) {
            $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
            $konsultasiEndTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_selesai);

            // Jika sudah lewat jam selesai
            if ($now->gt($konsultasiEndTime)) {
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