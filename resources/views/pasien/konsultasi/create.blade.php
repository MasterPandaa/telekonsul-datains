@extends('layouts.pasien')

@section('pasien-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Buat Permintaan Konsultasi</h1>
    <p class="text-sm text-gray-600">Isi formulir untuk membuat janji konsultasi dengan mahasiswa kedokteran</p>
</div>

<!-- Error messages will be handled by SweetAlert -->

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <form action="{{ route('pasien.konsultasi.store') }}" method="POST" id="form-konsultasi">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilih Mahasiswa -->
                <div class="md:col-span-2">
                    <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700 mb-3">Pilih Mahasiswa</label>
                    
                    <!-- Filter dan Pencarian -->
                    <div class="mb-4 flex flex-col md:flex-row gap-4">
                        <div class="relative flex-grow">
                            <input type="text" id="search-mahasiswa" placeholder="Cari nama atau NIM..." class="pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                            <div class="absolute left-3 top-2.5">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slider Navigation -->
                    <div class="flex justify-between items-center mb-3 bg-gray-50 p-3 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700">
                            <span id="mahasiswaShown">1-12</span> dari <span id="mahasiswaTotal">{{ count($mahasiswa) }}</span> mahasiswa
                        </h3>
                        <div class="flex space-x-2">
                            <button type="button" id="prev-slide" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button type="button" id="next-slide" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Mahasiswa List with Slider -->
                    <div class="overflow-hidden">
                        <div id="mahasiswa-slider">
                            @foreach($mahasiswa as $index => $m)
                                <div class="mahasiswa-card" 
                                     data-name="{{ strtolower($m->name) }}" 
                                     data-nim="{{ strtolower($m->mahasiswa->nim ?? '') }}"
                                     data-id="{{ $m->id }}">
                                    <div class="p-4">
                                        <div class="flex justify-center mb-2">
                                            @if($m->mahasiswa && $m->mahasiswa->foto)
                                                <img class="h-16 w-16 rounded-full object-cover" src="{{ asset($m->mahasiswa->foto) }}" alt="{{ $m->name }}">
                                            @else
                                                <div class="h-16 w-16 rounded-full flex items-center justify-center text-white text-xl font-semibold bg-blue-600">
                                                    {{ substr($m->name, 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-center mb-3">
                                            <p class="text-sm font-medium">{{ $m->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $m->mahasiswa->nim ?? 'NIM belum diisi' }}</p>
                                            <p class="text-xs text-gray-500">Fakultas {{ $m->mahasiswa->fakultas ?? 'belum diisi' }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" class="w-1/2 flex items-center justify-center bg-blue-100 text-blue-700 rounded text-xs py-1 px-2"
                                                    onclick="showMahasiswaDetail({{ $m->id }}, '{{ $m->name }}', '{{ $m->mahasiswa->nim ?? 'Belum diisi' }}', '{{ $m->mahasiswa->fakultas ?? 'Belum diisi' }}', '{{ $m->mahasiswa && $m->mahasiswa->foto ? asset($m->mahasiswa->foto) : 'https://ui-avatars.com/api/?name='.urlencode($m->name).'&background=4F46E5&color=fff' }}', '{{ $m->mahasiswa->semester ?? 'Belum diisi' }}', '{{ $m->mahasiswa->angkatan ?? 'Belum diisi' }}')">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                    <path d="M12 16v-4m0-4h.01" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                Detail
                                            </button>
                                            <button type="button" class="w-1/2 flex items-center justify-center bg-green-100 text-green-700 rounded text-xs py-1 px-2"
                                                    onclick="selectMahasiswa({{ $m->id }})">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Pilih
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="mahasiswa_id" id="mahasiswa_id" required>
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
                    <select name="jam_mulai" id="jam_mulai" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">-- Pilih Jam --</option>
                        @if(count($jam_tersedia) > 0)
                        @foreach($jam_tersedia as $key => $value)
                                @php
                                    $isBooked = false;
                                    $selectedDate = request('tanggal', $tanggal_mulai);
                                    $selectedMahasiswa = request('mahasiswa_id', 0);
                                    
                                    // Cek apakah slot ini sudah terisi untuk mahasiswa yang dipilih
                                    if (isset($jadwalTerisi[$selectedDate][$key.':00']) && 
                                        in_array($selectedMahasiswa, $jadwalTerisi[$selectedDate][$key.':00'])) {
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
                    @if(count($jam_tersedia) < 28)
                        <p class="mt-1 text-xs text-yellow-600">
                            <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Beberapa slot waktu tidak tersedia karena sudah lewat atau penuh
                        </p>
                    @endif
                </div>
                
                <!-- Keluhan Utama -->
                <div class="md:col-span-2">
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Keluhan Utama</label>
                    <input type="text" name="keluhan" id="keluhan" placeholder="Contoh: Sakit kepala dan demam" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                
                <!-- Keterangan Tambahan -->
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" placeholder="Jelaskan lebih detail tentang keluhan Anda, seperti sejak kapan, seberapa parah, dll." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                
                <!-- Informasi Tambahan -->
                <div class="md:col-span-2 bg-blue-50 p-4 rounded-md">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Penting</h3>
                    <ul class="list-disc pl-5 text-xs text-blue-700 space-y-1">
                        <li>Pastikan Anda telah mengisi data profil dengan lengkap dan akurat</li>
                        <li>Jadwal konsultasi dapat berubah jika mahasiswa tidak tersedia</li>
                        <li>Anda akan mendapatkan notifikasi jika permintaan konsultasi telah dikonfirmasi</li>
                        <li>Waktu konsultasi hanya dapat dipilih minimal 15 menit dari sekarang untuk hari ini</li>
                        <li>Jika semua slot waktu hari ini sudah lewat, sistem akan otomatis menetapkan tanggal minimum besok</li>
                        <li>Durasi setiap sesi konsultasi adalah 15 menit</li>
                        <li>Untuk kondisi darurat, segera hubungi layanan gawat darurat atau kunjungi rumah sakit terdekat</li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('pasien.konsultasi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" id="btn-submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Buat Janji Konsultasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Mahasiswa -->
<div id="mahasiswaDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="hideMahasiswaDetail()"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Mahasiswa</h3>
                <button onclick="hideMahasiswaDetail()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mt-4 flex flex-col items-center">
                <img id="modal-mahasiswa-photo" class="h-32 w-32 rounded-full object-cover border-4 border-blue-100" src="" alt="Foto Mahasiswa">
                <h3 id="modal-mahasiswa-name" class="mt-3 text-xl font-medium text-gray-900"></h3>
                <p id="modal-mahasiswa-nim" class="text-sm text-gray-600 mt-1"></p>
            </div>
            
            <div class="mt-6 grid grid-cols-1 gap-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Informasi Akademik</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Fakultas</p>
                            <p id="modal-mahasiswa-fakultas" class="text-sm font-medium text-gray-800"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Semester</p>
                            <p id="modal-mahasiswa-semester" class="text-sm font-medium text-gray-800"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Angkatan</p>
                            <p id="modal-mahasiswa-angkatan" class="text-sm font-medium text-gray-800"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <button onclick="hideMahasiswaDetail()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Tutup
                </button>
                <button id="modal-pilih-mahasiswa" onclick="selectMahasiswaFromModal()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Pilih Mahasiswa
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Bersihkan style */
    #mahasiswa-slider {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    
    @media (max-width: 1024px) {
        #mahasiswa-slider {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        #mahasiswa-slider {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        #mahasiswa-slider {
            grid-template-columns: 1fr;
        }
    }
    
    .mahasiswa-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    }
    
    .mahasiswa-card.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
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
        // Variabel untuk slider
        const slider = document.getElementById('mahasiswa-slider');
        const prevBtn = document.getElementById('prev-slide');
        const nextBtn = document.getElementById('next-slide');
        const cards = document.querySelectorAll('.mahasiswa-card');
        const totalMahasiswa = cards.length;
        const searchInput = document.getElementById('search-mahasiswa');
        const itemsPerPage = 12; // Selalu tampilkan 12 mahasiswa per halaman (3 baris x 4 kolom)
        let currentPage = 0;
        let filteredCards = [...cards];
        
        // Form konsultasi
        const formKonsultasi = document.getElementById('form-konsultasi');
        const btnSubmit = document.getElementById('btn-submit');
        
        if (formKonsultasi) {
            formKonsultasi.addEventListener('submit', function(e) {
                const mahasiswaId = document.getElementById('mahasiswa_id').value;
                const tanggal = document.getElementById('tanggal').value;
                const jamMulai = document.getElementById('jam_mulai').value;
                const keluhan = document.getElementById('keluhan').value;
                
                // Validasi form sebelum submit
                if (!mahasiswaId) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Silakan pilih mahasiswa terlebih dahulu',
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
        const mahasiswaShown = document.getElementById('mahasiswaShown');
        const mahasiswaTotal = document.getElementById('mahasiswaTotal');
        mahasiswaTotal.textContent = totalMahasiswa;
        
        // Update counter
        function updateCounter() {
            const start = currentPage * itemsPerPage + 1;
            const end = Math.min((currentPage + 1) * itemsPerPage, filteredCards.length);
            mahasiswaShown.textContent = `${filteredCards.length > 0 ? start : 0}-${end}`;
            mahasiswaTotal.textContent = filteredCards.length;
        }
        
        // Update slider
        function updateSlider() {
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
        
        // Handle search
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            
            // Filter cards based on search
            filteredCards = [...cards].filter(card => {
                const name = card.dataset.name;
                const nim = card.dataset.nim;
                return name.includes(searchValue) || nim.includes(searchValue);
            });
            
            // Reset to first page
            currentPage = 0;
            updateSlider();
        });
        
        // Initialize slider
        updateSlider();
        
        // Event listeners for buttons
        prevBtn.addEventListener('click', function() {
            if (currentPage > 0) {
                currentPage--;
                updateSlider();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            if ((currentPage + 1) * itemsPerPage < filteredCards.length) {
                currentPage++;
                updateSlider();
            }
        });
        
        // Inisialisasi tanggal konsultasi
        const tanggalInput = document.getElementById('tanggal');
        if (tanggalInput) {
            tanggalInput.addEventListener('change', updateJamOptions);
        }
    });
    
    // Variabel untuk modal
    let currentMahasiswaId = null;
    
    // Fungsi untuk menampilkan detail mahasiswa
    function showMahasiswaDetail(id, name, nim, fakultas, photo, semester, angkatan) {
        // Set current mahasiswa id
        currentMahasiswaId = id;
        
        // Populate modal with data
        document.getElementById('modal-mahasiswa-photo').src = photo;
        document.getElementById('modal-mahasiswa-name').textContent = name;
        document.getElementById('modal-mahasiswa-nim').textContent = nim;
        document.getElementById('modal-mahasiswa-fakultas').textContent = fakultas || 'Belum diisi';
        document.getElementById('modal-mahasiswa-semester').textContent = semester || 'Belum diisi';
        document.getElementById('modal-mahasiswa-angkatan').textContent = angkatan || 'Belum diisi';
        
        // Show modal
        document.getElementById('mahasiswaDetailModal').classList.remove('hidden');
    }
    
    // Fungsi untuk menyembunyikan detail mahasiswa
    function hideMahasiswaDetail() {
        document.getElementById('mahasiswaDetailModal').classList.add('hidden');
    }
    
    // Fungsi untuk memilih mahasiswa dari modal
    function selectMahasiswaFromModal() {
        if (currentMahasiswaId) {
            selectMahasiswa(currentMahasiswaId);
            hideMahasiswaDetail();
        }
    }
    
    // Modifikasi fungsi selectMahasiswa
    function selectMahasiswa(id) {
        document.getElementById('mahasiswa_id').value = id;
        
        // Remove selected class from all cards
        document.querySelectorAll('.mahasiswa-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Find the correct card and add selected class
        const cards = document.querySelectorAll('.mahasiswa-card');
        cards.forEach(card => {
            if (card.dataset.id == id) {
                card.classList.add('selected');
            }
        });
        
        // Update jam options based on selected mahasiswa
        updateJamOptions();
    }
    
    // Fungsi untuk memperbarui opsi jam berdasarkan mahasiswa dan tanggal yang dipilih
    function updateJamOptions() {
        const mahasiswaId = document.getElementById('mahasiswa_id').value;
        const tanggalValue = document.getElementById('tanggal').value;
        const jamSelect = document.getElementById('jam_mulai');
        
        if (!mahasiswaId || !tanggalValue || !jamSelect) {
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
            
            // Cek apakah slot ini sudah terisi untuk mahasiswa yang dipilih
            const jamKey = option.value + ':00';
            if (jadwalTerisi[tanggalValue] && 
                jadwalTerisi[tanggalValue][jamKey] && 
                jadwalTerisi[tanggalValue][jamKey].includes(parseInt(mahasiswaId))) {
                
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