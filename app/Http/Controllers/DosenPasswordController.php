<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;

class DosenPasswordController extends Controller
{
    /**
     * Menampilkan halaman pengaturan password
     */
    public function index()
    {
        return view('dosen.pengaturan.index', [
            'title' => 'Pengaturan Password'
        ]);
    }

    /**
     * Update password dosen
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $notificationService = app(NotificationService::class);
        $notificationService->createUserPasswordChangedNotification($user);

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }
} 