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
        /* Tailwind @apply tidak berlaku di inline style; gunakan warna langsung */
        background-color: #eef2ff; /* indigo-50 */
    }
    .select-hide {
        animation: slideUp 0.2s ease-out;
    }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Log Aktivitas Sistem</h1>
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

<div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100">
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b bg-white/60 backdrop-blur">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Aktivitas Sistem</h2>
                <p class="text-xs text-gray-500 mt-0.5">Total: {{ $logs->total() }} catatan</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">Halaman <span class="font-semibold">{{ $logs->currentPage() }}</span></span>
                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-gray-50 text-gray-700 text-xs">Per halaman <span class="font-semibold">{{ $logs->perPage() }}</span></span>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <form action="{{ route('admin.log.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua log?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition flex items-center shadow-sm">
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
        <form action="{{ route('admin.log.system') }}" method="GET" class="flex flex-wrap gap-4" id="filter-form">
            <div class="relative flex-1 min-w-[250px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan aksi atau deskripsi..." 
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
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="login">Login</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="logout">Logout</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="create">Create</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="update">Update</div>
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="delete">Delete</div>
                </div>
                <input type="hidden" name="action" id="action-input" value="{{ request('action') }}">
            </div>
        </form>
    </div>
    
    <form action="{{ route('admin.log.destroy') }}" method="POST" id="log-form">
        @csrf @method('DELETE')
        
        <!-- Table Container -->
        <div class="overflow-hidden rounded-b-xl">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="pl-4 pr-2 py-3 border-b">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 border-b">No</th>
                        <th class="px-4 py-3 border-b">Waktu</th>
                        <th class="px-4 py-3 border-b">Level</th>
                        <th class="px-4 py-3 border-b">User</th>
                        <th class="px-4 py-3 border-b">Aksi</th>
                        <th class="px-4 py-3 border-b">Deskripsi</th>
                        <th class="px-4 py-3 border-b">IP Address</th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="log-table-body">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-gray-50/70 transition-colors log-row" 
                            data-action="{{ strtolower($log->action) }}" 
                            data-description="{{ strtolower($log->description) }}"
                            data-user="{{ strtolower($log->user->name ?? '') }}"
                            data-ip="{{ $log->ip_address }}">
                        <td class="pl-4 pr-2 py-3 whitespace-nowrap">
                            <input type="checkbox" name="ids[]" value="{{ $log->id }}" class="log-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $logs->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            @php
                                $text = strtoupper(($log->description ?? '').' '.($log->action ?? ''));
                                $level = 'INFO';
                                foreach (['CRITICAL','FATAL','ERROR','WARN','WARNING','NOTICE','DEBUG','TRACE','INFO'] as $lvl) {
                                    if (strpos($text, $lvl) !== false) { $level = $lvl === 'WARNING' ? 'WARN' : $lvl; break; }
                                }
                                $colors = [
                                    'INFO' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                    'DEBUG' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                    'WARN' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                    'ERROR' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                    'FATAL' => ['bg' => 'bg-red-200', 'text' => 'text-red-900'],
                                    'TRACE' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800'],
                                    'NOTICE' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
                                    'CRITICAL' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800'],
                                ];
                                $c = $colors[$level] ?? $colors['INFO'];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $c['bg'] }} {{ $c['text'] }}">{{ $level }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-gray-100"></div>
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
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600 font-mono">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                        <tr id="no-data-row" class="hidden">
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
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
                                Belum ada data log sistem saat ini
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
    </form>
</div>

<!-- Legend / Deskripsi Level Log -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Jenis Level Log & Fungsinya</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="p-4 rounded-lg border border-gray-100 bg-blue-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">INFO</span></div>
            <p class="text-sm text-gray-700">Informasi umum tentang alur aplikasi (keadaan normal).</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-gray-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">DEBUG</span></div>
            <p class="text-sm text-gray-700">Detail teknis untuk debugging selama pengembangan.</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-yellow-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">WARN</span></div>
            <p class="text-sm text-gray-700">Peringatan adanya potensi masalah namun belum error.</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-red-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">ERROR</span></div>
            <p class="text-sm text-gray-700">Kesalahan yang menyebabkan fitur gagal, perlu ditangani.</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-rose-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">CRITICAL</span></div>
            <p class="text-sm text-gray-700">Masalah kritikal yang membutuhkan perhatian segera.</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-red-100/50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-900">FATAL</span></div>
            <p class="text-sm text-gray-700">Kegagalan fatal yang menghentikan aplikasi.</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-emerald-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">TRACE</span></div>
            <p class="text-sm text-gray-700">Jejak sangat detail untuk melacak eksekusi langkah demi langkah.</p>
        </div>
        <div class="p-4 rounded-lg border border-gray-100 bg-indigo-50">
            <div class="mb-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">NOTICE</span></div>
            <p class="text-sm text-gray-700">Peristiwa penting namun tidak bermasalah.</p>
        </div>
    </div>
    <p class="mt-4 text-xs text-gray-500">Catatan: Level ditentukan otomatis dari kata kunci pada kolom Aksi/Deskripsi.</p>
    
</div>

@push('scripts')
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
            const ip = row.getAttribute('data-ip');
            
            const matchesSearch = !searchTerm || 
                description.includes(searchTerm) || 
                user.includes(searchTerm) ||
                action.includes(searchTerm) ||
                ip.includes(searchTerm);
                
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

        // Update checkbox state
        updateSelectAllCheckbox();
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

    // Checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const logCheckboxes = document.querySelectorAll('.log-checkbox');
    const deleteSelectedButton = document.getElementById('delete-selected');

    function updateSelectAllCheckbox() {
        const visibleCheckboxes = Array.from(logCheckboxes).filter(checkbox => 
            !checkbox.closest('tr').classList.contains('hidden')
        );
        const allChecked = visibleCheckboxes.length > 0 && 
            visibleCheckboxes.every(checkbox => checkbox.checked);
        const someChecked = visibleCheckboxes.some(checkbox => checkbox.checked);
        
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
        
        if (deleteSelectedButton) {
            deleteSelectedButton.disabled = !someChecked;
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        logCheckboxes.forEach(checkbox => {
            if (!checkbox.closest('tr').classList.contains('hidden')) {
                checkbox.checked = isChecked;
            }
        });
        updateSelectAllCheckbox();
    });

    logCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAllCheckbox);
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
@endpush

@endsection 