@extends('layouts.admin')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Dosen Baru</h1>
        <p class="text-sm text-gray-600">Lengkapi form berikut untuk menambahkan data dosen baru</p>
    </div>
    <a href="{{ route('admin.dosen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
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
            <p class="font-semibold mb-2">Panduan pengisian data awal:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>NIP wajib diisi (maksimal 50 karakter).</li>
                <li>Nomor HP diawali 08 dan berisi 10-13 digit angka.</li>
                <li>Password minimal 8 karakter.</li>
            </ul>
        </div>

        <form action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required class="w-full px-3 py-2 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-red-500">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required class="w-full px-3 py-2 border @error('nip') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Pastikan NIP sesuai data resmi.</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="nip"></p>
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-3 py-2 border @error('jenis_kelamin') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-3 py-2 border @error('no_hp') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Contoh: 081234567890 (10-13 digit, diawali 08).</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="no_hp"></p>
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="w-full px-3 py-2 border @error('alamat') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Min. 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka.</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password"></p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password_confirmation"></p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.dosen.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
        nip: value => {
            if (!value) return 'NIP wajib diisi';
            return value.length <= 50 ? '' : 'NIP maksimal 50 karakter';
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

    const watchFields = ['nip', 'no_hp', 'password', 'password_confirmation'];

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