<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Support\ProfilePhoto;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Artisan;
use App\Services\KonsultasiService;
use App\Services\NotificationService;

class DokterPageController extends Controller
{
    protected $konsultasiService;
    
    public function __construct(KonsultasiService $konsultasiService)
    {
        $this->konsultasiService = $konsultasiService;
    }

    public function dashboard()
    {
        // Ambil user yang login
        $user = Auth::user();
        
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Hitung jumlah konsultasi aktif (menunggu dan terkonfirmasi)
        $konsultasiAktifCount = Konsultasi::where('dokter_id', $user->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->count();
        
        // Hitung jumlah konsultasi selesai
        $konsultasiSelesaiCount = Konsultasi::where('dokter_id', $user->id)
            ->where('status', 'Selesai')
            ->count();
        
        // Ambil data konsultasi aktif terbaru untuk ditampilkan di tabel
        $konsultasiAktif = Konsultasi::where('dokter_id', $user->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->with('pasien')
            ->take(5)
            ->get();
        
        // Siapkan data untuk tampilan tabel
        $jadwalKonsultasi = [];
        foreach ($konsultasiAktif as $item) {
            $jadwalKonsultasi[] = [
                'id' => $item->id,
                'tanggal' => $item->tanggal ? $item->tanggal->format('d F Y') : '-',
                'jam' => $item->jam_mulai . ' - ' . $item->jam_selesai,
                'pasien_nama' => $item->pasien->nama ?? 'Pasien',
                'pasien_gender' => $item->pasien->jenis_kelamin ?? '-',
                'pasien_usia' => $item->pasien->usia ?? '-',
                'status' => $item->status
            ];
        }
        
        // Hitung nilai rata-rata dari keempat aspek penilaian
        $totalNilai = 0;
        $countNilai = 0;
        
        // Ambil semua konsultasi yang sudah selesai
        $konsultasiNilai = Konsultasi::where('dokter_id', $user->id)
            ->where('status', 'Selesai')
            ->get();
            
        foreach ($konsultasiNilai as $konsultasi) {
            $nilaiKonsultasi = 0;
            $countAspek = 0;
            
            if (!is_null($konsultasi->nilai_komunikasi)) {
                $nilaiKonsultasi += $konsultasi->nilai_komunikasi;
                $countAspek++;
            }
            
            if (!is_null($konsultasi->nilai_anamnesis)) {
                $nilaiKonsultasi += $konsultasi->nilai_anamnesis;
                $countAspek++;
            }
            
            if (!is_null($konsultasi->nilai_diagnosa)) {
                $nilaiKonsultasi += $konsultasi->nilai_diagnosa;
                $countAspek++;
            }
            
            if (!is_null($konsultasi->nilai_empati)) {
                $nilaiKonsultasi += $konsultasi->nilai_empati;
                $countAspek++;
            }
            
            if ($countAspek > 0) {
                $totalNilai += ($nilaiKonsultasi / $countAspek);
                $countNilai++;
            }
        }
        
        // Hitung rata-rata nilai
        $nilaiAvg = $countNilai > 0 ? round($totalNilai / $countNilai) : 0;
        
        // Hitung nilai rata-rata dari rating konsultasi
        $ratingAvg = Konsultasi::where('dokter_id', $user->id)
            ->whereNotNull('rating')
            ->avg('rating');
        $ratingAvg = $ratingAvg ? round($ratingAvg, 1) : 0;
        
        return view('dokter.dashboard', [
            'title' => 'Dashboard Dokter',
            'konsultasiAktifCount' => $konsultasiAktifCount,
            'konsultasiSelesaiCount' => $konsultasiSelesaiCount,
            'jadwalKonsultasi' => $jadwalKonsultasi,
            'ratingAvg' => $ratingAvg,
            'nilaiAvg' => $nilaiAvg
        ]);
    }

    public function konsultasiIndex()
    {
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Ambil dokter berdasarkan user yang login
        $user = Auth::user();
        $dokter = Dokter::where('user_id', $user->id)->first();
        
        if (!$dokter) {
            return redirect()->route('dokter.profil.index')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }
        
        // Ambil data konsultasi aktif (menunggu, terkonfirmasi, dan berlangsung)
        $konsultasiAktif = Konsultasi::where('dokter_id', $user->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Berlangsung'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->with('pasien')
            ->paginate(10, ['*'], 'aktif');
            
        // Siapkan data untuk tampilan
        $konsultasiAktifData = [];
        foreach ($konsultasiAktif as $item) {
            // Cek apakah konsultasi hari ini dan sudah waktunya
            $now = Carbon::now();
            $bisa_dimulai = false;
            
            if ($item->tanggal) {
                $konsultasiDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                $konsultasiEndTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_selesai);
                
                // Konsultasi bisa dimulai jika:
                // 1. Sudah waktunya (waktu sekarang >= waktu mulai)
                // 2. Belum melewati waktu selesai (waktu sekarang < waktu selesai)
                // 3. Status konsultasi adalah "Terkonfirmasi" atau "Berlangsung"
                $bisa_dimulai = $now->gte($konsultasiDateTime) && 
                               $now->lt($konsultasiEndTime) && 
                               in_array($item->status, ['Terkonfirmasi', 'Berlangsung']);
            }
            
            // Menghitung timestamp untuk jadwal konsultasi
            $jadwalTimestamp = null;
            if ($item->tanggal) {
                $jadwalDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                $jadwalTimestamp = $jadwalDateTime->timestamp * 1000; // Konversi ke milliseconds untuk JavaScript
            }
            
            $konsultasiAktifData[] = [
                'id' => $item->id,
                'pasien_id' => $item->pasien_id,
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
        
        // Ambil data konsultasi tidak aktif (selesai, ditolak, dibatalkan, dan terlambat)
        $konsultasiTidakAktif = Konsultasi::where('dokter_id', $user->id)
            ->whereIn('status', ['Dibatalkan', 'Terlambat'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->with('pasien')
            ->paginate(10, ['*'], 'tidak_aktif');
            
        // Siapkan data untuk tampilan
        $konsultasiTidakAktifData = [];
        foreach ($konsultasiTidakAktif as $item) {
            // Menghitung timestamp untuk jadwal konsultasi
            $jadwalTimestamp = null;
            if ($item->tanggal) {
                $jadwalDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                $jadwalTimestamp = $jadwalDateTime->timestamp * 1000; // Konversi ke milliseconds untuk JavaScript
            }
            
            $konsultasiTidakAktifData[] = [
                'id' => $item->id,
                'pasien_id' => $item->pasien_id,
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
            ];
        }
        
        return view('dokter.konsultasi.index', [
            'title' => 'Daftar Konsultasi',
            'konsultasiAktif' => $konsultasiAktifData,
            'konsultasiSelesai' => $konsultasiTidakAktifData,
            'konsultasiAktifPaginator' => $konsultasiAktif,
            'konsultasiTidakAktifPaginator' => $konsultasiTidakAktif
        ]);
    }

    public function konfirmasiKonsultasi(Konsultasi $konsultasi)
    {
        // Cek apakah konsultasi milik dokter yang login
        if ($konsultasi->dokter_id !== Auth::id()) {
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
        // Cek apakah konsultasi milik dokter yang login
        if ($konsultasi->dokter_id !== Auth::id()) {
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
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Ambil user yang login
        $user = Auth::user();
        
        // Ambil data riwayat konsultasi (selesai, ditolak, dibatalkan, dan terlambat) dengan paginasi
        $konsultasi = Konsultasi::where('dokter_id', $user->id)
            ->whereIn('status', ['Selesai', 'Ditolak', 'Dibatalkan', 'Terlambat'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->with('pasien')
            ->paginate(10);
            
        // Siapkan data untuk tampilan
        $konsultasiSelesaiData = [];
        foreach ($konsultasi as $item) {
            // Format tanggal
            $tanggalFormat = $item->tanggal ? $item->tanggal->format('d F Y') : '-';
            
            // Format jam
            $jamFormat = $item->jam_mulai . ' - ' . $item->jam_selesai;
            
            $konsultasiSelesaiData[] = [
                'id' => $item->id,
                'pasien_id' => $item->pasien_id,
                'pasien_nama' => $item->pasien->nama ?? 'Pasien',
                'pasien_gender' => $item->pasien->jenis_kelamin ?? '-',
                'pasien_usia' => $item->pasien->usia ?? '-',
                'tanggal' => $tanggalFormat,
                'tanggal_tampil' => $item->tanggal,
                'jam' => $jamFormat,
                'jam_mulai' => $item->jam_mulai,
                'jam_selesai' => $item->jam_selesai,
                'keluhan' => $item->keluhan,
                'status' => $item->status,
                'alasan_tolak' => $item->alasan_tolak,
                'alasan_batal' => $item->alasan_batal,
                'rating' => $item->rating,
                'komentar_rating' => $item->komentar_rating,
                'diagnosa' => $item->diagnosa,
                'nilai' => $item->getNilaiRataRata()
            ];
        }
        
        // Hitung statistik
        $totalSelesai = Konsultasi::where('dokter_id', $user->id)
            ->where('status', 'Selesai')
            ->count();
            
        $totalDitolak = Konsultasi::where('dokter_id', $user->id)
            ->where('status', 'Ditolak')
            ->count();
            
        $totalDibatalkan = Konsultasi::where('dokter_id', $user->id)
            ->where('status', 'Dibatalkan')
            ->count();
            
        $totalTerlambat = Konsultasi::where('dokter_id', $user->id)
            ->where('status', 'Terlambat')
            ->count();
            
        // Hitung rating rata-rata
        $ratingAvg = Konsultasi::where('dokter_id', $user->id)
            ->whereNotNull('rating')
            ->avg('rating');
        $ratingAvg = $ratingAvg ? round($ratingAvg, 1) : 0;
        
        // Hitung jumlah rating berdasarkan nilai
        $rating5 = Konsultasi::where('dokter_id', $user->id)
            ->where('rating', 5)
            ->count();
            
        $rating4 = Konsultasi::where('dokter_id', $user->id)
            ->where('rating', 4)
            ->count();
            
        $rating3 = Konsultasi::where('dokter_id', $user->id)
            ->where('rating', 3)
            ->count();
            
        $rating2 = Konsultasi::where('dokter_id', $user->id)
            ->where('rating', 2)
            ->count();
            
        $rating1 = Konsultasi::where('dokter_id', $user->id)
            ->where('rating', 1)
            ->count();
            
        $totalRating = $rating5 + $rating4 + $rating3 + $rating2 + $rating1;
        
        // Hitung nilai statistik
        $nilaiAvg = 0;
        $nilaiMax = 0;
        $ratedKonsultasi = Konsultasi::where('dokter_id', $user->id)
            ->whereNotNull('rating')
            ->get();
            
        if ($ratedKonsultasi->count() > 0) {
            $nilaiMax = $ratedKonsultasi->max('rating');
            $nilaiAvg = $ratingAvg;
        }
        
        return view('dokter.riwayat.index', [
            'title' => 'Riwayat Konsultasi',
            'konsultasiSelesai' => $konsultasiSelesaiData,
            'konsultasiPaginator' => $konsultasi,
            'totalSelesai' => $totalSelesai,
            'totalDitolak' => $totalDitolak,
            'totalDibatalkan' => $totalDibatalkan,
            'totalTerlambat' => $totalTerlambat,
            'ratingAvg' => $ratingAvg,
            'rating5' => $rating5,
            'rating4' => $rating4,
            'rating3' => $rating3,
            'rating2' => $rating2,
            'rating1' => $rating1,
            'totalRating' => $totalRating,
            'nilaiRata' => $nilaiAvg,
            'nilaiTertinggi' => $nilaiMax,
            'ratingRata' => $ratingAvg
        ]);
    }

    public function profilIndex()
    {
        // Ambil dokter berdasarkan user yang login
        $user = Auth::user();
        $dokter = Dokter::where('user_id', $user->id)->first();
        
        if (!$dokter) {
            // Jika belum ada data dokter, buat baru
            $dokter = new Dokter([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email
            ]);
            $dokter->save();
        }
        
        // Ambil data keahlian dan prestasi
        $keahlian = $dokter->keahlians ?? collect([]);
        $prestasi = $dokter->prestasis ?? collect([]);
        
        // Siapkan data untuk tampilan dalam format array
        $profil = [
            'nama' => $dokter->nama ?? '',
            'no_sip' => $dokter->no_sip ?? '',
            'no_str' => $dokter->no_str ?? '',
            'email' => $dokter->email ?? '',
            'alamat' => $dokter->alamat ?? '',
            'telepon' => $dokter->no_hp ?? '',
            'jenis_kelamin' => $dokter->jenis_kelamin ?? '',
            'tempat_lahir' => $dokter->tempat_lahir ?? '',
            'tanggal_lahir_tampil' => $dokter->tanggal_lahir ? $dokter->tanggal_lahir->format('d F Y') : '',
            'tanggal_lahir' => $dokter->tanggal_lahir ?? '',
            'foto' => $dokter->foto_url,
            'spesialisasi' => $dokter->spesialisasi ?? '',
            'sub_spesialisasi' => $dokter->sub_spesialisasi ?? '',
            'universitas' => $dokter->universitas ?? '',
            'tahun_lulus' => $dokter->tahun_lulus ?? '',
            'tempat_praktik' => $dokter->tempat_praktik ?? '',
            'rumah_sakit' => $dokter->rumah_sakit ?? '',
            'status' => $dokter->status ?? 'Aktif',
            'pengalaman' => $dokter->pengalaman ?? '',
            'keahlian' => $keahlian->pluck('nama')->toArray(),
            'prestasi' => $prestasi->toArray()
        ];
        
        return view('dokter.profil.index', [
            'title' => 'Profil Dokter',
            'dokter' => $dokter,
            'keahlian' => $keahlian,
            'prestasi' => $prestasi,
            'profil' => $profil
        ]);
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Ambil data dokter
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();

        $fotoFile = $request->file('foto');
        $relativePath = ProfilePhoto::storeUploadedAsPng($fotoFile, (int) Auth::id());

        $dokter->foto = $relativePath;
        $dokter->save();
        
        return redirect()->route('dokter.profil.index')
            ->with('success', 'Foto profil berhasil diperbarui');
    }

    public function updateInformasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_sip' => 'required|string|max:50',
            'no_str' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);
        
        // Ambil data dokter
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();
        
        // Update data dokter
        $dokter->update([
            'nama' => $request->nama,
            'no_sip' => $request->no_sip,
            'no_str' => $request->no_str,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);
        
        return redirect()->route('dokter.profil.index')
            ->with('success', 'Informasi dasar berhasil diperbarui');
    }

    public function updateAkademik(Request $request)
    {
        $request->validate([
            'spesialisasi' => 'required|string|max:100',
            'sub_spesialisasi' => 'nullable|string|max:100',
            'universitas' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:1950|max:' . date('Y'),
            'tempat_praktik' => 'required|string|max:255',
            'rumah_sakit' => 'required|string|max:255',
            'status' => 'required|string|in:Aktif,Cuti,Tidak Praktik',
            'pengalaman' => 'nullable|string',
        ]);
        
        // Ambil data dokter
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();
        
        // Update data dokter
        $dokter->update([
            'spesialisasi' => $request->spesialisasi,
            'sub_spesialisasi' => $request->sub_spesialisasi,
            'universitas' => $request->universitas,
            'tahun_lulus' => $request->tahun_lulus,
            'tempat_praktik' => $request->tempat_praktik,
            'rumah_sakit' => $request->rumah_sakit,
            'status' => $request->status,
            'pengalaman' => $request->pengalaman,
        ]);
        
        return redirect()->route('dokter.profil.index')
            ->with('success', 'Informasi profesional berhasil diperbarui');
    }

    public function updateKeahlian(Request $request)
    {
        $request->validate([
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'required|string|max:100',
        ]);
        
        // Ambil data dokter
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();
        
        // Hapus keahlian lama
        $dokter->keahlians()->delete();
        
        // Tambahkan keahlian baru
        foreach ($request->keahlian as $keahlian) {
            if (!empty(trim($keahlian))) {
                $dokter->keahlians()->create([
                    'nama' => $keahlian
                ]);
            }
        }
        
        return redirect()->route('dokter.profil.index')
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
        
        // Ambil data dokter
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();
        
        // Hapus prestasi lama
        $dokter->prestasis()->delete();
        
        // Tambahkan prestasi baru
        foreach ($request->prestasi as $index => $prestasi) {
            if (!empty(trim($prestasi))) {
                $dokter->prestasis()->create([
                    'nama' => $prestasi,
                    'tahun' => $request->tahun[$index] ?? null
                ]);
            }
        }
        
        return redirect()->route('dokter.profil.index')
            ->with('success', 'Prestasi dan penghargaan berhasil diperbarui');
    }

    public function simpanDiagnosa(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|string',
            'catatan' => 'nullable|string'
        ]);
        
        $konsultasi = Konsultasi::findOrFail($id);
        
        // Pastikan konsultasi milik dokter yang login
        if ($konsultasi->dokter_id !== Auth::id()) {
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
        
        return redirect()->route('dokter.konsultasi.index')->with('success', 'Diagnosis berhasil disimpan');
    }

    public function gantiSesiKonsultasi(Request $request, Konsultasi $konsultasi)
    {
        // Cek apakah konsultasi milik dokter yang login
        if ($konsultasi->dokter_id !== Auth::id()) {
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