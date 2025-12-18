@extends('layouts.admin')
@section('admin-content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Pasien Baru</h1>
        <p class="text-sm text-gray-600">Lengkapi form berikut untuk menambahkan data pasien baru</p>
    </div>
    <a href="{{ route('admin.pasien.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-800 mb-6">
            <p class="font-semibold mb-2">Panduan pengisian data awal:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>NIK wajib diisi (maksimal 50 karakter).</li>
                <li>Nomor HP diawali 08 dan berisi 10-13 digit angka.</li>
                <li>Password minimal 8 karakter.</li>
            </ul>
        </div>

        <form action="{{ route('admin.pasien.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           class="w-full px-3 py-2 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required 
                           class="w-full px-3 py-2 border @error('nik') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Masukkan NIK sesuai KTP.</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="nik"></p>
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           class="w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                            class="w-full px-3 py-2 border @error('jenis_kelamin') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                           class="w-full px-3 py-2 border @error('no_hp') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Contoh: 081234567890 (10-13 digit, diawali 08).</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="no_hp"></p>
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
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password"></p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password_confirmation"></p>
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.pasien.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
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

    const showFieldError = (input, field, message) => {
        const errorEl = document.querySelector(`[data-error-for="${field}"]`);
        if (!errorEl) {
            return;
        }

        if (message) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
            input.classList.add('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
            input.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
        } else {
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
            input.classList.remove('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
            input.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
        }
    };

    const fieldValidators = {
        nik: value => {
            if (!value) return 'NIK wajib diisi';
            return value.length <= 50 ? '' : 'NIK maksimal 50 karakter';
        },
        no_hp: value => {
            if (!value) return '';
            return /^08\d{8,11}$/.test(value) ? '' : 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit';
        },
        password: value => {
            if (!value) return 'Password wajib diisi';
            if (value.length < 8) return 'Password minimal 8 karakter';
            return '';
        },
        password_confirmation: (value, allValues) => {
            if (!value) return 'Konfirmasi password wajib diisi';
            return value === allValues.password ? '' : 'Konfirmasi password tidak sama';
        }
    };

    const watchFields = ['nik', 'no_hp', 'password', 'password_confirmation'];

    const getAllValues = () => ({
        password: document.getElementById('password')?.value ?? '',
        password_confirmation: document.getElementById('password_confirmation')?.value ?? ''
    });

    watchFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input) return;

        const handler = () => {
            const values = getAllValues();
            const message = fieldValidators[field] ? fieldValidators[field](input.value.trim(), values) : '';
            showFieldError(input, field, message);
        };

        input.addEventListener('input', handler);
        input.addEventListener('blur', handler);
    });
</script>
@endpush

@endsection 