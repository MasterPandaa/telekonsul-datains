# Dokumentasi Integrasi Gemini 2.5 Pro AI

## Pengenalan

Sistem chatbot AI pada aplikasi Telekonsultasi kini sudah terintegrasi dengan Google Gemini 2.5 Pro untuk memberikan pengalaman chatbot yang lebih cerdas dan responsif. Dokumen ini memberikan panduan cara mengaktifkan dan menggunakan fitur Gemini AI.

## Prasyarat

1. **API Key Gemini**: Dapatkan API key dari [Google AI Studio](https://ai.google.dev/)
2. **PHP 8.x**: Pastikan server menggunakan PHP 8.0 atau lebih tinggi
3. **Koneksi Internet**: Sistem memerlukan koneksi internet yang stabil untuk berkomunikasi dengan API Gemini

## Konfigurasi

### Pengaturan .env

Tambahkan baris berikut pada file `.env` aplikasi:

```
# GEMINI AI CONFIG
GEMINI_API_KEY=your-gemini-api-key-here
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent
```

Ganti `your-gemini-api-key-here` dengan API key Gemini yang sudah Anda dapatkan.

### Aktifkan Kode API di View

Pada file `resources/views/pasien/chatbot/index.blade.php`, cari bagian kode JavaScript yang menangani pengiriman pesan:

```javascript
function sendMessage(message) {
    // Tampilkan pesan user
    addUserMessage(message);
    
    // Tambahkan ke riwayat
    chatHistory.push({
        role: 'user',
        content: message
    });
    
    // Tampilkan loading state
    setLoadingState(true);
    
    // Simulasi respons dari API untuk sementara
    setTimeout(() => {
        // ... kode simulasi ...
    }, 1500);
    
    // Kode untuk menggunakan Gemini API akan diaktifkan nanti
    // fetch('/pasien/chatbot/gemini', {
    // ... kode fetch yang dikomentari ...
    // });
}
```

Untuk mengaktifkan Gemini API, ubah kode tersebut dengan menghapus kode simulasi dan menghilangkan komentar pada kode fetch:

```javascript
function sendMessage(message) {
    // Tampilkan pesan user
    addUserMessage(message);
    
    // Tambahkan ke riwayat
    chatHistory.push({
        role: 'user',
        content: message
    });
    
    // Tampilkan loading state
    setLoadingState(true);
    
    // Kirim ke API Gemini
    fetch('/pasien/chatbot/gemini', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            message: message,
            history: chatHistory
        })
    })
    .then(response => response.json())
    .then(data => {
        // Sembunyikan loading state
        setLoadingState(false);
        
        if (data.success) {
            // Tampilkan respons AI
            addAIMessage(data.response);
            
            // Tambahkan ke riwayat
            chatHistory.push({
                role: 'assistant',
                content: data.response
            });
            
            // Batasi riwayat agar tidak terlalu panjang (maksimal 10 pesan terakhir)
            if (chatHistory.length > 10) {
                chatHistory = chatHistory.slice(chatHistory.length - 10);
            }
        } else {
            // Tampilkan pesan error
            addErrorMessage(data.message || 'Terjadi kesalahan saat memproses permintaan Anda.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        setLoadingState(false);
        addErrorMessage('Terjadi kesalahan koneksi. Silakan coba lagi.');
    });
}
```

## Fitur Chatbot dengan Gemini 2.5 Pro

### Kemampuan yang Didukung

1. **Riwayat Percakapan**: Chatbot menyimpan hingga 10 pesan terakhir untuk memberikan konteks yang berkelanjutan
2. **Format Respons**: Respons diformat dengan dukungan teks tebal, miring, dan daftar bullet/nomor
3. **Animasi Loading**: Indikator mengetik yang dinamis saat menunggu respons dari API
4. **Penanganan Error**: Pesan error yang jelas jika terjadi masalah dengan API
5. **Topik Populer**: Tombol untuk pertanyaan umum tentang kesehatan

### Parameter AI yang Dioptimalkan

Controller `GeminiAiController` telah dikonfigurasi dengan parameter AI berikut:

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

## Troubleshooting

### Tidak Dapat Terhubung ke API

Jika muncul pesan error "Gagal terhubung dengan layanan AI", cek hal-hal berikut:

1. Pastikan API key sudah benar di file `.env`
2. Verifikasi koneksi internet server
3. Periksa apakah ada batasan rate limit dari Google Gemini API

### Respons Kosong atau Error

Jika respons tidak muncul atau terjadi error:

1. Periksa log error di `storage/logs/laravel.log`
2. Pastikan format permintaan ke API sudah sesuai
3. Coba restart server aplikasi

## Pengembangan Lanjutan

Untuk pengembangan lanjutan, beberapa hal yang bisa ditambahkan:

1. Menambahkan fitur text-to-speech untuk respons AI
2. Integrasi dengan layanan pihak ketiga untuk data kesehatan real-time
3. Penyimpanan riwayat chat di database untuk analisis
4. Penggunaan model Gemini Vision untuk analisis gambar medis

## Referensi

- [Dokumentasi Gemini API](https://ai.google.dev/docs)
- [Laravel HTTP Client](https://laravel.com/docs/10.x/http-client)
- [JavaScript Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API) 