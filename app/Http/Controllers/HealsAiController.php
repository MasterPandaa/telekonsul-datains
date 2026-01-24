<?php

namespace App\Http\Controllers;

use App\Models\ChatbotSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatbotSession;
use App\Models\ChatbotMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HealsAiController extends Controller
{
    /**
     * Mengirim pesan ke AI Agent (n8n) dengan fallback ke HealsAI internal.
     * 
     * @return \Illuminate\Http\JsonResponse Selalu status 200 dengan struktur { success, response, source, message }
     */
    public function getResponse(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'session_id' => 'nullable|string', // UUID dari frontend untuk n8n Memory
                'conversation_id' => 'nullable|string', // UUID untuk tracking topik di database
                'history' => 'nullable|array', // Tetap terima untuk fallback internal
                'is_new_conversation' => 'nullable|boolean',
            ]);

            $message = $request->message;
            $sessionId = $request->input('session_id'); // UUID unik per percakapan (untuk n8n Memory Node)
            $conversationId = $request->input('conversation_id'); // UUID untuk tracking topik database
            $history = $request->history ?? []; // Hanya untuk fallback internal (Gemini)
            $isNewConversation = (bool) ($request->is_new_conversation ?? false);

            // === DB STORAGE: Save User Message ===
            $userId = Auth::id();
            if ($userId && $conversationId) {
                // Find or Create Session
                $session = ChatbotSession::firstOrCreate(
                    ['id' => $conversationId],
                    [
                        'user_id' => $userId,
                        'title' => Str::limit($message, 50)
                    ]
                );

                // Update timestamp for sorting
                $session->touch();

                // Save User Message
                ChatbotMessage::create([
                    'chatbot_session_id' => $session->id,
                    'role' => 'user',
                    'content' => $message
                ]);
            }


            // Coba kirim ke n8n terlebih dahulu
            $n8nResult = $this->forwardToN8nAgent($request, $message, $sessionId, $conversationId, $isNewConversation);

            if ($n8nResult['success']) {
                $responseText = $n8nResult['response'];

                // === DB STORAGE: Save Chatbot Message ===
                if (isset($session)) {
                    ChatbotMessage::create([
                        'chatbot_session_id' => $session->id,
                        'role' => 'assistant',
                        'content' => $responseText
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'response' => $responseText,
                    'source' => $n8nResult['source'] ?? 'n8n',
                ], 200);
            }

            // === FALLBACK ke HealsAI (Gemini) jika n8n gagal ===
            Log::info('HealsAI: Fallback triggered', [
                'reason' => $n8nResult['message'] ?? 'n8n request failed',
                'attempted' => $n8nResult['attempted'] ?? false,
            ]);

            $fallbackResult = $this->generateHealsAiResponse($message, $history, $isNewConversation);

            if ($fallbackResult['success']) {
                $responseText = $fallbackResult['response'];

                // === DB STORAGE: Save Chatbot Message ===
                if (isset($session)) {
                    ChatbotMessage::create([
                        'chatbot_session_id' => $session->id,
                        'role' => 'assistant',
                        'content' => $responseText
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'response' => $responseText,
                    'source' => $fallbackResult['source'] ?? 'healsai',
                    'fallback_used' => true,
                ], 200);
            }

            // Jika fallback juga gagal, kembalikan pesan error yang ramah
            return response()->json([
                'success' => false,
                'response' => 'Maaf, sistem AI sedang tidak dapat diakses saat ini. Silakan coba lagi beberapa saat atau gunakan fitur Telekonsultasi untuk berbicara langsung dengan dokter.',
                'message' => $fallbackResult['message'] ?? 'Fallback AI gagal',
                'source' => 'system',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'response' => 'Pesan tidak valid. Pastikan Anda mengirim pesan dengan format yang benar.',
                'message' => 'Validation failed',
                'source' => 'system',
            ], 200);
        } catch (\Throwable $th) {
            Log::error('HealsAI: Unexpected error in getResponse', [
                'exception' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan pada sistem. Silakan coba lagi atau gunakan fitur Telekonsultasi.',
                'message' => 'Unexpected system error',
                'source' => 'system',
            ], 200);
        }
    }

    /**
     * Kirim pesan ke workflow n8n (agent AI utama).
     * 
     * @param Request $request
     * @param string $message Pesan dari user
     * @param string|null $sessionId UUID unik per percakapan (untuk n8n Memory Node)
     * @param bool $isNewConversation Flag percakapan baru
     */
    private function forwardToN8nAgent(Request $request, string $message, ?string $sessionId, ?string $conversationId, bool $isNewConversation): array
    {
        $settings = ChatbotSetting::first();

        if (!$settings || empty($settings->webhook_url)) {
            Log::info('HealsAI: n8n webhook not configured');
            return [
                'success' => false,
                'attempted' => false,
                'message' => 'Fitur Chatbot API belum dikonfigurasi.'
            ];
        }

        $method = strtoupper($settings->method ?? 'POST');
        $timeout = (int) ($settings->timeout ?? 60);
        $connectTimeout = min($timeout, 15); // Max 15 detik untuk connect

        $allowInsecure = (bool) $settings->allow_insecure_ssl;

        try {
            $client = Http::timeout($timeout)
                ->connectTimeout($connectTimeout)
                ->acceptJson()
                ->withHeaders([
                    'User-Agent' => sprintf(
                        'Telekonsul-HealsAI/%s',
                        config('app.version', config('app.name'))
                    ),
                ]);

            if ($allowInsecure) {
                $client = $client->withoutVerifying();
            }

            // Authentication handling
            $authType = strtolower($settings->auth_type ?? 'none');
            switch ($authType) {
                case 'basic':
                    $client = $client->withBasicAuth(
                        $settings->basic_user ?? '',
                        $settings->basic_pass ?? ''
                    );
                    break;
                case 'bearer':
                    $client = $client->withToken($settings->bearer_token ?? '');
                    break;
                case 'header':
                    $headerKey = $settings->header_key;
                    $headerValue = $settings->header_value;
                    if ($headerKey && $headerValue) {
                        $client = $client->withHeaders([$headerKey => $headerValue]);
                    }
                    break;
                case 'jwt':
                    $client = $client->withHeaders([
                        'Authorization' => 'Bearer ' . ($settings->jwt_token ?? '')
                    ]);
                    break;
                default:
                    // none
                    break;
            }

            // === PAYLOAD untuk n8n ===
            // PENTING: Tidak mengirim history array!
            // n8n Memory Node akan handle history sendiri berdasarkan session_id
            $payload = [
                'message' => $message,
                'session_id' => $sessionId, // UUID unik per percakapan (untuk n8n Memory Node)
                'conversation_id' => $conversationId, // UUID untuk tracking topik di database
                'is_new_conversation' => $isNewConversation,
                'user' => [
                    'id' => optional(auth()->user())->id,
                    'name' => optional(auth()->user())->name,
                    'email' => optional(auth()->user())->email,
                    'role' => optional(auth()->user())->role ?? 'pasien',
                ],
                'context' => [
                    'timestamp' => now()->toIso8601String(),
                    'source' => 'web-telekonsul',
                    'execution_mode' => app()->environment('production') ? 'production' : 'test',
                ],
            ];

            // === DEEP LOGGING: Log sebelum request ===
            Log::info('HealsAI: Sending request to n8n', [
                'url' => $settings->webhook_url,
                'method' => $method,
                'timeout' => $timeout,
                'auth_type' => $authType,
                'session_id' => $sessionId, // Log session_id untuk debugging
                'conversation_id' => $conversationId, // Log conversation_id
                'payload_message' => substr($message, 0, 100),
                'is_new_conversation' => $isNewConversation,
            ]);

            $response = $client->send($method, $settings->webhook_url, [
                'json' => $payload,
            ]);

            // === DEEP LOGGING: Log raw response body SEBELUM parsing ===
            $rawBody = $response->body();
            Log::info('HealsAI: n8n Raw Response', [
                'status_code' => $response->status(),
                'body' => substr($rawBody, 0, 2000), // Batasi 2000 karakter untuk log
                'body_length' => strlen($rawBody),
                'content_type' => $response->header('Content-Type'),
            ]);

            // Cek apakah response berisi HTML (indikasi error page)
            if ($this->isHtmlResponse($rawBody)) {
                Log::warning('HealsAI: n8n returned HTML instead of JSON', [
                    'status' => $response->status(),
                    'body_preview' => substr($rawBody, 0, 500),
                ]);

                return [
                    'success' => false,
                    'attempted' => true,
                    'message' => 'n8n mengembalikan halaman error HTML, bukan JSON.'
                ];
            }

            if (!$response->successful()) {
                Log::warning('HealsAI: n8n webhook HTTP error', [
                    'status' => $response->status(),
                    'body' => substr($rawBody, 0, 1000),
                ]);

                return [
                    'success' => false,
                    'attempted' => true,
                    'message' => sprintf('n8n error HTTP %d: %s', $response->status(), $this->getHttpErrorMessage($response->status()))
                ];
            }

            $data = $response->json();

            if (is_null($data)) {
                Log::warning('HealsAI: n8n response is not valid JSON, trying to extract answer', [
                    'body_preview' => substr($rawBody, 0, 500),
                ]);

                $rawAnswer = $this->extractAnswerFromPayload($rawBody);

                if ($rawAnswer !== null) {
                    Log::info('HealsAI: Successfully extracted answer from non-JSON response');
                    return [
                        'success' => true,
                        'response' => $rawAnswer,
                        'source' => 'n8n',
                        'attempted' => true,
                    ];
                }

                return [
                    'success' => false,
                    'attempted' => true,
                    'message' => 'Format respons n8n tidak valid (bukan JSON).'
                ];
            }

            $successFlag = data_get($data, 'success');

            if ($successFlag === false) {
                Log::warning('HealsAI: n8n returned success=false', [
                    'message' => data_get($data, 'message'),
                ]);
                return [
                    'success' => false,
                    'attempted' => true,
                    'message' => data_get($data, 'message') ?? 'Sistem agent utama melaporkan kegagalan.'
                ];
            }

            // Extract answer from JSON response
            $answer = $this->extractAnswerFromPayload($data);

            if (is_string($answer) && trim($answer) !== '') {
                Log::info('HealsAI: Successfully extracted answer from n8n JSON', [
                    'answer_length' => strlen($answer),
                ]);
                return [
                    'success' => true,
                    'response' => $answer,
                    'source' => 'n8n',
                    'attempted' => true,
                ];
            }

            // Could not extract answer
            Log::warning('HealsAI: Could not extract answer from n8n response', [
                'data_keys' => is_array($data) ? array_keys($data) : 'not array',
            ]);

            return [
                'success' => false,
                'attempted' => true,
                'message' => 'Format respons webhook tidak valid (tidak ditemukan field jawaban).'
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            // Timeout atau connection error
            Log::error('HealsAI: n8n connection failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'url' => $settings->webhook_url ?? 'unknown',
            ]);

            return [
                'success' => false,
                'attempted' => true,
                'message' => 'Koneksi ke n8n gagal (timeout atau server tidak tersedia).'
            ];
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // HTTP error (4xx, 5xx)
            Log::error('HealsAI: n8n HTTP request exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'status' => $e->response ? $e->response->status() : 'unknown',
            ]);

            return [
                'success' => false,
                'attempted' => true,
                'message' => 'Error HTTP dari n8n: ' . $e->getMessage()
            ];
        } catch (\Throwable $th) {
            Log::error('HealsAI: n8n unexpected exception', [
                'exception' => get_class($th),
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return [
                'success' => false,
                'attempted' => true,
                'message' => 'Terjadi kesalahan tidak terduga saat menghubungi n8n.'
            ];
        }
    }

    /**
     * Hilangkan entri history default / tidak valid.
     */
    private function sanitizeHistory(array $history): array
    {
        $filtered = [];

        foreach ($history as $index => $entry) {
            if (!is_array($entry)) {
                continue;
            }

            $role = $entry['role'] ?? null;
            $content = isset($entry['content']) ? trim((string) $entry['content']) : '';

            if ($role === null || $content === '') {
                continue;
            }

            $isDefaultGreeting = $role === 'assistant'
                && $index === 0
                && stripos($content, 'Halo! Saya HealsAI') === 0;

            if ($isDefaultGreeting) {
                continue;
            }

            $filtered[] = [
                'role' => $role,
                'content' => $content,
            ];
        }

        return $filtered;
    }

    /**
     * Bangun metadata sesi untuk konteks chatbot.
     */
    private function buildSessionMetadata(Request $request): array
    {
        $session = $request->session();

        if (!$session->has('healsai_session_started_at')) {
            $session->put('healsai_session_started_at', now()->toIso8601String());
        }

        return [
            'id' => $session->getId(),
            'started_at' => $session->get('healsai_session_started_at'),
            'is_authenticated' => auth()->check(),
            'ip_address' => $request->getClientIp(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ];
    }

    /**
     * Ambil jawaban teks dari berbagai struktur payload webhook.
     */
    private function extractAnswerFromPayload($payload): ?string
    {
        if (is_null($payload)) {
            return null;
        }

        if ($payload instanceof \Stringable) {
            $payload = (string) $payload;
        }

        if (is_string($payload)) {
            $trimmed = trim($payload);
            return $trimmed === '' ? null : $trimmed;
        }

        if ($payload instanceof \JsonSerializable) {
            $payload = $payload->jsonSerialize();
        }

        if (!is_array($payload)) {
            return null;
        }

        // === Candidate keys yang lebih lengkap ===
        // Tambahan: 'text', 'content', 'data', 'reply', 'result', 'generated_text', 'value'
        $candidatePaths = [
            // Primary response keys
            'response',
            'reply',
            'answer',
            'output',
            'text',
            'content',
            'message',
            'generated_text',
            'value',
            'result',

            // Nested in 'data'
            'data.response',
            'data.reply',
            'data.answer',
            'data.output',
            'data.text',
            'data.content',
            'data.message',

            // Nested in 'result'
            'result.response',
            'result.reply',
            'result.answer',
            'result.output',
            'result.text',
            'result.content',
            'result.message',

            // Nested in 'payload'
            'payload.response',
            'payload.output',
            'payload.text',
            'payload.content',

            // Nested in 'body'
            'body.response',
            'body.output',
            'body.text',
            'body.content',
            'body',

            // Wildcard patterns
            '*.response',
            '*.output',
            '*.text',
            '*.content',
            '*.message',
            '*.json.response',
            '*.json.output',
            '*.json.text',
            '*.body.output',
        ];

        foreach ($candidatePaths as $path) {
            $value = data_get($payload, $path);

            if ($value instanceof \Stringable) {
                $value = (string) $value;
            }

            if (is_array($value)) {
                $value = $this->extractAnswerFromPayload($value);
            }

            if (is_string($value)) {
                $trimmed = trim($value);
                if ($trimmed !== '') {
                    return $trimmed;
                }
            } elseif (is_iterable($value)) {
                foreach ($value as $item) {
                    $nested = $this->extractAnswerFromPayload($item);
                    if ($nested !== null) {
                        return $nested;
                    }
                }
            }
        }

        if ($this->isListArray($payload)) {
            foreach ($payload as $item) {
                $nested = $this->extractAnswerFromPayload($item);
                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        foreach ($payload as $value) {
            if ($value instanceof \Stringable) {
                $value = (string) $value;
            }

            if (is_string($value)) {
                $trimmed = trim($value);
                if ($trimmed !== '') {
                    return $trimmed;
                }
            } elseif (is_array($value)) {
                $nested = $this->extractAnswerFromPayload($value);
                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        return null;
    }

    private function isListArray(array $array): bool
    {
        if (function_exists('array_is_list')) {
            return array_is_list($array);
        }

        return array_values($array) === $array;
    }

    /**
     * Cek apakah response body berisi HTML (error page dari webserver)
     */
    private function isHtmlResponse(string $body): bool
    {
        $trimmed = trim($body);

        // Cek apakah dimulai dengan tag HTML
        if (preg_match('/^\s*<(!DOCTYPE|html|head|body)/i', $trimmed)) {
            return true;
        }

        // Cek apakah mengandung tag HTML umum
        if (preg_match('/<(html|head|body|title|div|span|p)\b/i', $trimmed)) {
            return true;
        }

        return false;
    }

    /**
     * Dapatkan pesan error yang ramah untuk kode HTTP
     */
    private function getHttpErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad Request - parameter tidak valid',
            401 => 'Unauthorized - autentikasi gagal',
            403 => 'Forbidden - akses ditolak',
            404 => 'Not Found - endpoint tidak ditemukan',
            408 => 'Request Timeout',
            429 => 'Too Many Requests - rate limit terlampaui',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway - server n8n tidak merespons',
            503 => 'Service Unavailable - server tidak tersedia',
            504 => 'Gateway Timeout',
            default => 'Error tidak dikenal',
        };
    }

    /**
     * Bangun pesan pembuka ketika fallback dipakai.
     */
    private function buildFallbackIntro(?string $reason = null): string
    {
        if ($reason) {
            Log::info('HealsAI fallback reason', ['reason' => $reason]);
        }

        return "Maaf ya, sistem AI utama kami sedang sedikit sibuk. HealsAI akan bantu kamu sementara. "
            . "Silakan lanjutkan chat, aku siap bantu! 😊";
    }

    /**
     * Jalankan HealsAI internal (simulasi atau Gemini).
     */
    private function generateHealsAiResponse(string $message, array $history, bool $isNewConversation): array
    {
        try {
            // Default false - gunakan real AI jika env tidak diset
            $simulationMode = filter_var(
                env('HEALSAI_SIMULATION_MODE', false),
                FILTER_VALIDATE_BOOLEAN
            );

            Log::info('HealsAI: generateHealsAiResponse called', [
                'simulation_mode' => $simulationMode,
                'has_api_key' => !empty(env('HEALSAI_API_KEY')),
                'model' => env('HEALSAI_MODEL'),
            ]);

            if ($simulationMode) {
                $simulated = $this->getSimulatedResponse($message, $isNewConversation);
                $simulated['source'] = 'healsai-simulation';
                return $simulated;
            }

            $apiKey = env('HEALSAI_API_KEY');
            $model = env('HEALSAI_MODEL');

            if (!$apiKey || !$model) {
                Log::warning('HealsAI config incomplete', ['has_key' => (bool) $apiKey, 'model' => $model]);
                return $this->buildMaintenanceResponse('Konfigurasi HealsAI belum lengkap.');
            }

            $promptText = $this->formatPromptWithHistory($message, $history, $isNewConversation);

            $payload = [
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

            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $payload);

            if ($response->successful()) {
                $result = $response->json();
                $responseText = $result['candidates'][0]['content']['parts'][0]['text']
                    ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';

                return [
                    'success' => true,
                    'response' => $responseText,
                    'source' => 'healsai',
                ];
            }

            Log::error('HealsAI API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return $this->buildMaintenanceResponse('Gagal terhubung dengan layanan AI');
        } catch (\Throwable $th) {
            Log::error('HealsAI API Exception', [
                'message' => $th->getMessage(),
            ]);

            return $this->buildMaintenanceResponse('Terjadi kesalahan: ' . $th->getMessage());
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
    private function getSimulatedResponse($message, $isNewConversation): array
    {
        // Pilih respons berdasarkan keyword dan konteks
        $aiResponse = '';
        $messageLower = strtolower($message);

        // Cek apakah pertanyaan di luar topik kesehatan
        if (
            stripos($messageLower, 'politik') !== false ||
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
            stripos($messageLower, 'kode program') !== false
        ) {

            return [
                'success' => true,
                'response' => 'Hai! Mohon maaf ya, saya adalah HealsAI yang berfokus khusus pada informasi kesehatan. Saya tidak dapat membantu dengan pertanyaan di luar topik kesehatan.


Silakan ajukan pertanyaan seputar kesehatan, kedokteran, gejala penyakit, atau informasi medis lainnya, dan saya akan dengan senang hati membantu Anda! 😊'
            ];
        }

        // Cek permintaan untuk resep obat atau diagnosis
        if (
            stripos($messageLower, 'berikan saya resep') !== false ||
            stripos($messageLower, 'resepkan saya') !== false ||
            stripos($messageLower, 'butuh resep') !== false ||
            stripos($messageLower, 'minta resep') !== false ||
            stripos($messageLower, 'obat apa yang harus') !== false ||
            stripos($messageLower, 'resep obat') !== false ||
            stripos($messageLower, 'diagnosa penyakit saya') !== false ||
            stripos($messageLower, 'diagnosis penyakit saya') !== false
        ) {

            return [
                'success' => true,
                'response' => 'Hai! Saya memahami kekhawatiran Anda dan keinginan untuk mendapatkan solusi segera. Namun sebagai HealsAI, saya tidak dapat memberikan resep obat atau diagnosis pasti.


Untuk mendapatkan penanganan yang tepat dan aman, Anda memerlukan pemeriksaan langsung oleh dokter yang profesional.


Sebagai gantinya, saya sarankan Anda untuk segera berkonsultasi dengan dokter melalui fitur Telekonsultasi yang tersedia di platform ini.'
            ];
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
                $aiResponse = "Hai! Wah, topik yang menarik dan penting nih! Senang sekali bisa membahas tentang virus-virus mematikan dan bagaimana cara kita mencegahnya. Yuk, kita simak bersama!\n\n" .
                    "Ada beberapa contoh virus yang dikenal sangat mematikan di dunia. Penting untuk diingat bahwa tingkat kematian akibat virus bisa bervariasi tergantung pada banyak faktor, seperti akses ke perawatan medis, kondisi kesehatan individu, dan jenis virusnya itu sendiri.\n\n" .
                    "Berikut adalah beberapa contoh virus mematikan dan upaya pencegahannya:\n\n" .
                    "- Virus Ebola:\n" .
                    "  Ebola menyebabkan demam berdarah yang parah.\n" .
                    "  Gejala meliputi demam, sakit kepala, nyeri otot, dan pendarahan internal.\n" .
                    "  Pencegahan: Vaksinasi (tersedia untuk beberapa jenis Ebola), kebersihan yang ketat, dan isolasi pasien yang terinfeksi.\n\n" .
                    "- Virus HIV (Human Immunodeficiency Virus):\n" .
                    "  HIV menyerang sistem kekebalan tubuh, menyebabkan AIDS (Acquired Immunodeficiency Syndrome).\n" .
                    "  HIV ditularkan melalui cairan tubuh seperti darah, air mani, dan ASI.\n" .
                    "  Pencegahan: Penggunaan kondom saat berhubungan seks, hindari berbagi jarum suntik, dan terapi antiretroviral (ART) untuk menekan virus.\n\n" .
                    "- Virus Influenza (Flu):\n" .
                    "  Beberapa jenis influenza, seperti H1N1 (flu babi) dan H5N1 (flu burung), bisa sangat mematikan.\n" .
                    "  Gejala meliputi demam, batuk, sakit tenggorokan, dan nyeri otot.\n" .
                    "  Pencegahan: Vaksinasi flu tahunan, mencuci tangan yang baik, dan hindari kontak dekat dengan orang sakit.\n\n" .
                    "Apakah ada virus tertentu yang ingin Anda ketahui lebih detail? Saya siap membantu memberikan informasi lebih lanjut! 😊";
            } else if (stripos($messageLower, 'indonesia') !== false && (stripos($messageLower, 'penyakit') !== false || stripos($messageLower, 'sakit') !== false)) {
                $aiResponse = "Hai! Wah, pertanyaan yang menarik tentang penyakit yang hanya ada di Indonesia!\n\n" .
                    "Sebenarnya, agak sulit untuk mengatakan bahwa suatu penyakit hanya ada di Indonesia, karena penyebaran penyakit bisa sangat dinamis dan kompleks. Namun, ada beberapa penyakit yang lebih sering ditemukan atau memiliki karakteristik unik di Indonesia dibandingkan negara lain. Penyakit-penyakit ini seringkali terkait dengan faktor lingkungan, gaya hidup, atau bahkan genetika populasi tertentu di Indonesia.\n\n" .
                    "Berikut adalah beberapa contoh penyakit atau kondisi kesehatan yang memiliki karakteristik khusus di Indonesia:\n\n" .
                    "- Demam Berdarah Dengue (DBD): DBD adalah masalah kesehatan masyarakat yang signifikan di Indonesia. Vektor nyamuk Aedes aegypti sangat umum di daerah tropis dan sub-tropis seperti Indonesia, dan curah hujan yang tinggi serta sanitasi yang kurang baik dapat memperburuk penyebaran penyakit ini.\n\n" .
                    "- Malaria: Meskipun malaria juga ditemukan di banyak negara tropis lainnya, Indonesia memiliki beban malaria yang cukup tinggi, terutama di wilayah timur seperti Papua. Berbagai program pengendalian malaria terus dilakukan untuk mengurangi kasus dan kematian akibat penyakit ini.\n\n" .
                    "- Filariasis (Penyakit Kaki Gajah): Filariasis adalah penyakit parasit yang menyebabkan pembengkakan ekstrem pada anggota tubuh, terutama kaki. Indonesia masih memiliki beberapa daerah endemis filariasis, meskipun upaya eliminasi terus dilakukan.\n\n" .
                    "- Penyakit Akibat Kurang Yodium (GAKI): Meskipun program fortifikasi yodium telah berjalan, beberapa daerah pegunungan dan pedalaman di Indonesia masih memiliki prevalensi GAKI yang cukup tinggi.\n\n" .
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

        return [
            'success' => true,
            'response' => $aiResponse
        ];
    }

    private function buildMaintenanceResponse(?string $reason = null): array
    {
        if ($reason) {
            Log::info('HealsAI maintenance mode', ['reason' => $reason]);
        }

        $message = "Halo! Sistem AI utama kami sedang dijeda sebentar, tapi saya tetap siap bantu menjawab pertanyaan kesehatan umum Anda. "
            . "Silakan lanjutkan chat ya, dan jika butuh konsultasi lebih lanjut, fitur Telekonsultasi selalu tersedia.";

        return [
            'success' => true,
            'response' => $message,
            'source' => 'healsai-maintenance',
            'fallback_used' => true,
        ];
    }
}