<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Menampilkan halaman log sistem
     *
     * @return \Illuminate\View\View
     */
    public function system()
    {
        $logs = Log::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.log.system', compact('logs'));
    }
    
    /**
     * Menampilkan halaman log database
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        $logs = Log::with('user')
            ->where('action', 'like', '%create%')
            ->orWhere('action', 'like', '%update%')
            ->orWhere('action', 'like', '%delete%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.log.database', compact('logs'));
    }
    
    /**
     * Menghapus log yang dipilih
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:logs,id'
        ]);
        
        Log::whereIn('id', $request->ids)->delete();
        
        return back()->with('success', 'Log berhasil dihapus!');
    }
    
    /**
     * Menghapus semua log
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Log::truncate();
        
        return back()->with('success', 'Semua log berhasil dihapus!');
    }

    /**
     * Mendapatkan data aktivitas pengguna untuk grafik
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserActivityData()
    {
        // Ambil data aktivitas 7 hari terakhir
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        
        // Data untuk grafik berdasarkan tanggal
        $activityByDate = Log::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Siapkan array tanggal untuk 7 hari terakhir
        $dates = [];
        $counts = [];
        
        // Isi dengan 0 untuk tanggal yang tidak ada aktivitas
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays(6 - $i)->format('Y-m-d');
            $dates[] = now()->subDays(6 - $i)->format('d M');
            
            $found = false;
            foreach ($activityByDate as $activity) {
                if ($activity->date == $date) {
                    $counts[] = $activity->count;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $counts[] = 0;
            }
        }
        
        // Data untuk grafik berdasarkan tipe aktivitas
        $activityByType = Log::selectRaw('action, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
            
        $actionTypes = [];
        $actionCounts = [];
        
        foreach ($activityByType as $activity) {
            $actionTypes[] = $activity->action;
            $actionCounts[] = $activity->count;
        }
        
        return response()->json([
            'dateLabels' => $dates,
            'dateCounts' => $counts,
            'actionLabels' => $actionTypes,
            'actionCounts' => $actionCounts
        ]);
    }
}
