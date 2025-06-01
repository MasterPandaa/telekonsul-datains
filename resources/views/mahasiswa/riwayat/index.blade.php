@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat & Nilai Konsultasi</h1>
    <p class="text-sm text-gray-600">Lihat riwayat konsultasi pasien dan nilai yang diperoleh</p>
</div>

<!-- Tab Navigation -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-white border-b">
        <nav class="flex">
            <button class="px-6 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                Riwayat Konsultasi
            </button>
            <button class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-800">
                Rekap Nilai
            </button>
        </nav>
    </div>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Riwayat</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" placeholder="Cari pasien..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Pasien</option>
                <option value="1">Budi Santoso</option>
                <option value="2">Siti Rahayu</option>
                <option value="3">Ahmad Hidayat</option>
                <option value="4">Dewi Lestari</option>
                <option value="5">Joko Widodo</option>
            </select>
        </div>
    </div>
</div>

<!-- Riwayat Konsultasi List -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pasien
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keluhan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nilai
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Item 1 -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">5 Juni 2023</div>
                        <div class="text-xs text-gray-500">10:30 - 11:30</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Ahmad+Hidayat&background=4F46E5&color=fff" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Ahmad Hidayat</div>
                                <div class="text-sm text-gray-500">Laki-laki, 52 tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">Kontrol diabetes</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                85/100
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>

                <!-- Item 2 -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">1 Juni 2023</div>
                        <div class="text-xs text-gray-500">14:00 - 15:00</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Dewi+Lestari&background=4F46E5&color=fff" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Dewi Lestari</div>
                                <div class="text-sm text-gray-500">Perempuan, 30 tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">Konsultasi kehamilan</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                75/100
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>

                <!-- Item 3 -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">28 Mei 2023</div>
                        <div class="text-xs text-gray-500">09:00 - 10:00</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Joko+Widodo&background=4F46E5&color=fff" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Joko Widodo</div>
                                <div class="text-sm text-gray-500">Laki-laki, 60 tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">Kontrol tekanan darah</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                90/100
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Sebelumnya
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Selanjutnya
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">3</span> dari <span class="font-medium">3</span> hasil
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Sebelumnya</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            1
                        </a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Selanjutnya</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary of Nilai -->
<div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Rekap Nilai Konsultasi</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-green-700 font-medium">Nilai Rata-rata</p>
                        <p class="text-3xl font-bold text-green-800 mt-1">83.3</p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Konsultasi Selesai</p>
                        <p class="text-3xl font-bold text-blue-800 mt-1">3</p>
                    </div>
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-purple-700 font-medium">Nilai Tertinggi</p>
                        <p class="text-3xl font-bold text-purple-800 mt-1">90</p>
                    </div>
                    <div class="bg-purple-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-md font-medium text-gray-700 mb-4">Aspek Penilaian</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Komunikasi</div>
                            <div class="text-sm font-medium text-gray-700">85%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Anamnesis</div>
                            <div class="text-sm font-medium text-gray-700">80%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Diagnosa</div>
                            <div class="text-sm font-medium text-gray-700">88%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Penanganan</div>
                            <div class="text-sm font-medium text-gray-700">82%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 82%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 