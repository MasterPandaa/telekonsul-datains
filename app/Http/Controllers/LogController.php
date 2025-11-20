<?php
namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    // Halaman Log Database (CRUD) - menggunakan model Log yang sama agar konsisten dengan view
    public function databaseLog(Request $request)
    {
        $query = Log::with('user')->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%$s%")
                  ->orWhere('action', 'like', "%$s%");
            });
        }

        $logs = $query->paginate(10)->withQueryString();
        return view('admin.log.database', compact('logs'));
    }

    // Halaman Log Sistem - konsisten menggunakan model Log (bukan file) agar tampilan tidak rusak
    public function systemLog(Request $request)
    {
        $query = Log::with('user')->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%$s%")
                  ->orWhere('action', 'like', "%$s%");
            });
        }

        $logs = $query->paginate(10)->withQueryString();
        return view('admin.log.system', compact('logs'));
    }
}