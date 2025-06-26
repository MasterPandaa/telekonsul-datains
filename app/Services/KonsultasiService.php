<?php

namespace App\Services;

use App\Models\Konsultasi;
use App\Models\ChatRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KonsultasiService
{
    /**
     * Update status konsultasi secara real-time
     *
     * @return array Statistik hasil update
     */
    public function updateStatus()
    {
        $now = Carbon::now();
        $stats = [
            'berlangsung' => 0,
            'terlambat' => 0,
            'selesai' => 0
        ];

        try {
            // 1. Update konsultasi yang berlangsung
            $stats['berlangsung'] = $this->updateBerlangsung($now);
            
            // 2. Update konsultasi yang terlambat
            $stats['terlambat'] = $this->updateTerlambat($now);
            
            // 3. Update konsultasi yang selesai
            $stats['selesai'] = $this->updateSelesai($now);
            
            Log::info('Auto update status konsultasi: ' . json_encode($stats));
        } catch (\Exception $e) {
            Log::error('Error pada update status konsultasi: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Update status konsultasi menjadi Berlangsung
     *
     * @param Carbon $now
     * @return int Jumlah konsultasi yang diupdate
     */
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
                Log::info("Konsultasi ID: {$konsultasi->id} diubah menjadi Berlangsung");
                $count++;
            }
        }

        return $count;
    }

    /**
     * Update status konsultasi menjadi Terlambat
     *
     * @param Carbon $now
     * @return int Jumlah konsultasi yang diupdate
     */
    private function updateTerlambat($now)
    {
        // Konsultasi dengan status Terkonfirmasi atau Berlangsung
        $konsultasiAktif = Konsultasi::whereIn('status', ['Terkonfirmasi', 'Berlangsung'])
            ->whereDate('tanggal', '<=', $now->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($konsultasiAktif as $konsultasi) {
            $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
            $konsultasiEndTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_selesai);

            // Jika sudah lewat jam selesai dan belum ada chat room yang dibuat
            if ($now->gt($konsultasiEndTime) && !$konsultasi->chatRoom) {
                $konsultasi->update(['status' => 'Terlambat']);
                Log::info("Konsultasi ID: {$konsultasi->id} diubah menjadi Terlambat");
                $count++;
            }
        }

        return $count;
    }

    /**
     * Update status konsultasi menjadi Selesai
     *
     * @param Carbon $now
     * @return int Jumlah konsultasi yang diupdate
     */
    private function updateSelesai($now)
    {
        $konsultasiAktif = Konsultasi::whereIn('status', ['Terkonfirmasi', 'Berlangsung', 'Terlambat'])
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
                
                Log::info("Konsultasi ID: {$konsultasi->id} diubah menjadi Selesai");
                $count++;
            }
        }

        return $count;
    }
} 