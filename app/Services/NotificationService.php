<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Konsultasi;
use Illuminate\Support\Collection;

class NotificationService
{
    private function notifyAdmins(string $title, string $type, string $message, ?string $link = null, array $data = []): void
    {
        User::where('role', 'admin')
            ->select(['id'])
            ->chunkById(200, function (Collection $admins) use ($title, $type, $message, $link, $data) {
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => $title,
                        'type' => $type,
                        'message' => $message,
                        'link' => $link,
                        'data' => $data,
                        'is_read' => false,
                    ]);
                }
            });
    }

    private function notifyUser(User $user, string $title, string $type, string $message, ?string $link = null, array $data = []): void
    {
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'type' => $type,
            'message' => $message,
            'link' => $link,
            'data' => $data,
            'is_read' => false,
        ]);
    }

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

    public function createKonsultasiDibatalkanOlehPasienNotification(Konsultasi $konsultasi): void
    {
        $dokter = User::find($konsultasi->dokter_id);
        $pasien = $konsultasi->pasien;

        if (!$dokter || !$pasien) {
            return;
        }

        Notification::create([
            'user_id' => $dokter->id,
            'title' => 'Konsultasi Dibatalkan',
            'type' => 'konsultasi_dibatalkan',
            'message' => "Pasien {$pasien->nama_lengkap} membatalkan permintaan konsultasi.",
            'link' => route('dokter.konsultasi.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'pasien_id' => $pasien->id,
                'alasan_batal' => $konsultasi->alasan_batal,
            ],
            'is_read' => false,
        ]);
    }

    public function createNilaiDosenBaruNotification(Konsultasi $konsultasi): void
    {
        $dokter = User::find($konsultasi->dokter_id);

        if (!$dokter) {
            return;
        }

        Notification::create([
            'user_id' => $dokter->id,
            'title' => 'Penilaian Dosen Masuk',
            'type' => 'nilai_dosen_baru',
            'message' => 'Dosen telah memberikan penilaian untuk konsultasi Anda.',
            'link' => route('dokter.riwayat.index'),
            'data' => [
                'konsultasi_id' => $konsultasi->id,
                'nilai_dosen' => $konsultasi->nilai_dosen,
                'dosen_id' => $konsultasi->dosen_id,
            ],
            'is_read' => false,
        ]);
    }

    public function createUserProfileUpdatedByAdminNotification(User $targetUser, User $adminUser): void
    {
        $this->notifyUser(
            $targetUser,
            'Data Diri Diperbarui',
            'profil_diubah_admin',
            'Data diri Anda telah diperbarui oleh admin.',
            null,
            [
                'admin_id' => $adminUser->id,
                'target_role' => $targetUser->role,
            ]
        );

        $this->notifyAdmins(
            'Perubahan Data User',
            'admin_update_user',
            "Admin {$adminUser->name} memperbarui data user: {$targetUser->name}",
            null,
            [
                'admin_id' => $adminUser->id,
                'target_user_id' => $targetUser->id,
                'target_role' => $targetUser->role,
            ]
        );
    }

    public function createUserPasswordChangedNotification(User $user): void
    {
        $this->notifyAdmins(
            'User Mengganti Password',
            'user_password_changed',
            "User {$user->name} mengganti password.",
            null,
            [
                'user_id' => $user->id,
                'role' => $user->role,
            ]
        );
    }

    public function createUserEmailChangedNotification(User $user, string $oldEmail): void
    {
        $this->notifyAdmins(
            'User Mengganti Email',
            'user_email_changed',
            "User {$user->name} mengganti email dari {$oldEmail} ke {$user->email}.",
            null,
            [
                'user_id' => $user->id,
                'role' => $user->role,
                'old_email' => $oldEmail,
                'new_email' => $user->email,
            ]
        );
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