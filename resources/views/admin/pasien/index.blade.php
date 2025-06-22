@extends('layouts.admin')
@section('admin-content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Pasien</h1>
    <p class="text-sm text-gray-500">Kelola data pasien dalam sistem</p>
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

<div class="bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="p-4 border-b flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium text-gray-800">Daftar Pasien</h2>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $pasiens->count() }} pasien</p>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.pasien.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Cari pasien...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search']))
                    <a href="{{ route('admin.pasien.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Reset
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.pasien.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <th class="px-4 py-3 border-b">Pasien</th>
                    <th class="px-4 py-3 border-b">Kontak</th>
                    <th class="px-4 py-3 border-b">Status</th>
                    <th class="px-4 py-3 border-b">Bergabung</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
            </tr>
        </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($pasiens as $pasien)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($pasien->foto)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$pasien->foto) }}" alt="{{ $pasien->nama }}">
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
                        <div class="text-sm text-gray-900">{{ $pasien->email }}</div>
                        <div class="text-sm text-gray-500">{{ $pasien->no_hp }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($pasien->user)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pasien->user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $pasien->user->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Tidak Ada User
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $pasien->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.pasien.show', $pasien->id) }}" class="text-blue-600 hover:text-blue-900">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.pasien.edit', $pasien->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.pasien.destroy', $pasien->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pasien ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                    </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <p>Belum ada data pasien</p>
                </td>
            </tr>
                @endforelse
        </tbody>
    </table>
</div>

    <!-- Pagination -->
    @if(method_exists($pasiens, 'links'))
    <div class="bg-white px-4 py-3 border-t sm:px-6">
        {{ $pasiens->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection 