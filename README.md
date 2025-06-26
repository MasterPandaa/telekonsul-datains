# Telekonsultasi Datains

Aplikasi telekonsultasi jarak jauh antara dokter dan pasien.

## Persyaratan Sistem

- PHP 8.0 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Composer
- Node.js dan NPM

## Instalasi

1. Clone repositori
2. Jalankan `composer install`
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi database
4. Jalankan `php artisan key:generate`
5. Jalankan `php artisan migrate --seed`
6. Jalankan `npm install && npm run dev`

## Fitur Utama

- Autentikasi multi-role (admin, dokter, pasien)
- Manajemen profil dokter dan pasien
- Permintaan konsultasi
- Chat room untuk telekonsultasi
- Riwayat konsultasi

## Menjalankan Update Status Konsultasi

Aplikasi ini menggunakan Laravel Scheduler untuk memperbarui status konsultasi secara otomatis. Status konsultasi akan diperbarui menjadi:
- "Terlambat" jika dokter belum masuk ke chat room 15 menit setelah waktu mulai
- "Selesai" jika sudah lewat waktu selesai dan chat room sudah dibuat

Ada beberapa cara untuk menjalankan update status konsultasi:

### 1. Menggunakan Route Publik

Aplikasi ini menyediakan route publik yang dapat diakses untuk menjalankan update status:
```
/update-konsultasi-status
```

Route ini dapat diakses melalui browser atau dipanggil melalui HTTP request.

### 2. Menjalankan Command Artisan

Untuk memperbarui status konsultasi secara manual, jalankan perintah berikut:
```
php artisan konsultasi:update-status
```

### 3. Menjalankan Auto Update Command

Untuk menjalankan update status secara otomatis dan terus menerus (setiap 5 menit), gunakan command:
```
php artisan auto:update-konsultasi-status
```

Command ini akan berjalan terus-menerus dengan interval 5 menit. Gunakan terminal terpisah atau jalankan sebagai background process.

### 4. Menggunakan Task Scheduler di Windows

Untuk menjalankan update status secara otomatis di Windows, buat task scheduler dengan langkah-langkah berikut:

1. Buka Task Scheduler Windows
2. Pilih "Create Basic Task"
3. Beri nama task "TelekonsulStatusUpdate"
4. Pilih "Daily" dan klik Next
5. Pilih waktu mulai dan klik Next
6. Pilih "Start a program" dan klik Next
7. Di bagian Program/script, masukkan path ke PHP: `C:\path\to\php.exe`
8. Di bagian Add arguments, masukkan: `D:\laragon2\laragon\www\telekonsul_datains\artisan konsultasi:update-status`
9. Klik Next dan Finish

Untuk menjalankan setiap 5 menit:
1. Setelah task dibuat, klik kanan task dan pilih Properties
2. Buka tab Triggers dan pilih Edit
3. Pilih "Daily" dan centang "Repeat task every"
4. Atur ke 5 minutes dan pilih "for a duration of: Indefinitely"
5. Klik OK dan OK lagi

### 5. Menggunakan Script update-scheduler.bat

Anda juga dapat menggunakan file batch yang telah disediakan:
```
update-scheduler.bat
```

Tambahkan shortcut file ini ke folder Startup Windows untuk menjalankannya secara otomatis saat komputer dinyalakan.

## Lisensi

Aplikasi ini dilisensikan di bawah [MIT license](LICENSE).
