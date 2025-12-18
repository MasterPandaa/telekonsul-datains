# Sistem Foto Profil - Dokumentasi

## Ringkasan
Sistem foto profil telah dibangun ulang dengan logika yang **sangat sederhana** - langsung menyimpan ke direktori `public/` tanpa menggunakan Storage facade atau symlink.

## Struktur Penyimpanan

### Lokasi File
- **Path Fisik**: `public/profile/{user_id}/profile.{ext}`
- **URL**: Langsung diakses via `asset()` → `/profile/{user_id}/profile.{ext}`
- **Tidak perlu** `php artisan storage:link`

### Format File
- Ekstensi yang didukung: jpg, jpeg, png, webp
- Ukuran maksimal: 2048 KB (2 MB)
- Nama file: Selalu `profile.{ext}` untuk konsistensi

## Helper Class: ProfilePhoto

### Metode Utama

#### 1. `ProfilePhoto::store(UploadedFile $file, int $userId): string`
Upload dan simpan foto profil baru.
- Membuat direktori `public/profile/{user_id}/` jika belum ada
- Menghapus foto lama secara otomatis (semua file `profile.*`)
- Menyimpan foto dengan nama standar `profile.{ext}`
- Return: path relatif file (`profile/{user_id}/profile.{ext}`)

**Contoh:**
```php
$path = ProfilePhoto::store($request->file('foto'), Auth::id());
$user->foto = $path;
$user->save();
```

#### 2. `ProfilePhoto::getUrl(?string $photoPath): string`
Mendapatkan URL foto untuk ditampilkan.
- Jika path kosong → return default avatar
- Jika URL eksternal → return as-is
- Jika path lokal → return URL via `asset()`
- Jika file tidak ada di `public/` → return default avatar

**Contoh:**
```php
// Di Model
public function getFotoUrlAttribute(): string
{
    return ProfilePhoto::getUrl($this->foto);
}

// Di View
<img src="{{ $user->foto_url }}" alt="Profile">
```

#### 3. `ProfilePhoto::delete(int $userId): void`
Menghapus semua foto profil user.
- Menghapus semua file `profile.*` di `public/profile/{user_id}/`
- Direktori tetap ada untuk upload berikutnya

#### 4. `ProfilePhoto::getDefaultUrl(): string`
Mendapatkan URL avatar default (SVG placeholder dengan icon user).

## Implementasi di Model

### Dokter, Dosen, Pasien
Semua model menggunakan accessor yang sama:

```php
public function getFotoUrlAttribute(): string
{
    return ProfilePhoto::getUrl($this->foto);
}
```

## Implementasi di Controller

### Upload Foto
```php
if ($request->hasFile('foto')) {
    $path = ProfilePhoto::store($request->file('foto'), $userId);
    $model->foto = $path;
    $model->save();
}
```

### Hapus Foto
```php
ProfilePhoto::delete($userId);
$model->foto = null;
$model->save();
```

## Implementasi di View

### Menampilkan Foto
```blade
<img src="{{ $user->foto_url }}" alt="{{ $user->nama }}" class="w-10 h-10 rounded-full">
```

### Fallback Manual (jika diperlukan)
```blade
<img src="{{ $user->foto_url ?? \App\Support\ProfilePhoto::getDefaultUrl() }}" alt="User">
```

## Titik-titik Implementasi

### 1. Form Edit Data
- ✅ Admin: Edit Dokter (`admin/dokter/edit`)
- ✅ Admin: Edit Dosen (`admin/dosen/edit`)
- ✅ Admin: Edit Pasien (`admin/pasien/edit`)
- ✅ Dokter: Profil Saya (`dokter/profil`)
- ✅ Dosen: Profil Saya (`dosen/profil`)
- ✅ Pasien: Profil Saya (`pasien/profil`)

### 2. Tabel Data
- ✅ Admin: Data Dokter
- ✅ Admin: Data Dosen
- ✅ Admin: Data Pasien
- ✅ Dosen: Rekap Data Konsultasi
- ✅ Dosen: Penilaian Konsultasi

### 3. Chat & Konsultasi
- ✅ Chat Room (dokter & pasien view)
- ✅ Daftar Konsultasi Dokter
- ✅ Daftar Konsultasi Pasien
- ✅ Buat Konsultasi (pilih dokter)
- ✅ Riwayat Konsultasi

### 4. Dashboard
- ✅ Dashboard Dokter
- ✅ Dashboard Dosen
- ✅ Dashboard Pasien

### 5. Navbar/Header
- ✅ Navbar Dokter
- ✅ Navbar Dosen
- ✅ Header Pasien
- ✅ Sidebar Dokter
- ✅ Sidebar Dosen

### 6. Chatbot AI
- ✅ Chatbot Pasien

### 7. Menu Profil
- ✅ Profil Dokter
- ✅ Profil Dosen
- ✅ Profil Pasien

## Keuntungan Sistem Baru

1. **Sangat Sederhana**: Hanya 4 metode utama, langsung ke `public/`
2. **Tidak Perlu Storage Link**: Tidak perlu `php artisan storage:link`
3. **Konsisten**: Semua role menggunakan logika yang sama
4. **Railway-Ready**: Kompatibel dengan persistent volume di Railway
5. **Auto-Cleanup**: Foto lama otomatis terhapus saat upload baru
6. **Fallback Otomatis**: Selalu menampilkan avatar default jika foto tidak ada
7. **Type-Safe**: Return type yang jelas untuk semua metode
8. **Direct Access**: File langsung accessible via URL tanpa symlink

## Migrasi dari Sistem Lama

Tidak perlu migrasi data. Sistem baru kompatibel dengan:
- Path lama yang tersimpan di database
- URL eksternal
- Path kosong/null

## Testing

Untuk memastikan sistem berfungsi:

1. Upload foto di form profil
2. Verifikasi foto muncul di navbar/header
3. Verifikasi foto muncul di tabel data
4. Verifikasi foto muncul di chat room
5. Upload foto baru → foto lama harus terhapus
6. Hapus foto → harus muncul avatar default
