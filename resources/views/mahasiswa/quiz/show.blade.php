@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Quiz Diabetes Mellitus</h1>
        <p class="text-sm text-gray-600">Evaluasi pengetahuan tentang penanganan pasien diabetes</p>
    </div>
    <a href="{{ route('mahasiswa.quiz.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<!-- Quiz Info Box -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="flex items-center p-4 bg-blue-50 rounded-lg">
            <div class="mr-4 bg-blue-100 rounded-full p-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-blue-700">Waktu</p>
                <p class="text-base font-semibold text-blue-800">30 menit</p>
            </div>
        </div>
        <div class="flex items-center p-4 bg-purple-50 rounded-lg">
            <div class="mr-4 bg-purple-100 rounded-full p-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-purple-700">Jumlah Soal</p>
                <p class="text-base font-semibold text-purple-800">10 pertanyaan</p>
            </div>
        </div>
        <div class="flex items-center p-4 bg-yellow-50 rounded-lg">
            <div class="mr-4 bg-yellow-100 rounded-full p-2">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-yellow-700">Batas Waktu</p>
                <p class="text-base font-semibold text-yellow-800">13 Juni 2023</p>
            </div>
        </div>
    </div>
</div>

<!-- Quiz Content -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Pertanyaan Quiz</h2>
            <div class="flex items-center bg-blue-50 rounded-lg px-3 py-1">
                <svg class="w-5 h-5 text-blue-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium text-blue-700">Sisa waktu: 28:45</span>
            </div>
        </div>
    </div>
    
    <form action="#" method="POST">
        @csrf
        <div class="p-6 space-y-8">
            <!-- Pertanyaan 1 -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-2 mb-3">
                    <span class="flex items-center justify-center bg-blue-600 text-white rounded-full w-6 h-6 text-sm font-medium">1</span>
                    <h3 class="text-md font-medium text-gray-800">Apa ciri-ciri utama dari diabetes melitus tipe 2?</h3>
                </div>
                <div class="ml-8 space-y-3">
                    <div class="flex items-center">
                        <input id="q1_a" name="q1" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q1_a" class="ml-3 block text-sm text-gray-700">
                            Defisiensi insulin absolut akibat kerusakan sel beta pankreas
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q1_b" name="q1" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q1_b" class="ml-3 block text-sm text-gray-700">
                            Resistensi insulin dan defisiensi insulin relatif
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q1_c" name="q1" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q1_c" class="ml-3 block text-sm text-gray-700">
                            Hanya menyerang anak-anak dan remaja
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q1_d" name="q1" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q1_d" class="ml-3 block text-sm text-gray-700">
                            Selalu memerlukan terapi insulin sejak awal diagnosis
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pertanyaan 2 -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-2 mb-3">
                    <span class="flex items-center justify-center bg-blue-600 text-white rounded-full w-6 h-6 text-sm font-medium">2</span>
                    <h3 class="text-md font-medium text-gray-800">Berapakah nilai HbA1c yang menjadi target pengobatan untuk pasien diabetes pada umumnya?</h3>
                </div>
                <div class="ml-8 space-y-3">
                    <div class="flex items-center">
                        <input id="q2_a" name="q2" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q2_a" class="ml-3 block text-sm text-gray-700">
                            &lt; 5.0%
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q2_b" name="q2" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q2_b" class="ml-3 block text-sm text-gray-700">
                            &lt; 6.0%
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q2_c" name="q2" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q2_c" class="ml-3 block text-sm text-gray-700">
                            &lt; 7.0%
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q2_d" name="q2" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q2_d" class="ml-3 block text-sm text-gray-700">
                            &lt; 8.5%
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pertanyaan 3 -->
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-2 mb-3">
                    <span class="flex items-center justify-center bg-blue-600 text-white rounded-full w-6 h-6 text-sm font-medium">3</span>
                    <h3 class="text-md font-medium text-gray-800">Dalam konsultasi jarak jauh untuk pasien diabetes, aspek mana yang paling penting untuk dimonitor secara rutin?</h3>
                </div>
                <div class="ml-8 space-y-3">
                    <div class="flex items-center">
                        <input id="q3_a" name="q3" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q3_a" class="ml-3 block text-sm text-gray-700">
                            Pengukuran tinggi badan
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q3_b" name="q3" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q3_b" class="ml-3 block text-sm text-gray-700">
                            Kadar gula darah mandiri
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q3_c" name="q3" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q3_c" class="ml-3 block text-sm text-gray-700">
                            Suhu tubuh harian
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="q3_d" name="q3" type="radio" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="q3_d" class="ml-3 block text-sm text-gray-700">
                            Jumlah konsumsi karbohidrat harian
                        </label>
                    </div>
                </div>
            </div>

            <!-- Navigasi Soal -->
            <div class="grid grid-cols-10 gap-2 pt-6">
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-medium">1</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-medium">2</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-medium">3</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">4</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">5</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">6</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">7</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">8</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">9</button>
                <button type="button" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-gray-700 font-medium">10</button>
            </div>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-between">
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Sebelumnya
            </button>
            <div>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition mr-2">
                    Simpan Sementara
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Warning Modal -->
<div id="warningModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-800 opacity-50"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 p-6">
        <div class="flex items-center justify-center mb-4 text-yellow-500">
            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Perhatian!</h3>
        <p class="text-gray-600 text-center mb-6">
            Anda akan meninggalkan halaman quiz. Jawaban Anda akan otomatis tersimpan. Yakin ingin melanjutkan?
        </p>
        <div class="flex justify-center space-x-3">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" onclick="document.getElementById('warningModal').classList.add('hidden')">
                Batal
            </button>
            <a href="{{ route('mahasiswa.quiz.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Ya, Simpan & Keluar
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simulasi timer
    let duration = 1725; // 28 minutes 45 seconds
    const timerDisplay = document.querySelector('.text-blue-700');
    
    function updateTimer() {
        const minutes = Math.floor(duration / 60);
        const seconds = duration % 60;
        timerDisplay.textContent = `Sisa waktu: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        
        if (duration > 0) {
            duration--;
            setTimeout(updateTimer, 1000);
        } else {
            // Automatically submit when time runs out
            alert('Waktu habis! Quiz akan otomatis dikumpulkan.');
            document.querySelector('form').submit();
        }
    }
    
    // Start the timer
    updateTimer();
    
    // Show warning modal when user tries to leave
    document.querySelector('a[href="{{ route("mahasiswa.quiz.index") }}"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('warningModal').classList.remove('hidden');
    });
</script>
@endpush
@endsection 