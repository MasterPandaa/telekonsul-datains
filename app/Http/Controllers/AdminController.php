<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function updateKonsultasiStatus()
    {
        // Jalankan command untuk update status konsultasi
        Artisan::call('konsultasi:update-status');
        
        // Ambil output dari command
        $output = Artisan::output();
        
        return redirect()->back()->with('success', 'Status konsultasi berhasil diperbarui. ' . $output);
    }
} 