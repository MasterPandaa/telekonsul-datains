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
}
