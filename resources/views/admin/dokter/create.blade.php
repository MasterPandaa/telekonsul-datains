@extends('layouts.admin')
@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Dokter Baru</h1>
        <p class="text-sm text-gray-600">Lengkapi form berikut untuk menambahkan data dokter baru</p>
    </div>
    <a href="{{ route('admin.dokter.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 space-y-6">
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5.455 19h13.09c1.54 0 2.502-1.667 1.732-3L13.732 5c-.77-1.333-2.694-1.333-3.464 0L3.723 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-800">
            <p class="font-semibold mb-2">Panduan pengisian:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>No. SIP hanya huruf kapital, angka, atau simbol <code class="bg-white px-1 rounded">/ . -</code> (5-50 karakter).</li>
                <li>No. STR wajib 13 digit angka tanpa spasi atau tanda baca.</li>
                <li>Email harus aktif dan unik, contoh: <strong>nama@institusi.ac.id</strong>.</li>
                <li>Password min. 8 karakter, wajib huruf besar, huruf kecil, dan angka.</li>
                <li>Nomor HP diawali 08 dan berisi 10-13 digit angka.</li>
            </ul>
        </div>

        <form action="{{ route('admin.dokter.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           class="w-full px-3 py-2 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- No SIP -->
                <div>
                    <label for="no_sip" class="block text-sm font-medium text-gray-700 mb-1">No. SIP <span class="text-red-500">*</span></label>
                    <div class="flex space-x-2">
                        <input type="text" name="no_sip" id="no_sip" value="{{ old('no_sip') }}" required 
                               class="flex-1 px-3 py-2 border @error('no_sip') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase">
                        <button type="button" id="btn-check-sip" class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                            Periksa
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Contoh: <span class="font-semibold">SIP/123/456/2025</span></p>
                    @error('no_sip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No STR -->
                <div>
                    <label for="no_str" class="block text-sm font-medium text-gray-700 mb-1">No. STR <span class="text-red-500">*</span></label>
                    <div class="flex space-x-2">
                        <input type="text" name="no_str" id="no_str" value="{{ old('no_str') }}" 
                               class="flex-1 px-3 py-2 border @error('no_str') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" maxlength="13">
                        <button type="button" id="btn-check-str" class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                            Periksa
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Isi 13 digit angka tanpa spasi atau tanda baca.</p>
                    @error('no_str')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           class="w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Gunakan email aktif yang dapat menerima informasi sistem.</p>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                           class="w-full px-3 py-2 border @error('no_hp') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Contoh: 081234567890 (10-13 digit, diawali 08).</p>
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Min. 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka.</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.dokter.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Sweet alert untuk notifikasi error
    @if($errors->any())
    Swal.fire({
        title: 'Error!',
        html: `<ul class="text-left">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
              </ul>`,
        icon: 'error',
        iconColor: '#ef4444',
        showConfirmButton: true,
        confirmButtonColor: '#3b82f6',
        confirmButtonText: 'Baik, saya mengerti',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-xl shadow-md',
            title: 'text-red-600'
        }
    });
    @endif

    const checkConfig = {
        sip: {
            input: document.getElementById('no_sip'),
            button: document.getElementById('btn-check-sip'),
            url: '{{ route("admin.dokter.check-sip") }}',
            param: 'no_sip',
            emptyMessage: 'Masukkan Nomor SIP terlebih dahulu.'
        },
        str: {
            input: document.getElementById('no_str'),
            button: document.getElementById('btn-check-str'),
            url: '{{ route("admin.dokter.check-str") }}',
            param: 'no_str',
            emptyMessage: 'Masukkan Nomor STR terlebih dahulu.'
        }
    };

    Object.values(checkConfig).forEach(({ button, input, url, param, emptyMessage }) => {
        button.addEventListener('click', async () => {
            const value = input.value.trim();
            if (!value) {
                Swal.fire('Informasi', emptyMessage, 'info');
                return;
            }

            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');
            try {
                const query = new URLSearchParams({ [param]: value });
                const response = await fetch(`${url}?${query.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                Swal.fire({
                    title: data.available ? 'Nomor tersedia' : 'Nomor sudah terpakai',
                    text: data.message ?? '',
                    icon: data.available ? 'success' : 'warning',
                    confirmButtonColor: data.available ? '#10b981' : '#f97316'
                });
            } catch (error) {
                console.error(error);
                Swal.fire('Terjadi Kesalahan', 'Tidak dapat memeriksa saat ini. Coba beberapa saat lagi.', 'error');
            } finally {
                button.disabled = false;
                button.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        });
    });
</script>
@endpush

@endsection