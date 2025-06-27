<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Dosen;
use App\Models\Konsultasi;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 dokter users
        $this->command->info('Creating 10 dokter users...');
        $dokterUsers = [];
        $dokters = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => 'Dr. ' . fake()->firstName() . ' ' . fake()->lastName(),
                'email' => 'dokter' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
            ]);
            
            $dokterUsers[] = $user;
            
            $dokter = Dokter::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'no_sip' => fake()->unique()->numerify('SIP/##########'),
                'no_str' => fake()->unique()->numerify('STR/##########'),
                'email' => $user->email,
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'tempat_lahir' => fake()->city(),
                'tanggal_lahir' => fake()->date('Y-m-d', '-30 years'),
                'spesialisasi' => fake()->randomElement(['Umum', 'Penyakit Dalam', 'Anak', 'Bedah', 'Jantung', 'Kulit dan Kelamin', 'THT', 'Mata', 'Saraf', 'Gigi']),
                'sub_spesialisasi' => fake()->optional(0.5)->word(),
                'universitas' => fake()->randomElement(['Universitas Indonesia', 'Universitas Gadjah Mada', 'Universitas Airlangga', 'Universitas Padjadjaran', 'Universitas Diponegoro']),
                'tahun_lulus' => fake()->numberBetween(2000, 2020),
                'tempat_praktik' => fake()->optional(0.8)->company(),
                'rumah_sakit' => fake()->optional(0.8)->company() . ' Hospital',
                'status' => 'Aktif',
                'pengalaman' => fake()->paragraph(),
                'foto' => 'img/dokter/default.jpg',
            ]);
            
            $dokters[] = $dokter;
        }
        
        // Create 10 pasien users
        $this->command->info('Creating 10 pasien users...');
        $pasienUsers = [];
        $pasiens = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => 'pasien' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
            ]);
            
            $pasienUsers[] = $user;
            
            $pasien = Pasien::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'nik' => fake()->unique()->numerify('################'),
                'email' => $user->email,
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'tempat_lahir' => fake()->city(),
                'tanggal_lahir' => fake()->date('Y-m-d', '-20 years'),
                'tinggi_badan' => fake()->numberBetween(150, 190),
                'berat_badan' => fake()->numberBetween(45, 100),
                'tekanan_darah' => fake()->randomElement(['120/80', '130/85', '110/70']),
                'riwayat_penyakit' => fake()->optional(0.7)->sentence(),
                'alergi' => fake()->optional(0.5)->sentence(),
                'foto' => 'img/pasien/default.jpg',
            ]);
            
            $pasiens[] = $pasien;
        }
        
        // Create 10 dosen users
        $this->command->info('Creating 10 dosen users...');
        $dosenUsers = [];
        $dosens = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => 'Prof. Dr. ' . fake()->firstName() . ' ' . fake()->lastName() . ', ' . fake()->randomElement(['M.Kes', 'Sp.PD', 'Ph.D']),
                'email' => 'dosen' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]);
            
            $dosenUsers[] = $user;
            
            $dosen = Dosen::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'nip' => fake()->unique()->numerify('##########'),
                'email' => $user->email,
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
            ]);
            
            $dosens[] = $dosen;
        }
        
        // Tambahkan default users ke array
        $this->command->info('Adding default users to data generation...');
        
        // Ambil default dokter user
        $defaultDokterUser = User::where('email', 'dokter@example.com')->first();
        if ($defaultDokterUser) {
            $dokterUsers[] = $defaultDokterUser;
            $defaultDokter = Dokter::where('user_id', $defaultDokterUser->id)->first();
            if ($defaultDokter) {
                $dokters[] = $defaultDokter;
            }
        }
        
        // Ambil default pasien user
        $defaultPasienUser = User::where('email', 'pasien@example.com')->first();
        if ($defaultPasienUser) {
            $pasienUsers[] = $defaultPasienUser;
            $defaultPasien = Pasien::where('user_id', $defaultPasienUser->id)->first();
            if ($defaultPasien) {
                $pasiens[] = $defaultPasien;
            }
        }
        
        // Create konsultasi data
        $this->command->info('Creating konsultasi data...');
        
        $statuses = [
            'Menunggu', 'Terkonfirmasi', 'Ditolak', 'Selesai', 'Dibatalkan', 'Terlambat', 'Berlangsung'
        ];
        
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
            'Hidung tersumbat dan bersin-bersin',
            'Nyeri dada sebelah kiri',
            'Kesemutan pada tangan dan kaki',
            'Nyeri punggung bawah',
            'Gangguan tidur dan insomnia',
            'Kelelahan dan lemas'
        ];
        
        $diagnosaList = [
            'Infeksi saluran pernapasan akut (ISPA)',
            'Gastroenteritis akut',
            'Hipertensi',
            'Dermatitis kontak',
            'Migrain',
            'Infeksi saluran kemih',
            'Asma bronkial',
            'Sindrom iritasi usus besar',
            'Konjungtivitis',
            'Faringitis',
            'Dispepsia',
            'Low back pain',
            'Rhinitis alergi',
            'Vertigo posisional',
            'Anemia defisiensi besi'
        ];
        
        $catatanList = [
            'Disarankan istirahat cukup dan minum air putih minimal 2L/hari',
            'Hindari makanan pedas dan berlemak selama masa pemulihan',
            'Kontrol kembali setelah 1 minggu untuk evaluasi',
            'Jika keluhan tidak membaik dalam 3 hari, segera kontrol kembali',
            'Lakukan kompres dingin pada area yang bengkak',
            'Hindari aktivitas berat selama 1 minggu',
            'Rutin minum obat sesuai jadwal yang diberikan',
            'Pantau tekanan darah setiap pagi dan malam',
            'Jaga kebersihan area yang terinfeksi',
            'Hindari kontak dengan alergen yang sudah diketahui'
        ];
        
        $chatMessages = [
            [
                'dokter' => 'Selamat pagi, apa keluhan Anda saat ini?',
                'pasien' => 'Pagi dok, saya mengalami demam dan sakit kepala sejak kemarin malam.',
                'dokter' => 'Berapa suhu tubuh Anda terakhir diukur?',
                'pasien' => 'Sekitar 38.5°C dok, dan saya juga merasa badan saya lemas.',
                'dokter' => 'Apakah ada gejala lain seperti batuk, pilek, atau nyeri tenggorokan?',
                'pasien' => 'Ada sedikit batuk kering, tapi tidak ada pilek.',
                'dokter' => 'Baik, berdasarkan gejala yang Anda alami, kemungkinan Anda mengalami infeksi virus. Saya akan berikan resep untuk meredakan gejala.',
                'pasien' => 'Terima kasih dok, apakah saya perlu istirahat di rumah?',
                'dokter' => 'Ya, sebaiknya Anda istirahat di rumah selama 2-3 hari dan minum banyak air putih. Jika demam tidak turun dalam 3 hari, segera kontrol kembali.',
                'pasien' => 'Baik dok, terima kasih atas sarannya.'
            ],
            [
                'dokter' => 'Halo, selamat siang. Apa yang bisa saya bantu?',
                'pasien' => 'Siang dok, saya mengalami nyeri perut bagian bawah sejak 2 hari yang lalu.',
                'dokter' => 'Apakah nyerinya terus-menerus atau hilang timbul?',
                'pasien' => 'Hilang timbul dok, tapi semakin parah setelah makan.',
                'dokter' => 'Apakah ada perubahan pada buang air besar atau kecil?',
                'pasien' => 'Ya, saya mengalami diare ringan sejak kemarin.',
                'dokter' => 'Berdasarkan gejala yang Anda sampaikan, kemungkinan Anda mengalami gastroenteritis. Saya akan berikan obat untuk meredakan gejala.',
                'pasien' => 'Apakah saya perlu diet khusus, dok?',
                'dokter' => 'Ya, hindari makanan pedas, berlemak, dan minuman berkafein selama beberapa hari. Konsumsi makanan lunak dan minum banyak cairan untuk mencegah dehidrasi.',
                'pasien' => 'Baik, terima kasih banyak dok.'
            ],
            [
                'dokter' => 'Selamat sore, apa keluhan Anda?',
                'pasien' => 'Sore dok, saya mengalami gatal-gatal di kulit sejak 3 hari yang lalu.',
                'dokter' => 'Apakah ada riwayat alergi sebelumnya?',
                'pasien' => 'Ya dok, saya alergi terhadap debu dan serbuk bunga.',
                'dokter' => 'Apakah Anda menggunakan produk baru belakangan ini, seperti sabun atau deterjen?',
                'pasien' => 'Ya, saya baru ganti sabun mandi 4 hari yang lalu.',
                'dokter' => 'Sepertinya Anda mengalami dermatitis kontak akibat sabun baru tersebut. Saya sarankan untuk menghentikan penggunaan sabun tersebut dan kembali ke sabun yang biasa Anda gunakan.',
                'pasien' => 'Baik dok, apakah ada obat yang bisa meredakan gatal ini?',
                'dokter' => 'Ya, saya akan berikan antihistamin dan krim kortikosteroid untuk meredakan gatal dan peradangan. Oleskan krim 2 kali sehari pada area yang gatal.',
                'pasien' => 'Terima kasih banyak atas sarannya dok.'
            ],
            [
                'dokter' => 'Selamat malam, apa yang bisa saya bantu?',
                'pasien' => 'Malam dok, saya mengalami sakit kepala yang sangat parah sejak tadi siang.',
                'dokter' => 'Apakah sakit kepalanya di satu sisi atau menyeluruh?',
                'pasien' => 'Di satu sisi saja dok, sebelah kanan, dan terasa berdenyut.',
                'dokter' => 'Apakah ada gejala lain seperti mual, muntah, atau sensitif terhadap cahaya dan suara?',
                'pasien' => 'Ya dok, saya mual dan sangat sensitif terhadap cahaya.',
                'dokter' => 'Berdasarkan gejala yang Anda alami, kemungkinan Anda mengalami migrain. Sudah berapa lama dan seberapa sering Anda mengalami gejala seperti ini?',
                'pasien' => 'Sudah sekitar 2 tahun, biasanya muncul saat saya stress atau kurang tidur.',
                'dokter' => 'Saya akan berikan obat untuk meredakan gejala migrain. Untuk pencegahan, penting untuk mengelola stress dan menjaga pola tidur yang teratur.',
                'pasien' => 'Baik dok, terima kasih banyak.'
            ],
            [
                'dokter' => 'Selamat pagi, apa keluhan Anda?',
                'pasien' => 'Pagi dok, saya mengalami nyeri punggung bawah yang sangat mengganggu sejak 1 minggu yang lalu.',
                'dokter' => 'Apakah ada cedera atau aktivitas berat sebelum nyeri muncul?',
                'pasien' => 'Ya dok, saya mengangkat barang berat seminggu yang lalu.',
                'dokter' => 'Apakah nyerinya menjalar ke kaki atau hanya di punggung bawah saja?',
                'pasien' => 'Hanya di punggung bawah saja dok.',
                'dokter' => 'Sepertinya Anda mengalami low back pain akibat strain otot. Saya akan berikan obat pereda nyeri dan anti-inflamasi.',
                'pasien' => 'Apakah saya perlu istirahat total?',
                'dokter' => 'Tidak perlu istirahat total, tapi hindari mengangkat beban berat dan aktivitas yang membebani punggung selama 2 minggu. Kompres hangat juga bisa membantu meredakan nyeri.',
                'pasien' => 'Terima kasih atas sarannya dok.'
            ]
        ];
        
        // Create konsultasi for each dokter-pasien combination
        $konsultasiCount = 0;
        
        foreach ($dokterUsers as $dokterUser) {
            foreach ($pasiens as $pasien) {
                // Create 5 completed konsultasi
                for ($i = 1; $i <= 5; $i++) {
                    $tanggal = Carbon::now()->subDays(rand(5, 30));
                    $jamMulai = Carbon::createFromTime(rand(8, 15), rand(0, 30), 0);
                    $jamSelesai = (clone $jamMulai)->addHours(1);
                    
                    $konsultasi = Konsultasi::create([
                        'pasien_id' => $pasien->id,
                        'dokter_id' => $dokterUser->id,
                        'dosen_id' => null,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_mulai' => $jamMulai->format('H:i'),
                        'jam_selesai' => $jamSelesai->format('H:i'),
                        'keluhan' => fake()->randomElement($keluhanList),
                        'keterangan' => fake()->optional(0.7)->paragraph(),
                        'diagnosa' => fake()->randomElement($diagnosaList),
                        'catatan' => fake()->randomElement($catatanList),
                        'nilai' => null,
                        'nilai_dosen' => null,
                        'catatan_dosen' => null,
                        'rating' => rand(3, 5),
                        'komentar_rating' => fake()->optional(0.5)->sentence(),
                        'status' => 'Selesai',
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);
                    
                    // Create chat room and messages
                    $chatRoom = ChatRoom::create([
                        'konsultasi_id' => $konsultasi->id,
                        'room_id' => Str::uuid(),
                        'is_active' => false,
                        'started_at' => (clone $tanggal)->setTimeFromTimeString($jamMulai->format('H:i:s')),
                        'ended_at' => (clone $tanggal)->setTimeFromTimeString($jamSelesai->format('H:i:s')),
                    ]);
                    
                    // Create chat messages
                    $messageTime = (clone $tanggal)->setTimeFromTimeString($jamMulai->format('H:i').':00');
                    
                    // Buat array pesan dokter dan pasien secara bergantian
                    $messages = [];
                    $roles = [];
                    
                    // Gunakan pesan default untuk semua konsultasi
                    $defaultMessages = [
                        "Halo, apa keluhan Anda?",
                        "Saya mengalami " . $konsultasi->keluhan,
                        "Sejak kapan Anda mengalami keluhan tersebut?",
                        "Sudah sekitar 3 hari yang lalu",
                        "Apakah ada gejala lain yang Anda rasakan?",
                        "Saya juga merasa " . fake()->randomElement(['pusing', 'mual', 'demam', 'lemas', 'nyeri']),
                    ];
                    
                    for ($j = 0; $j < count($defaultMessages); $j++) {
                        $messages[] = $defaultMessages[$j];
                        $roles[] = ($j % 2 == 0) ? 'dokter' : 'pasien';
                    }
                    
                    // Buat pesan chat
                    for ($i = 0; $i < count($messages); $i++) {
                        $role = $roles[$i];
                        $message = $messages[$i];
                        $messageTime = (clone $messageTime)->addMinutes(rand(1, 3));
                        
                        if ($messageTime > (clone $tanggal)->setTimeFromTimeString($jamSelesai->format('H:i').':00')) {
                            break;
                        }
                        
                        ChatMessage::create([
                            'chat_room_id' => $chatRoom->id,
                            'user_id' => $role === 'dokter' ? $dokterUser->id : $pasien->user_id,
                            'message' => $message,
                            'is_read' => true,
                            'created_at' => $messageTime,
                            'updated_at' => $messageTime,
                        ]);
                    }
                    
                    // Tambahkan diagnosa sebagai pesan terakhir dari dokter
                    $messageTime = (clone $messageTime)->addMinutes(rand(1, 3));
                    if ($messageTime <= (clone $tanggal)->setTimeFromTimeString($jamSelesai->format('H:i').':00')) {
                        ChatMessage::create([
                            'chat_room_id' => $chatRoom->id,
                            'user_id' => $dokterUser->id,
                            'message' => "Diagnosa: " . $konsultasi->diagnosa . "\n\nSaran: " . $konsultasi->catatan,
                            'is_read' => true,
                            'created_at' => $messageTime,
                            'updated_at' => $messageTime,
                        ]);
                    }
                    
                    // Add dosen evaluation to some konsultasi
                    if ($i <= 3) { // 60% of completed konsultasi have dosen evaluations
                        $dosen = fake()->randomElement($dosens);
                        
                        $konsultasi->dosen_id = $dosen->id;
                        $konsultasi->nilai_komunikasi = rand(60, 100);
                        $konsultasi->nilai_anamnesis = rand(60, 100);
                        $konsultasi->nilai_diagnosa = rand(60, 100);
                        $konsultasi->nilai_empati = rand(60, 100);
                        $konsultasi->nilai_dosen = round(($konsultasi->nilai_komunikasi + $konsultasi->nilai_anamnesis + $konsultasi->nilai_diagnosa + $konsultasi->nilai_empati) / 4);
                        $konsultasi->save();
                    }
                    
                    $konsultasiCount++;
                }
                
                // Create 5 cancelled konsultasi
                for ($i = 1; $i <= 5; $i++) {
                    $tanggal = Carbon::now()->subDays(rand(5, 30));
                    $jamMulai = Carbon::createFromTime(rand(8, 15), rand(0, 30), 0);
                    $jamSelesai = (clone $jamMulai)->addHours(1);
                    
                    Konsultasi::create([
                        'pasien_id' => $pasien->id,
                        'dokter_id' => $dokterUser->id,
                        'dosen_id' => null,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_mulai' => $jamMulai->format('H:i'),
                        'jam_selesai' => $jamSelesai->format('H:i'),
                        'keluhan' => fake()->randomElement($keluhanList),
                        'keterangan' => fake()->optional(0.7)->paragraph(),
                        'diagnosa' => null,
                        'catatan' => null,
                        'nilai' => null,
                        'nilai_dosen' => null,
                        'catatan_dosen' => null,
                        'rating' => null,
                        'komentar_rating' => null,
                        'status' => fake()->randomElement(['Dibatalkan', 'Ditolak']),
                        'alasan_tolak' => fake()->optional(0.7)->sentence(),
                        'alasan_batal' => fake()->optional(0.7)->sentence(),
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);
                    
                    $konsultasiCount++;
                }
                
                // Create 5 waiting konsultasi
                for ($i = 1; $i <= 5; $i++) {
                    $tanggal = Carbon::now()->addDays(rand(1, 14));
                    $jamMulai = Carbon::createFromTime(rand(8, 15), rand(0, 30), 0);
                    $jamSelesai = (clone $jamMulai)->addHours(1);
                    
                    Konsultasi::create([
                        'pasien_id' => $pasien->id,
                        'dokter_id' => $dokterUser->id,
                        'dosen_id' => null,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_mulai' => $jamMulai->format('H:i'),
                        'jam_selesai' => $jamSelesai->format('H:i'),
                        'keluhan' => fake()->randomElement($keluhanList),
                        'keterangan' => fake()->optional(0.7)->paragraph(),
                        'diagnosa' => null,
                        'catatan' => null,
                        'nilai' => null,
                        'nilai_dosen' => null,
                        'catatan_dosen' => null,
                        'rating' => null,
                        'komentar_rating' => null,
                        'status' => fake()->randomElement(['Menunggu', 'Terkonfirmasi']),
                        'created_at' => Carbon::now()->subDays(rand(1, 5)),
                        'updated_at' => Carbon::now()->subDays(rand(1, 5)),
                    ]);
                    
                    $konsultasiCount++;
                }
            }
        }
        
        $this->command->info("Created {$konsultasiCount} konsultasi records with chat data.");
    }
}
