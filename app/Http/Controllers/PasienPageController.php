<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Konsultasi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Services\KonsultasiService;
use App\Services\NotificationService;

class PasienPageController extends Controller
{
    protected $konsultasiService;
    
    public function __construct(KonsultasiService $konsultasiService)
    {
        $this->konsultasiService = $konsultasiService;
    }

    public function dashboard()
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return view('pasien.dashboard', [
                'title' => 'Dashboard Pasien',
                'konsultasiSelesai' => 0,
                'jadwalMendatang' => 0,
                'interaksiChatbot' => 0,
                'tingkatKepuasan' => 0,
                'konsultasiMendatang' => [],
                'belumLengkap' => true,
                'persenKonsultasi' => 0,
                'persenJadwal' => 0,
                'persenChatbot' => 0,
                'keteranganKepuasan' => 'Belum ada rating'
            ]);
        }
        
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Tanggal untuk perhitungan perbandingan
        $today = Carbon::today();
        $oneMonthAgo = $today->copy()->subMonth();
        $twoMonthsAgo = $today->copy()->subMonths(2);
        $oneWeekAgo = $today->copy()->subWeek();
        $twoWeeksAgo = $today->copy()->subWeeks(2);
        
        // Hitung jumlah konsultasi selesai bulan ini
        $konsultasiSelesaiBulanIni = Konsultasi::where('pasien_id', $pasien->id)
            ->where('status', 'Selesai')
            ->whereDate('tanggal', '>=', $oneMonthAgo)
            ->count();
        
        // Hitung jumlah konsultasi selesai bulan sebelumnya
        $konsultasiSelesaiBulanLalu = Konsultasi::where('pasien_id', $pasien->id)
            ->where('status', 'Selesai')
            ->whereDate('tanggal', '>=', $twoMonthsAgo)
            ->whereDate('tanggal', '<', $oneMonthAgo)
            ->count();
        
        // Hitung persentase perubahan konsultasi selesai
        $persenKonsultasi = 0;
        if ($konsultasiSelesaiBulanLalu > 0) {
            $persenKonsultasi = round((($konsultasiSelesaiBulanIni - $konsultasiSelesaiBulanLalu) / $konsultasiSelesaiBulanLalu) * 100);
        }
        
        // Total konsultasi selesai (untuk tampilan)
        $konsultasiSelesai = Konsultasi::where('pasien_id', $pasien->id)
            ->where('status', 'Selesai')
            ->count();
        
        // Hitung jumlah jadwal mendatang minggu ini
        $jadwalMendatangMingguIni = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->whereDate('tanggal', '>=', $today)
            ->whereDate('tanggal', '<=', $today->copy()->addDays(7))
            ->count();
        
        // Hitung jumlah jadwal mendatang minggu lalu
        $jadwalMendatangMingguLalu = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->whereDate('tanggal', '>=', $oneWeekAgo)
            ->whereDate('tanggal', '<', $today)
            ->count();
        
        // Hitung persentase perubahan jadwal mendatang
        $persenJadwal = 0;
        if ($jadwalMendatangMingguLalu > 0) {
            $persenJadwal = round((($jadwalMendatangMingguIni - $jadwalMendatangMingguLalu) / $jadwalMendatangMingguLalu) * 100);
        }
        
        // Total jadwal mendatang (untuk tampilan)
        $jadwalMendatang = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->count();
        
        // Ambil data jadwal konsultasi mendatang untuk ditampilkan di tabel
        $konsultasiMendatang = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->with('mahasiswa')
            ->take(3) // Ambil 3 jadwal terdekat
            ->get();
        
        // Hitung rata-rata rating (tingkat kepuasan)
        $avgRating = Konsultasi::where('pasien_id', $pasien->id)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;
        $tingkatKepuasan = number_format($avgRating, 1);
        
        // Tentukan keterangan tingkat kepuasan
        $keteranganKepuasan = 'Belum ada rating';
        if ($avgRating > 0) {
            if ($avgRating >= 4.5) {
                $keteranganKepuasan = 'Sangat Baik';
            } elseif ($avgRating >= 4.0) {
                $keteranganKepuasan = 'Baik';
            } elseif ($avgRating >= 3.0) {
                $keteranganKepuasan = 'Cukup';
            } else {
                $keteranganKepuasan = 'Perlu Ditingkatkan';
            }
        }
        
        // Default interaksi chatbot (akan diperbarui oleh JavaScript)
        $interaksiChatbot = 0;
        
        // Coba hitung interaksi dari ChatMessage jika ada
        try {
            $interaksiChatbot = \App\Models\ChatMessage::where('user_id', $user->id)->count();
        } catch (\Exception $e) {
            // Jika error, gunakan nilai default
            $interaksiChatbot = 0;
        }
        
        // Default persentase perubahan interaksi chatbot
        $persenChatbot = 25;
        
        return view('pasien.dashboard', [
            'title' => 'Dashboard Pasien',
            'konsultasiSelesai' => $konsultasiSelesai,
            'jadwalMendatang' => $jadwalMendatang,
            'interaksiChatbot' => $interaksiChatbot,
            'tingkatKepuasan' => $tingkatKepuasan,
            'persenKonsultasi' => $persenKonsultasi,
            'persenJadwal' => $persenJadwal,
            'persenChatbot' => $persenChatbot,
            'konsultasiMendatang' => $konsultasiMendatang,
            'belumLengkap' => false,
            'keteranganKepuasan' => $keteranganKepuasan
        ]);
    }

    public function konsultasiIndex()
    {
        // Jalankan update status konsultasi terlebih dahulu
        $this->konsultasiService->updateStatus();
        
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->route('pasien.profil.index')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }
        
        // Ambil data konsultasi aktif (menunggu dan terkonfirmasi)
        $konsultasiAktif = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->with('mahasiswa')
            ->get();
            
        // Ambil data riwayat konsultasi (selesai, dibatalkan, ditolak, dan terlambat)
        $riwayatKonsultasi = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Selesai', 'Dibatalkan', 'Ditolak', 'Terlambat'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->with('mahasiswa')
            ->take(5)
            ->get();
        
        return view('pasien.konsultasi.index', [
            'title' => 'Konsultasi Saya',
            'konsultasiAktif' => $konsultasiAktif,
            'riwayatKonsultasi' => $riwayatKonsultasi
        ]);
    }

    public function konsultasiCreate()
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->route('pasien.profil.index')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }
        
        // Daftar mahasiswa untuk dipilih
        $mahasiswa = User::where('role', 'mahasiswa')
            ->with('mahasiswa')
            ->get();
        
        // Semua slot jam konsultasi
        $semua_jam = [
            '08:00' => '08:00 - 08:15',
            '08:15' => '08:15 - 08:30',
            '08:30' => '08:30 - 08:45',
            '08:45' => '08:45 - 09:00',
            '09:00' => '09:00 - 09:15',
            '09:15' => '09:15 - 09:30',
            '09:30' => '09:30 - 09:45',
            '09:45' => '09:45 - 10:00',
            '10:00' => '10:00 - 10:15',
            '10:15' => '10:15 - 10:30',
            '10:30' => '10:30 - 10:45',
            '10:45' => '10:45 - 11:00',
            '11:00' => '11:00 - 11:15',
            '11:15' => '11:15 - 11:30',
            '11:30' => '11:30 - 11:45',
            '11:45' => '11:45 - 12:00',
            '13:00' => '13:00 - 13:15',
            '13:15' => '13:15 - 13:30',
            '13:30' => '13:30 - 13:45',
            '13:45' => '13:45 - 14:00',
            '14:00' => '14:00 - 14:15',
            '14:15' => '14:15 - 14:30',
            '14:30' => '14:30 - 14:45',
            '14:45' => '14:45 - 15:00',
            '15:00' => '15:00 - 15:15',
            '15:15' => '15:15 - 15:30',
            '15:30' => '15:30 - 15:45',
            '15:45' => '15:45 - 16:00',
        ];
        
        // Inisialisasi tanggal mulai bisa memilih konsultasi (default: hari ini)
        $today = Carbon::today();
        $tanggal_mulai = $today->format('Y-m-d');
        $jam_tersedia = $semua_jam;
        
        // Cek waktu saat ini
        $now = Carbon::now();
        
        // Jam kerja klinik
        $jamBuka = '08:00';
        $jamTutup = '16:00';
        
        // Jam pertama hari ini
        $firstSlotToday = $today->copy()->setTimeFromTimeString($jamBuka . ':00');
        
        // Jam terakhir hari ini
        $lastSlotToday = $today->copy()->setTimeFromTimeString($jamTutup . ':00');
        
        // Jika waktu saat ini sebelum jam buka klinik (sebelum jam 8 pagi),
        // tampilkan semua slot hari ini karena klinik belum buka
        if ($now->lt($firstSlotToday)) {
            // Klinik belum buka, semua slot tersedia untuk hari ini
            $jam_tersedia = $semua_jam;
        } 
        // Jika waktu saat ini setelah jam tutup klinik (setelah jam 4 sore),
        // jadwal hanya tersedia mulai besok
        else if ($now->gt($lastSlotToday)) {
            // Klinik sudah tutup, jadwal hanya tersedia besok
            $tomorrow = $today->copy()->addDay();
            $tanggal_mulai = $tomorrow->format('Y-m-d');
            $jam_tersedia = $semua_jam;
            session()->flash('warning', 'Klinik sudah tutup hari ini. Jadwal konsultasi hanya tersedia mulai besok.');
        } 
        // Jika dalam jam operasional klinik, filter slot yang sudah lewat
        else {
            // Tambahkan buffer 15 menit
            $currentTime = $now->copy()->addMinutes(15);
            $currentHour = $currentTime->format('H:i');
            
            // Filter jam tersedia yang belum lewat
            $jam_tersedia = array_filter($semua_jam, function($key) use ($currentHour) {
                return $key >= $currentHour;
            }, ARRAY_FILTER_USE_KEY);
            
            // Jika semua slot waktu terfilter habis, jadwal hanya tersedia besok
            if (empty($jam_tersedia)) {
                $tomorrow = $today->copy()->addDay();
                $tanggal_mulai = $tomorrow->format('Y-m-d');
                $jam_tersedia = $semua_jam;
                session()->flash('warning', 'Semua slot waktu hari ini sudah lewat. Jadwal konsultasi hanya tersedia mulai besok.');
            }
        }
        
        // Ambil data jadwal yang sudah terisi (status Menunggu atau Terkonfirmasi)
        // untuk mahasiswa yang tersedia selama 7 hari ke depan
        $startDate = $today->format('Y-m-d');
        $endDate = $today->copy()->addDays(7)->format('Y-m-d');
        
        $bookedSlots = Konsultasi::whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get(['tanggal', 'jam_mulai', 'mahasiswa_id'])
            ->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d');
            });
        
        // Informasi slot yang sudah dibooking untuk tampilan
        $jadwalTerisi = [];
        
        foreach ($bookedSlots as $date => $slots) {
            foreach ($slots as $slot) {
                $jadwalTerisi[$date][$slot->jam_mulai][] = $slot->mahasiswa_id;
            }
        }
        
        return view('pasien.konsultasi.create', [
            'title' => 'Buat Permintaan Konsultasi',
            'mahasiswa' => $mahasiswa,
            'tanggal_mulai' => $tanggal_mulai,
            'jam_tersedia' => $jam_tersedia,
            'pasien' => $pasien,
            'jadwalTerisi' => $jadwalTerisi
        ]);
    }

    public function konsultasiStore(Request $request)
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->route('pasien.profil.index')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }
        
        // Validasi request
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'keluhan' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);
        
        // Jam kerja klinik
        $jamBuka = '08:00';
        $jamTutup = '16:00';
        
        // Validasi waktu konsultasi tidak boleh di masa lalu
        $now = Carbon::now();
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();
        
        // Cek apakah tanggal yang dipilih adalah hari ini
        $selectedDate = Carbon::parse($validated['tanggal']);
        $isToday = $selectedDate->isSameDay($today);
        
        // Jika hari ini, dan waktu sekarang sudah lewat jam tutup klinik,
        // tolak pemesanan untuk hari ini
        if ($isToday) {
            $lastSlotToday = $today->copy()->setTimeFromTimeString($jamTutup . ':00');
            
            if ($now->gt($lastSlotToday)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['tanggal' => 'Klinik sudah tutup hari ini. Silakan pilih tanggal besok atau setelahnya.']);
            }
            
            // Jika waktu konsultasi untuk hari ini sudah lewat
            $konsultasiDateTime = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam_mulai'] . ':00');
            
            if ($konsultasiDateTime->isPast()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jam_mulai' => 'Waktu konsultasi tidak boleh sudah lewat. Silakan pilih waktu minimal 15 menit dari sekarang.']);
            }
            
            // Validasi minimal 15 menit dari sekarang
            $currentTimePlus15 = $now->copy()->addMinutes(15);
            
            if ($konsultasiDateTime->lt($currentTimePlus15)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jam_mulai' => 'Waktu konsultasi minimal 15 menit dari sekarang.']);
            }
        }
        
        // Cek apakah jadwal sudah terisi untuk mahasiswa dan waktu yang sama
        $existingConsultation = Konsultasi::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('jam_mulai', $validated['jam_mulai'] . ':00')
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi'])
            ->first();
            
        if ($existingConsultation) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jam_mulai' => 'Jadwal konsultasi pada waktu tersebut sudah terisi. Silakan pilih waktu lain.']);
        }
        
        // Tentukan jam selesai (15 menit setelah jam mulai)
        $jam_mulai_obj = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $jam_selesai = $jam_mulai_obj->copy()->addMinutes(15)->format('H:i:s');
        
        // Simpan data konsultasi ke database
        $konsultasi = new Konsultasi();
        $konsultasi->pasien_id = $pasien->id;
        $konsultasi->mahasiswa_id = $validated['mahasiswa_id'];
        $konsultasi->tanggal = $validated['tanggal'];
        $konsultasi->jam_mulai = $validated['jam_mulai'] . ':00';
        $konsultasi->jam_selesai = $jam_selesai;
        $konsultasi->keluhan = $validated['keluhan'];
        $konsultasi->keterangan = $validated['keterangan'];
        $konsultasi->status = 'Menunggu';
        $konsultasi->save();
        
        return redirect()->route('pasien.konsultasi.index')
            ->with('success', 'Permintaan konsultasi berhasil dibuat dan menunggu konfirmasi.');
    }

    public function riwayatIndex()
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien) {
            return redirect()->route('pasien.profil.index')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }
        
        // Ambil semua riwayat konsultasi
        $riwayatKonsultasi = Konsultasi::where('pasien_id', $pasien->id)
            ->whereIn('status', ['Selesai', 'Dibatalkan', 'Ditolak'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->with('mahasiswa')
            ->paginate(10);
        
        return view('pasien.riwayat.index', [
            'title' => 'Riwayat Konsultasi',
            'riwayatKonsultasi' => $riwayatKonsultasi
        ]);
    }

    public function profilIndex()
    {
        return view('pasien.profil.index', [
            'title' => 'Profil Saya'
        ]);
    }

    public function chatbotIndex()
    {
        return view('pasien.chatbot.index', [
            'title' => 'Chatbot AI'
        ]);
    }

    public function batalkanKonsultasi(Request $request, Konsultasi $konsultasi)
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien || $konsultasi->pasien_id !== $pasien->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        
        // Cek apakah status masih menunggu
        if ($konsultasi->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Konsultasi tidak dapat dibatalkan');
        }
        
        // Update status menjadi dibatalkan dan simpan alasan pembatalan
        $konsultasi->update([
            'status' => 'Dibatalkan',
            'alasan_batal' => $request->alasan_batal
        ]);
        
        return redirect()->back()->with('success', 'Konsultasi berhasil dibatalkan');
    }
    
    public function terimaGantiSesi(Konsultasi $konsultasi)
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien || $konsultasi->pasien_id !== $pasien->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        
        // Cek apakah status pergantian sesi
        if ($konsultasi->status !== 'Pergantian Sesi') {
            return redirect()->back()->with('error', 'Status konsultasi tidak valid');
        }
        
        // Update konsultasi dengan jadwal baru dan status terkonfirmasi
        $konsultasi->update([
            'status' => 'Terkonfirmasi',
            'tanggal' => $konsultasi->tanggal_baru,
            'jam_mulai' => $konsultasi->jam_mulai_baru,
            'jam_selesai' => $konsultasi->jam_selesai_baru
        ]);
        
        return redirect()->back()->with('success', 'Pergantian sesi konsultasi berhasil diterima');
    }
    
    public function tolakGantiSesi(Konsultasi $konsultasi)
    {
        // Ambil data pasien berdasarkan user yang login
        $user = Auth::user();
        $pasien = Pasien::where('email', $user->email)->first();
        
        if (!$pasien || $konsultasi->pasien_id !== $pasien->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
            
        // Cek apakah status pergantian sesi
        if ($konsultasi->status !== 'Pergantian Sesi') {
            return redirect()->back()->with('error', 'Status konsultasi tidak valid');
        }
        
        // Update status menjadi dibatalkan
        $konsultasi->update([
            'status' => 'Dibatalkan',
            'alasan_batal' => 'Permintaan pergantian sesi ditolak oleh pasien'
        ]);
        
        return redirect()->back()->with('success', 'Pergantian sesi konsultasi berhasil ditolak');
    }

    public function berikanRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar_rating' => 'nullable|string|max:255'
        ]);
        
        $konsultasi = Konsultasi::findOrFail($id);
        
        // Pastikan konsultasi milik pasien yang login
        if ($konsultasi->pasien_id !== Auth::user()->pasien->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke konsultasi ini');
        }
        
        // Pastikan konsultasi sudah selesai
        if ($konsultasi->status !== 'Selesai') {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan rating untuk konsultasi yang sudah selesai');
        }
        
        // Update rating
        $konsultasi->rating = $request->rating;
        $konsultasi->komentar_rating = $request->komentar_rating;
        $konsultasi->save();
        
        // Buat notifikasi untuk mahasiswa
        $notificationService = app(NotificationService::class);
        $notificationService->createRatingBaruNotification($konsultasi);
        
        return redirect()->route('pasien.konsultasi.index')->with('success', 'Rating berhasil diberikan');
    }
} 