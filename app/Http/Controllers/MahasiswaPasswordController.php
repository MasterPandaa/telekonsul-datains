<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MahasiswaPasswordController extends Controller
{
    /**
     * Menampilkan halaman pengaturan password
     */
    public function index()
    {
        return view('mahasiswa.pengaturan.index', [
            'title' => 'Pengaturan Password'
        ]);
    }

    /**
     * Memperbarui password mahasiswa
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->numbers()
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Password berhasil diperbarui.');
    }
} 