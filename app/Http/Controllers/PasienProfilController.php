<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PasienProfilController extends Controller
{
    /**
     * Menampilkan halaman profil pasien
     */
    public function index()
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            // Jika data pasien belum ada, buat data pasien baru
            $pasien = new Pasien();
            $pasien->nama = $user->name;
            $pasien->email = $user->email;
            $pasien->user_id = $user->id;
            // Generate NIK sementara yang unik
            $pasien->nik = 'P' . time() . rand(1000, 9999);
            $pasien->save();
        }
        
        return view('pasien.profil.index', [
            'title' => 'Profil Saya',
            'pasien' => $pasien
        ]);
    }
    
    /**
     * Update informasi dasar pasien
     */
    public function updateInformasiDasar(Request $request)
    {
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan');
        }
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|max:255|unique:pasiens,email,'.$pasien->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'nik' => 'nullable|string|max:16|unique:pasiens,nik,'.$pasien->id,
        ]);
        
        $pasien->nama = $request->nama;
        $pasien->jenis_kelamin = $request->jenis_kelamin;
        $pasien->tempat_lahir = $request->tempat_lahir;
        $pasien->tanggal_lahir = $request->tanggal_lahir;
        $pasien->email = $request->email;
        $pasien->no_hp = $request->no_hp;
        $pasien->alamat = $request->alamat;
        
        // Update NIK jika diisi
        if ($request->filled('nik')) {
            $pasien->nik = $request->nik;
        }
        
        $pasien->save();
        
        // Update nama user juga
        $user->name = $request->nama;
        $user->save();
        
        return redirect()->route('pasien.profil.index')->with('success', 'Informasi dasar berhasil diperbarui');
    }
    
    /**
     * Update informasi medis pasien
     */
    public function updateInformasiMedis(Request $request)
    {
        $request->validate([
            'tinggi_badan' => 'nullable|integer|min:50|max:250',
            'berat_badan' => 'nullable|integer|min:20|max:200',
            'tekanan_darah' => 'nullable|string|max:10',
            'alergi' => 'nullable|string|max:500',
            'riwayat_penyakit' => 'nullable|string|max:1000',
        ]);
        
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan');
        }
        
        $pasien->tinggi_badan = $request->tinggi_badan;
        $pasien->berat_badan = $request->berat_badan;
        $pasien->tekanan_darah = $request->tekanan_darah;
        $pasien->alergi = $request->alergi;
        $pasien->riwayat_penyakit = $request->riwayat_penyakit;
        $pasien->save();
        
        return redirect()->route('pasien.profil.index')->with('success', 'Informasi medis berhasil diperbarui');
    }
    
    /**
     * Upload foto profil pasien
     */
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan');
        }
        
        // Hapus foto lama jika ada (selain foto default)
        if ($pasien->foto && file_exists(public_path('img/pasien/' . $pasien->foto)) && $pasien->foto != 'default.jpg') {
            unlink(public_path('img/pasien/' . $pasien->foto));
        }
        
        // Upload foto baru
        $foto = $request->file('foto');
        $namaFoto = 'pasien_' . Str::slug($pasien->nama) . '_' . time() . '.' . $foto->getClientOriginalExtension();
        
        // Pindahkan file ke folder public/img/pasien
        $foto->move(public_path('img/pasien'), $namaFoto);
        
        // Simpan nama file foto ke database
        $pasien->foto = $namaFoto;
        $pasien->save();
        
        return redirect()->route('pasien.profil.index')->with('success', 'Foto profil berhasil diperbarui');
    }
} 