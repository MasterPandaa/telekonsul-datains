<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Artisan;
use App\Services\KonsultasiService;
use App\Services\NotificationService;

class MahasiswaPageController extends Controller
{
    protected $konsultasiService;
    
    public function __construct(KonsultasiService $konsultasiService)
    {
        $this->konsultasiService = $konsultasiService;
    }

    public function dashboard()
    {
        return view('mahasiswa.dashboard', [
            'title' => 'Dashboard Mahasiswa'
        ]);
    }

    public function konsultasiIndex()
    {
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Ambil mahasiswa berdasarkan user yang login
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.profil.index')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }
        
        // Ambil data konsultasi aktif (menunggu dan terkonfirmasi)
        $konsultasiAktif = Konsultasi::where('mahasiswa_id', $user->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->with('pasien')
            ->get();
            
        // Siapkan data untuk tampilan
        $konsultasiAktifData = [];
        foreach ($konsultasiAktif as $item) {
            // Cek apakah konsultasi hari ini dan sudah waktunya
            $now = Carbon::now();
            $bisa_dimulai = false;
            
            if ($item->tanggal) {
                $konsultasiDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                $bisa_dimulai = $now->isSameDay($konsultasiDateTime) && 
                               $now->gte($konsultasiDateTime) && 
                               $now->lt($konsultasiDateTime->copy()->addMinutes(15));
            }
            
            // Menghitung timestamp untuk jadwal konsultasi
            $jadwalTimestamp = null;
            if ($item->tanggal) {
                $jadwalDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                $jadwalTimestamp = $jadwalDateTime->timestamp * 1000; // Konversi ke milliseconds untuk JavaScript
            }
            
            $konsultasiAktifData[] = [
                'id' => $item->id,
                'pasien_nama' => $item->pasien->nama ?? 'Pasien',
                'pasien_gender' => $item->pasien->jenis_kelamin ?? '-',
                'pasien_usia' => $item->pasien->usia ?? '-',
                'tanggal_tampil' => $item->tanggal,
                'jam_mulai' => $item->jam_mulai,
                'jam_selesai' => $item->jam_selesai,
                'keluhan' => $item->keluhan,
                'status' => $item->status,
                'bisa_dimulai' => $bisa_dimulai,
                'tanggal_timestamp' => $jadwalTimestamp
            ];
        }
        
        // Ambil data riwayat konsultasi (selesai, ditolak, dibatalkan, dan terlambat)
        $konsultasiSelesai = Konsultasi::where('mahasiswa_id', $user->id)
            ->whereIn('status', ['Selesai', 'Ditolak', 'Dibatalkan', 'Terlambat'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->with('pasien')
            ->take(5)
            ->get();
            
        // Siapkan data untuk tampilan
        $konsultasiSelesaiData = [];
        foreach ($konsultasiSelesai as $item) {
            // Menghitung timestamp untuk jadwal konsultasi
            $jadwalTimestamp = null;
            if ($item->tanggal) {
                $jadwalDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                $jadwalTimestamp = $jadwalDateTime->timestamp * 1000; // Konversi ke milliseconds untuk JavaScript
            }
            
            $konsultasiSelesaiData[] = [
                'id' => $item->id,
                'pasien_nama' => $item->pasien->nama ?? 'Pasien',
                'pasien_gender' => $item->pasien->jenis_kelamin ?? '-',
                'pasien_usia' => $item->pasien->usia ?? '-',
                'tanggal_tampil' => $item->tanggal,
                'jam_mulai' => $item->jam_mulai,
                'jam_selesai' => $item->jam_selesai,
                'keluhan' => $item->keluhan,
                'status' => $item->status,
                'tanggal_timestamp' => $jadwalTimestamp,
                'alasan_tolak' => $item->alasan_tolak,
                'alasan_batal' => $item->alasan_batal,
                'rating' => $item->rating,
                'komentar_rating' => $item->komentar_rating
            ];
        }
        
        // Hitung total konsultasi
        $total = count($konsultasiAktifData) + count($konsultasiSelesaiData);
        $totalAktif = count($konsultasiAktifData);
        $totalSelesai = count($konsultasiSelesaiData);
        
        return view('mahasiswa.konsultasi.index', [
            'title' => 'Daftar Konsultasi',
            'konsultasiAktif' => $konsultasiAktifData,
            'konsultasiSelesai' => $konsultasiSelesaiData,
            'total' => $total,
            'totalAktif' => $totalAktif,
            'totalSelesai' => $totalSelesai
        ]);
    }

    public function konfirmasiKonsultasi(Konsultasi $konsultasi)
    {
        // Cek apakah konsultasi milik mahasiswa yang login
        if ($konsultasi->mahasiswa_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }

        // Cek apakah status masih menunggu
        if ($konsultasi->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Status konsultasi tidak valid');
        }

        // Update status menjadi terkonfirmasi
        $konsultasi->update(['status' => 'Terkonfirmasi']);

        return redirect()->back()->with('success', 'Konsultasi berhasil dikonfirmasi');
    }

    public function tolakKonsultasi(Request $request, Konsultasi $konsultasi)
    {
        // Cek apakah konsultasi milik mahasiswa yang login
        if ($konsultasi->mahasiswa_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }

        // Cek apakah status masih menunggu
        if ($konsultasi->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Status konsultasi tidak valid');
        }

        // Update status menjadi ditolak dan simpan alasan penolakan
        $konsultasi->update([
            'status' => 'Ditolak',
            'alasan_tolak' => $request->alasan_tolak
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil ditolak');
    }

    public function riwayatIndex()
    {
        // Membuat data dummy untuk riwayat konsultasi
        $riwayatKonsultasi = [
            [
                'id' => 1,
                'pasien_nama' => 'Budi Santoso',
                'pasien_gender' => 'Laki-laki',
                'pasien_usia' => 45,
                'tanggal' => '2023-05-15',
                'jam_mulai' => '09:00',
                'jam_selesai' => '10:00',
                'keluhan' => 'Nyeri dada dan sesak napas',
                'diagnosa' => 'Acid Reflux',
                'catatan' => 'Pasien disarankan untuk menghindari makanan pedas dan asam',
                'nilai' => 90,
                'status' => 'Selesai'
            ],
            [
                'id' => 2,
                'pasien_nama' => 'Siti Rahayu',
                'pasien_gender' => 'Perempuan',
                'pasien_usia' => 38,
                'tanggal' => '2023-05-10',
                'jam_mulai' => '13:00',
                'jam_selesai' => '14:00',
                'keluhan' => 'Sakit kepala berulang',
                'diagnosa' => 'Tension Headache',
                'catatan' => 'Pasien disarankan untuk mengurangi stres dan istirahat cukup',
                'nilai' => 85,
                'status' => 'Selesai'
            ],
            [
                'id' => 3,
                'pasien_nama' => 'Ahmad Hidayat',
                'pasien_gender' => 'Laki-laki',
                'pasien_usia' => 52,
                'tanggal' => '2023-05-05',
                'jam_mulai' => '10:30',
                'jam_selesai' => '11:30',
                'keluhan' => 'Kontrol diabetes',
                'diagnosa' => 'Diabetes Mellitus Tipe 2',
                'catatan' => 'Gula darah terkontrol, tetap lanjutkan pengobatan',
                'nilai' => 95,
                'status' => 'Selesai'
            ]
        ];

        // Menambahkan tampilan tanggal dalam format Indonesia
        foreach ($riwayatKonsultasi as &$item) {
            $tanggal_obj = Carbon::createFromFormat('Y-m-d', $item['tanggal']);
            $item['tanggal_tampil'] = $tanggal_obj->translatedFormat('d F Y');
        }
        
        // Statistik nilai
        $nilaiRata = array_sum(array_column($riwayatKonsultasi, 'nilai')) / count($riwayatKonsultasi);
        $nilaiTertinggi = max(array_column($riwayatKonsultasi, 'nilai'));
        $nilaiTerendah = min(array_column($riwayatKonsultasi, 'nilai'));
        
        return view('mahasiswa.riwayat.index', [
            'title' => 'Riwayat & Nilai Konsultasi',
            'riwayatKonsultasi' => $riwayatKonsultasi,
            'total' => count($riwayatKonsultasi),
            'nilaiRata' => round($nilaiRata, 1),
            'nilaiTertinggi' => $nilaiTertinggi,
            'nilaiTerendah' => $nilaiTerendah
        ]);
    }

    public function profilIndex()
    {
        // Ambil data mahasiswa dari database berdasarkan user yang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->with(['keahlians', 'prestasis'])->firstOrFail();
        
        // Siapkan data untuk view
        $profil = [
            'nama' => $mahasiswa->nama,
            'nim' => $mahasiswa->nim,
            'foto' => $mahasiswa->foto,
            'jenis_kelamin' => $mahasiswa->jenis_kelamin,
            'tempat_lahir' => $mahasiswa->tempat_lahir,
            'tanggal_lahir' => $mahasiswa->tanggal_lahir->format('Y-m-d'),
            'alamat' => $mahasiswa->alamat,
            'telepon' => $mahasiswa->no_hp,
            'email' => $mahasiswa->email,
            'spesialisasi' => $mahasiswa->spesialisasi,
            'semester' => $mahasiswa->semester,
            'tahun_masuk' => $mahasiswa->tahun_masuk,
            'pembimbing' => $mahasiswa->pembimbing,
            'ipk' => $mahasiswa->ipk,
            'status' => $mahasiswa->status,
            'prestasi' => $mahasiswa->prestasis->map(function($prestasi) {
                return [
                    'nama' => $prestasi->nama,
                    'tahun' => $prestasi->tahun
                ];
            })->toArray(),
            'keahlian' => $mahasiswa->keahlians->pluck('nama')->toArray()
        ];
        
        // Format tanggal lahir untuk tampilan
        $tanggal_lahir = Carbon::createFromFormat('Y-m-d', $profil['tanggal_lahir']);
        $profil['tanggal_lahir_tampil'] = $tanggal_lahir->translatedFormat('d F Y');
        
        return view('mahasiswa.profil.index', [
            'title' => 'Profil Saya',
            'profil' => $profil
        ]);
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        
        // Hapus foto lama jika ada dan bukan foto default
        if ($mahasiswa->foto && $mahasiswa->foto != 'img/mahasiswa/default.jpg' && file_exists(public_path($mahasiswa->foto))) {
            unlink(public_path($mahasiswa->foto));
        }
        
        // Upload foto baru
        $fotoFile = $request->file('foto');
        $fotoName = time() . '_' . $mahasiswa->nim . '.' . $fotoFile->getClientOriginalExtension();
        $fotoPath = 'img/mahasiswa/';
        $fotoFile->move(public_path($fotoPath), $fotoName);
        
        // Update data mahasiswa
        $mahasiswa->foto = $fotoPath . $fotoName;
        $mahasiswa->save();
        
        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Foto profil berhasil diperbarui');
    }

    public function updateInformasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);
        
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        
        // Update data mahasiswa
        $mahasiswa->update([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);
        
        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Informasi dasar berhasil diperbarui');
    }

    public function updateAkademik(Request $request)
    {
        $request->validate([
            'spesialisasi' => 'required|string|max:100',
            'semester' => 'required|integer|min:1|max:14',
            'tahun_masuk' => 'required|integer|min:2000|max:' . date('Y'),
            'ipk' => 'required|numeric|min:0|max:4',
            'status' => 'required|string|in:Aktif,Cuti,Lulus',
            'pembimbing' => 'required|string|max:255',
        ]);
        
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        
        // Update data mahasiswa
        $mahasiswa->update([
            'spesialisasi' => $request->spesialisasi,
            'semester' => $request->semester,
            'tahun_masuk' => $request->tahun_masuk,
            'ipk' => $request->ipk,
            'status' => $request->status,
            'pembimbing' => $request->pembimbing,
        ]);
        
        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Informasi akademik berhasil diperbarui');
    }

    public function updateKeahlian(Request $request)
    {
        $request->validate([
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'required|string|max:100',
        ]);
        
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        
        // Hapus keahlian lama
        $mahasiswa->keahlians()->delete();
        
        // Tambahkan keahlian baru
        foreach ($request->keahlian as $keahlian) {
            if (!empty(trim($keahlian))) {
                $mahasiswa->keahlians()->create([
                    'nama' => $keahlian
                ]);
            }
        }
        
        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Keahlian khusus berhasil diperbarui');
    }

    public function updatePrestasi(Request $request)
    {
        $request->validate([
            'prestasi' => 'required|array|min:1',
            'prestasi.*' => 'required|string|max:255',
            'tahun' => 'required|array|min:1',
            'tahun.*' => 'nullable|string|max:4',
        ]);
        
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        
        // Hapus prestasi lama
        $mahasiswa->prestasis()->delete();
        
        // Tambahkan prestasi baru
        foreach ($request->prestasi as $index => $prestasi) {
            if (!empty(trim($prestasi))) {
                $mahasiswa->prestasis()->create([
                    'nama' => $prestasi,
                    'tahun' => $request->tahun[$index] ?? null
                ]);
            }
        }
        
        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Prestasi dan penghargaan berhasil diperbarui');
    }

    public function quizIndex()
    {
        return view('mahasiswa.quiz.index', [
            'title' => 'Quiz & Evaluasi'
        ]);
    }

    public function quizShow($id)
    {
        return view('mahasiswa.quiz.show', [
            'title' => 'Detail Quiz',
            'quiz_id' => $id
        ]);
    }

    public function simpanDiagnosa(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|string',
            'catatan' => 'nullable|string'
        ]);
        
        $konsultasi = Konsultasi::findOrFail($id);
        
        // Pastikan konsultasi milik mahasiswa yang login
        if ($konsultasi->mahasiswa_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        
        // Pastikan konsultasi sudah selesai
        if ($konsultasi->status !== 'Selesai') {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan diagnosis untuk konsultasi yang sudah selesai');
        }
        
        // Update diagnosis
        $konsultasi->diagnosa = $request->diagnosa;
        $konsultasi->catatan = $request->catatan;
        $konsultasi->save();
        
        // Buat notifikasi untuk pasien
        $notificationService = app(NotificationService::class);
        $notificationService->createDiagnosisBaruNotification($konsultasi);
        
        return redirect()->route('mahasiswa.konsultasi.index')->with('success', 'Diagnosis berhasil disimpan');
    }

    public function gantiSesiKonsultasi(Request $request, Konsultasi $konsultasi)
    {
        // Cek apakah konsultasi milik mahasiswa yang login
        if ($konsultasi->mahasiswa_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }

        // Cek apakah status terlambat
        if ($konsultasi->status !== 'Terlambat') {
            return redirect()->back()->with('error', 'Status konsultasi tidak valid untuk pergantian sesi');
        }

        // Validasi input
        $request->validate([
            'alasan_terlambat' => 'required|string',
            'tanggal_baru' => 'required|date|after_or_equal:today',
            'sesi_jam' => 'required|string'
        ]);

        // Pisahkan jam mulai dan jam selesai dari sesi_jam
        $jamArray = explode('-', $request->sesi_jam);
        $jamMulai = $jamArray[0];
        $jamSelesai = $jamArray[1];

        // Simpan data pergantian sesi
        $konsultasi->update([
            'status' => 'Pergantian Sesi',
            'alasan_terlambat' => $request->alasan_terlambat,
            'tanggal_baru' => $request->tanggal_baru,
            'jam_mulai_baru' => $jamMulai,
            'jam_selesai_baru' => $jamSelesai
        ]);

        return redirect()->back()->with('success', 'Permintaan pergantian sesi berhasil dikirim');
    }
}