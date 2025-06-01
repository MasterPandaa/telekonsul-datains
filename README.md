<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Telekonsultasi Datains

Aplikasi telekonsultasi untuk mahasiswa dan pasien.

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

- Autentikasi multi-role (admin, mahasiswa, pasien)
- Manajemen profil mahasiswa dan pasien
- Permintaan konsultasi
- Chat room untuk telekonsultasi
- Riwayat konsultasi

## Menjalankan Update Status Konsultasi

Aplikasi ini menggunakan Laravel Scheduler untuk memperbarui status konsultasi secara otomatis. Status konsultasi akan diperbarui menjadi:
- "Terlambat" jika mahasiswa belum masuk ke chat room 15 menit setelah waktu mulai
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
