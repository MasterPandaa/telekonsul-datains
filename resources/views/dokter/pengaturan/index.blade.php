@extends('layouts.dokter')

@section('dokter-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h1>
    <p class="text-sm text-gray-600">Kelola keamanan dan preferensi akun dokter Anda tanpa meninggalkan dashboard.</p>
</div>

<div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                    <p class="text-sm text-gray-500">Perkuat keamanan akun dengan mengganti password Anda secara berkala.</p>
                </div>
                <div class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.66 0 3-1.567 3-3.5S13.66 4 12 4 9 5.567 9 7.5 10.34 11 12 11zM17 20c0-2.21-2.239-4-5-4s-5 1.79-5 4" />
                    </svg>
                    Info Pribadi
                </div>
            </div>

            <form method="POST" action="{{ route('dokter.pengaturan.update') }}" class="px-6 py-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <div class="mt-1 relative">
                            <input type="password" id="current_password" name="current_password" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('current_password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <div class="mt-1 relative">
                                <input type="password" id="password" name="password" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Minimal 8 karakter">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Gunakan kombinasi huruf besar, kecil, angka, dan simbol.</p>
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <div class="mt-1 relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 space-y-3">
            <div class="flex items-start space-x-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <div>
                    <h3 class="text-sm font-semibold text-blue-800">Setelah Perubahan</h3>
                    <p class="text-sm text-blue-700">Anda tetap masuk di perangkat ini, namun wajib login ulang di perangkat lain menggunakan password baru.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#0ea5e9'
        });
        @endif

        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: `<ul class="text-left space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>`,
            confirmButtonColor: '#ef4444'
        });
        @endif
    });
</script>
@endpush