@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Mahasiswa</h1>
    <p class="text-sm text-gray-600">Selamat datang di sistem Telekonsultasi</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card Konsultasi Aktif -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Konsultasi Aktif</h2>
                <p class="text-3xl font-bold text-gray-800">2</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('mahasiswa.konsultasi.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua →</a>
        </div>
    </div>

    <!-- Card Konsultasi Selesai -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Konsultasi Selesai</h2>
                <p class="text-3xl font-bold text-gray-800">5</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('mahasiswa.riwayat.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">Lihat Riwayat →</a>
        </div>
    </div>

    <!-- Card Quiz -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Quiz Tersedia</h2>
                <p class="text-3xl font-bold text-gray-800">3</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('mahasiswa.quiz.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">Lihat Quiz →</a>
        </div>
    </div>

    <!-- Card Nilai -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Nilai Rata-rata</h2>
                <p class="text-3xl font-bold text-gray-800">85</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('mahasiswa.riwayat.index') }}" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">Lihat Detail →</a>
        </div>
    </div>
</div>

<!-- Jadwal Konsultasi Terbaru -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Jadwal Konsultasi Terbaru</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Juni 2023</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:00 - 10:00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Budi+Santoso&background=4F46E5&color=fff" alt="Budi Santoso">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                    <div class="text-sm text-gray-500">Laki-laki, 45 tahun</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Konfirmasi</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('mahasiswa.konsultasi.index') }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12 Juni 2023</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">13:00 - 14:00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Siti+Rahayu&background=4F46E5&color=fff" alt="Siti Rahayu">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Siti Rahayu</div>
                                    <div class="text-sm text-gray-500">Perempuan, 38 tahun</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('mahasiswa.konsultasi.index') }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quiz Terbaru -->
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Quiz & Evaluasi Terbaru</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <h3 class="font-medium text-gray-800 mb-2">Evaluasi Penanganan Pasien Diabetes</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Quiz</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">10 pertanyaan pilihan ganda tentang penanganan pasien diabetes mellitus</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Batas: 13 Juni 2023</span>
                    <a href="{{ route('mahasiswa.quiz.show', 1) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Mulai Quiz →</a>
                </div>
            </div>
            
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <h3 class="font-medium text-gray-800 mb-2">Evaluasi Telekonsultasi Hipertensi</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Selesai</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Evaluasi praktek telekonsultasi untuk pasien dengan hipertensi</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Nilai: 85/100</span>
                    <a href="{{ route('mahasiswa.quiz.show', 2) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Lihat Hasil →</a>
                </div>
            </div>
            
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <h3 class="font-medium text-gray-800 mb-2">Quiz Teori Konsultasi Jarak Jauh</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Teori</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">15 pertanyaan tentang teori dan etika dalam telekonsultasi</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Batas: 20 Juni 2023</span>
                    <a href="{{ route('mahasiswa.quiz.show', 3) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Mulai Quiz →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 