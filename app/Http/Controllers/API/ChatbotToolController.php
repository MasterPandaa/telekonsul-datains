<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Konsultasi;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ChatbotToolController extends Controller
{
    /**
     * Middleware 'auth:sanctum' is optional if we use a shared secret for n8n
     * For now, we'll try to use a simple header check 'X-Chatbot-Token'
     * defined in .env as CHATBOT_API_SECRET
     */
    public function __construct()
    {
        // Manual check or middleware can be applied in api.php
        // For simplicity, we check in methods or use a route middleware
    }

    private function validateSecret(Request $request)
    {
        $secret = env('CHATBOT_API_SECRET');
        // If no secret is set in env, allow for dev/testing (or block, safer to block)
        if (!$secret) {
            Log::warning('CHATBOT_API_SECRET not set in .env');
            return true; // Allow for now but log warning, or return false to be strict
        }

        if ($request->header('X-Chatbot-Token') !== $secret) {
            return false;
        }
        return true;
    }

    /**
     * Tool: Search Doctors
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDoctors(Request $request)
    {
        if (!$this->validateSecret($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = $request->input('query'); // e.g. "Anak", "Gigi", "Umum"

        $doctors = Dokter::with('user')
            ->where('status', 'Items') // Assuming 'active' or similar, strict check removed for now
            ->orWhere('status', 'Aktif') // Adjust based on actual status values
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('spesialisasi', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        // If simple search yielded nothing, try just all active
        if ($doctors->isEmpty() && !$query) {
            $doctors = Dokter::with('user')->limit(5)->get();
        }

        $result = $doctors->map(function ($doc) {
            return [
                'id' => $doc->id,
                'name' => $doc->user->name ?? 'Dokter',
                'specialization' => $doc->spesialisasi,
                'hospital' => $doc->rumah_sakit,
                'experience' => $doc->pengalaman . ' tahun',
                'is_available' => true // Placeholder logic
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Tool: Get Patient Consultation History (SOAP/Notes)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientHistory(Request $request)
    {
        if (!$this->validateSecret($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Identifier can be user_id (auth) or email
        $userId = $request->input('user_id');
        $email = $request->input('email');

        $pasien = null;

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $pasien = $user->pasien;
            }
        } elseif ($email) {
            $pasien = Pasien::where('email', $email)->first();
        }

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien not found'
            ]);
        }

        // Get last 5 consultations
        $history = Konsultasi::with('dokter.user')
            ->where('pasien_id', $pasien->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $data = $history->map(function ($k) {
            return [
                'date' => $k->tanggal_indonesia,
                'doctor' => $k->dokter->name ?? 'Dokter',
                'diagnosis' => $k->diagnosa ?? 'Belum ada diagnosa',
                'complaint' => $k->keluhan,
                'status' => $k->status,
                'advice' => $k->catatan
            ];
        });

        return response()->json([
            'success' => true,
            'patient' => $pasien->nama,
            'history' => $data
        ]);
    }

    /**
     * Tool: Check Schedule
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSchedule(Request $request)
    {
        if (!$this->validateSecret($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $doctorId = $request->input('doctor_id');
        $date = $request->input('date', now()->format('Y-m-d'));

        // Basic availability check (mock logic since simple model)
        // In real app, check JadwalDokter table. 
        // Here we check if there are conflicting consultations?
        // Or mostly return the doctor's standard hours if available.

        $doctor = Dokter::with('user')->find($doctorId);

        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor not found']);
        }

        // Mock schedule for demo: Available 09:00 - 16:00
        // Check existing appointments
        $appointments = Konsultasi::where('dokter_id', $doctor->user_id) // Dokter links to User ID usually in relation
            ->whereDate('tanggal', $date)
            ->whereIn('status', ['Menunggu', 'Berlangsung', 'Selesai'])
            ->get(['jam_mulai', 'jam_selesai']);

        return response()->json([
            'success' => true,
            'doctor' => $doctor->user->name ?? 'Dokter',
            'date' => $date,
            'message' => 'Dokter tersedia jam 09:00 - 15:00',
            'booked_slots' => $appointments,
            'status' => 'Available'
        ]);
    }
    /**
     * Tool: Book Consultation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookConsultation(Request $request)
    {
        if (!$this->validateSecret($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $request->input('user_id');
        $doctorQuery = $request->input('doctor_name'); // e.g. "Dokter PSTI" or "PSTI"
        $date = $request->input('date');
        $time = $request->input('time'); // "HH:mm"
        $complaint = $request->input('complaint', 'Keluhan via Chatbot');

        // 1. Validate User
        $user = User::find($userId);
        if (!$user || !$user->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'User pasien tidak ditemukan. Pastikan Anda sudah login.'
            ]);
        }
        $pasienId = $user->pasien->id;

        // 2. Find Doctor
        $doctorUser = User::where('role', 'dokter')
            ->where('name', 'like', "%{$doctorQuery}%")
            ->first();

        if (!$doctorUser) {
            // Try searching in dokters table (e.g. spesialisasi)
            $doctorUser = User::where('role', 'dokter')
                ->whereHas('dokter', function ($q) use ($doctorQuery) {
                    $q->where('spesialisasi', 'like', "%{$doctorQuery}%");
                })
                ->first();
        }

        if (!$doctorUser) {
            return response()->json([
                'success' => false,
                'message' => "Maaf, saya tidak menemukan dokter dengan nama atau spesialisasi '{$doctorQuery}'. Mohon sebutkan nama dokter yang lebih spesifik."
            ]);
        }

        // 3. Validate Slot
        // Basic check: is there already a booking for this doctor at this time?
        $exists = Konsultasi::where('dokter_id', $doctorUser->id)
            ->where('tanggal', $date)
            ->where('jam_mulai', $time . ':00')
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Berlangsung'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Mohon maaf, jadwal dr. {$doctorUser->name} pada tanggal {$date} jam {$time} sudah terisi. Silakan pilih jam lain."
            ]);
        }

        // 4. Create Booking
        try {
            $konsultasi = new Konsultasi();
            $konsultasi->pasien_id = $pasienId;
            $konsultasi->dokter_id = $doctorUser->id;
            $konsultasi->tanggal = $date;
            $konsultasi->jam_mulai = $time . ':00';
            $konsultasi->jam_selesai = date('H:i:s', strtotime($time) + 15 * 60); // +15 mins
            $konsultasi->keluhan = $complaint;
            $konsultasi->status = 'Menunggu';
            $konsultasi->keterangan = 'Dibuat otomatis via HealsAI Chatbot';
            $konsultasi->save();

            // Notify Doctor (Optional, assume NotificationService is handled elsewhere or manual trigger)
            // app(\App\Services\NotificationService::class)->createKonsultasiBaruNotification($konsultasi);

            return response()->json([
                'success' => true,
                'message' => "Jadwal konsultasi berhasil dibuat!",
                'data' => [
                    'id' => $konsultasi->id,
                    'doctor_name' => $doctorUser->name,
                    'date' => $konsultasi->tanggal_indonesia,
                    'time' => $time,
                    'status' => 'Menunggu'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Chatbot Booking Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan sistem saat membuat jadwal. Silakan coba lagi nanti."
            ]);
        }
    }
}
