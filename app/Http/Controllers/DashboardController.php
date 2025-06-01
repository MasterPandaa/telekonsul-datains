<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Services\KonsultasiService;

class DashboardController extends Controller
{
    protected $konsultasiService;
    
    public function __construct(KonsultasiService $konsultasiService)
    {
        $this->konsultasiService = $konsultasiService;
    }
    
    public function index()
    {
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return view('admin.dashboard', [
                    'user' => $user,
                    'title' => 'Dashboard Admin'
                ]);
            case 'mahasiswa':
                return view('mahasiswa.dashboard', [
                    'user' => $user,
                    'title' => 'Dashboard Mahasiswa'
                ]);
            case 'pasien':
                return redirect()->route('pasien.dashboard');
            case 'dosen':
                return view('dosen.dashboard', [
                    'user' => $user,
                    'title' => 'Dashboard Dosen'
                ]);
            default:
                abort(403);
        }
    }
} 