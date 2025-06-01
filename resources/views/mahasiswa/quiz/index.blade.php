@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Quiz & Evaluasi</h1>
    <p class="text-sm text-gray-600">Daftar quiz dan evaluasi pengetahuan tentang telekonsultasi</p>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Quiz</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" placeholder="Cari quiz..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Tipe</option>
                <option value="quiz">Quiz</option>
                <option value="teori">Teori</option>
                <option value="evaluasi">Evaluasi Praktik</option>
            </select>
            <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="completed">Selesai</option>
                <option value="expired">Kadaluarsa</option>
            </select>
        </div>
    </div>
</div>

<!-- Quiz Cards Section -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-800 mb-4">Quiz Aktif</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Quiz 1 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="h-3 bg-blue-600"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Evaluasi Penanganan Pasien Diabetes</h4>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Quiz</span>
                </div>
                <p class="text-gray-600 text-sm mb-6">10 pertanyaan pilihan ganda tentang penanganan pasien diabetes mellitus</p>
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="text-xs text-gray-500">Batas waktu:</p>
                        <p class="text-sm font-medium text-gray-700">13 Juni 2023</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Durasi:</p>
                        <p class="text-sm font-medium text-gray-700">30 menit</p>
                    </div>
                    <a href="{{ route('mahasiswa.quiz.show', 1) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                        Mulai Quiz
                    </a>
                </div>
            </div>
        </div>

        <!-- Quiz 2 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="h-3 bg-purple-600"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Quiz Teori Konsultasi Jarak Jauh</h4>
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Teori</span>
                </div>
                <p class="text-gray-600 text-sm mb-6">15 pertanyaan tentang teori dan etika dalam telekonsultasi</p>
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="text-xs text-gray-500">Batas waktu:</p>
                        <p class="text-sm font-medium text-gray-700">20 Juni 2023</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Durasi:</p>
                        <p class="text-sm font-medium text-gray-700">45 menit</p>
                    </div>
                    <a href="{{ route('mahasiswa.quiz.show', 3) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                        Mulai Quiz
                    </a>
                </div>
            </div>
        </div>

        <!-- Quiz 3 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="h-3 bg-green-600"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Evaluasi Komunikasi Medis</h4>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Evaluasi</span>
                </div>
                <p class="text-gray-600 text-sm mb-6">Evaluasi keterampilan komunikasi medis dalam telekonsultasi</p>
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="text-xs text-gray-500">Batas waktu:</p>
                        <p class="text-sm font-medium text-gray-700">25 Juni 2023</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Durasi:</p>
                        <p class="text-sm font-medium text-gray-700">60 menit</p>
                    </div>
                    <a href="{{ route('mahasiswa.quiz.show', 4) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                        Mulai Quiz
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Completed Quiz Section -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-800 mb-4">Quiz Selesai</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Completed Quiz 1 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition opacity-80">
            <div class="h-3 bg-gray-400"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Evaluasi Telekonsultasi Hipertensi</h4>
                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">Selesai</span>
                </div>
                <p class="text-gray-600 text-sm mb-6">Evaluasi praktek telekonsultasi untuk pasien dengan hipertensi</p>
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="text-xs text-gray-500">Dikerjakan pada:</p>
                        <p class="text-sm font-medium text-gray-700">2 Juni 2023</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Nilai:</p>
                        <p class="text-sm font-medium text-green-600">85/100</p>
                    </div>
                    <a href="{{ route('mahasiswa.quiz.show', 2) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">
                        Lihat Hasil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Box -->
<div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 text-blue-700 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium">Informasi Quiz</p>
            <ul class="mt-2 text-sm">
                <li class="mb-1">• Pastikan koneksi internet stabil sebelum memulai quiz</li>
                <li class="mb-1">• Jawaban akan otomatis tersimpan setiap 2 menit</li>
                <li>• Hubungi dosen pembimbing jika mengalami kendala teknis</li>
            </ul>
        </div>
    </div>
</div>
@endsection 