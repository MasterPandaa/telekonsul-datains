<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KonsultasiController extends Controller
{
    /**
     * Mendapatkan detail konsultasi
     */
    public function getDetail($id)
    {
        try {
            Log::info('API Request: /api/konsultasi/' . $id . ' by user ' . Auth::id());
            
            // Cek dulu apakah konsultasi dengan ID tersebut ada
            $konsultasi = Konsultasi::with('mahasiswa', 'pasien')->find($id);
            
            if (!$konsultasi) {
                Log::warning('Konsultasi not found: ' . $id);
                return response()->json([
                    'error' => 'Konsultasi tidak ditemukan',
                    'message' => 'Data konsultasi dengan ID ' . $id . ' tidak ditemukan di sistem'
                ], 404);
            }
            
            // Verifikasi akses pengguna ke data konsultasi
            $user = Auth::user();
            $hasAccess = false;
            
            if ($user->role === 'admin') {
                $hasAccess = true; // Admin memiliki akses ke semua konsultasi
            } else if ($user->role === 'mahasiswa' && $konsultasi->mahasiswa_id === $user->id) {
                $hasAccess = true; // Mahasiswa hanya memiliki akses ke konsultasinya sendiri
            } else if ($user->role === 'pasien' && $konsultasi->pasien->user_id === $user->id) {
                $hasAccess = true; // Pasien hanya memiliki akses ke konsultasinya sendiri
            }
            
            if (!$hasAccess) {
                Log::warning('Unauthorized access to konsultasi: ' . $id . ' by user ' . Auth::id());
                return response()->json([
                    'error' => 'Akses ditolak',
                    'message' => 'Anda tidak memiliki akses ke konsultasi ini'
                ], 403);
            }
            
            // Pastikan format tanggal dan waktu sesuai
            $data = [
                'id' => $konsultasi->id,
                'tanggal' => $konsultasi->tanggal,
                'tanggal_formatted' => $konsultasi->tanggal->isoFormat('D MMMM Y'),
                'jam_mulai' => $konsultasi->jam_mulai,
                'jam_selesai' => $konsultasi->jam_selesai,
                'keluhan' => $konsultasi->keluhan,
                'keterangan' => $konsultasi->keterangan,
                'diagnosa' => $konsultasi->diagnosa,
                'catatan' => $konsultasi->catatan,
                'status' => $konsultasi->status,
                'mahasiswa' => $konsultasi->mahasiswa ? [
                    'id' => $konsultasi->mahasiswa->id,
                    'name' => $konsultasi->mahasiswa->name,
                ] : null,
                'pasien' => $konsultasi->pasien ? [
                    'id' => $konsultasi->pasien->id,
                    'nama' => $konsultasi->pasien->nama,
                ] : null,
                'resep' => explode("\n", $konsultasi->resep ?? ''),
                'saran' => explode("\n", $konsultasi->saran ?? ''),
            ];
            
            Log::info('API Response success for konsultasi: ' . $id);
            
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
