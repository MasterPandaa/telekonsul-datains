# Dokumentasi Integrasi HealsAI

## Pengenalan

Sistem chatbot AI pada aplikasi Telekonsultasi kini sudah terintegrasi dengan HealsAI untuk memberikan pengalaman chatbot yang lebih cerdas dan responsif. Dokumen ini memberikan panduan cara mengaktifkan dan menggunakan fitur HealsAI.

## Prasyarat

1. **API Key**: Pastikan Anda memiliki API key yang valid
2. **PHP 8.x**: Pastikan server menggunakan PHP 8.0 atau lebih tinggi
3. **Koneksi Internet**: Sistem memerlukan koneksi internet yang stabil untuk berkomunikasi dengan API

## Konfigurasi

### Pengaturan .env

Tambahkan baris berikut pada file `.env` aplikasi:

```
# HEALSAI CONFIG
HEALSAI_API_KEY=your-api-key-here
```

Ganti `your-api-key-here` dengan API key yang sudah Anda dapatkan.

## Fitur Chatbot HealsAI

### Kemampuan yang Didukung

1. **Riwayat Percakapan**: Chatbot menyimpan hingga 10 pesan terakhir untuk memberikan konteks yang berkelanjutan
2. **Format Respons**: Respons diformat dengan dukungan teks tebal, miring, dan daftar bullet/nomor
3. **Animasi Loading**: Indikator mengetik yang dinamis saat menunggu respons dari API
4. **Penanganan Error**: Pesan error yang jelas jika terjadi masalah dengan API
5. **Topik Populer**: Tombol untuk pertanyaan umum tentang kesehatan
6. **Riwayat Chat**: Pengguna dapat melihat dan mengklik pertanyaan sebelumnya untuk diajukan kembali

### Parameter AI yang Dioptimalkan

Controller `HealsAiController` telah dikonfigurasi dengan parameter AI berikut:

```php
'generationConfig' => [
    'temperature' => 0.7,      // Keseimbangan antara kreativitas dan konsistensi
    'topK' => 40,              // Jumlah token dengan probabilitas tertinggi
    'topP' => 0.95,            // Sampling dengan nucleus probability
    'maxOutputTokens' => 1024, // Panjang respons maksimum
],
```

Parameter ini dapat disesuaikan untuk mengubah gaya respons AI.

## Keamanan

Chatbot telah dilengkapi dengan pengaturan keamanan berikut:

```php
'safetySettings' => [
    [
        'category' => 'HARM_CATEGORY_HARASSMENT',
        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
    ],
    [
        'category' => 'HARM_CATEGORY_HATE_SPEECH',
        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
    ],
    [
        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
    ],
    [
        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
    ]
],
```

Pengaturan ini memastikan bahwa respons dari AI tetap aman dan sesuai untuk penggunaan dalam konteks kesehatan.

## Mode Simulasi

HealsAI dapat dijalankan dalam mode simulasi untuk keperluan testing:

```php
// Mode simulasi untuk testing (ubah menjadi false untuk menggunakan API)
$simulationMode = true;
```

Dalam mode simulasi, sistem akan memberikan respons yang telah diprogram sebelumnya untuk pertanyaan umum tentang kesehatan, tanpa memanggil API eksternal.

## Troubleshooting

### Tidak Dapat Terhubung ke API

Jika muncul pesan error "Gagal terhubung dengan layanan AI", cek hal-hal berikut:

1. Pastikan API key sudah benar di file `.env`
2. Verifikasi koneksi internet server
3. Periksa apakah ada batasan rate limit dari API

### Respons Kosong atau Error

Jika respons tidak muncul atau terjadi error:

1. Periksa log error di `storage/logs/laravel.log`
2. Pastikan format permintaan ke API sudah sesuai
3. Coba restart server aplikasi

## Pengembangan Lanjutan

Untuk pengembangan lanjutan, beberapa hal yang bisa ditambahkan:

1. Menambahkan fitur text-to-speech untuk respons AI
2. Integrasi dengan layanan pihak ketiga untuk data kesehatan real-time
3. Penyimpanan riwayat chat di database untuk analisis jangka panjang
4. Pengenalan dan analisis gambar medis 