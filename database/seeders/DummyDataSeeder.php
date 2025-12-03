<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Dosen;
use App\Models\Konsultasi;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('id_ID');

        $this->command?->info('Resetting konsultasi data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ChatMessage::truncate();
        ChatRoom::truncate();
        Konsultasi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $dokterUsers = User::where('role', 'dokter')->with('dokter')->get();
        $pasienRecords = Pasien::with('user')->get();
        $dosenRecords = Dosen::with('user')->get();

        if ($dokterUsers->isEmpty() || $pasienRecords->isEmpty()) {
            $this->command?->warn('Tidak ada data dokter atau pasien. Lewati DummyDataSeeder.');
            return;
        }

        $keluhanList = [
            'Demam tinggi dan sakit kepala',
            'Batuk kering dan sesak napas',
            'Nyeri perut bagian bawah',
            'Sakit tenggorokan dan sulit menelan',
            'Gatal-gatal pada kulit',
            'Nyeri sendi dan otot',
            'Gangguan pencernaan dan mual',
            'Pusing dan vertigo',
            'Mata merah dan berair',
            'Nyeri dada sebelah kiri',
        ];

        $diagnosaList = [
            'Infeksi saluran pernapasan akut (ISPA)',
            'Gastroenteritis akut',
            'Hipertensi',
            'Dermatitis kontak',
            'Migrain',
            'Asma bronkial',
            'Sindrom iritasi usus besar',
            'Konjungtivitis',
            'Faringitis',
            'Low back pain',
        ];

        $catatanList = [
            'Disarankan istirahat cukup dan minum air putih minimal 2L/hari',
            'Hindari makanan pedas dan berlemak selama masa pemulihan',
            'Kontrol kembali setelah 1 minggu untuk evaluasi',
            'Jika keluhan tidak membaik dalam 3 hari, segera kontrol kembali',
            'Pantau tekanan darah setiap pagi dan malam',
        ];

        $chatTemplates = [
            [
                'dokter' => 'Selamat pagi, apa keluhan utama Anda hari ini?',
                'pasien' => 'Saya mengalami ' . $faker->randomElement(['demam', 'batuk kering', 'nyeri perut', 'pusing']) . ' sejak kemarin.',
                'dokter' => 'Apakah ada gejala lain yang menyertai?',
                'pasien' => 'Ada sedikit ' . $faker->randomElement(['mual', 'gatal', 'sesak napas', 'lemas']),
            ],
            [
                'dokter' => 'Selamat siang, apa yang bisa saya bantu?',
                'pasien' => 'Saya merasa tidak nyaman pada bagian ' . $faker->randomElement(['tenggorokan', 'dada', 'punggung']).'.',
                'dokter' => 'Sudah berapa lama keluhan tersebut dirasakan?',
                'pasien' => 'Kurang lebih ' . rand(2, 5) . ' hari, dok.',
            ],
        ];

        $selectedPasien = $pasienRecords->take(min(6, $pasienRecords->count()));

        $completedCount = 0;
        $scheduledCount = 0;
        $cancelledCount = 0;

        foreach ($selectedPasien as $index => $pasien) {
            $dokterUser = $dokterUsers[$index % $dokterUsers->count()];

            // Completed consultation with chat history
            $startTime = Carbon::now()->subDays(rand(5, 14))->setTime(rand(8, 17), rand(0, 45), 0);
            $endTime = (clone $startTime)->addMinutes(30);

            $konsultasi = Konsultasi::create([
                'pasien_id' => $pasien->id,
                'dokter_id' => $dokterUser->id,
                'dosen_id' => $dosenRecords->isNotEmpty() && $index % 2 === 0 ? $dosenRecords[$index % $dosenRecords->count()]->id : null,
                'tanggal' => $startTime->toDateString(),
                'jam_mulai' => $startTime->format('H:i:s'),
                'jam_selesai' => $endTime->format('H:i:s'),
                'keluhan' => $faker->randomElement($keluhanList),
                'keterangan' => $faker->optional()->paragraph(),
                'diagnosa' => $faker->randomElement($diagnosaList),
                'catatan' => $faker->randomElement($catatanList),
                'nilai' => rand(75, 95),
                'nilai_dosen' => $dosenRecords->isNotEmpty() ? rand(75, 95) : null,
                'nilai_komunikasi' => rand(70, 95),
                'nilai_anamnesis' => rand(70, 95),
                'nilai_diagnosa' => rand(70, 95),
                'nilai_empati' => rand(70, 95),
                'catatan_dosen' => $dosenRecords->isNotEmpty() ? $faker->optional()->sentence() : null,
                'rating' => rand(4, 5),
                'komentar_rating' => $faker->optional()->sentence(),
                'status' => 'Selesai',
            ]);

            $chatTemplate = $chatTemplates[$index % count($chatTemplates)];
            $chatRoom = ChatRoom::create([
                'konsultasi_id' => $konsultasi->id,
                'room_id' => Str::uuid(),
                'is_active' => false,
                'started_at' => $startTime,
                'ended_at' => $endTime,
            ]);

            $messageTimestamp = (clone $startTime);
            foreach ($chatTemplate as $role => $message) {
                ChatMessage::create([
                    'chat_room_id' => $chatRoom->id,
                    'user_id' => $role === 'dokter' ? $dokterUser->id : $pasien->user_id,
                    'message' => $message,
                    'is_read' => true,
                    'created_at' => $messageTimestamp,
                    'updated_at' => $messageTimestamp,
                ]);
                $messageTimestamp = (clone $messageTimestamp)->addMinutes(3);
            }

            ChatMessage::create([
                'chat_room_id' => $chatRoom->id,
                'user_id' => $dokterUser->id,
                'message' => 'Diagnosa: ' . $konsultasi->diagnosa . "\n\nSaran: " . $konsultasi->catatan,
                'is_read' => true,
                'created_at' => $endTime,
                'updated_at' => $endTime,
            ]);

            $completedCount++;

            // Upcoming booking
            $bookingStart = Carbon::now()->addDays(rand(2, 10))->setTime(rand(8, 17), rand(0, 45), 0);
            $bookingStatus = $index % 2 === 0 ? 'Terkonfirmasi' : 'Menunggu';

            Konsultasi::create([
                'pasien_id' => $pasien->id,
                'dokter_id' => $dokterUser->id,
                'dosen_id' => null,
                'tanggal' => $bookingStart->toDateString(),
                'jam_mulai' => $bookingStart->format('H:i:s'),
                'jam_selesai' => $bookingStart->copy()->addMinutes(30)->format('H:i:s'),
                'keluhan' => $faker->randomElement($keluhanList),
                'keterangan' => $faker->optional()->paragraph(),
                'status' => $bookingStatus,
            ]);

            $scheduledCount++;

            // Cancelled consultation
            $cancelStart = Carbon::now()->subDays(rand(2, 8))->setTime(rand(8, 17), rand(0, 45), 0);

            Konsultasi::create([
                'pasien_id' => $pasien->id,
                'dokter_id' => $dokterUser->id,
                'dosen_id' => null,
                'tanggal' => $cancelStart->toDateString(),
                'jam_mulai' => $cancelStart->format('H:i:s'),
                'jam_selesai' => $cancelStart->copy()->addMinutes(30)->format('H:i:s'),
                'keluhan' => $faker->randomElement($keluhanList),
                'keterangan' => $faker->optional()->paragraph(),
                'status' => $index % 2 === 0 ? 'Dibatalkan' : 'Ditolak',
                'alasan_batal' => 'Pasien membatalkan karena ' . $faker->randomElement(['jadwal bentrok', 'kondisi membaik']),
                'alasan_tolak' => $faker->randomElement(['Dokter tidak tersedia di jadwal tersebut', 'Permintaan dialihkan ke jadwal lain']),
            ]);

            $cancelledCount++;
        }

        $this->command?->info("Dummy konsultasi selesai: {$completedCount} riwayat, {$scheduledCount} jadwal aktif, {$cancelledCount} pembatalan.");
    }
}
