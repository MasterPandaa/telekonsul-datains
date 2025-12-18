@extends('layouts.admin')
@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Dokter</h1>
        <p class="text-sm text-gray-600">Perbarui informasi dokter: {{ $dokter->nama }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.dokter.show', $dokter) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Lihat Detail
        </a>
        <a href="{{ route('admin.dokter.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <form action="{{ route('admin.dokter.update', $dokter) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $dokter->nama) }}" required 
                           class="w-full px-3 py-2 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required class="w-full px-3 py-2 border @error('jenis_kelamin') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $dokter->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $dokter->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No SIP -->
                <div>
                    <label for="no_sip" class="block text-sm font-medium text-gray-700 mb-1">No. SIP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_sip" id="no_sip" value="{{ old('no_sip', $dokter->no_sip) }}" required 
                           class="w-full px-3 py-2 border @error('no_sip') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="no_sip"></p>
                    @error('no_sip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No STR -->
                <div>
                    <label for="no_str" class="block text-sm font-medium text-gray-700 mb-1">No. STR</label>
                    <input type="text" name="no_str" id="no_str" value="{{ old('no_str', $dokter->no_str) }}" 
                           class="w-full px-3 py-2 border @error('no_str') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="no_str"></p>
                    @error('no_str')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $dokter->email) }}" required 
                           class="w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}" required
                           class="w-full px-3 py-2 border @error('no_hp') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxxx</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="no_hp"></p>
                </div>
                
                <!-- Tempat Lahir -->
                <div>
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $dokter->tempat_lahir) }}"
                           class="w-full px-3 py-2 border @error('tempat_lahir') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tempat_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $dokter->tanggal_lahir ? \Illuminate\Support\Carbon::parse($dokter->tanggal_lahir)->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border @error('tanggal_lahir') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Universitas -->
                <div>
                    <label for="universitas" class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                    <input type="text" name="universitas" id="universitas" value="{{ old('universitas', $dokter->universitas) }}"
                           class="w-full px-3 py-2 border @error('universitas') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('universitas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Lulus -->
                <div>
                    <label for="tahun_lulus" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" id="tahun_lulus" value="{{ old('tahun_lulus', $dokter->tahun_lulus) }}" min="1950" max="{{ now()->year }}"
                           class="w-full px-3 py-2 border @error('tahun_lulus') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tahun_lulus')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spesialisasi -->
                <div>
                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <input type="text" name="spesialisasi" id="spesialisasi" value="{{ old('spesialisasi', $dokter->spesialisasi) }}"
                           class="w-full px-3 py-2 border @error('spesialisasi') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('spesialisasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Praktik -->
                <div>
                    <label for="tempat_praktik" class="block text-sm font-medium text-gray-700 mb-1">Tempat Praktik</label>
                    <input type="text" name="tempat_praktik" id="tempat_praktik" value="{{ old('tempat_praktik', $dokter->tempat_praktik) }}"
                           class="w-full px-3 py-2 border @error('tempat_praktik') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tempat_praktik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rumah Sakit -->
                <div>
                    <label for="rumah_sakit" class="block text-sm font-medium text-gray-700 mb-1">Rumah Sakit</label>
                    <input type="text" name="rumah_sakit" id="rumah_sakit" value="{{ old('rumah_sakit', $dokter->rumah_sakit) }}"
                           class="w-full px-3 py-2 border @error('rumah_sakit') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('rumah_sakit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="w-full px-3 py-2 border @error('alamat') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $dokter->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengalaman -->
                <div class="md:col-span-2">
                    <label for="pengalaman" class="block text-sm font-medium text-gray-700 mb-1">Pengalaman</label>
                    <textarea name="pengalaman" id="pengalaman" rows="3" class="w-full px-3 py-2 border @error('pengalaman') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('pengalaman', $dokter->pengalaman) }}</textarea>
                    @error('pengalaman')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border @error('status') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach(['Aktif', 'Tidak Aktif', 'Cuti'] as $status)
                            <option value="{{ $status }}" {{ old('status', $dokter->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Profile -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 border">
                            <img src="{{ $dokter->foto_url }}" alt="{{ $dokter->nama }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto" id="foto" accept="image/*" class="w-full text-sm text-gray-700">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password"></p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password_confirmation"></p>
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.dokter.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

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
        no_sip: value => {
            if (!value) return 'Nomor SIP wajib diisi';
            return /^[A-Z0-9\/.\-]{5,50}$/.test(value.toUpperCase()) ? '' : 'Format SIP hanya huruf kapital, angka, dan /. -';
        },
        no_str: value => {
            if (!value) return '';
            return /^\d{13}$/.test(value) ? '' : 'Nomor STR harus berisi 13 digit angka';
        },
        no_hp: value => {
            if (!value) return 'Nomor HP wajib diisi';
            return /^08\d{8,11}$/.test(value) ? '' : 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit';
        },
        password: value => {
            if (!value) return '';
            if (value.length < 8) return 'Password minimal 8 karakter';
            return '';
        },
        password_confirmation: (value, allValues) => {
            if (!allValues.password && !value) return '';
            if (!allValues.password && value) return 'Isi password terlebih dahulu';
            if (allValues.password && !value) return 'Konfirmasi password wajib diisi';
            return value === allValues.password ? '' : 'Konfirmasi password tidak sama';
        }
    };

    const watchFields = ['no_sip', 'no_str', 'no_hp', 'password', 'password_confirmation'];

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