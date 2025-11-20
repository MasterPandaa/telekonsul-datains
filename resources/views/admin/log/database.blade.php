@extends('layouts.admin')
@section('admin-content')

<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    .custom-select {
        position: relative;
        display: inline-block;
    }
    .custom-select select {
        display: none;
    }
    .select-selected {
        position: relative;
        cursor: pointer;
    }
    .select-items {
        animation: slideDown 0.2s ease-out;
        position: absolute;
        z-index: 50;
    }
    .select-items div:hover {
        /* Tailwind @apply tidak berlaku di style tag, gunakan warna langsung */
        background-color: #eef2ff; /* indigo-50 */
    }
    .select-hide {
        animation: slideUp 0.2s ease-out;
    }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Log Database</h1>
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

<div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100">
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b bg-white/60 backdrop-blur">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Log Database</h2>
                <p class="text-xs text-gray-500 mt-0.5">Total: {{ $logs->total() }} aktivitas CRUD</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">Halaman <span class="font-semibold">{{ $logs->currentPage() }}</span></span>
                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-gray-50 text-gray-700 text-xs">Per halaman <span class="font-semibold">{{ $logs->perPage() }}</span></span>
            </div>
        </div>
    </div>
    
    <!-- Filter dan Search -->
    <div class="p-4 border-b bg-gray-50">
        <form action="{{ route('admin.log.database') }}" method="GET" class="flex flex-wrap gap-4" id="filter-form">
            <div class="relative flex-1 min-w-[250px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan deskripsi..." 
                    class="block w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="custom-select relative min-w-[200px]">
                <div class="select-selected px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 transition-all duration-200 flex items-center justify-between group">
                    <span class="text-gray-700">{{ request('action') ? ucfirst(request('action')) : 'Semua Aksi' }}</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 transform group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="select-items w-full py-1 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="">Semua Aksi</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="create">Create</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="update">Update</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="delete">Delete</div>
                </div>
                <input type="hidden" name="action" id="action-input" value="{{ request('action') }}">
            </div>
        </form>
    </div>
    
    <!-- Table Container -->
    <div class="overflow-hidden rounded-b-xl">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3 border-b">No</th>
                        <th class="px-4 py-3 border-b">Waktu</th>
                        <th class="px-4 py-3 border-b">User</th>
                        <th class="px-4 py-3 border-b">Aksi</th>
                        <th class="px-4 py-3 border-b">Deskripsi</th>
                        <th class="px-4 py-3 border-b">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="log-table-body">
                    @forelse($logs as $index => $log)
                    <tr class="hover:bg-gray-50/70 transition-colors log-row" 
                        data-action="{{ strtolower($log->action) }}" 
                        data-description="{{ strtolower($log->description) }}"
                        data-user="{{ strtolower($log->user->name ?? '') }}">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $logs->firstItem() + $index }}
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
                                    'create' => 'green',
                                    'update' => 'blue',
                                    'delete' => 'red'
                                ];
                                
                                $actionType = collect(['create', 'update', 'delete'])
                                    ->first(function($type) use ($log) {
                                        return strpos(strtolower($log->action), $type) !== false;
                                    }) ?? 'default';
                                
                                $color = $actionColors[$actionType] ?? 'gray';
                            @endphp
                            
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 max-w-[28rem]">
                            <span class="line-clamp-2" title="{{ $log->description }}">{{ $log->description }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $desc = (string) ($log->description ?? '');
                                $duration = null;
                                // Try patterns like: 123ms, 1.23s, duration: 45 ms, waktu=30ms, took 12ms
                                if (preg_match('/(?:(?:duration|durasi|waktu|took)\s*[:=]?\s*)?(\d+(?:[\.,]\d+)?)\s*(ms|millisecond|milliseconds|s|sec|secs|second|seconds)?/i', $desc, $m)) {
                                    $value = str_replace(',', '.', $m[1]);
                                    $unit = strtolower($m[2] ?? 'ms');
                                    // Normalize to ms for display
                                    if (in_array($unit, ['s','sec','secs','second','seconds'])) {
                                        $duration = (float)$value * 1000;
                                    } else {
                                        $duration = (float)$value;
                                    }
                                }
                            @endphp
                            @if (!is_null($duration))
                                <span class="px-2 py-1 text-xs font-medium rounded bg-blue-50 text-blue-700">{{ number_format($duration, 0) }} ms</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="no-data-row" class="hidden">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Tidak ada data log yang sesuai dengan filter</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if(count($logs) > 0)
                    <a href="{{ $logs->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $logs->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Sebelumnya
                    </a>
                    <a href="{{ $logs->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$logs->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if(count($logs) > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $logs->firstItem() }}</span> sampai <span class="font-medium">{{ $logs->lastItem() }}</span> dari <span class="font-medium">{{ $logs->total() }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">
                            Belum ada data log database saat ini
                        </p>
                    @endif
                </div>
                @if(count($logs) > 0)
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="{{ $logs->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $logs->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Sebelumnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            
                            <!-- Contoh sederhana untuk menampilkan halaman saat ini saja -->
                            <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                {{ $logs->currentPage() }}
                            </a>
                            
                            <a href="{{ $logs->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$logs->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customSelect = document.querySelector('.custom-select');
    const selectSelected = customSelect.querySelector('.select-selected');
    const selectItems = customSelect.querySelector('.select-items');
    const hiddenInput = document.getElementById('action-input');
    const searchInput = document.getElementById('search-input');
    const filterForm = document.getElementById('filter-form');
    const logRows = document.querySelectorAll('.log-row');
    const noDataRow = document.getElementById('no-data-row');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const actionFilter = hiddenInput.value.toLowerCase();
        let visibleCount = 0;

        logRows.forEach(row => {
            const action = row.getAttribute('data-action');
            const description = row.getAttribute('data-description');
            const user = row.getAttribute('data-user');
            
            const matchesSearch = !searchTerm || 
                description.includes(searchTerm) || 
                user.includes(searchTerm) ||
                action.includes(searchTerm);
                
            const matchesAction = !actionFilter || action.includes(actionFilter);

            if (matchesSearch && matchesAction) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        // Show/hide no results message
        if (noDataRow) {
            noDataRow.classList.toggle('hidden', visibleCount > 0);
        }
    }

    // Toggle dropdown with animation
    selectSelected.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = !selectItems.classList.contains('hidden');
        
        if (!isOpen) {
            openSelect();
        } else {
            closeSelect();
        }
    });

    // Handle option selection with animation
    selectItems.querySelectorAll('div').forEach(item => {
        item.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const text = this.textContent.trim();
            
            selectSelected.querySelector('span').textContent = text;
            hiddenInput.value = value;
            
            // Add selected styling
            selectItems.querySelectorAll('div').forEach(div => {
                div.classList.remove('bg-indigo-50', 'text-indigo-600');
            });
            this.classList.add('bg-indigo-50', 'text-indigo-600');
            
            closeSelect();
            filterTable(); // Filter table immediately
            filterForm.submit(); // Submit form for server-side filtering
        });
    });

    // Handle search input with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        filterTable(); // Filter immediately for better UX
        
        searchTimeout = setTimeout(() => {
            filterForm.submit(); // Submit form after delay for server-side filtering
        }, 500);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', closeSelect);

    function openSelect() {
        selectItems.classList.remove('hidden');
        selectSelected.querySelector('svg').classList.add('rotate-180');
        selectSelected.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-200');
        selectItems.style.animation = 'slideDown 0.2s ease-out';
    }

    function closeSelect() {
        selectItems.style.animation = 'slideUp 0.2s ease-out';
        setTimeout(() => {
            selectItems.classList.add('hidden');
            selectSelected.querySelector('svg').classList.remove('rotate-180');
            selectSelected.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-200');
        }, 180);
    }

    // Highlight current selection on page load
    if (hiddenInput.value) {
        const selectedOption = selectItems.querySelector(`[data-value="${hiddenInput.value}"]`);
        if (selectedOption) {
            selectedOption.classList.add('bg-indigo-50', 'text-indigo-600');
        }
    }

    // Initial filter on page load
    filterTable();
});
</script>

@endsection 