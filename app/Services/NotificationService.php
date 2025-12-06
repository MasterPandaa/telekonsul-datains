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
        $dokter = User::find($konsultasi->dokter_id);
        $pasien = $konsultasi->pasien;
        
        // Buat notifikasi untuk dokter
        Notification::create([
            'user_id' => $dokter->id,
            'title' => 'Permintaan Konsultasi Baru',
            'type' => 'konsultasi_baru',
            'message' => "Permintaan konsultasi baru dari {$pasien->nama_lengkap}",
            'link' => route('dokter.konsultasi.index'),
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
        $dokter = User::find($konsultasi->dokter_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->user_id,
            'title' => 'Konsultasi Ditolak',
            'type' => 'konsultasi_ditolak',
            'message' => "Permintaan konsultasi Anda ditolak oleh {$dokter->name}",
            'link' => route('pasien.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'dokter_id' => $dokter->id,
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
        $dokter = User::find($konsultasi->dokter_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->user_id,
            'title' => 'Konsultasi Terkonfirmasi',
            'type' => 'konsultasi_terkonfirmasi',
            'message' => "Permintaan konsultasi Anda telah dikonfirmasi oleh {$dokter->name}",
            'link' => route('pasien.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'dokter_id' => $dokter->id,
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
        $dokter = User::find($konsultasi->dokter_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->user_id,
            'title' => 'Pengingat Konsultasi',
            'type' => 'konsultasi_akan_dimulai',
            'message' => "Konsultasi Anda dengan {$dokter->name} akan segera dimulai",
            'link' => route('pasien.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'dokter_id' => $dokter->id,
                'tanggal' => $konsultasi->tanggal->format('Y-m-d'),
                'jam' => $konsultasi->jam_mulai
            ]
        ]);
        
        // Buat notifikasi untuk dokter
        Notification::create([
            'user_id' => $dokter->id,
            'title' => 'Pengingat Konsultasi',
            'type' => 'konsultasi_akan_dimulai',
            'message' => "Konsultasi Anda dengan {$pasien->nama_lengkap} akan segera dimulai",
            'link' => route('dokter.konsultasi.index'),
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
        $dokter = User::find($konsultasi->dokter_id);
        $pasien = $konsultasi->pasien;
        
        // Buat notifikasi untuk dokter
        Notification::create([
            'user_id' => $dokter->id,
            'title' => 'Rating Baru Diterima',
            'type' => 'rating_baru',
            'message' => "Pasien {$pasien->nama_lengkap} memberikan rating {$konsultasi->rating} bintang",
            'link' => route('dokter.riwayat.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'rating' => $konsultasi->rating,
                'komentar' => $konsultasi->komentar_rating
            ],
            'is_read' => false
        ]);
    }
    
    /**
     * Membuat notifikasi untuk diagnosis baru dari dokter
     */
    public function createDiagnosisBaruNotification(Konsultasi $konsultasi)
    {
        $dokter = User::find($konsultasi->dokter_id);
        $pasien = User::find($konsultasi->pasien->user_id);
        
        // Buat notifikasi untuk pasien
        Notification::create([
            'user_id' => $pasien->id,
            'title' => 'Diagnosis Baru',
            'type' => 'diagnosis_baru',
            'message' => "Dokter {$dokter->name} telah memberikan diagnosis untuk konsultasi Anda",
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