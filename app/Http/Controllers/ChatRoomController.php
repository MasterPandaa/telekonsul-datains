<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Services\KonsultasiService;

class ChatRoomController extends Controller
{
    protected $konsultasiService;
    
    public function __construct(KonsultasiService $konsultasiService)
    {
        $this->konsultasiService = $konsultasiService;
    }
    
    public function create(Konsultasi $konsultasi)
    {
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Cek apakah sudah waktunya konsultasi
        $now = Carbon::now();
        $tanggalFormatted = $konsultasi->tanggal->format('Y-m-d');
        $konsultasiDateTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_mulai);
        $konsultasiEndTime = Carbon::parse($tanggalFormatted . ' ' . $konsultasi->jam_selesai);

        // Cek keterlambatan (lebih dari 15 menit setelah waktu mulai)
        $terlambatDateTime = $konsultasiDateTime->copy()->addMinutes(15);
        $isTerlambat = $now->gt($terlambatDateTime) && $now->lt($konsultasiEndTime);
        
        // Jika konsultasi sudah selesai, set isTerlambat berdasarkan status
        $isFinished = in_array($konsultasi->status, ['Selesai', 'Terlambat']);
        if ($isFinished) {
            $isTerlambat = $konsultasi->status === 'Terlambat';
        }
        
        // Hitung sisa waktu konsultasi (dalam menit)
        $sisaWaktu = $konsultasiEndTime->diffInMinutes($now);
        if ($now->gt($konsultasiEndTime)) {
            $sisaWaktu = 0;
        }

        // Jika belum waktunya konsultasi dan bukan konsultasi yang sudah selesai
        if ($now->lt($konsultasiDateTime) && !$isFinished) {
            return redirect()->back()->with('error', 'Konsultasi belum dimulai');
        }

        // Jika sudah lewat jam selesai dan bukan konsultasi yang sudah selesai
        if ($now->gt($konsultasiEndTime) && !$isFinished) {
            // Jika konsultasi masih berstatus Terkonfirmasi atau Berlangsung (belum pernah dibuka)
            if (in_array($konsultasi->status, ['Terkonfirmasi', 'Berlangsung'])) {
                $konsultasi->update(['status' => 'Terlambat']);
                return redirect()->back()->with('error', 'Konsultasi sudah berakhir dan Anda terlambat mengakses');
            } else {
                return redirect()->back()->with('error', 'Konsultasi sudah berakhir');
            }
        }

        // Cek apakah user memiliki akses ke konsultasi ini
        $user = Auth::user();
        if ($user->role === 'mahasiswa' && $konsultasi->mahasiswa_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        if ($user->role === 'pasien' && $konsultasi->pasien_id !== $user->pasien->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }

        // Jika terlambat dan konsultasi masih berlangsung, update status menjadi "Terlambat"
        if ($isTerlambat && in_array($konsultasi->status, ['Terkonfirmasi', 'Berlangsung'])) {
            $konsultasi->update(['status' => 'Terlambat']);
            $isTerlambat = true;
        }

        // Buat atau dapatkan chat room
        $chatRoom = ChatRoom::firstOrCreate(
            ['konsultasi_id' => $konsultasi->id],
            [
                'room_id' => Str::uuid(),
                'is_active' => true,
                'started_at' => $now
            ]
        );

        return view('chat.room', [
            'chatRoom' => $chatRoom,
            'konsultasi' => $konsultasi,
            'isTerlambat' => $isTerlambat,
            'sisaWaktu' => $sisaWaktu
        ]);
    }

    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        // Periksa status konsultasi
        $konsultasi = $chatRoom->konsultasi;
        if ($konsultasi->status !== 'Terkonfirmasi') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengirim pesan karena konsultasi telah berakhir'
            ], 403);
        }

        $message = $chatRoom->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('user')
        ]);
    }

    public function getMessages(ChatRoom $chatRoom)
    {
        $messages = $chatRoom->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function endChat(ChatRoom $chatRoom)
    {
        $konsultasi = $chatRoom->konsultasi;
        
        // Jika status bukan 'Berlangsung', kembalikan dengan pesan error
        if ($konsultasi->status !== 'Berlangsung') {
            return redirect()->back()->with('error', 'Konsultasi hanya dapat diakhiri jika sedang berlangsung');
        }
        
        // Ubah status menjadi Selesai
        $konsultasi->update(['status' => 'Selesai']);
        
        // Update chat room
        $chatRoom->update([
            'is_active' => false,
            'ended_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Konsultasi telah berakhir');
    }
} 