<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi pengguna
     */
    public function index()
    {
        $data = [
            'notifications' => $this->getNotificationsForUser(),
            'unreadCount' => $this->getUnreadCountForUser()
        ];

        if (Auth::user()->role === 'admin') {
            return view('notifications.admin.index', $data);
        } else if (Auth::user()->role === 'dokter') {
            return view('notifications.dokter.index', $data);
        } else if (Auth::user()->role === 'dosen') {
            return view('notifications.dosen.index', $data);
        } else if (Auth::user()->role === 'pasien') {
            return view('notifications.pasien.index', $data);
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
    
    /**
     * Menandai notifikasi sebagai dibaca dan redirect ke link
     */
    public function read($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Pastikan notifikasi milik user yang sedang login
        if ($notification->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke notifikasi ini');
        }
        
        // Tandai sebagai dibaca
        $notification->markAsRead();
        
        // Redirect ke link jika ada
        if ($notification->link) {
            return redirect($notification->link);
        }
        
        return redirect()->back()->with('success', 'Notifikasi telah dibaca');
    }
    
    /**
     * Menandai semua notifikasi sebagai dibaca
     */
    public function readAll()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Semua notifikasi telah dibaca');
    }
    
    /**
     * Mendapatkan notifikasi terbaru melalui AJAX
     */
    public function getLatest()
    {
        // Periksa apakah user terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'User tidak terautentikasi'
            ], 401);
        }
        
        $user = Auth::user();
        
        // Ambil notifikasi terbaru dengan limit 10
        $rawNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(20) // Ambil lebih banyak untuk difilter
            ->get();
        
        // Filter notifikasi yang duplikat (pesan sama dalam waktu 1 menit)
        $uniqueNotifications = $this->filterDuplicateNotifications($rawNotifications);
        
        // Batasi hanya 10 notifikasi
        $notifications = $uniqueNotifications->take(10);
        
        // Hitung jumlah notifikasi yang belum dibaca
        $unreadCount = $user->unreadNotificationsCount();
        
        // Tambahkan informasi waktu relatif untuk setiap notifikasi
        $notifications->transform(function($notification) {
            $notification->time_ago = Carbon::parse($notification->created_at)->diffForHumans();
            return $notification;
        });
        
        // Tambahkan header cache control untuk mencegah caching
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'timestamp' => now()->timestamp
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
    
    /**
     * Menandai notifikasi sebagai dibaca melalui AJAX
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        
        // Pastikan notifikasi milik user yang sedang login
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        // Tandai sebagai dibaca
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Filter notifikasi duplikat berdasarkan pesan dan waktu
     * Notifikasi dianggap duplikat jika memiliki pesan yang sama dan dibuat dalam waktu 1 menit
     */
    private function filterDuplicateNotifications(Collection $notifications)
    {
        $uniqueNotifications = collect();
        $processedMessages = [];
        
        foreach ($notifications as $notification) {
            $key = $notification->message . '_' . $notification->type;
            
            // Cek apakah pesan ini sudah diproses
            if (isset($processedMessages[$key])) {
                $existingNotification = $processedMessages[$key];
                $timeDiff = Carbon::parse($notification->created_at)->diffInSeconds(Carbon::parse($existingNotification->created_at));
                
                // Jika dibuat dalam waktu 60 detik, anggap duplikat
                if ($timeDiff <= 60) {
                    // Jika notifikasi baru belum dibaca, prioritaskan yang belum dibaca
                    if (!$notification->is_read && $existingNotification->is_read) {
                        $uniqueNotifications = $uniqueNotifications->reject(function ($item) use ($existingNotification) {
                            return $item->id === $existingNotification->id;
                        });
                        $uniqueNotifications->push($notification);
                        $processedMessages[$key] = $notification;
                    }
                    continue;
                }
            }
            
            // Tambahkan notifikasi baru ke hasil dan tandai sebagai diproses
            $uniqueNotifications->push($notification);
            $processedMessages[$key] = $notification;
        }
        
        return $uniqueNotifications->sortByDesc('created_at')->values();
    }
    
    /**
     * Menghapus semua notifikasi milik pengguna
     */
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        
        // Cek jika request dari AJAX
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Semua notifikasi berhasil dihapus.']);
        }
        
        return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
