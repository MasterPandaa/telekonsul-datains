@extends('layouts.admin')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Dosen</h1>
        <p class="text-sm text-gray-600">Perbarui data dosen pada form di bawah</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.dosen.show', $dosen) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Lihat Detail
        </a>
        <a href="{{ route('admin.dosen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" required class="w-full px-3 py-2 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-red-500">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $dosen->nip) }}" required class="w-full px-3 py-2 border @error('nip') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="nip"></p>
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $dosen->email) }}" required class="w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-3 py-2 border @error('jenis_kelamin') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ old('jenis_kelamin', $dosen->jenis_kelamin) ? '' : 'selected' }}>Pilih</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $dosen->no_hp) }}" class="w-full px-3 py-2 border @error('no_hp') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Contoh: 081234567890 (10-13 digit, diawali 08).</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="no_hp"></p>
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="w-full px-3 py-2 border @error('alamat') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $dosen->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 border">
                            <img id="foto-preview" src="{{ $dosen->foto_url }}" alt="{{ $dosen->nama }}" class="h-full w-full object-cover cursor-pointer" onclick="openFotoUploadModal()">
                        </div>
                        <div class="flex-1">
                            <button type="button" onclick="openFotoUploadModal()" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                Ubah Foto
                            </button>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password"></p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-red-600 mt-1 hidden" data-error-for="password_confirmation"></p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.dosen.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
                </div>
            </div>

            <div id="uploadFotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="closeFotoUploadModal()"></div>
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Upload Foto Profil</h3>
                        <div class="mt-2 px-7 py-3">
                            <div class="w-40 h-40 mx-auto bg-gray-200 mb-4 overflow-hidden rounded-full">
                                <img id="foto-preview-modal" src="{{ $dosen->foto_url }}" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Foto
                                </label>
                                <input type="file" name="foto" id="foto" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB</p>
                            </div>
                            <div class="mt-5 flex justify-center space-x-3">
                                <button type="button" onclick="closeFotoUploadModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Tutup
                                </button>
                                <button type="button" onclick="closeFotoUploadModal()" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Gunakan
                                </button>
                            </div>
                        </div>
                    </div>
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

    // Photo preview functionality
    const fotoInput = document.getElementById('foto');
    const fotoPreview = document.getElementById('foto-preview');
    const fotoPreviewModal = document.getElementById('foto-preview-modal');
    
    if (fotoInput && fotoPreview) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                    if (fotoPreviewModal) {
                        fotoPreviewModal.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function openFotoUploadModal() {
        document.getElementById('uploadFotoModal')?.classList.remove('hidden');
    }

    function closeFotoUploadModal() {
        document.getElementById('uploadFotoModal')?.classList.add('hidden');
    }
</script>
@endpush