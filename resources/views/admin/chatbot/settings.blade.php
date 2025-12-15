@extends('layouts.admin')

@section('admin-content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Chatbot API</h1>
        <p class="text-sm text-gray-600">Atur koneksi webhook n8n / agen AI utama yang digunakan HealsAI.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-start space-x-3">
            <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div>
                <p class="font-semibold text-sm">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <p class="font-semibold text-sm mb-2">Form belum valid:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.chatbot.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Webhook Target</h2>
                <p class="text-sm text-gray-500">Masukkan URL webhook n8n atau layanan eksternal lain yang menerima request chatbot.</p>
            </div>

            <div class="grid gap-5">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Production</label>
                        <input type="url" name="webhook_url_prod"
                            value="{{ old('webhook_url_prod', $setting->webhook_url_prod ?? $setting->webhook_url) }}"
                            placeholder="https://flow.n8n.cloud/webhook/chatbot-prod"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-2 text-xs text-gray-500">Digunakan otomatis ketika aplikasi berjalan di environment
                            <span class="font-semibold text-gray-700">production</span>.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Testing</label>
                        <input type="url" name="webhook_url_test"
                            value="{{ old('webhook_url_test', $setting->webhook_url_test ?? $setting->webhook_url) }}"
                            placeholder="https://flow.n8n.cloud/webhook/chatbot-test"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-2 text-xs text-gray-500">Dipakai saat aplikasi berjalan di environment local/staging.</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Default (opsional)</label>
                    <input type="url" name="webhook_url" value="{{ old('webhook_url', $setting->webhook_url) }}"
                        placeholder="Fallback URL jika salah satu mode belum diisi"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-2 text-xs text-gray-500">Jika Production/Testing kosong, sistem akan memakai URL ini.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                        <select name="method" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ($methods as $method)
                                <option value="{{ $method }}" @selected(old('method', $setting->method ?? 'POST') === $method)>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Timeout (detik)</label>
                        <input type="number" name="timeout" min="5" max="300"
                            value="{{ old('timeout', $setting->timeout ?? 15) }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-2 text-xs text-gray-500">Maksimal 300 detik (5 menit).</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <input type="hidden" name="allow_insecure_ssl" value="0">
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="allow_insecure_ssl" value="1"
                        @checked(old('allow_insecure_ssl', $setting->allow_insecure_ssl) ? true : false)
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-600">Izinkan SSL tidak valid (hanya untuk testing)</span>
                </label>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Otentikasi</h2>
                <p class="text-sm text-gray-500">Sesuaikan dengan mekanisme keamanan yang digunakan di webhook tujuan.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Auth</label>
                    <select name="auth_type" id="authTypeSelect"
                        class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($authTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('auth_type', $setting->auth_type ?? 'none') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div data-auth="basic" class="col-span-2 auth-section hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" name="basic_user" value="{{ old('basic_user', $setting->basic_user) }}"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="text" name="basic_pass" value="{{ old('basic_pass', $setting->basic_pass) }}"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div data-auth="bearer" class="col-span-2 auth-section hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bearer Token</label>
                    <textarea name="bearer_token" rows="2"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan token">{{ old('bearer_token', $setting->bearer_token) }}</textarea>
                </div>

                <div data-auth="header" class="col-span-2 auth-section hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Header</label>
                            <input type="text" name="header_key" value="{{ old('header_key', $setting->header_key) }}"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="x-api-key">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Header</label>
                            <input type="text" name="header_value" value="{{ old('header_value', $setting->header_value) }}"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div data-auth="jwt" class="col-span-2 auth-section hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">JWT Token</label>
                    <textarea name="jwt_token" rows="2"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan token">{{ old('jwt_token', $setting->jwt_token) }}</textarea>
                </div>

                <div data-auth="none" class="col-span-2 auth-section hidden text-sm text-gray-500">
                    Endpoint tidak membutuhkan credential tambahan.
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                Simpan Konfigurasi
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const select = document.getElementById('authTypeSelect');
                const sections = document.querySelectorAll('.auth-section');

                function toggleSections() {
                    const value = select.value;
                    sections.forEach(section => {
                        if (section.dataset.auth === value) {
                            section.classList.remove('hidden');
                        } else if (section.dataset.auth === 'none') {
                            section.classList.toggle('hidden', value !== 'none');
                        } else {
                            section.classList.add('hidden');
                        }
                    });
                }

                select.addEventListener('change', toggleSections);
                toggleSections();
            });
        </script>
    @endpush
@endsection
