@extends('layouts.admin')
@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Log Database</h1>
    <p class="text-sm text-gray-600">Catatan aktivitas CRUD pada database</p>
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
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b">
        <div>
            <h2 class="text-lg font-medium text-gray-800">Log Database</h2>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $logs->total() }} aktivitas CRUD</p>
        </div>
    </div>
    
    <!-- Filter dan Search -->
    <div class="p-4 border-b bg-gray-50">
        <form action="{{ route('admin.log.database') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="relative flex-1 min-w-[250px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan deskripsi..." class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400">
            </div>
            
            <div>
                <select name="action" class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400">
                    <option value="">Semua Aksi</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                </select>
            </div>
            
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 border-b">Waktu</th>
                    <th class="px-4 py-3 border-b">User</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
                    <th class="px-4 py-3 border-b">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->created_at->format('d M Y H:i:s') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <img class="h-8 w-8 rounded-full bg-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name ?? 'User') }}&background=3b82f6&color=fff" alt="{{ $log->user->name ?? 'User' }}">
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'User tidak ditemukan' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        @php
                            $actionColors = [
                                'create' => 'green',
                                'update' => 'blue',
                                'delete' => 'red'
                            ];
                            
                            $actionType = collect(['create', 'update', 'delete'])
                                ->first(function($type) use ($log) {
                                    return strpos($log->action, $type) !== false;
                                }) ?? 'default';
                            
                            $color = $actionColors[$actionType] ?? 'gray';
                        @endphp
                        
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $log->description }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Tidak ada data log database yang tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t">
        {{ $logs->links() }}
    </div>
</div>
@endsection 