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
use Illuminate\Support\Facades\Validator;

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

        // Cek apakah user memiliki akses ke konsultasi ini
        $user = Auth::user();
        if ($user->role === 'dokter' && $konsultasi->dokter_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        if ($user->role === 'pasien' && $konsultasi->pasien_id !== $user->pasien->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        
        // Jika belum waktunya konsultasi
        if ($now->lt($konsultasiDateTime)) {
            $timeUntilStart = $now->diffForHumans($konsultasiDateTime, true);
            return redirect()->back()->with('error', 'Konsultasi belum dimulai. Tersisa ' . $timeUntilStart . ' lagi hingga waktu konsultasi.');
        }
        
        // Hitung sisa waktu konsultasi (dalam menit)
        $sisaWaktu = 0;
        if ($now->lt($konsultasiEndTime)) {
            $sisaWaktu = $now->diffInMinutes($konsultasiEndTime);
        }

        // Cek status konsultasi
        $isTerlambat = false;
        
        // Jika sudah lewat jam selesai
        if ($now->gt($konsultasiEndTime)) {
            // Jika konsultasi belum pernah dibuka (tidak ada chat room)
            if (!$konsultasi->chatRoom) {
                // Jika masih berstatus Terkonfirmasi atau Berlangsung, ubah menjadi Terlambat
                if (in_array($konsultasi->status, ['Terkonfirmasi', 'Berlangsung'])) {
                    $konsultasi->update(['status' => 'Terlambat']);
                }
                return redirect()->back()->with('error', 'Konsultasi sudah berakhir');
            }
            
            // Jika sudah ada chat room, izinkan akses tapi tandai sebagai terlambat
            $isTerlambat = true;
        }

        // Jika konsultasi berstatus Terkonfirmasi dan sudah waktunya, ubah menjadi Berlangsung
        if ($konsultasi->status === 'Terkonfirmasi' && $now->gte($konsultasiDateTime) && $now->lt($konsultasiEndTime)) {
            $konsultasi->update(['status' => 'Berlangsung']);
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
        \Log::info('Chat message request received', [
            'user_id' => Auth::id(),
            'chat_room_id' => $chatRoom->id,
            'request_data' => $request->all()
        ]);

        // Basic validation
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:5000'
        ]);

        if ($validator->fails()) {
            \Log::warning('Chat message validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422);
        }

        // Check if the chat room is active
        if (!$chatRoom->is_active) {
            \Log::warning('Attempt to send message to inactive chat room', [
                'chat_room_id' => $chatRoom->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengirim pesan karena konsultasi telah berakhir'
            ], 403);
        }

        try {
            \Log::info('Creating new chat message', [
                'user_id' => Auth::id(),
                'chat_room_id' => $chatRoom->id,
                'message_length' => strlen($request->message)
            ]);

            // Create message
            $message = new ChatMessage();
            $message->chat_room_id = $chatRoom->id;
            $message->user_id = Auth::id();
            $message->message = $request->message;
            $message->is_read = false;
            
            // Save message
            $saved = $message->save();
            
            if (!$saved) {
                throw new \Exception('Failed to save message to database');
            }

            \Log::info('Message saved successfully', [
                'message_id' => $message->id
            ]);
            
            // Load user relationship for response
            $message->load('user');
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'chat_room_id' => $chatRoom->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMessages(ChatRoom $chatRoom)
    {
        try {
            $messages = $chatRoom->messages()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Error retrieving messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function endChat(ChatRoom $chatRoom)
    {
        $konsultasi = $chatRoom->konsultasi;
        
        // Jika status bukan 'Berlangsung' atau 'Terkonfirmasi', kembalikan dengan pesan error
        if (!in_array($konsultasi->status, ['Berlangsung', 'Terkonfirmasi'])) {
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

    /**
     * View chat room for dosen (read-only)
     */
    public function viewRoom(ChatRoom $chatRoom)
    {
        // Cek apakah user adalah dosen
        $user = Auth::user();
        if ($user->role !== 'dosen') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $konsultasi = $chatRoom->konsultasi;
        
        // Tandai chat room sebagai read-only untuk dosen
        $isDosenView = true;
        
        return view('chat.room', [
            'chatRoom' => $chatRoom,
            'konsultasi' => $konsultasi,
            'isDosenView' => $isDosenView,
            'isTerlambat' => false,
            'sisaWaktu' => 0
        ]);
    }
}