<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HealsAiController extends Controller
{
    /**
     * Mengirim pesan ke API HealsAI (yang menggunakan Gemini di backend)
     */
    public function getResponse(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
            'is_new_conversation' => 'nullable|boolean',
        ]);

        $message = $request->message;
        $history = $request->history ?? [];
        $isNewConversation = $request->is_new_conversation ?? false;

        try {
            // Gunakan nilai dari .env untuk keamanan
            $apiKey = env('HEALSAI_API_KEY');
            $model = env('HEALSAI_MODEL');
            
            // Mode simulasi dari .env
            $simulationMode = env('HEALSAI_SIMULATION_MODE', true);
            
            // Jika dalam mode simulasi, berikan respons langsung tanpa memanggil API
            if ($simulationMode) {
                return $this->getSimulatedResponse($message, $isNewConversation);
            }
            
            // Format pesan dengan riwayat percakapan
            $promptText = $this->formatPromptWithHistory($message, $history, $isNewConversation);
            
            // Format data untuk API Gemini 2.0 Flash
            $data = [
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" => $promptText
                            ]
                        ]
                    ]
                ],
                "generationConfig" => [
                    "temperature" => 0.7,
                    "topK" => 40,
                    "topP" => 0.95,
                    "maxOutputTokens" => 1024,
                ],
                "safetySettings" => [
                    [
                        "category" => "HARM_CATEGORY_HARASSMENT",
                        "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                    ],
                    [
                        "category" => "HARM_CATEGORY_HATE_SPEECH",
                        "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                    ],
                    [
                        "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                        "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                    ],
                    [
                        "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                        "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
                    ]
                ]
            ];
            
            // Panggil API Gemini (tapi tetap dengan label HealsAI)
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
            
            $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($url, $data);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // Ekstrak teks respons dari hasil Gemini
                $responseText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';
                
                return response()->json([
                    'success' => true,
                    'response' => $responseText
                ]);
            } else {
                Log::error('HealsAI API Error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal terhubung dengan layanan AI',
                    'error' => $response->json()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('HealsAI API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Format prompt dengan riwayat percakapan
     */
    private function formatPromptWithHistory($message, $history, $isNewConversation)
    {
        $prompt = "Kamu adalah HealsAI, asisten kesehatan pintar. Berikut adalah pedoman penting yang HARUS kamu ikuti:\n\n";
        
        // Pedoman dasar
        $prompt .= "1. Berikan jawaban yang RAMAH, EXCITED, INTERAKTIF, akurat, dan informatif dalam Bahasa Indonesia yang natural seperti dokter profesional yang bersahabat.\n";
        $prompt .= "2. Fokus HANYA pada informasi kesehatan yang dapat dipercaya dan berbasis bukti ilmiah terkini.\n";
        
        // Format dan kreativitas
        $prompt .= "3. FORMAT JAWABAN DAN KREATIVITAS:\n";
        $prompt .= "   a. SANGAT PENTING: Gunakan DUA baris kosong (dua kali Enter) untuk memisahkan paragraf, sehingga jawabanmu terlihat lebih rapi dan mudah dibaca. Selalu berikan jarak kosong yang cukup antar paragraf. Contoh format yang benar:\n";
        $prompt .= "      Paragraf pertama tentang suatu topik.\n\n";
        $prompt .= "      Paragraf kedua dengan informasi lanjutan.\n\n";
        $prompt .= "      Paragraf ketiga dengan informasi tambahan.\n\n";
        $prompt .= "   b. SANGAT PENTING: Saat membuat daftar atau poin-poin, berikan indentasi dan format yang tepat. Contoh format yang benar:\n\n";
        $prompt .= "      Berikut adalah beberapa contoh penyakit:\n\n";
        $prompt .= "      - Contoh penyakit pertama dan penjelasannya\n";
        $prompt .= "      - Contoh penyakit kedua dan penjelasannya\n";
        $prompt .= "      - Contoh penyakit ketiga dan penjelasannya\n\n";
        $prompt .= "      Atau dengan format alfabet:\n\n";
        $prompt .= "      a. Langkah pertama dalam prosedur\n";
        $prompt .= "      b. Langkah kedua dalam prosedur\n";
        $prompt .= "      c. Langkah ketiga dalam prosedur\n\n";
        $prompt .= "      Atau dengan format angka:\n\n";
        $prompt .= "      1. Poin pertama yang penting\n";
        $prompt .= "      2. Poin kedua yang penting\n";
        $prompt .= "      3. Poin ketiga yang penting\n\n";
        $prompt .= "   c. Tunjukkan ANTUSIASME dan KERAMAHAN dengan sesekali menggunakan kata seru dan kalimat yang mengandung semangat positif.\n";
        $prompt .= "   d. Gunakan sapaan yang ramah dan hangat (\"Hai\", \"Halo\", menggunakan emoji sesekali).\n";
        $prompt .= "   e. Variasikan cara menjawab agar tidak monoton dan terkesan lebih natural dan bersahabat.\n";
        $prompt .= "   f. Sesuaikan nada dan gaya bahasa berdasarkan topik (lebih santai dan cheerful untuk topik umum, lebih empati dan supportive untuk kondisi serius).\n";
        $prompt .= "   g. Gunakan analogi atau perumpamaan sederhana untuk menjelaskan konsep yang kompleks.\n";
        $prompt .= "   h. Berikan apresiasi atau semangat kepada pasien (\"Pertanyaan bagus!\", \"Keren sekali Anda sudah memperhatikan ini\", \"Anda sudah melakukan langkah tepat\").\n";
        $prompt .= "   i. Gunakan beberapa kalimat pendek di antara kalimat panjang untuk menciptakan ritme dalam percakapan.\n";
        $prompt .= "   j. Mulai jawaban dengan berbagai variasi pembuka yang ramah dan excited (\"Hai! Pertanyaan yang menarik!\", \"Wah, topik yang penting nih!\", \"Senang Anda bertanya tentang ini!\").\n";
        
        // Batasan penting
        $prompt .= "4. BATASAN PENTING:\n";
        $prompt .= "   a. Kamu HANYA boleh menjawab pertanyaan tentang kesehatan, kedokteran, anatomi tubuh, penyakit, gejala, cara pencegahan, dan topik terkait kesehatan lainnya.\n";
        $prompt .= "   b. Kamu DILARANG KERAS memberikan diagnosis pasti, resep obat, dosis obat, atau anjuran pengobatan spesifik.\n";
        $prompt .= "   c. Untuk pertanyaan non-kesehatan (politik, hiburan, berita, dll), jawab dengan sopan bahwa kamu hanya dapat membantu dengan informasi kesehatan.\n";
        $prompt .= "   d. Jika pasien meminta resep obat, diagnosis, atau konsultasi medis spesifik, selalu gunakan kalimat: \"Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini.\" Kalimat ini penting untuk memicu popup telekonsultasi.\n";
        $prompt .= "   e. Kamu boleh menjelaskan informasi umum tentang obat-obatan (fungsi, jenis, cara kerja) tapi JANGAN memberikan rekomendasi spesifik.\n\n";
        
        if ($isNewConversation) {
            // Untuk percakapan baru, fokus pada pertanyaan saat ini tanpa referensi konteks sebelumnya
            $prompt .= "5. Ini adalah PERCAKAPAN BARU, jadi berikan informasi umum tanpa mengasumsikan pasien memiliki kondisi yang sedang dibicarakan sebelumnya.\n";
            $prompt .= "6. Mulai dengan sambutan yang ramah, excited dan fokus pada topik yang ditanyakan.\n";
            $prompt .= "7. Jangan memberikan diagnosis pasti, tetapi berikan informasi dan saran yang membantu.\n";
            $prompt .= "8. Jika ada kondisi serius, selalu sarankan untuk berkonsultasi dengan dokter melalui fitur Telekonsultasi di platform ini.\n\n";
        } else {
            // Untuk percakapan lanjutan, pertahankan konteks
            $prompt .= "5. SANGAT PENTING: Kamu harus MEMAHAMI KONTEKS percakapan dan MENGINGAT informasi yang diberikan pasien di pesan-pesan sebelumnya.\n";
            $prompt .= "6. Berikan jawaban yang PERSONAL, RAMAH dan berkesinambungan, jangan hanya menjawab pertanyaan terakhir tanpa mempertimbangkan konteks sebelumnya.\n";
            $prompt .= "7. Jika pasien menyebutkan gejala baru, kaitkan dengan gejala yang disebutkan sebelumnya jika relevan.\n";
            $prompt .= "8. Ajukan pertanyaan lanjutan yang relevan untuk membantu pemahaman lebih lanjut.\n";
            $prompt .= "9. Tunjukkan empati dan support ketika membahas keluhan kesehatan pasien.\n";
            $prompt .= "10. Jangan memberikan diagnosis pasti, tetapi berikan informasi dan saran yang membantu.\n";
            $prompt .= "11. Jika ada kondisi serius, selalu sarankan untuk berkonsultasi dengan dokter melalui fitur Telekonsultasi di platform ini.\n\n";
        }
        
        // Tambahkan riwayat percakapan jika ada
        if (!empty($history)) {
            $prompt .= "Berikut adalah riwayat percakapan dengan pasien:\n\n";
            
            // Tambahkan maksimal 10 pesan terakhir untuk konteks yang relevan
            $recentHistory = array_slice($history, -10);
            
            foreach ($recentHistory as $entry) {
                if ($entry['role'] === 'user') {
                    $prompt .= "Pasien: " . $entry['content'] . "\n\n";
                } else {
                    $prompt .= "HealsAI: " . $entry['content'] . "\n\n";
                }
            }
        }
        
        // Tambahkan pertanyaan saat ini
        $prompt .= "Pertanyaan/keluhan terkini dari pasien: " . $message . "\n\n";
        
        if ($isNewConversation) {
            $prompt .= "Jawablah pertanyaan ini sebagai awal percakapan baru, tanpa mengacu pada konteks sebelumnya yang mungkin tidak relevan. Tunjukkan keramahan dan antusiasme dalam jawabanmu!\n\n";
        } else {
            $prompt .= "Jawablah dengan mempertimbangkan seluruh riwayat percakapan dan berikan respons yang personal, natural, ramah, dan berkesinambungan, seolah-olah kamu adalah seorang dokter yang bersahabat yang sedang berkonsultasi dengan pasien secara langsung.\n\n";
        }
        
        $prompt .= "PENGINGAT PENTING: Jangan pernah memberikan diagnosis pasti atau resep obat. Jika pasien membutuhkan konsultasi spesifik, gunakan kalimat: \"Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini.\" untuk memicu popup telekonsultasi.\n\n";
        $prompt .= "INGAT TENTANG FORMAT: Gunakan DUA BARIS KOSONG (dua kali Enter) di antara paragraf untuk memisahkan paragraf dengan jelas. Berikan indentasi yang tepat untuk daftar atau poin-poin. Gunakan nada yang ramah, excited, dan supportive dalam setiap jawabanmu.\n\n";
        
        $prompt .= "Jawaban HealsAI:";
        
        return $prompt;
    }
    
    /**
     * Mendapatkan respons simulasi untuk testing
     */
    private function getSimulatedResponse($message, $isNewConversation)
    {
        // Pilih respons berdasarkan keyword dan konteks
        $aiResponse = '';
        $messageLower = strtolower($message);
        
        // Cek apakah pertanyaan di luar topik kesehatan
        if (stripos($messageLower, 'politik') !== false || 
            stripos($messageLower, 'presiden') !== false || 
            stripos($messageLower, 'partai') !== false ||
            stripos($messageLower, 'pilpres') !== false ||
            stripos($messageLower, 'film') !== false ||
            stripos($messageLower, 'artis') !== false ||
            stripos($messageLower, 'lagu') !== false ||
            stripos($messageLower, 'musik') !== false ||
            stripos($messageLower, 'teknologi') !== false ||
            stripos($messageLower, 'ekonomi') !== false ||
            stripos($messageLower, 'investasi') !== false ||
            stripos($messageLower, 'game') !== false ||
            stripos($messageLower, 'kode program') !== false) {
            
            return response()->json([
                'success' => true,
                'response' => 'Hai! Mohon maaf ya, saya adalah HealsAI yang berfokus khusus pada informasi kesehatan. Saya tidak dapat membantu dengan pertanyaan di luar topik kesehatan.


Silakan ajukan pertanyaan seputar kesehatan, kedokteran, gejala penyakit, atau informasi medis lainnya, dan saya akan dengan senang hati membantu Anda! 😊'
            ]);
        }
        
        // Cek permintaan untuk resep obat atau diagnosis
        if (stripos($messageLower, 'berikan saya resep') !== false || 
            stripos($messageLower, 'resepkan saya') !== false || 
            stripos($messageLower, 'butuh resep') !== false ||
            stripos($messageLower, 'minta resep') !== false ||
            stripos($messageLower, 'obat apa yang harus') !== false ||
            stripos($messageLower, 'resep obat') !== false ||
            stripos($messageLower, 'diagnosa penyakit saya') !== false ||
            stripos($messageLower, 'diagnosis penyakit saya') !== false) {
            
            return response()->json([
                'success' => true,
                'response' => 'Hai! Saya memahami kekhawatiran Anda dan keinginan untuk mendapatkan solusi segera. Namun sebagai HealsAI, saya tidak dapat memberikan resep obat atau diagnosis pasti.


Untuk mendapatkan penanganan yang tepat dan aman, Anda memerlukan pemeriksaan langsung oleh dokter yang profesional.


Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini.'
            ]);
        }
        
        // Tanggapan untuk percakapan baru vs lanjutan
        if ($isNewConversation) {
            // Respons untuk percakapan baru - lebih umum
            if (stripos($messageLower, 'sakit gigi') !== false) {
                $aiResponse = 'Hai! Sakit gigi memang bisa sangat mengganggu ya. Sakit gigi bisa disebabkan oleh berbagai faktor seperti gigi berlubang, infeksi gusi, keretakan pada gigi, atau bahkan masalah pada sinus.


Bisa Anda jelaskan lebih detail bagaimana rasanya sakit gigi yang Anda alami? Apakah terus-menerus berdenyut, hanya sakit saat makan, atau saat terkena makanan/minuman panas atau dingin? Informasi ini akan sangat membantu untuk memahami kondisi Anda lebih baik!


Jika sakitnya parah atau berkelanjutan, sebaiknya Anda berkonsultasi dengan dokter gigi untuk mendapatkan penanganan yang tepat. Semoga cepat sembuh ya! 😊';
            } else if (stripos($messageLower, 'diabetes') !== false) {
                $aiResponse = 'Wah, pertanyaan yang menarik tentang diabetes! 👍 Diabetes adalah kondisi kronis di mana tubuh tidak dapat mengatur kadar gula darah dengan baik.


Ada dua tipe utama: Tipe 1 (tubuh tidak memproduksi insulin) dan Tipe 2 (tubuh tidak menggunakan insulin dengan baik). Bayangkan insulin seperti kunci yang membuka pintu sel tubuh agar gula bisa masuk dan digunakan sebagai energi. Tanpa kunci yang berfungsi, gula akan menumpuk di dalam aliran darah!


Apakah Anda memiliki riwayat diabetes dalam keluarga atau mengalami gejala seperti sering haus, sering buang air kecil, atau merasa sangat lelah? Untuk diagnosis yang akurat, sebaiknya Anda berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini.';
            } else if (stripos($messageLower, 'jantung') !== false) {
                $aiResponse = 'Halo! Senang Anda bertanya tentang kesehatan jantung! ❤️ Ini adalah topik yang sangat penting karena jantung adalah "mesin" utama tubuh kita.


Jantung kita seperti pompa super hebat yang bekerja 24/7 tanpa istirahat. Untuk menjaga agar pompa ini tetap berfungsi dengan baik, disarankan untuk:

   - Berolahraga secara teratur (30 menit aktivitas sedang 5x seminggu)
   - Makan makanan sehat rendah lemak jenuh dan garam
   - Mengelola stres dengan baik
   - Tidak merokok (rokok adalah musuh besar jantung!)
   - Menjaga berat badan ideal


Apakah Anda merasakan gejala tertentu yang berkaitan dengan jantung? Jika Anda memiliki kekhawatiran tentang kesehatan jantung Anda, sebaiknya konsultasikan dengan dokter melalui fitur Telekonsultasi untuk evaluasi yang tepat. Jaga jantung Anda baik-baik ya! 💪';
            } else if (stripos($messageLower, 'air') !== false || stripos($messageLower, 'minum') !== false) {
                $aiResponse = 'Hai! Pertanyaan bagus tentang kebutuhan air minum! 💧 Mengonsumsi air yang cukup sangat penting untuk kesehatan kita. Air adalah komponen utama tubuh dan berperan dalam hampir semua fungsi vital.


Rekomendasi umum adalah minum sekitar 8 gelas (2 liter) air per hari, tetapi kebutuhan bisa bervariasi tergantung aktivitas fisik, iklim, dan kondisi kesehatan. Tubuh kita seperti tanaman yang perlu disiram secara teratur agar tetap segar dan berfungsi optimal!


Apakah Anda merasa kesulitan untuk memenuhi kebutuhan air harian Anda? Ada beberapa trik yang bisa membantu, seperti selalu membawa botol air kemanapun Anda pergi, menyetel pengingat di ponsel, atau menambahkan irisan buah untuk rasa yang lebih menarik. Mencukupi kebutuhan air adalah salah satu langkah sederhana namun super efektif untuk menjaga kesehatan lho! 😊';
            } else if (stripos($messageLower, 'covid') !== false) {
                $aiResponse = 'Halo! Terima kasih sudah bertanya tentang COVID-19. COVID-19 adalah penyakit yang disebabkan oleh virus SARS-CoV-2 dengan gejala utama seperti demam, batuk kering, kelelahan, kehilangan rasa atau bau, sakit tenggorokan, sakit kepala, dan sesak napas.


Virus ini menyebar terutama melalui tetesan pernapasan ketika orang yang terinfeksi batuk, bersin, atau bahkan berbicara. Oleh karena itu, menjaga jarak, menggunakan masker, dan mencuci tangan tetap menjadi langkah pencegahan yang penting.


Apakah Anda sedang mengalami gejala-gejala tersebut? Jika ya, jangan khawatir berlebihan, tapi sebaiknya Anda segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini untuk mendapatkan penanganan yang tepat. Semoga Anda selalu sehat ya! 🙏';
            } else if (stripos($messageLower, 'imunitas') !== false || stripos($messageLower, 'imun') !== false) {
                $aiResponse = 'Hai! Saya senang sekali Anda tertarik dengan topik imunitas! 🛡️ Sistem imun adalah tentara pertahanan tubuh kita yang luar biasa. Saat berfungsi dengan baik, ia dapat melindungi tubuh dari berbagai serangan mikroorganisme penyebab penyakit.


Makanan yang baik untuk meningkatkan imunitas termasuk: 

   - Buah-buahan kaya vitamin C (jeruk, kiwi, stroberi)
   - Sayuran hijau (bayam, brokoli, kale)
   - Bawang putih (mengandung senyawa anti-mikroba alami!)
   - Jahe (luar biasa untuk melawan peradangan)
   - Yogurt (probiotik untuk usus sehat)
   - Kacang-kacangan (sumber protein nabati dan vitamin E)
   - Makanan fermentasi (kimchi, kombucha)


Selain pola makan, faktor gaya hidup juga sangat penting. Tidur cukup (7-8 jam), olahraga teratur (minimal 30 menit sehari), dan mengelola stres adalah pilar penting untuk sistem imun yang kuat.


Bagaimana pola makan dan gaya hidup Anda saat ini? Adakah area yang perlu ditingkatkan? Saya yakin Anda bisa membuat perubahan positif untuk meningkatkan imunitas Anda! 💪';
            } else if (stripos($messageLower, 'diagnosa') !== false || stripos($messageLower, 'diagnosis') !== false) {
                $aiResponse = 'Hai! Terima kasih atas pertanyaannya. Sebagai HealsAI, saya tidak dapat memberikan diagnosis medis, meskipun saya sangat ingin membantu Anda!


Diagnosis medis ibarat detektif yang memecahkan misteri - membutuhkan pengumpulan berbagai petunjuk, pengalaman langsung, dan alat khusus yang hanya dimiliki oleh dokter. Tanpa semua itu, sangat berisiko memberikan kesimpulan yang tidak akurat.


Saya bisa membantu menjelaskan informasi umum tentang gejala atau kondisi tertentu, tetapi untuk diagnosis resmi yang aman dan akurat, sebaiknya Anda menggunakan fitur Telekonsultasi di platform ini untuk berbicara dengan dokter. Kesehatan Anda terlalu berharga untuk ditebak-tebak! 😊';
            } else if (stripos($messageLower, 'virus') !== false) {
                $aiResponse = "Hai! Wah, topik yang menarik dan penting nih! Senang sekali bisa membahas tentang virus-virus mematikan dan bagaimana cara kita mencegahnya. Yuk, kita simak bersama!\n\n".
                "Ada beberapa contoh virus yang dikenal sangat mematikan di dunia. Penting untuk diingat bahwa tingkat kematian akibat virus bisa bervariasi tergantung pada banyak faktor, seperti akses ke perawatan medis, kondisi kesehatan individu, dan jenis virusnya itu sendiri.\n\n".
                "Berikut adalah beberapa contoh virus mematikan dan upaya pencegahannya:\n\n".
                "- Virus Ebola:\n".
                "  Ebola menyebabkan demam berdarah yang parah.\n".
                "  Gejala meliputi demam, sakit kepala, nyeri otot, dan pendarahan internal.\n".
                "  Pencegahan: Vaksinasi (tersedia untuk beberapa jenis Ebola), kebersihan yang ketat, dan isolasi pasien yang terinfeksi.\n\n".
                "- Virus HIV (Human Immunodeficiency Virus):\n".
                "  HIV menyerang sistem kekebalan tubuh, menyebabkan AIDS (Acquired Immunodeficiency Syndrome).\n".
                "  HIV ditularkan melalui cairan tubuh seperti darah, air mani, dan ASI.\n".
                "  Pencegahan: Penggunaan kondom saat berhubungan seks, hindari berbagi jarum suntik, dan terapi antiretroviral (ART) untuk menekan virus.\n\n".
                "- Virus Influenza (Flu):\n".
                "  Beberapa jenis influenza, seperti H1N1 (flu babi) dan H5N1 (flu burung), bisa sangat mematikan.\n".
                "  Gejala meliputi demam, batuk, sakit tenggorokan, dan nyeri otot.\n".
                "  Pencegahan: Vaksinasi flu tahunan, mencuci tangan yang baik, dan hindari kontak dekat dengan orang sakit.\n\n".
                "Apakah ada virus tertentu yang ingin Anda ketahui lebih detail? Saya siap membantu memberikan informasi lebih lanjut! 😊";
            } else if (stripos($messageLower, 'indonesia') !== false && (stripos($messageLower, 'penyakit') !== false || stripos($messageLower, 'sakit') !== false)) {
                $aiResponse = "Hai! Wah, pertanyaan yang menarik tentang penyakit yang hanya ada di Indonesia!\n\n".
                "Sebenarnya, agak sulit untuk mengatakan bahwa suatu penyakit hanya ada di Indonesia, karena penyebaran penyakit bisa sangat dinamis dan kompleks. Namun, ada beberapa penyakit yang lebih sering ditemukan atau memiliki karakteristik unik di Indonesia dibandingkan negara lain. Penyakit-penyakit ini seringkali terkait dengan faktor lingkungan, gaya hidup, atau bahkan genetika populasi tertentu di Indonesia.\n\n".
                "Berikut adalah beberapa contoh penyakit atau kondisi kesehatan yang memiliki karakteristik khusus di Indonesia:\n\n".
                "- Demam Berdarah Dengue (DBD): DBD adalah masalah kesehatan masyarakat yang signifikan di Indonesia. Vektor nyamuk Aedes aegypti sangat umum di daerah tropis dan sub-tropis seperti Indonesia, dan curah hujan yang tinggi serta sanitasi yang kurang baik dapat memperburuk penyebaran penyakit ini.\n\n".
                "- Malaria: Meskipun malaria juga ditemukan di banyak negara tropis lainnya, Indonesia memiliki beban malaria yang cukup tinggi, terutama di wilayah timur seperti Papua. Berbagai program pengendalian malaria terus dilakukan untuk mengurangi kasus dan kematian akibat penyakit ini.\n\n".
                "- Filariasis (Penyakit Kaki Gajah): Filariasis adalah penyakit parasit yang menyebabkan pembengkakan ekstrem pada anggota tubuh, terutama kaki. Indonesia masih memiliki beberapa daerah endemis filariasis, meskipun upaya eliminasi terus dilakukan.\n\n".
                "- Penyakit Akibat Kurang Yodium (GAKI): Meskipun program fortifikasi yodium telah berjalan, beberapa daerah pegunungan dan pedalaman di Indonesia masih memiliki prevalensi GAKI yang cukup tinggi.\n\n".
                "Apakah ada penyakit spesifik yang ingin Anda ketahui lebih detail? Saya bisa memberikan informasi lebih lanjut tentang pencegahan, gejala, atau penanganan penyakit-penyakit tersebut!";
            } else {
                $aiResponse = 'Hai! Terima kasih atas pertanyaan Anda. Sebagai HealsAI, saya sangat senang dan siap membantu dengan informasi kesehatan yang Anda butuhkan! 😊


Kesehatan adalah aset berharga yang perlu kita jaga dengan baik. Semakin spesifik pertanyaan Anda, semakin terarah informasi yang bisa saya berikan untuk membantu Anda.


Bisa Anda jelaskan lebih detail tentang apa yang ingin Anda ketahui atau keluhan kesehatan yang Anda alami saat ini? Saya di sini untuk mendukung perjalanan kesehatan Anda!';
            }
        } else {
            // Respons untuk percakapan lanjutan - lebih kontekstual
            if (stripos($messageLower, 'sakit gigi') !== false && stripos($messageLower, 'atas') !== false) {
                $aiResponse = 'Saya memahami bahwa Anda mengalami sakit gigi di bagian atas dan mungkin gigi Anda berlubang. Pasti sangat tidak nyaman ya! 😔


Sakit pada gigi atas kadang bisa berkaitan dengan masalah sinus juga, karena kedekatan anatomisnya. Sinus maksilaris (rongga di belakang pipi) terletak tepat di atas akar gigi atas, sehingga infeksi atau peradangan di salah satu area bisa mempengaruhi area lainnya.


Selain sakit, apakah Anda mengalami gejala lain seperti sensitif terhadap makanan panas atau dingin, bengkak di sekitar gigi, atau sakit saat mengunyah? Informasi tambahan ini akan sangat membantu!


Jika kondisi ini mengganggu aktivitas sehari-hari atau memburuk, sebaiknya Anda segera berkonsultasi dengan dokter gigi melalui fitur Telekonsultasi yang tersedia di platform ini. Semoga cepat membaik ya! 🙏';
            } else if (stripos($messageLower, 'berlubang') !== false) {
                $aiResponse = 'Wah, gigi berlubang memang bisa menyebabkan rasa sakit yang signifikan ya! Saya bisa bayangkan ketidaknyamanan yang Anda rasakan saat ini. 😔


Ketika lubang sudah mencapai saraf gigi, rasa sakitnya bisa menjalar ke area lain seperti telinga, kepala, atau bahkan leher. Ini terjadi karena jaringan saraf di area tersebut saling berhubungan.


Bayangkan gigi seperti lapisan bawang - email di luar, dentin di tengah, dan pulpa (saraf) di dalam. Semakin dalam lubangnya, semakin dekat dengan saraf, dan semakin nyeri yang dirasakan. Keren bahwa Anda sudah menyadari adanya masalah ini - itu langkah pertama yang bagus!


Apakah Anda juga mengalami sakit kepala atau area lain yang terasa nyeri bersamaan dengan sakit gigi Anda? 


Untuk penanganan yang tepat dan melegakan rasa sakit Anda, sebaiknya Anda segera berkonsultasi dengan dokter gigi melalui fitur Telekonsultasi yang tersedia di platform ini. Jangan biarkan gigi berlubang terlalu lama ya, bisa semakin parah! 💪';
            } else if (stripos($messageLower, 'pusing') !== false || stripos($messageLower, 'kepala') !== false) {
                $aiResponse = 'Wah, menarik sekali bahwa Anda juga mengalami pusing atau sakit kepala bersamaan dengan sakit gigi! Ini adalah informasi penting yang Anda bagikan. 👍


Ini memang bisa terjadi karena saraf gigi dan saraf kepala saling berhubungan melalui saraf trigeminal. Tubuh kita benar-benar seperti jaringan yang saling terhubung!


Infeksi pada gigi atas terutama bisa menyebabkan tekanan pada sinus yang kemudian menimbulkan sakit kepala. Kadang, otot rahang yang tegang akibat menahan sakit gigi juga dapat memicu sakit kepala tegang.


Sudah berapa lama Anda mengalami kedua gejala ini bersamaan? Dan apakah sakitnya lebih parah pada waktu-waktu tertentu, misalnya saat berbaring atau saat bangun tidur? Informasi ini akan sangat membantu!


Kombinasi gejala ini perlu mendapat perhatian profesional. Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini. Semoga Anda segera mendapat kelegaan dari ketidaknyamanan ini! 🙏';
            } else if (stripos($messageLower, 'tidur') !== false) {
                $aiResponse = 'Oh, saya sangat memahami betapa mengganggu dan menyebalkannya sulit tidur karena sakit gigi! 😔 Ini pasti berdampak signifikan pada kualitas hidup sehari-hari Anda.


Posisi berbaring terkadang meningkatkan tekanan darah ke area kepala yang bisa memperparah rasa sakit pada gigi yang bermasalah. Ini terjadi karena gravitasi meningkatkan aliran darah ke kepala saat Anda berbaring.


Untuk sementara, Anda bisa mencoba tidur dengan kepala lebih tinggi menggunakan beberapa bantal. Ini adalah trik sederhana yang dapat membantu mengurangi tekanan darah ke area kepala dan mungkin memberikan sedikit kelegaan sehingga Anda bisa tidur lebih nyenyak.


Namun, ini hanya solusi sementara ya! Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini. Semoga Anda bisa segera tidur nyenyak kembali! 💤';
            } else if (stripos($messageLower, 'obat') !== false) {
                $aiResponse = 'Hai! Saya memahami Anda mencari informasi tentang obat untuk kondisi Anda. Ini adalah kekhawatiran yang sangat wajar ketika Anda mengalami rasa sakit atau ketidaknyamanan. 😊


Secara umum, obat anti-inflamasi non-steroid (NSAID) dapat membantu mengurangi peradangan dan nyeri, dan berkumur dengan air garam hangat juga bisa meredakan peradangan. Air garam bekerja dengan cara mengurangi bakteri dan menarik cairan dari jaringan yang meradang - solusi alami yang cukup efektif lho!


Namun, saya tidak dapat merekomendasikan obat spesifik atau dosis karena itu memerlukan diagnosis dan resep dari dokter. Setiap orang memiliki kondisi kesehatan dan riwayat medis yang unik dan berbeda yang dapat mempengaruhi keamanan obat tertentu.


Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini. Dokter akan memberikan solusi yang tepat untuk Anda! 👍';
            } else if (stripos($messageLower, 'apa obat yang') !== false || stripos($messageLower, 'obat apa yang') !== false) {
                $aiResponse = 'Pertanyaan bagus tentang obat spesifik! Saya mengerti Anda ingin solusi yang tepat untuk kondisi Anda. 👍


Sebagai HealsAI, saya tidak dapat merekomendasikan obat spesifik karena itu memerlukan diagnosis dan penilaian medis yang tepat dari seorang dokter. Pemilihan obat yang tepat tergantung pada banyak faktor penting seperti:

   - Diagnosis yang akurat dari kondisi Anda
   - Riwayat kesehatan pribadi Anda
   - Obat lain yang mungkin sedang Anda konsumsi
   - Kemungkinan alergi atau reaksi yang Anda miliki
   - Tingkat keparahan kondisi Anda


Semua faktor ini memerlukan penilaian profesional medis yang tidak bisa saya lakukan. Obat yang tepat untuk satu orang mungkin tidak cocok atau bahkan berbahaya untuk orang lain!


Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini. Dokter akan dapat menilai kondisi Anda secara menyeluruh dan memberikan resep yang tepat dan aman untuk Anda. Kesehatan Anda adalah prioritas! 😊';
            } else {
                $aiResponse = 'Hai! Terima kasih banyak atas informasi tambahan yang Anda berikan. Berdasarkan percakapan kita sebelumnya, saya jadi memahami kondisi Anda lebih baik sekarang. 😊


Setiap detail yang Anda bagikan sangat berharga dan membantu membentuk gambaran yang lebih jelas tentang situasi kesehatan Anda. Ini seperti menyusun kepingan puzzle - semakin banyak informasi, semakin lengkap gambarannya!


Untuk memberikan saran yang lebih tepat dan membantu, bisakah Anda memberitahu saya apakah ada perubahan pada gejala yang Anda alami sejak terakhir kali kita berbicara? Atau mungkin ada hal lain yang ingin Anda tanyakan terkait kesehatan Anda?


Saya di sini untuk mendukung perjalanan kesehatan Anda dan memberikan informasi yang Anda butuhkan! 💪';
            }
        }
        
        return response()->json([
            'success' => true,
            'response' => $aiResponse
        ]);
    }
} 