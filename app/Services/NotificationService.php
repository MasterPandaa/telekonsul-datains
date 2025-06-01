<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Konsultasi;

class NotificationService
{
    /**
     * Membuat notifikasi untuk permintaan konsultasi baru
     */
    public function createKonsultasiBaruNotification(Konsultasi $konsultasi)
    {
        $mahasiswa = User::find($konsultasi->mahasiswa_id);
        $pasien = $konsultasi->pasien;
        
        // Buat notifikasi untuk mahasiswa
        Notification::create([
            'user_id' => $mahasiswa->id,
            'type' => 'konsultasi_baru',
            'message' => "Permintaan konsultasi baru dari {$pasien->nama_lengkap}",
            'link' => route('mahasiswa.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'pasien_id' => $pasien->id,
                'tanggal' => $konsultasi->tanggal->format('Y-m-d'),
                'jam' => $konsultasi->jam_mulai
            ]
        ]);
    }
    
    /**
     * Membuat notifikasi untuk konsultasi yang ditolak
     */
    public function createKonsultasiDitolakNotification(Konsultasi $konsultasi, $alasanTolak)
    {
        $pasien = $konsultasi->pasien;
        $mahasiswa = User::find($konsultasi->mahasiswa_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->user_id,
            'type' => 'konsultasi_ditolak',
            'message' => "Permintaan konsultasi Anda ditolak oleh {$mahasiswa->name}",
            'link' => route('pasien.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'mahasiswa_id' => $mahasiswa->id,
                'alasan_tolak' => $alasanTolak
            ]
        ]);
    }
    
    /**
     * Membuat notifikasi untuk konsultasi yang dikonfirmasi
     */
    public function createKonsultasiTerkonfirmasiNotification(Konsultasi $konsultasi)
    {
        $pasien = $konsultasi->pasien;
        $mahasiswa = User::find($konsultasi->mahasiswa_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->user_id,
            'type' => 'konsultasi_terkonfirmasi',
            'message' => "Permintaan konsultasi Anda telah dikonfirmasi oleh {$mahasiswa->name}",
            'link' => route('pasien.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal' => $konsultasi->tanggal->format('Y-m-d'),
                'jam' => $konsultasi->jam_mulai
            ]
        ]);
    }
    
    /**
     * Membuat notifikasi untuk konsultasi yang akan segera dimulai
     */
    public function createKonsultasiAkanDimulaiNotification(Konsultasi $konsultasi)
    {
        $pasien = $konsultasi->pasien;
        $mahasiswa = User::find($konsultasi->mahasiswa_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->user_id,
            'type' => 'konsultasi_akan_dimulai',
            'message' => "Konsultasi Anda dengan {$mahasiswa->name} akan segera dimulai",
            'link' => route('pasien.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal' => $konsultasi->tanggal->format('Y-m-d'),
                'jam' => $konsultasi->jam_mulai
            ]
        ]);
        
        // Buat notifikasi untuk mahasiswa
        Notification::create([
            'user_id' => $mahasiswa->id,
            'type' => 'konsultasi_akan_dimulai',
            'message' => "Konsultasi Anda dengan {$pasien->nama_lengkap} akan segera dimulai",
            'link' => route('mahasiswa.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'pasien_id' => $pasien->id,
                'tanggal' => $konsultasi->tanggal->format('Y-m-d'),
                'jam' => $konsultasi->jam_mulai
            ]
        ]);
    }
    
    /**
     * Membuat notifikasi untuk rating baru dari pasien
     */
    public function createRatingBaruNotification(Konsultasi $konsultasi)
    {
        $mahasiswa = User::find($konsultasi->mahasiswa_id);
        $pasien = $konsultasi->pasien;
        
        // Buat notifikasi untuk mahasiswa
        Notification::create([
            'user_id' => $mahasiswa->id,
            'type' => 'rating_baru',
            'message' => "Pasien {$pasien->nama_lengkap} memberikan rating {$konsultasi->rating} bintang",
            'link' => route('mahasiswa.riwayat.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'rating' => $konsultasi->rating,
                'komentar' => $konsultasi->komentar_rating
            ],
            'is_read' => false
        ]);
    }
    
    /**
     * Membuat notifikasi untuk diagnosis baru dari mahasiswa
     */
    public function createDiagnosisBaruNotification(Konsultasi $konsultasi)
    {
        $mahasiswa = User::find($konsultasi->mahasiswa_id);
        $pasien = User::find($konsultasi->pasien->user_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->id,
            'type' => 'diagnosis_baru',
            'message' => "Mahasiswa {$mahasiswa->name} telah memberikan diagnosis untuk konsultasi Anda",
            'link' => route('pasien.riwayat.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'diagnosa' => $konsultasi->diagnosa,
                'catatan' => $konsultasi->catatan
            ],
            'is_read' => false
        ]);
    }
} 