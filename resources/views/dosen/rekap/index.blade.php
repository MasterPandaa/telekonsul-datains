@extends('layouts.dosen')

@section('title', 'Rekap Data Konsultasi')

@section('dosen-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Rekap Data Konsultasi</h1>
    <p class="text-sm text-gray-600">Lihat statistik dan rekap data konsultasi</p>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Konsultasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Konsultasi::where('status', 'Selesai')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Sudah Dinilai</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Konsultasi::where('status', 'Selesai')->whereNotNull('nilai_dosen')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Rata-rata Nilai</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ number_format(\App\Models\Konsultasi::where('status', 'Selesai')->whereNotNull('nilai_dosen')->avg('nilai_dosen'), 1) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Data Dokter</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari nama dokter..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <select id="filter-nilai" class="w-full md:w-auto px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Nilai</option>
                <option value="high">Nilai Tinggi (>80)</option>
                <option value="medium">Nilai Sedang (50-80)</option>
                <option value="low">Nilai Rendah (<50)</option>
                <option value="no">Belum Dinilai</option>
            </select>
        </div>
    </div>
</div>

<!-- Tabel Rekap Dokter -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Rekap Nilai per Dokter</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokter</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Konsultasi</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sudah Dinilai</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="dokter-table-body">
                    @php
                        $dokters = \App\Models\Dokter::paginate(10);
                        $no = ($dokters->currentPage() - 1) * $dokters->perPage() + 1;
                    @endphp
                    
                    @forelse($dokters as $dokter)
                    @php
                        $totalKonsultasi = \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                            ->where('status', 'Selesai')
                            ->count();
                            
                        $sudahDinilai = \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                            ->where('status', 'Selesai')
                            ->whereNotNull('nilai_dosen')
                            ->count();
                            
                        $rataRata = \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                            ->where('status', 'Selesai')
                            ->whereNotNull('nilai_dosen')
                            ->avg('nilai_dosen');
                    @endphp
                    <tr class="hover:bg-gray-50 dokter-row" 
                        data-nama="{{ $dokter->nama ?? 'Tidak ada nama' }}" 
                        data-nilai="{{ $rataRata ?? 0 }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $no++ }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($dokter->nama ?? 'Tidak ada nama') }}&background=4F46E5&color=fff" alt="{{ $dokter->nama ?? 'Tidak ada nama' }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $dokter->nama ?? 'Tidak ada nama' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalKonsultasi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sudahDinilai }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($rataRata)
                                <span class="font-medium">{{ number_format($rataRata, 1) }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('dosen.rekap.dokter', $dokter->id) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data dokter
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex justify-between items-center">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($dokters->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $dokters->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @endif
                        
                        @if ($dokters->hasMorePages())
                            <a href="{{ $dokters->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                                Selanjutnya
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan
                                <span class="font-medium">{{ $dokters->firstItem() ?? 0 }}</span>
                                sampai
                                <span class="font-medium">{{ $dokters->lastItem() ?? 0 }}</span>
                                dari
                                <span class="font-medium">{{ $dokters->total() }}</span>
                                hasil
                            </p>
                        </div>
                        <div>
                            @include('layouts.partials.pagination-limit-5', ['paginator' => $dokters])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show SweetAlert notifications for session messages
        @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @endif
        
        // Filter functionality
        const searchInput = document.getElementById('search-input');
        const filterNilai = document.getElementById('filter-nilai');
        const dokterRows = document.querySelectorAll('#dokter-table-body .dokter-row');
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = '<td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data yang sesuai dengan filter</td>';
        emptyRow.id = 'empty-filter-row';
        emptyRow.style.display = 'none';
        
        if (dokterRows.length > 0) {
            dokterRows[0].parentNode.appendChild(emptyRow);
        }
        
        // Add event listeners
        searchInput.addEventListener('input', filterDokters);
        filterNilai.addEventListener('change', filterDokters);
        
        function filterDokters() {
            const searchTerm = searchInput.value.toLowerCase();
            const nilaiFilter = filterNilai.value;
            let visibleCount = 0;
            
            dokterRows.forEach(row => {
                const namaDokter = row.getAttribute('data-nama').toLowerCase();
                const nilaiDokter = parseFloat(row.getAttribute('data-nilai')) || 0;
                
                const matchesSearch = namaDokter.includes(searchTerm);
                let matchesNilai = true;
                
                if (nilaiFilter === 'high') {
                    matchesNilai = nilaiDokter > 80;
                } else if (nilaiFilter === 'medium') {
                    matchesNilai = nilaiDokter >= 50 && nilaiDokter <= 80;
                } else if (nilaiFilter === 'low') {
                    matchesNilai = nilaiDokter > 0 && nilaiDokter < 50;
                } else if (nilaiFilter === 'no') {
                    matchesNilai = nilaiDokter === 0;
                }
                
                if (matchesSearch && matchesNilai) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide empty message
            const emptyFilterRow = document.getElementById('empty-filter-row');
            if (emptyFilterRow) {
                if (visibleCount === 0) {
                    emptyFilterRow.style.display = '';
                } else {
                    emptyFilterRow.style.display = 'none';
                }
            }
        }
    });
</script>
@endpush
@endsection 