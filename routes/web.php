<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ChatbotSettingController;
use App\Http\Controllers\DokterPageController;
use App\Http\Controllers\PasienPageController;
use App\Http\Controllers\PasienProfilController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\HealsAiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\KonsultasiController;
use App\Http\Controllers\PasienPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\DokterPasswordController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPasswordController;

Route::get('/', function () {
    return redirect()->route('login');
});

/**
 * Fallback handler untuk file di storage/public ketika symlink tidak tersedia.
 */
Route::get('storage/{path}', function (string $path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path);
})->where('path', '.*');

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
        Route::resource('dokter', DokterController::class);
        Route::resource('pasien', PasienController::class);
        Route::resource('dosen', AdminDosenController::class);
        
        // Chatbot API settings
        Route::get('chatbot/settings', [ChatbotSettingController::class, 'index'])->name('chatbot.settings');
        Route::post('chatbot/settings', [ChatbotSettingController::class, 'update'])->name('chatbot.settings.update');
        
        // Log routes
        Route::get('log/database', [LogController::class, 'database'])->name('log.database');
        Route::get('log/system', [LogController::class, 'system'])->name('log.system');
        Route::delete('log/destroy', [LogController::class, 'destroy'])->name('log.destroy');
        Route::delete('log/clear', [LogController::class, 'clear'])->name('log.clear');
        Route::get('log/activity-data', [LogController::class, 'getUserActivityData'])->name('log.activity-data');
        // Generate sample logs (for testing)
        Route::get('log/generate-samples', [LogController::class, 'generateSamples'])->name('log.generate-samples');
        
        // Dokter utility routes
        Route::get('dokter/check-sip', [DokterController::class, 'checkSip'])->name('dokter.check-sip');
        Route::get('dokter/check-str', [DokterController::class, 'checkStr'])->name('dokter.check-str');
        
        // Konsultasi status update
        Route::get('konsultasi/update-status', [AdminController::class, 'updateKonsultasiStatus'])->name('konsultasi.update-status');
    });
});

// Dokter Routes
Route::middleware(['auth', 'can:isDokter'])->prefix('dokter')->name('dokter.')->group(function () {
    // Dashboard Dokter
    Route::get('/dashboard', [DokterPageController::class, 'dashboard'])->name('dashboard');
    
    // Profil Dokter
    Route::get('/profil', [DokterPageController::class, 'profilIndex'])->name('profil.index');
    Route::post('/profil/update-foto', [DokterPageController::class, 'updateFoto'])->name('profil.update-foto');
    Route::post('/profil/update-informasi', [DokterPageController::class, 'updateInformasi'])->name('profil.update-informasi');
    Route::post('/profil/update-akademik', [DokterPageController::class, 'updateAkademik'])->name('profil.update-akademik');
    Route::post('/profil/update-keahlian', [DokterPageController::class, 'updateKeahlian'])->name('profil.update-keahlian');
    Route::post('/profil/update-prestasi', [DokterPageController::class, 'updatePrestasi'])->name('profil.update-prestasi');
    
    // Konsultasi
    Route::prefix('konsultasi')->name('konsultasi.')->group(function() {
        Route::get('/', [DokterPageController::class, 'konsultasiIndex'])->name('index');
        Route::post('/{konsultasi}/diagnosa', [DokterPageController::class, 'simpanDiagnosa'])->name('diagnosa');
    });
    
    // Riwayat & Nilai
    Route::get('/riwayat', [DokterPageController::class, 'riwayatIndex'])->name('riwayat.index');

    Route::post('/konsultasi/{konsultasi}/konfirmasi', [DokterPageController::class, 'konfirmasiKonsultasi'])->name('konsultasi.konfirmasi');
    Route::post('/konsultasi/{konsultasi}/tolak', [DokterPageController::class, 'tolakKonsultasi'])->name('konsultasi.tolak');
    Route::post('/konsultasi/{konsultasi}/ganti-sesi', [DokterPageController::class, 'gantiSesiKonsultasi'])->name('konsultasi.gantiSesi');
    
    // Rute untuk pengaturan password
    Route::get('/pengaturan', [DokterPasswordController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [DokterPasswordController::class, 'update'])->name('pengaturan.update');
});

// Dosen Routes
Route::middleware(['auth', 'can:isDosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard Dosen
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    
    // Penilaian Konsultasi
    Route::prefix('penilaian')->name('penilaian.')->group(function() {
        Route::get('/', [DosenController::class, 'penilaianIndex'])->name('index');
        Route::get('/{id}', [DosenController::class, 'penilaianShow'])->name('show');
        Route::post('/{id}', [DosenController::class, 'penilaianStore'])->name('store');
    });
    
    // Rekap Data
    Route::prefix('rekap')->name('rekap.')->group(function() {
        Route::get('/', [DosenController::class, 'rekapIndex'])->name('index');
        Route::get('/dokter/{id}', [DosenController::class, 'rekapDokter'])->name('dokter');
    });
    
    // Profil Dosen
    Route::prefix('profil')->name('profil.')->group(function() {
        Route::get('/', [DosenController::class, 'profilIndex'])->name('index');
        Route::post('/update-foto', [DosenController::class, 'updateFoto'])->name('update-foto');
        Route::post('/update-informasi', [DosenController::class, 'updateInformasi'])->name('update-informasi');
    });
    
    // Pengaturan Password
    Route::get('/pengaturan', [DosenPasswordController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [DosenPasswordController::class, 'update'])->name('pengaturan.update');
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
Route::middleware(['auth', 'verified'])->group(function () {
    require __DIR__.'/admin.php';
    require __DIR__.'/chat.php';
    require __DIR__.'/dokter.php';
    require __DIR__.'/dosen.php';
    // Profil pasien
    Route::get('/pasien/profil', [PasienProfilController::class, 'index'])->name('pasien.profil.index');
    Route::post('/pasien/profil/update-informasi', [PasienProfilController::class, 'updateInformasiDasar'])->name('pasien.profil.update-informasi');
    Route::post('/pasien/profil/update-medis', [PasienProfilController::class, 'updateInformasiMedis'])->name('pasien.profil.update-medis');
    Route::post('/pasien/profil/upload-foto', [PasienProfilController::class, 'uploadFoto'])->name('pasien.profil.upload-foto');
});

// Chat Room Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{konsultasi}', [ChatRoomController::class, 'create'])->name('chat.create');
    Route::get('/chat/room/{chatRoom}', [ChatRoomController::class, 'viewRoom'])->name('chat.room');
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
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
});

require __DIR__.'/auth.php';
