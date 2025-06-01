@extends('layouts.admin')
@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Log Aktivitas Sistem</h1>
    <p class="text-sm text-gray-600">Catatan aktivitas semua pengguna dalam sistem</p>
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
            <h2 class="text-lg font-medium text-gray-800">Aktivitas Sistem</h2>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $logs->total() }} catatan aktivitas</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <form action="{{ route('admin.log.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua log?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Semua Log
                </button>
            </form>
        </div>
    </div>
    
    <!-- Filter dan Search -->
    <div class="p-4 border-b bg-gray-50">
        <form action="{{ route('admin.log.system') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="relative flex-1 min-w-[250px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan aksi atau deskripsi..." class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400">
            </div>
            
            <div>
                <select name="action" class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400">
                    <option value="">Semua Aksi</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
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
    
    <form action="{{ route('admin.log.destroy') }}" method="POST" id="log-form">
        @csrf @method('DELETE')
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="pl-4 pr-2 py-3 border-b">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 border-b">Waktu</th>
                        <th class="px-4 py-3 border-b">User</th>
                        <th class="px-4 py-3 border-b">Aksi</th>
                        <th class="px-4 py-3 border-b">Deskripsi</th>
                        <th class="px-4 py-3 border-b">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="pl-4 pr-2 py-3 whitespace-nowrap">
                            <input type="checkbox" name="ids[]" value="{{ $log->id }}" class="log-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
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
                                    'login' => 'green',
                                    'logout' => 'yellow',
                                    'create' => 'blue',
                                    'update' => 'purple',
                                    'delete' => 'red',
                                ];
                                
                                $actionType = collect(['login', 'logout', 'create', 'update', 'delete'])
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
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Tidak ada data log yang tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t">
    @if(count($logs) > 0)
            <div class="flex items-center justify-between">
                <div>
                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition flex items-center" id="delete-selected" disabled>
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
    @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const logCheckboxes = document.querySelectorAll('.log-checkbox');
        const deleteSelectedButton = document.getElementById('delete-selected');
        const logForm = document.getElementById('log-form');
        
        // Select all logic
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            logCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateDeleteButton();
        });
        
        // Individual checkbox logic
        logCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateDeleteButton);
        });
        
        // Update delete button state
        function updateDeleteButton() {
            const checkedCount = document.querySelectorAll('.log-checkbox:checked').length;
            deleteSelectedButton.disabled = checkedCount === 0;
        }
        
        // Form submission confirmation
        logForm.addEventListener('submit', function(e) {
            const checkedCount = document.querySelectorAll('.log-checkbox:checked').length;
            
            if (checkedCount === 0) {
                e.preventDefault();
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} log yang dipilih?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection 