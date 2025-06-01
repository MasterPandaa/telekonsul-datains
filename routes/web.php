<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\MahasiswaPageController;
use App\Http\Controllers\PasienPageController;
use App\Http\Controllers\PasienProfilController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\HealsAiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\KonsultasiController;
use App\Http\Controllers\PasienPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MahasiswaPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// API Routes
Route::middleware('auth')->prefix('api')->group(function() {
    Route::get('/konsultasi/{id}', [KonsultasiController::class, 'getDetail']);
});

// Tambahkan route publik untuk update status konsultasi (tidak perlu login)
Route::get('/update-konsultasi-status', [AdminController::class, 'updateKonsultasiStatus'])->name('update-konsultasi-status');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Admin menu
    Route::prefix('admin')->name('admin.')->middleware('can:isAdmin')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('dosen', DosenController::class);
        Route::resource('pasien', PasienController::class);
        
        // Log routes
        Route::get('log/database', [LogController::class, 'database'])->name('log.database');
        Route::get('log/system', [LogController::class, 'system'])->name('log.system');
        Route::delete('log/destroy', [LogController::class, 'destroy'])->name('log.destroy');
        Route::delete('log/clear', [LogController::class, 'clear'])->name('log.clear');
        
        // Konsultasi status update
        Route::get('konsultasi/update-status', [AdminController::class, 'updateKonsultasiStatus'])->name('konsultasi.update-status');
    });
});

// Mahasiswa Routes
Route::middleware(['auth', 'can:isMahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard Mahasiswa
    Route::get('/dashboard', [MahasiswaPageController::class, 'dashboard'])->name('dashboard');
    
    // Profil Mahasiswa
    Route::get('/profil', [MahasiswaPageController::class, 'profilIndex'])->name('profil.index');
    Route::post('/profil/update-foto', [MahasiswaPageController::class, 'updateFoto'])->name('profil.update-foto');
    Route::post('/profil/update-informasi', [MahasiswaPageController::class, 'updateInformasi'])->name('profil.update-informasi');
    Route::post('/profil/update-akademik', [MahasiswaPageController::class, 'updateAkademik'])->name('profil.update-akademik');
    Route::post('/profil/update-keahlian', [MahasiswaPageController::class, 'updateKeahlian'])->name('profil.update-keahlian');
    Route::post('/profil/update-prestasi', [MahasiswaPageController::class, 'updatePrestasi'])->name('profil.update-prestasi');
    
    // Konsultasi
    Route::prefix('konsultasi')->name('konsultasi.')->group(function() {
        Route::get('/', [MahasiswaPageController::class, 'konsultasiIndex'])->name('index');
        Route::post('/{konsultasi}/diagnosa', [MahasiswaPageController::class, 'simpanDiagnosa'])->name('diagnosa');
    });
    
    // Riwayat & Nilai
    Route::get('/riwayat', [MahasiswaPageController::class, 'riwayatIndex'])->name('riwayat.index');
    
    // Quiz
    Route::prefix('quiz')->name('quiz.')->group(function() {
        Route::get('/', [MahasiswaPageController::class, 'quizIndex'])->name('index');
        Route::get('/{id}', [MahasiswaPageController::class, 'quizShow'])->name('show');
    });

    Route::post('/konsultasi/{konsultasi}/konfirmasi', [MahasiswaPageController::class, 'konfirmasiKonsultasi'])->name('konsultasi.konfirmasi');
    Route::post('/konsultasi/{konsultasi}/tolak', [MahasiswaPageController::class, 'tolakKonsultasi'])->name('konsultasi.tolak');
    Route::post('/konsultasi/{konsultasi}/ganti-sesi', [MahasiswaPageController::class, 'gantiSesiKonsultasi'])->name('konsultasi.gantiSesi');
    
    // Rute untuk pengaturan password
    Route::get('/pengaturan', [MahasiswaPasswordController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [MahasiswaPasswordController::class, 'update'])->name('pengaturan.update');
});

// Pasien Routes
Route::middleware(['auth', 'can:isPasien'])->prefix('pasien')->name('pasien.')->group(function () {
    // Dashboard Pasien
    Route::get('/dashboard', [PasienPageController::class, 'dashboard'])->name('dashboard');
    
    // Profil Pasien
    Route::get('/profil', [PasienPageController::class, 'profilIndex'])->name('profil.index');
    
    // Chatbot AI
    Route::get('/chatbot', [PasienPageController::class, 'chatbotIndex'])->name('chatbot.index');
    Route::post('/chatbot/healsai', [HealsAiController::class, 'getResponse'])->name('chatbot.healsai');
    
    // Konsultasi
    Route::prefix('konsultasi')->name('konsultasi.')->group(function() {
        Route::get('/', [PasienPageController::class, 'konsultasiIndex'])->name('index');
        Route::get('/create', [PasienPageController::class, 'konsultasiCreate'])->name('create');
        Route::post('/', [PasienPageController::class, 'konsultasiStore'])->name('store');
        Route::post('/{konsultasi}/batalkan', [PasienPageController::class, 'batalkanKonsultasi'])->name('batalkan');
        Route::post('/{konsultasi}/terima-ganti-sesi', [PasienPageController::class, 'terimaGantiSesi'])->name('terimaGantiSesi');
        Route::post('/{konsultasi}/tolak-ganti-sesi', [PasienPageController::class, 'tolakGantiSesi'])->name('tolakGantiSesi');
        Route::post('/{konsultasi}/rating', [PasienPageController::class, 'berikanRating'])->name('rating');
    });
    
    // Riwayat
    Route::get('/riwayat', [PasienPageController::class, 'riwayatIndex'])->name('riwayat.index');

    // Rute untuk pengaturan password
    Route::get('/pengaturan', [PasienPasswordController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PasienPasswordController::class, 'update'])->name('pengaturan.update');
});

// Tambahkan route untuk profil pasien
Route::middleware(['auth', 'can:isPasien'])->group(function () {
    // Profil pasien
    Route::get('/pasien/profil', [PasienProfilController::class, 'index'])->name('pasien.profil.index');
    Route::post('/pasien/profil/update-informasi', [PasienProfilController::class, 'updateInformasiDasar'])->name('pasien.profil.update-informasi');
    Route::post('/pasien/profil/update-medis', [PasienProfilController::class, 'updateInformasiMedis'])->name('pasien.profil.update-medis');
    Route::post('/pasien/profil/upload-foto', [PasienProfilController::class, 'uploadFoto'])->name('pasien.profil.upload-foto');
});

// Chat Room Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{konsultasi}', [ChatRoomController::class, 'create'])->name('chat.create');
    Route::post('/chat/{chatRoom}/send', [ChatRoomController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{chatRoom}/messages', [ChatRoomController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{chatRoom}/end', [ChatRoomController::class, 'endChat'])->name('chat.end');
});

// Rute untuk notifikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifications/get-latest', [NotificationController::class, 'getLatest'])->name('notifications.getLatest');
});

require __DIR__.'/auth.php';
