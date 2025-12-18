@extends('layouts.dosen')

@section('title', 'Detail Rekap Dokter')

@section('dosen-content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Rekap Dokter</h1>
            <p class="text-sm text-gray-600">Analisis lengkap performa konsultasi untuk {{ $dokter->nama ?? 'Dokter' }}</p>
        </div>
        <a href="{{ route('dosen.rekap.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
        <div class="flex flex-col items-center text-center space-y-4">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-blue-50 shadow bg-gray-100">
                <img src="{{ $dokter->foto_url ?? asset('img/default.jpg') }}" alt="{{ $dokter->nama ?? 'Dokter' }}" class="w-full h-full object-cover">
            </div>
            <div class="space-y-1">
                <h2 class="text-lg font-semibold text-gray-900">{{ $dokter->nama ?? 'Tidak ada nama' }}</h2>
                <p class="text-sm text-gray-500">{{ $dokter->spesialisasi ?? 'Spesialis belum diatur' }}</p>
            </div>
            <div class="w-full bg-blue-50 border border-blue-100 rounded-xl p-4 space-y-2 text-sm text-blue-700">
                <div class="flex justify-between"><span>Total Konsultasi</span><span class="font-semibold text-blue-900">{{ $totalKonsultasi }}</span></div>
                <div class="flex justify-between"><span>Sudah Dinilai</span><span class="font-semibold text-blue-900">{{ $sudahDinilai }}</span></div>
                <div class="flex justify-between"><span>Rata-rata Nilai</span><span class="font-semibold text-blue-900">{{ $rataRata ? number_format($rataRata, 1) : '-' }}</span></div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Nilai Dosen</h3>
            @if($chartData->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase tracking-wide text-xs border-b">
                                <th class="pb-3 pr-6">#</th>
                                <th class="pb-3 pr-6">ID Konsultasi</th>
                                <th class="pb-3 pr-6">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($chartData as $index => $item)
                                <tr class="border-b last:border-none">
                                    <td class="py-2 pr-6">{{ $index + 1 }}</td>
                                    <td class="py-2 pr-6">#{{ $item->id }}</td>
                                    <td class="py-2 pr-6 font-semibold">{{ $item->nilai_dosen }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">Belum ada nilai yang dapat ditampilkan.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Konsultasi</h3>
                    <p class="text-sm text-gray-500">Riwayat konsultasi selesai dengan status penilaian.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-600">Total {{ $konsultasis->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Pasien</th>
                            <th class="px-6 py-3 text-left">Keluhan</th>
                            <th class="px-6 py-3 text-center">Nilai Dosen</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                        @forelse($konsultasis as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->tanggal_indonesia }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->pasien->nama ?? '-' }}</td>
                                <td class="px-6 py-4">{{ \Illuminate\Support\Str::limit($item->keluhan, 60) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->nilai_dosen)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">{{ $item->nilai_dosen }}</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Belum dinilai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500">Belum ada konsultasi selesai untuk dokter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($konsultasis->total() > 0)
                            <a href="{{ $konsultasis->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $konsultasis->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                Sebelumnya
                            </a>
                            <a href="{{ $konsultasis->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$konsultasis->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                Selanjutnya
                            </a>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            @if($konsultasis->total() > 0)
                                <p class="text-sm text-gray-700">Menampilkan <span class="font-medium">{{ $konsultasis->firstItem() }}</span> sampai <span class="font-medium">{{ $konsultasis->lastItem() }}</span> dari <span class="font-medium">{{ $konsultasis->total() }}</span> hasil</p>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada konsultasi untuk ditampilkan.</p>
                            @endif
                        </div>
                        @if($konsultasis->total() > 0)
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <a href="{{ $konsultasis->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $konsultasis->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        <span class="sr-only">Sebelumnya</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ $konsultasis->currentPage() }}</span>
                                    <a href="{{ $konsultasis->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$konsultasis->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        <span class="sr-only">Selanjutnya</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
