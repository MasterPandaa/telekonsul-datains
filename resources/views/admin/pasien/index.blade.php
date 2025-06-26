@extends('layouts.admin')
@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Pasien</h1>
    <p class="text-sm text-gray-600">Kelola data pasien pada sistem</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
        <div class="flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Header dan Filter -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 border-b">
        <h2 class="text-lg font-medium text-gray-800 mb-3 md:mb-0">Daftar Pasien</h2>
        
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-3 md:space-y-0 md:space-x-3 w-full md:w-auto">
            <!-- Filter and Search -->
            <form action="{{ route('admin.pasien.index') }}" method="GET" class="flex items-center w-full md:w-auto">
                <div class="relative flex-grow">
                    <input type="text" name="search" placeholder="Cari pasien..." value="{{ $searchTerm ?? '' }}" 
                           class="w-full md:w-64 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
                
                @if(!empty($searchTerm))
                    <a href="{{ route('admin.pasien.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </form>
            
            <a href="{{ route('admin.pasien.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pasien
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
        <thead>
                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 border-b">No</th>
                    <th class="px-4 py-3 border-b">
                        <a href="{{ route('admin.pasien.index', [
                            'search' => $searchTerm ?? '', 
                            'sort_by' => 'nama', 
                            'sort_order' => $sortBy === 'nama' && $sortOrder === 'asc' ? 'desc' : 'asc'
                        ]) }}" class="flex items-center group">
                            <span>Nama</span>
                            <span class="ml-1">
                                @if($sortBy === 'nama')
                                    @if($sortOrder === 'asc')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </span>
                        </a>
                    </th>
                    <th class="px-4 py-3 border-b">
                        <a href="{{ route('admin.pasien.index', [
                            'search' => $searchTerm ?? '', 
                            'sort_by' => 'nik', 
                            'sort_order' => $sortBy === 'nik' && $sortOrder === 'asc' ? 'desc' : 'asc'
                        ]) }}" class="flex items-center group">
                            <span>NIK</span>
                            <span class="ml-1">
                                @if($sortBy === 'nik')
                                    @if($sortOrder === 'asc')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </span>
                        </a>
                    </th>
                    <th class="px-4 py-3 border-b">
                        <a href="{{ route('admin.pasien.index', [
                            'search' => $searchTerm ?? '', 
                            'sort_by' => 'email', 
                            'sort_order' => $sortBy === 'email' && $sortOrder === 'asc' ? 'desc' : 'asc'
                        ]) }}" class="flex items-center group">
                            <span>Kontak</span>
                            <span class="ml-1">
                                @if($sortBy === 'email')
                                    @if($sortOrder === 'asc')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </span>
                        </a>
                    </th>
                    <th class="px-4 py-3 border-b text-center">Aksi</th>
            </tr>
        </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($pasiens as $index => $pasien)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ $pasiens->firstItem() + $index }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($pasien->foto && file_exists(public_path('img/pasien/' . $pasien->foto)))
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('img/pasien/' . $pasien->foto) }}" alt="{{ $pasien->nama }}">
                                @else
                                    <img class="h-10 w-10 rounded-full bg-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode($pasien->nama) }}&background=3b82f6&color=fff" alt="{{ $pasien->nama }}">
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $pasien->nama }}</div>
                                <div class="text-sm text-gray-500">{{ $pasien->jenis_kelamin }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-gray-900">{{ $pasien->nik }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-gray-900">{{ $pasien->email }}</div>
                        <div class="text-sm text-gray-500">{{ $pasien->no_hp }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Lihat Detail -->
                            <a href="{{ route('admin.pasien.show', $pasien->id) }}" class="p-1.5 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            
                            <!-- Edit -->
                            <a href="{{ route('admin.pasien.edit', $pasien->id) }}" class="p-1.5 bg-yellow-100 text-yellow-600 rounded-md hover:bg-yellow-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </a>
                            
                            <!-- Hapus -->
                            <button type="button" 
                                    onclick="konfirmasiHapus('{{ $pasien->id }}', '{{ $pasien->nama }}')" 
                                    class="p-1.5 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            <form id="form-hapus-{{ $pasien->id }}" action="{{ route('admin.pasien.destroy', $pasien->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                    </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center py-6">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                            <p class="text-gray-500">Tidak ada data pasien yang tersedia.</p>
                            <a href="{{ route('admin.pasien.create') }}" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                                Tambahkan data pasien baru
                            </a>
                        </div>
                </td>
            </tr>
                @endforelse
        </tbody>
    </table>
</div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if(count($pasiens) > 0)
                    <a href="{{ $pasiens->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $pasiens->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Sebelumnya
                    </a>
                    <a href="{{ $pasiens->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$pasiens->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if(count($pasiens) > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $pasiens->firstItem() }}</span> sampai <span class="font-medium">{{ $pasiens->lastItem() }}</span> dari <span class="font-medium">{{ $pasiens->total() }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">
                            Belum ada data pasien saat ini
                        </p>
                    @endif
                </div>
                @if(count($pasiens) > 0)
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="{{ $pasiens->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $pasiens->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Sebelumnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            
                            <!-- Contoh sederhana untuk menampilkan halaman saat ini saja -->
                            <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                {{ $pasiens->currentPage() }}
                            </a>
                            
                            <a href="{{ $pasiens->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$pasiens->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
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

@push('scripts')
<script>
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus Data',
            html: `Apakah Anda yakin ingin menghapus data pasien <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
            iconColor: '#ef4444',
            background: '#ffffff',
            padding: '1rem',
            customClass: {
                confirmButton: 'px-4 py-2 rounded text-white text-sm font-medium',
                cancelButton: 'px-4 py-2 rounded text-white text-sm font-medium',
                title: 'text-xl text-gray-800 font-bold',
                popup: 'rounded-xl shadow-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-hapus-${id}`).submit();
            }
        });
    }
    
    // Sweet alert untuk notifikasi sukses
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        iconColor: '#10b981',
        showConfirmButton: false,
        timer: 3000,
        background: '#ffffff',
        customClass: {
            popup: 'rounded-xl shadow-md',
            title: 'text-green-600'
        }
    });
    @endif
</script>
@endpush
@endsection 