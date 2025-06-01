<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi pengguna
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('notifications.index', [
            'title' => 'Notifikasi',
            'notifications' => $notifications
        ]);
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
        
        return redirect()->back()->with('success', 'Semua notifikasi telah dibaca');
    }
    
    /**
     * Mendapatkan notifikasi terbaru melalui AJAX
     */
    public function getLatest()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $unreadCount = $user->unreadNotificationsCount();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}
