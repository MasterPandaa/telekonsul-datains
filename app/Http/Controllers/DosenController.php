<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Dokter;
use App\Models\Konsultasi;
use App\Models\User;
use App\Support\ProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    /**
     * Menampilkan dashboard dosen
     */
    public function dashboard()
    {
        return view('dosen.dashboard', [
            'title' => 'Dashboard Dosen'
        ]);
    }

    /**
     * Menampilkan halaman penilaian konsultasi
     */
    public function penilaianIndex()
    {
        return view('dosen.penilaian.index', [
            'title' => 'Penilaian Konsultasi'
        ]);
    }

    /**
     * Menampilkan detail konsultasi untuk penilaian
     */
    public function penilaianShow($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        
        $chatRoom = $konsultasi->chatRoom;

        if (!$chatRoom) {
            return redirect()->route('dosen.penilaian.index')->with('error', 'Chat room untuk konsultasi ini tidak ditemukan.');
        }

        return redirect()->route('chat.room', $chatRoom);
    }

    /**
     * Menyimpan penilaian konsultasi
     */
    public function penilaianStore(Request $request, $id)
    {
        \Log::info('Penilaian konsultasi request:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'nilai_komunikasi' => 'required|integer|min:1|max:100',
            'nilai_anamnesis' => 'required|integer|min:1|max:100',
            'nilai_diagnosa' => 'required|integer|min:1|max:100',
            'nilai_empati' => 'required|integer|min:1|max:100',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            \Log::error('Validasi gagal:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $konsultasi = Konsultasi::findOrFail($id);
            
            // Simpan nilai-nilai aspek
            $konsultasi->nilai_komunikasi = $request->nilai_komunikasi;
            $konsultasi->nilai_anamnesis = $request->nilai_anamnesis;
            $konsultasi->nilai_diagnosa = $request->nilai_diagnosa;
            $konsultasi->nilai_empati = $request->nilai_empati;
            $konsultasi->catatan_dosen = $request->catatan_dosen;
            
            // Hitung nilai rata-rata
            $nilai_dosen = round(($request->nilai_komunikasi + $request->nilai_anamnesis + 
                                $request->nilai_diagnosa + $request->nilai_empati) / 4);
            
            $konsultasi->nilai_dosen = $nilai_dosen;
            $konsultasi->dosen_id = Auth::user()->dosen->id;
            $konsultasi->save();
            
            \Log::info('Penilaian berhasil disimpan:', [
                'konsultasi_id' => $id,
                'nilai_dosen' => $nilai_dosen
            ]);

            return redirect()->route('dosen.penilaian.index')->with('success', 'Penilaian berhasil disimpan');
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan penilaian:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan penilaian. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan halaman rekap data
     */
    public function rekapIndex()
    {
        return view('dosen.rekap.index', [
            'title' => 'Rekap Data Konsultasi'
        ]);
    }

    /**
     * Menampilkan detail rekap data dokter
     */
    public function rekapDokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        
        $totalKonsultasi = Konsultasi::where('dokter_id', $dokter->user_id)
            ->where('status', 'Selesai')
            ->count();
            
        $sudahDinilai = Konsultasi::where('dokter_id', $dokter->user_id)
            ->where('status', 'Selesai')
            ->whereNotNull('nilai_dosen')
            ->count();
            
        $rataRata = Konsultasi::where('dokter_id', $dokter->user_id)
            ->where('status', 'Selesai')
            ->whereNotNull('nilai_dosen')
            ->avg('nilai_dosen');
            
        $konsultasis = Konsultasi::where('dokter_id', $dokter->user_id)
            ->where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        $chartData = Konsultasi::where('dokter_id', $dokter->user_id)
            ->where('status', 'Selesai')
            ->whereNotNull('nilai_dosen')
            ->orderBy('id', 'asc')
            ->select('id', 'nilai_dosen')
            ->get();
        
        return view('dosen.rekap.dokter', [
            'title' => 'Detail Rekap Dokter',
            'dokter' => $dokter,
            'totalKonsultasi' => $totalKonsultasi,
            'sudahDinilai' => $sudahDinilai,
            'rataRata' => $rataRata,
            'konsultasis' => $konsultasis,
            'chartData' => $chartData
        ]);
    }

    /**
     * Menampilkan halaman profil dosen
     */
    public function profilIndex()
    {
        $dosen = Auth::user()->dosen;
        
        return view('dosen.profil.index', [
            'title' => 'Profil Saya',
            'dosen' => $dosen
        ]);
    }

    /**
     * Update foto profil dosen
     */
    public function updateFoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dosen = Auth::user()->dosen;
        
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $relativePath = ProfilePhoto::storeUploadedAsPng($foto, (int) Auth::id());

            $dosen->foto = $relativePath;
            $dosen->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }

    /**
     * Update informasi dosen
     */
    public function updateInformasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:dosens,nip,' . Auth::user()->dosen->id,
            'email' => 'required|email|max:255|unique:dosens,email,' . Auth::user()->dosen->id,
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $dosen = Auth::user()->dosen;
            $dosen->nip = $request->nip;
            $dosen->email = $request->email;
            $dosen->no_hp = $request->no_hp;
            $dosen->alamat = $request->alamat;
            $dosen->save();

            // Update name dan email di tabel users
            $user = Auth::user();
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->save();
            
            DB::commit();

            return redirect()->back()->with('success', 'Informasi profil berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update password dosen
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
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

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }
} 