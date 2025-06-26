@extends('layouts.pasien')

@section('pasien-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Buat Permintaan Konsultasi</h1>
    <p class="text-sm text-gray-600">Isi formulir untuk membuat janji konsultasi dengan dokter</p>
</div>

<!-- Error messages will be handled by SweetAlert -->

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <form action="{{ route('pasien.konsultasi.store') }}" method="POST" id="form-konsultasi">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilih Dokter -->
                <div class="md:col-span-2">
                    <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-3">Pilih Dokter</label>
                    
                    <!-- Filter dan Pencarian -->
                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-3 w-full">
                            <div class="relative w-full sm:w-3/4 md:w-2/3">
                                <input type="text" id="search-dokter" placeholder="Cari nama atau SIP dokter..." class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full shadow-sm h-[42px]" autocomplete="off">
                            <div class="absolute left-3 top-2.5">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                </div>
                            </div>
                            <div class="custom-select relative w-full sm:w-1/4 md:w-1/3">
                                <div class="select-selected px-4 py-2.5 text-sm bg-white border border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-all duration-200 flex items-center justify-between group h-[42px]">
                                    <span class="text-gray-700 truncate">Semua Spesialisasi</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 transform group-hover:text-blue-500 flex-shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div class="select-items absolute z-10 w-full py-1 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto" id="spesialisasi-list">
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="">Semua Spesialisasi</div>
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="Dokter Umum">Dokter Umum</div>
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="Spesialis Anak">Spesialis Anak</div>
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="Spesialis Penyakit Dalam">Spesialis Penyakit Dalam</div>
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="Spesialis Bedah">Spesialis Bedah</div>
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="Spesialis Kulit dan Kelamin">Spesialis Kulit dan Kelamin</div>
                                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-blue-600 cursor-pointer transition-colors duration-150 hover:bg-blue-50" data-value="Spesialis Jantung">Spesialis Jantung</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slider Navigation -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 bg-gray-50 p-3 rounded-lg gap-2">
                        <h3 class="text-sm font-medium text-gray-700 text-center sm:text-left">
                            <span id="dokterShown">1-9</span> dari <span id="dokterTotal">{{ count($dokter) }}</span> dokter
                        </h3>
                        <div class="flex space-x-2 justify-center sm:justify-end">
                            <button type="button" id="prev-slide" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button type="button" id="next-slide" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Dokter List with Grid Layout (Improved design) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-3">
                        @forelse($dokter as $index => $m)
                            @if($m->dokter)
                            <div class="dokter-card border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all" 
                                 data-name="{{ strtolower($m->name) }}" 
                                 data-nim="{{ strtolower($m->dokter->no_sip ?? '') }}"
                                 data-spesialisasi="{{ $m->dokter->spesialisasi ?? 'Dokter Umum' }}"
                                 data-id="{{ $m->id }}">
                                <div class="p-4 flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($m->dokter && $m->dokter->foto)
                                            <img class="h-16 w-16 rounded-full object-cover border-3 border-gray-100" src="{{ asset($m->dokter->foto) }}" alt="{{ $m->name }}">
                                        @else
                                            <div class="h-16 w-16 rounded-full flex items-center justify-center text-white text-lg font-semibold bg-blue-600 border-3 border-blue-100">
                                                {{ substr($m->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div class="truncate pr-2">
                                                <h3 class="font-medium text-gray-800 truncate">{{ Str::limit($m->name, 14) }}</h3>
                                                <p class="text-xs font-medium text-blue-600 truncate">{{ $m->dokter->spesialisasi ?? 'Dokter Umum' }}</p>
                                                <p class="text-xs text-gray-500 mt-3 truncate">{{ $m->dokter->no_sip ?? '122123/abc' }}</p>
                                            </div>
                                            <div class="flex flex-col items-end gap-1">
                                                <div class="bg-yellow-50 text-yellow-700 px-1.5 py-0.5 rounded text-xs font-medium border border-yellow-200 flex items-center flex-shrink-0">
                                                    <svg class="w-2.5 h-2.5 text-yellow-500 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    @if($m->dokter && $m->dokter->rating)
                                                        {{ number_format($m->dokter->rating, 1) }}
                                                    @else
                                                        <span class="text-gray-500 text-xs">Belum ada rating</span>
                                                    @endif
                                                </div>
                                                <div class="flex gap-1 mt-3">
                                                    <button type="button" class="px-2.5 py-1.5 bg-gray-700 text-white text-xs font-medium rounded hover:bg-gray-800 transition-colors focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-gray-500"
                                                            onclick="showDokterDetail({{ $m->id }}, '{{ $m->name }}', '{{ $m->dokter->no_sip ?? '122123/abc' }}', '{{ $m->dokter->spesialisasi ?? 'Dokter Umum' }}', '{{ $m->dokter && $m->dokter->foto ? asset($m->dokter->foto) : 'https://ui-avatars.com/api/?name='.urlencode($m->name).'&background=4F46E5&color=fff' }}', '{{ $m->dokter->pengalaman ?? '' }}', '{{ $m->dokter->tempat_praktik ?? '' }}')">
                                                        Detail
                                                    </button>
                                                    <button type="button" class="px-2.5 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500"
                                                            onclick="selectDokter({{ $m->id }})">
                                                        Pilih
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 p-8 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada dokter tersedia</h3>
                                <p class="text-sm text-gray-500 mb-4">Saat ini tidak ada dokter yang tersedia untuk konsultasi. Silakan coba lagi nanti.</p>
                            </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="dokter_id" id="dokter_id" required>
                    <p class="text-xs text-gray-500 mt-1">Pilih dokter yang sesuai dengan kebutuhan konsultasi Anda</p>
                </div>
                
                <!-- Tanggal Konsultasi -->
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Konsultasi</label>
                    <input type="date" name="tanggal" id="tanggal" min="{{ $tanggal_mulai }}" value="{{ $tanggal_mulai }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    <p class="mt-1 text-xs text-gray-500">Minimal tanggal pemesanan: <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggal_mulai)->format('d F Y') }}</span></p>
                </div>
                
                <!-- Jam Konsultasi -->
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Konsultasi</label>
                    <select name="jam_mulai" id="jam_mulai" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">-- Pilih Jam --</option>
                        @if(count($jam_tersedia) > 0)
                        @foreach($jam_tersedia as $key => $value)
                                @php
                                    $isBooked = false;
                                    $selectedDate = request('tanggal', $tanggal_mulai);
                                    $selectedDokter = request('dokter_id', 0);
                                    
                                    // Cek apakah slot ini sudah terisi untuk dokter yang dipilih
                                    if (isset($jadwalTerisi[$selectedDate][$key.':00']) && 
                                        in_array($selectedDokter, $jadwalTerisi[$selectedDate][$key.':00'])) {
                                        $isBooked = true;
                                    }
                                @endphp
                                <option value="{{ $key }}" {{ $isBooked ? 'disabled' : '' }}>
                                    {{ $value }} {{ $isBooked ? '(Sudah Terisi)' : '' }}
                                </option>
                        @endforeach
                        @else
                            <option value="" disabled>Tidak ada slot waktu yang tersedia</option>
                        @endif
                    </select>
                    <input type="hidden" name="jam_selesai" id="jam_selesai" value="">
                    <p class="mt-1 text-xs text-gray-500">Durasi konsultasi adalah 15 menit</p>
                    @if(count($jam_tersedia) < 44)
                        <p class="mt-1 text-xs flex items-start text-amber-600">
                            <svg class="inline-block w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span>Beberapa slot waktu tidak tersedia karena sudah lewat atau penuh</span>
                        </p>
                    @endif
                </div>
                
                <!-- Keluhan Utama -->
                <div class="md:col-span-2">
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Keluhan Utama</label>
                    <input type="text" name="keluhan" id="keluhan" placeholder="Contoh: Sakit kepala dan demam" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                
                <!-- Keterangan Tambahan -->
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" placeholder="Jelaskan lebih detail tentang keluhan Anda, seperti sejak kapan, seberapa parah, dll." class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                
                <!-- Informasi Tambahan -->
                <div class="md:col-span-2 bg-blue-50 p-5 rounded-lg border border-blue-100">
                    <h3 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Informasi Penting
                    </h3>
                    <ul class="list-disc pl-5 text-xs text-blue-700 space-y-1.5">
                        <li>Pastikan Anda telah mengisi data profil dengan lengkap dan akurat</li>
                        <li>Jadwal konsultasi dapat berubah jika dokter tidak tersedia</li>
                        <li>Anda akan mendapatkan notifikasi jika permintaan konsultasi telah dikonfirmasi</li>
                        <li>Waktu konsultasi hanya dapat dipilih minimal 15 menit dari sekarang untuk hari ini</li>
                        <li>Jika semua slot waktu hari ini sudah lewat, sistem akan otomatis menetapkan tanggal minimum besok</li>
                        <li>Durasi setiap sesi konsultasi adalah 15 menit</li>
                        <li>Jam konsultasi tersedia mulai pukul 08:00 hingga 20:00 WIB</li>
                        <li>Untuk kondisi darurat, segera hubungi layanan gawat darurat atau kunjungi rumah sakit terdekat</li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('pasien.konsultasi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" id="btn-submit" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Buat Janji Konsultasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Dokter -->
<div id="dokterDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-70" onclick="hideDokterDetail()"></div>
    <div class="relative top-20 mx-auto p-0 border w-11/12 max-w-md shadow-lg rounded-lg bg-white overflow-hidden">
        <div class="bg-gradient-to-b from-blue-600 to-blue-700 py-5 px-5 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium">Detail Dokter</h3>
                <button onclick="hideDokterDetail()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            </div>
            
        <div class="px-5 pt-5 pb-6">
            <div class="flex flex-col items-center -mt-12 mb-3">
                <img id="modal-dokter-photo" class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md" src="" alt="Foto Dokter">
                <h3 id="modal-dokter-name" class="mt-3 text-xl font-medium text-gray-900"></h3>
                <p id="modal-dokter-nim" class="text-sm text-gray-600 mt-1"></p>
            </div>
            
            <div class="grid grid-cols-1 gap-5 mt-4">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Informasi Dokter
                    </h4>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-start">
                            <div class="w-24 flex-shrink-0">
                                <p class="text-xs text-gray-500">Spesialisasi</p>
                            </div>
                            <div class="flex-1">
                            <p id="modal-dokter-fakultas" class="text-sm font-medium text-gray-800"></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-24 flex-shrink-0">
                                <p class="text-xs text-gray-500">Pengalaman</p>
                            </div>
                            <div class="flex-1">
                            <p id="modal-dokter-semester" class="text-sm font-medium text-gray-800"></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-24 flex-shrink-0">
                                <p class="text-xs text-gray-500">Tempat Praktik</p>
                            </div>
                            <div class="flex-1">
                            <p id="modal-dokter-angkatan" class="text-sm font-medium text-gray-800"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <button onclick="hideDokterDetail()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 shadow-sm transition-colors">
                    Tutup
                </button>
                <button id="modal-pilih-dokter" onclick="selectDokterFromModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Pilih Dokter
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Custom dropdown animations */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    
    /* Update styles for new doctor card layout */
    .dokter-card {
        transition: all 0.3s ease;
    }
    
    .dokter-card.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
    
    /* Loading animation */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading:after {
        content: '';
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 2px solid transparent;
        border-top-color: white;
        position: absolute;
        right: 0.75rem;
        animation: loading-spinner 0.8s linear infinite;
    }
    
    @keyframes loading-spinner {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
        // Custom select dropdown functionality
        const customSelect = document.querySelector('.custom-select');
        const selectSelected = customSelect.querySelector('.select-selected');
        const selectItems = customSelect.querySelector('.select-items');
        const spesialisasiList = document.getElementById('spesialisasi-list');
        
        let selectedSpesialisasi = '';
        
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
        
        // Handle option selection
        spesialisasiList.querySelectorAll('div').forEach(item => {
            item.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const text = this.textContent.trim();
                
                selectSelected.querySelector('span').textContent = text;
                selectedSpesialisasi = value;
                
                // Add selected styling
                spesialisasiList.querySelectorAll('div').forEach(div => {
                    div.classList.remove('bg-blue-50', 'text-blue-600');
                });
                this.classList.add('bg-blue-50', 'text-blue-600');
                
                applyFilters();
                closeSelect();
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', closeSelect);
        
        function openSelect() {
            selectItems.classList.remove('hidden');
            selectSelected.querySelector('svg').classList.add('rotate-180');
            selectSelected.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
            selectItems.style.animation = 'slideDown 0.2s ease-out';
        }
        
        function closeSelect() {
            if (selectItems.classList.contains('hidden')) return;
            
            selectItems.style.animation = 'slideUp 0.2s ease-out';
            setTimeout(() => {
                selectItems.classList.add('hidden');
                selectSelected.querySelector('svg').classList.remove('rotate-180');
                selectSelected.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
            }, 180);
        }

        // Variabel untuk grid pagination
        const cards = document.querySelectorAll('.dokter-card');
        const prevBtn = document.getElementById('prev-slide');
        const nextBtn = document.getElementById('next-slide');
        const searchInput = document.getElementById('search-dokter');
        const totalDokter = cards.length;
        const itemsPerPage = 9; // Show exactly 9 doctors per page (3x3 grid)
        let currentPage = 0;
        let filteredCards = [...cards];
        
        // Form konsultasi
        const formKonsultasi = document.getElementById('form-konsultasi');
        const btnSubmit = document.getElementById('btn-submit');
        
        if (formKonsultasi) {
            formKonsultasi.addEventListener('submit', function(e) {
                const dokterId = document.getElementById('dokter_id').value;
                const tanggal = document.getElementById('tanggal').value;
                const jamMulai = document.getElementById('jam_mulai').value;
                const keluhan = document.getElementById('keluhan').value;
                
                // Validasi form sebelum submit
                if (!dokterId) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Silakan pilih dokter terlebih dahulu',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                if (!tanggal || !jamMulai || !keluhan) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Silakan lengkapi semua field yang diperlukan',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                // Tampilkan loading state
                btnSubmit.classList.add('btn-loading');
                btnSubmit.innerHTML = 'Memproses...';
                
                // Tampilkan notifikasi sedang memproses
                Swal.fire({
                    title: 'Membuat Janji Konsultasi',
                    html: 'Mohon tunggu sebentar...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                return true;
            });
        }
        
        // Update counter display
        const dokterShown = document.getElementById('dokterShown');
        const dokterTotal = document.getElementById('dokterTotal');
        dokterTotal.textContent = totalDokter;
        
        // Update counter
        function updateCounter() {
            const start = filteredCards.length > 0 ? currentPage * itemsPerPage + 1 : 0;
            const end = Math.min((currentPage + 1) * itemsPerPage, filteredCards.length);
            dokterShown.textContent = `${start}-${end}`;
            dokterTotal.textContent = filteredCards.length;
            
            // Show/hide navigation based on whether there are doctors
            if (filteredCards.length === 0) {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
            }
        }
        
        // Update display of doctor cards
        function updateDisplay() {
            // Hide all cards first
            cards.forEach(card => {
                card.style.display = 'none';
            });
            
            // Show only filtered and paginated cards
            filteredCards.forEach((card, index) => {
                if (index >= currentPage * itemsPerPage && index < (currentPage + 1) * itemsPerPage) {
                    card.style.display = 'block';
                }
            });
            
            // Update counter
            updateCounter();
            
            // Update button states
            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = (currentPage + 1) * itemsPerPage >= filteredCards.length;
        }
        
        // Apply filters (both search and specialization)
        function applyFilters() {
            const searchValue = searchInput.value.toLowerCase();
            const spesialisasiValue = selectedSpesialisasi;
            
            // Filter cards based on search and specialization
            filteredCards = [...cards].filter(card => {
                const name = card.dataset.name;
                const nim = card.dataset.nim;
                const spesialisasi = card.dataset.spesialisasi || '';
                
                // Apply both filters
                const matchesSearch = name.includes(searchValue) || nim.includes(searchValue);
                const matchesSpesialisasi = spesialisasiValue === '' || spesialisasi === spesialisasiValue;
                
                return matchesSearch && matchesSpesialisasi;
            });
            
            // Reset to first page
            currentPage = 0;
            updateDisplay();
        }
        
        // Handle search
        searchInput.addEventListener('input', applyFilters);
        
        // Initialize display
        updateDisplay();
        
        // Event listeners for pagination buttons
        prevBtn.addEventListener('click', function() {
            if (currentPage > 0) {
                currentPage--;
                updateDisplay();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            if ((currentPage + 1) * itemsPerPage < filteredCards.length) {
                currentPage++;
                updateDisplay();
            }
        });
        
        // Initialize date input
        const tanggalInput = document.getElementById('tanggal');
        if (tanggalInput) {
            tanggalInput.addEventListener('change', updateJamOptions);
        }
    });
    
    // Variabel untuk modal
    let currentDokterId = null;
    
    // Fungsi untuk menampilkan detail dokter
    function showDokterDetail(id, name, nim, spesialisasi, photo, pengalaman, tempat_praktik) {
        // Set current dokter id
        currentDokterId = id;
        
        // Populate modal with data
        document.getElementById('modal-dokter-photo').src = photo;
        document.getElementById('modal-dokter-name').textContent = name;
        document.getElementById('modal-dokter-nim').textContent = 'SIP/' + nim;
        document.getElementById('modal-dokter-fakultas').textContent = spesialisasi || 'Dokter Umum';
        document.getElementById('modal-dokter-semester').textContent = pengalaman || 'Belum diisi';
        document.getElementById('modal-dokter-angkatan').textContent = tempat_praktik || 'Belum diisi';
        
        // Show modal
        document.getElementById('dokterDetailModal').classList.remove('hidden');
    }
    
    // Fungsi untuk menyembunyikan detail dokter
    function hideDokterDetail() {
        document.getElementById('dokterDetailModal').classList.add('hidden');
    }
    
    // Fungsi untuk memilih dokter dari modal
    function selectDokterFromModal() {
        if (currentDokterId) {
            selectDokter(currentDokterId);
            hideDokterDetail();
        }
    }
    
    // Fungsi untuk memilih dokter
    function selectDokter(id) {
        document.getElementById('dokter_id').value = id;
        
        // Remove selected class from all cards
        document.querySelectorAll('.dokter-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Find the correct card and add selected class
        const cards = document.querySelectorAll('.dokter-card');
        cards.forEach(card => {
            if (card.dataset.id == id) {
                card.classList.add('selected');
                // Scroll to the selected card if not visible
                if (card.getBoundingClientRect().top < 0 || card.getBoundingClientRect().bottom > window.innerHeight) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Update jam options based on selected dokter
        updateJamOptions();
    }
    
    // Fungsi untuk memperbarui opsi jam berdasarkan dokter dan tanggal yang dipilih
    function updateJamOptions() {
        const dokterId = document.getElementById('dokter_id').value;
        const tanggalValue = document.getElementById('tanggal').value;
        const jamSelect = document.getElementById('jam_mulai');
        
        if (!dokterId || !tanggalValue || !jamSelect) {
            return;
        }
        
        // Data jadwal terisi dari controller
        const jadwalTerisi = @json($jadwalTerisi);
        
        // Reset semua opsi jam
        for (let i = 0; i < jamSelect.options.length; i++) {
            const option = jamSelect.options[i];
            
            if (option.value === "") continue; // Skip opsi default "Pilih Jam"
            
            // Reset disabled state dan text
            option.disabled = false;
            option.text = option.text.replace(' (Sudah Terisi)', '');
            
            // Cek apakah slot ini sudah terisi untuk dokter yang dipilih
            const jamKey = option.value + ':00';
            if (jadwalTerisi[tanggalValue] && 
                jadwalTerisi[tanggalValue][jamKey] && 
                jadwalTerisi[tanggalValue][jamKey].includes(parseInt(dokterId))) {
                
                option.disabled = true;
                option.text += ' (Sudah Terisi)';
            }
        }
    }
    
    // Menampilkan notifikasi warning jika ada
    @if(session('warning'))
        Swal.fire({
            title: 'Perhatian!',
            text: "{{ session('warning') }}",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
    
    // Menampilkan notifikasi error jika ada
    @if($errors->any())
        Swal.fire({
            title: 'Oops!',
            html: `
                <div class="text-left">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-600">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endpush
@endsection 