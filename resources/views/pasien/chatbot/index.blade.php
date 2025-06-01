@extends('layouts.pasien')

@section('pasien-content')
<style>
    /* Custom styles untuk chatbot */
    .ai-message {
        line-height: 1.6;
    }
    
    .ai-message p {
        margin-bottom: 1.8em;
        padding: 0;
    }
    
    .ai-message ul, .ai-message ol {
        margin: 1.5em 0 1.8em 0 !important;
        padding-left: 2.5em !important;
        list-style-position: outside !important;
    }
    
    .ai-message ul li, .ai-message ol li {
        margin-bottom: 0.8em !important;
        padding-left: 0.5em !important;
        display: list-item !important;
    }
    
    .ai-message ul {
        list-style-type: disc !important;
    }
    
    .ai-message ol {
        list-style-type: decimal !important;
    }
    
    .ai-message ol.alpha {
        list-style-type: lower-alpha !important;
    }
    
    .ai-message li > ul, 
    .ai-message li > ol {
        margin-top: 0.8em !important;
        margin-bottom: 0.8em !important;
    }
    
    /* Fixing nested lists */
    .ai-message li > ul li, 
    .ai-message li > ol li {
        margin-left: 1em !important;
    }
    
    /* Extra space for list items that contain multiple lines */
    .ai-message li {
        margin-bottom: 1em !important;
        line-height: 1.6 !important;
    }
    
    /* Ensure dash-prefixed lines are properly formatted */
    .ai-message p span.list-dash {
        display: block;
        margin-left: 1.5em;
        text-indent: -1em;
        margin-bottom: 0.5em;
    }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">HealsAI - Asisten Kesehatan</h1>
    <p class="text-sm text-gray-600">Tanyakan informasi kesehatan kepada asisten AI pintar kami</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Chat -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-600 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">HealsAI</h3>
                        <p class="text-xs text-blue-100">Asisten Kesehatan Pintar</p>
                    </div>
                </div>
            </div>
            
            <div class="h-[500px] p-4 overflow-y-auto" id="chatContainer">
                <!-- Pesan AI -->
                <div class="flex mb-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="bg-gradient-to-r from-gray-100 to-gray-50 rounded-lg py-3 px-4 max-w-[80%] shadow-sm">
                        <p class="text-sm text-gray-800">Halo! Saya HealsAI, asisten kesehatan pintar Anda. Apa yang ingin Anda tanyakan tentang kesehatan hari ini?</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t bg-gray-50">
                <form id="chatForm" class="flex items-center">
                    <input type="text" id="messageInput" class="flex-grow border border-gray-300 rounded-l-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik pertanyaan Anda...">
                    <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-r-md transition flex items-center">
                        <span id="sendText">Kirim</span>
                        <svg id="sendIcon" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <div id="loadingIcon" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Kolom Topik Populer -->
    <div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                <h3 class="font-semibold">Topik Populer</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <button class="w-full text-left p-2 rounded-md hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Apa itu diabetes?')">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Apa itu diabetes?
                        </button>
                    </li>
                    <li>
                        <button class="w-full text-left p-2 rounded-md hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Bagaimana cara menjaga kesehatan jantung?')">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            Menjaga kesehatan jantung
                        </button>
                    </li>
                    <li>
                        <button class="w-full text-left p-2 rounded-md hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Berapa banyak air yang harus diminum setiap hari?')">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            Konsumsi air yang ideal
                        </button>
                    </li>
                    <li>
                        <button class="w-full text-left p-2 rounded-md hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Apa saja gejala COVID-19?')">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Gejala COVID-19
                        </button>
                    </li>
                    <li>
                        <button class="w-full text-left p-2 rounded-md hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Makanan apa yang baik untuk meningkatkan imunitas?')">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            Makanan peningkat imunitas
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white flex justify-between items-center">
                <h3 class="font-semibold">Riwayat Percakapan</h3>
                <div class="flex space-x-2">
                    <button id="newChatBtn" class="text-xs bg-green-500 hover:bg-green-600 text-white py-1 px-2 rounded-md transition-colors">
                        Percakapan Baru
                    </button>
                    <button id="clearHistoryBtn" class="text-xs bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded-md transition-colors">
                        Hapus Riwayat
                    </button>
                </div>
            </div>
            <div class="p-4" id="chatHistoryContainer">
                <p class="text-xs text-gray-500 mb-2">Percakapan terbaru Anda</p>
                <ul class="space-y-2" id="chatHistoryList">
                    <!-- Riwayat percakapan akan ditampilkan di sini -->
                    <li class="text-xs text-gray-400 italic">Belum ada riwayat percakapan</li>
                </ul>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                <h3 class="font-semibold">Disclaimer</h3>
            </div>
            <div class="p-4">
                <p class="text-sm text-gray-600">Chatbot AI ini hanya memberikan informasi umum dan tidak menggantikan konsultasi dengan profesional kesehatan. Untuk masalah kesehatan serius, segera hubungi dokter atau layanan kesehatan terdekat.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatContainer = document.getElementById('chatContainer');
        const submitBtn = document.getElementById('submitBtn');
        const sendText = document.getElementById('sendText');
        const sendIcon = document.getElementById('sendIcon');
        const loadingIcon = document.getElementById('loadingIcon');
        const newChatBtn = document.getElementById('newChatBtn');
        const clearHistoryBtn = document.getElementById('clearHistoryBtn');
        
        // Struktur data untuk riwayat percakapan
        let chatHistories = [];       // Array untuk menyimpan seluruh riwayat percakapan
        let currentChatId = null;     // ID percakapan yang sedang aktif
        let currentMessages = [];     // Pesan-pesan dalam percakapan aktif

        // Memuat riwayat percakapan dari localStorage
        function loadChatHistories() {
            try {
                const savedHistories = localStorage.getItem('healsai_chat_histories');
                if (savedHistories) {
                    chatHistories = JSON.parse(savedHistories);
                    // Muat percakapan terakhir jika ada
                    if (chatHistories.length > 0) {
                        currentChatId = chatHistories[0].id;
                        currentMessages = [...chatHistories[0].messages];
                    }
                }
            } catch (e) {
                console.error('Gagal memuat riwayat percakapan:', e);
                chatHistories = [];
            }
        }

        // Menyimpan semua riwayat percakapan ke localStorage
        function saveAllChatHistories() {
            try {
                localStorage.setItem('healsai_chat_histories', JSON.stringify(chatHistories));
            } catch (e) {
                console.error('Gagal menyimpan riwayat percakapan:', e);
            }
        }
        
        // Load chat histories dan inisialisasi pertama kali
        loadChatHistories();
        
        // Jika belum ada riwayat atau tidak ada chat yang dipilih, mulai chat baru
        if (chatHistories.length === 0 || !currentChatId) {
            startNewChat();
        } else {
            // Jika ada riwayat, muat chat terakhir
            loadChat(chatHistories[0].id);
        }
        
        // Handler untuk form submit
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message) {
                sendMessage(message);
                messageInput.value = '';
            }
        });
        
        // Fungsi untuk memulai chat baru
        function startNewChat() {
            // Simpan chat saat ini jika ada
            saveCurrentChat();
            
            // Buat ID baru berdasarkan timestamp
            currentChatId = Date.now();
            currentMessages = [{
                role: 'assistant',
                content: 'Halo! Saya HealsAI, asisten kesehatan pintar Anda. Apa yang ingin Anda tanyakan tentang kesehatan hari ini?'
            }];
            
            // Reset flag popup telekonsultasi untuk percakapan baru
            resetTeleconsultPopupFlag();
            
            // Kosongkan chat container
            chatContainer.innerHTML = '';
            
            // Tampilkan pesan selamat datang
            addAIMessage('Halo! Saya HealsAI, asisten kesehatan pintar Anda. Apa yang ingin Anda tanyakan tentang kesehatan hari ini?', false);
            
            // Tampilkan indikator percakapan baru
            showNewConversationIndicator();
            
            // Perbarui UI histori
            updateChatHistoriesUI();
        }
        
        // Fungsi untuk mereset flag popup telekonsultasi
        function resetTeleconsultPopupFlag() {
            // Hapus flag untuk percakapan saat ini
            if (currentChatId) {
                localStorage.removeItem(`healsai_teleconsult_popup_shown_${currentChatId}`);
            }
        }
        
        // Fungsi untuk menampilkan indikator percakapan baru
        function showNewConversationIndicator() {
            const indicator = document.createElement('div');
            indicator.className = 'flex justify-center my-4';
            indicator.innerHTML = `
                <div class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1.5 rounded-full">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Percakapan Baru Dimulai
                </div>
            `;
            chatContainer.appendChild(indicator);
            scrollToBottom();
        }
        
        // Fungsi untuk mengirim pesan
        function sendMessage(message) {
            // Tampilkan pesan user
            addUserMessage(message);
            
            // Tambahkan ke messages saat ini
            currentMessages.push({
                role: 'user',
                content: message
            });
            
            // Simpan ke localStorage
            saveCurrentChat();
            
            // Tampilkan loading state
            setLoadingState(true);
            
            // Cek apakah ini adalah pertanyaan pertama dalam chat baru
            const isFirstQuestion = currentMessages.length === 2 && currentMessages[0].role === 'assistant';
            
            // Kirim ke API HealsAI
            fetch('/pasien/chatbot/healsai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message,
                    history: currentMessages,
                    is_new_conversation: isFirstQuestion
                })
            })
            .then(response => response.json())
            .then(data => {
                // Sembunyikan loading state
                setLoadingState(false);
                
                if (data.success) {
                    // Tampilkan respons AI
                    addAIMessage(data.response);
                    
                    // Tambahkan ke messages saat ini
                    currentMessages.push({
                        role: 'assistant',
                        content: data.response
                    });
                    
                    // Simpan ke localStorage
                    saveCurrentChat();
                } else {
                    // Tampilkan pesan error
                    addErrorMessage(data.message || 'Terjadi kesalahan saat memproses permintaan Anda.');
                    showNotification(data.message || 'Terjadi kesalahan saat memproses permintaan Anda.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                setLoadingState(false);
                addErrorMessage('Terjadi kesalahan koneksi. Silakan coba lagi.');
                showNotification('Terjadi kesalahan koneksi. Silakan coba lagi.', 'error');
            });
        }
        
        // Fungsi untuk menampilkan pesan user
        function addUserMessage(message, updateHistory = true) {
        const userMessageHTML = `
            <div class="flex mb-4 justify-end">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg py-3 px-4 max-w-[80%] shadow-sm">
                        <p class="text-sm text-white">${escapeHtml(message)}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 ml-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        `;
        chatContainer.insertAdjacentHTML('beforeend', userMessageHTML);
            scrollToBottom();
            
            // Perbarui UI riwayat jika perlu
            if (updateHistory) {
                updateChatHistoriesUI();
            }
        }
        
        // Fungsi untuk menampilkan pesan AI
        function addAIMessage(message, updateHistory = true) {
            // Format pesan dengan markdown sederhana dan paragraf
            const formattedMessage = formatMessage(message);
            
            const aiMessageHTML = `
                <div class="flex mb-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="bg-gradient-to-r from-gray-100 to-gray-50 rounded-lg py-3 px-4 max-w-[80%] shadow-sm">
                        <div class="text-sm text-gray-800 ai-message">${formattedMessage}</div>
                    </div>
                </div>
            `;
            chatContainer.insertAdjacentHTML('beforeend', aiMessageHTML);
            scrollToBottom();
            
            // Perbarui UI riwayat jika perlu
            if (updateHistory) {
                updateChatHistoriesUI();
            }
            
            // Deteksi rekomendasi telekonsultasi
            checkForTeleconsultRecommendation(message);
        }
        
        // Fungsi untuk menampilkan pesan error
        function addErrorMessage(message) {
            const errorMessageHTML = `
                <div class="flex mb-4">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-2 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="bg-red-50 rounded-lg py-3 px-4 max-w-[80%] shadow-sm">
                        <p class="text-sm text-red-800">${escapeHtml(message)}</p>
                    </div>
                </div>
            `;
            chatContainer.insertAdjacentHTML('beforeend', errorMessageHTML);
            scrollToBottom();
        }
        
        // Fungsi untuk menampilkan indikator typing
        function addLoadingIndicator() {
            const typingIndicator = document.createElement('div');
            typingIndicator.id = 'typingIndicator';
            typingIndicator.classList.add('flex', 'mb-4');
            typingIndicator.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg py-3 px-4 max-w-[80%]">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            `;
            chatContainer.appendChild(typingIndicator);
            scrollToBottom();
        }
        
        // Fungsi untuk menghapus indikator typing
        function removeLoadingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
        
        // Fungsi untuk mengatur state loading
        function setLoadingState(isLoading) {
            if (isLoading) {
                sendText.classList.add('hidden');
                sendIcon.classList.add('hidden');
                loadingIcon.classList.remove('hidden');
                submitBtn.disabled = true;
                messageInput.disabled = true;
                addLoadingIndicator();
            } else {
                sendText.classList.remove('hidden');
                sendIcon.classList.remove('hidden');
                loadingIcon.classList.add('hidden');
                submitBtn.disabled = false;
                messageInput.disabled = false;
                removeLoadingIndicator();
            }
        }
        
        // Fungsi untuk scroll ke bawah
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        
        // Fungsi untuk escape HTML
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        // Fungsi untuk format pesan
        function formatMessage(message) {
            // Simpan format asli untuk deteksi rekomendasi telekonsultasi
            const originalMessage = message;
            
            // Langkah 1: Bersihkan input dari tag HTML
            message = message.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            
            // Pola regex untuk berbagai jenis item list
            const bulletPattern = /^(\s*)-\s+(.+)$/;
            const numberPattern = /^(\s*)\d+\.\s+(.+)$/;
            const letterPattern = /^(\s*)[a-z]\.\s+(.+)$/;
            
            // Langkah 2: Split pesan ke dalam paragraf (blok teks dipisahkan oleh 2+ baris kosong)
            const paragraphs = message.split(/\n{2,}/);
            let formattedParagraphs = [];
            
            // Langkah 3: Proses setiap paragraf
            paragraphs.forEach(paragraph => {
                // Jika paragraf kosong, lewati
                if (paragraph.trim() === '') return;
                
                // Split paragraf menjadi baris-baris
                const lines = paragraph.split('\n');
                
                // Deteksi apakah ini adalah blok list
                let isList = false;
                let listType = null;
                let indentLevel = 0;
                
                // Cek apakah mayoritas baris adalah item list
                const bulletLines = lines.filter(line => line.match(bulletPattern)).length;
                const numberLines = lines.filter(line => line.match(numberPattern)).length;
                const letterLines = lines.filter(line => line.match(letterPattern)).length;
                
                if (bulletLines > 0 && bulletLines >= numberLines && bulletLines >= letterLines) {
                    isList = true;
                    listType = 'ul';
                } else if (numberLines > 0 && numberLines >= bulletLines && numberLines >= letterLines) {
                    isList = true;
                    listType = 'ol';
                } else if (letterLines > 0 && letterLines >= bulletLines && letterLines >= numberLines) {
                    isList = true;
                    listType = 'ol-alpha';
                }
                
                // Jika ini adalah list, proses sebagai list
                if (isList) {
                    let listHtml = '';
                    let currentListType = listType;
                    let inList = false;
                    let itemContent = '';
                    let currentIndent = 0;
                    
                    // Tambahkan tag pembuka list
                    if (currentListType === 'ul') {
                        listHtml += '<ul>';
                    } else if (currentListType === 'ol') {
                        listHtml += '<ol>';
                    } else if (currentListType === 'ol-alpha') {
                        listHtml += '<ol class="alpha">';
                    }
                    
                    // Proses setiap baris
                    lines.forEach(line => {
                        // Cek tipe item
                        const bulletMatch = line.match(bulletPattern);
                        const numberMatch = line.match(numberPattern);
                        const letterMatch = line.match(letterPattern);
                        
                        if (bulletMatch || numberMatch || letterMatch) {
                            // Jika sudah ada item sebelumnya, tutup dulu
                            if (itemContent) {
                                listHtml += `<li>${itemContent}</li>`;
                                itemContent = '';
                            }
                            
                            // Tentukan konten item baru
                            if (bulletMatch) {
                                itemContent = bulletMatch[2];
                            } else if (numberMatch) {
                                itemContent = numberMatch[2];
                            } else if (letterMatch) {
                                itemContent = letterMatch[2];
                            }
                        } else if (line.trim() !== '') {
                            // Baris lanjutan untuk item list sebelumnya
                            if (itemContent) {
                                itemContent += ' ' + line.trim();
                            } else {
                                // Jika belum ada item list, anggap sebagai teks biasa
                                listHtml += `<p>${line}</p>`;
                            }
                        }
                    });
                    
                    // Pastikan item terakhir ditutup
                    if (itemContent) {
                        listHtml += `<li>${itemContent}</li>`;
                    }
                    
                    // Tutup tag list
                    if (currentListType === 'ul') {
                        listHtml += '</ul>';
                    } else if (currentListType === 'ol' || currentListType === 'ol-alpha') {
                        listHtml += '</ol>';
                    }
                    
                    formattedParagraphs.push(listHtml);
                } else {
                    // Bukan list, proses sebagai paragraf biasa
                    // Cek apakah ada format spesial dalam paragraf (misalnya single-line dash)
                    const formattedLines = lines.map(line => {
                        const dashMatch = line.match(/^\s*-\s+(.+)$/);
                        if (dashMatch) {
                            return `<span class="list-dash">- ${dashMatch[1]}</span>`;
                        }
                        return line;
                    });
                    
                    const paragraphContent = formattedLines.join(' ');
                    formattedParagraphs.push(`<p>${paragraphContent}</p>`);
                }
            });
            
            // Langkah 4: Gabungkan semua paragraf yang sudah diformat
            let html = formattedParagraphs.join('\n');
            
            // Langkah 5: Format untuk emoji dan styling sederhana
            html = html.replace(/:\)/g, '😊');
            html = html.replace(/:\(/g, '😔');
            html = html.replace(/:\-\)/g, '🙂');
            html = html.replace(/;\)/g, '😉');
            
            // Format untuk styling sederhana
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
            
            // Langkah 6: Tambahkan class wrapper
            html = `<div class="ai-message">${html}</div>`;
            
            return html;
        }
        
        // Menyimpan percakapan saat ini ke dalam riwayat
        function saveCurrentChat() {
            if (!currentChatId || currentMessages.length === 0) return;
            
            // Dapatkan judul dari pesan pertama user jika ada
            let title = 'Percakapan Baru';
            const firstUserMessage = currentMessages.find(msg => msg.role === 'user');
            if (firstUserMessage) {
                title = firstUserMessage.content.substring(0, 30) + (firstUserMessage.content.length > 30 ? '...' : '');
            }
            
            // Cari apakah chat ini sudah ada di histories
            const existingIndex = chatHistories.findIndex(chat => chat.id === currentChatId);
            
            const chatData = {
                id: currentChatId,
                title: title,
                timestamp: Date.now(),
                messages: [...currentMessages]
            };
            
            if (existingIndex !== -1) {
                // Update chat yang sudah ada
                chatHistories[existingIndex] = chatData;
            } else {
                // Tambahkan chat baru
                chatHistories.unshift(chatData);
            }
            
            // Batasi jumlah riwayat chat (maksimal 5)
            if (chatHistories.length > 5) {
                chatHistories = chatHistories.slice(0, 5);
            }
            
            // Urutkan berdasarkan waktu akses terbaru
            chatHistories.sort((a, b) => b.timestamp - a.timestamp);
            
            // Simpan ke localStorage
            saveAllChatHistories();
        }
        
        // Memuat percakapan tertentu
        function loadChat(chatId) {
            const chat = chatHistories.find(c => c.id === chatId);
            if (chat) {
                // Set chat yang dipilih sebagai chat saat ini
                currentChatId = chat.id;
                currentMessages = [...chat.messages];
                
                // Reset flag popup telekonsultasi saat memuat percakapan berbeda
                resetTeleconsultPopupFlag();
                
                // Kosongkan container chat
                chatContainer.innerHTML = '';
                
                // Tampilkan semua pesan dari riwayat
                currentMessages.forEach(message => {
                    if (message.role === 'user') {
                        addUserMessage(message.content, false);
                    } else {
                        addAIMessage(message.content, false);
                    }
                });
                
                // Scroll ke bawah
                scrollToBottom();
                
                // Tandai chat ini sebagai chat yang terakhir diakses
                chat.timestamp = Date.now();
                
                // Urutkan ulang berdasarkan timestamp terbaru
                chatHistories.sort((a, b) => b.timestamp - a.timestamp);
                
                // Simpan perubahan
                saveAllChatHistories();
                
                // Perbarui UI histori
                updateChatHistoriesUI();
            }
        }
        
        // Memperbarui tampilan UI riwayat chat
        function updateChatHistoriesUI() {
            const historyList = document.getElementById('chatHistoryList');
            
            // Kosongkan list
            historyList.innerHTML = '';
            
            // Tampilkan pesan jika belum ada riwayat
            if (chatHistories.length === 0) {
                historyList.innerHTML = '<li class="text-xs text-gray-400 italic">Belum ada riwayat percakapan</li>';
                return;
            }
            
            // Tampilkan riwayat chat (maksimal 5)
            chatHistories.forEach(chat => {
                const isActive = chat.id === currentChatId;
                const formattedDate = new Date(chat.timestamp).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const chatItemHTML = `
                    <li>
                        <button class="w-full text-left p-2 rounded-md ${isActive ? 'bg-blue-100' : 'hover:bg-blue-50'} text-sm text-gray-700 transition flex items-center" 
                               onclick="window.loadChatById(${chat.id})">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <p class="truncate font-medium">${escapeHtml(chat.title)}</p>
                                <p class="text-xs text-gray-400">${formattedDate}</p>
                            </div>
                        </button>
                    </li>
                `;
                historyList.insertAdjacentHTML('beforeend', chatItemHTML);
            });
        }
        
        // Event listener untuk tombol Percakapan Baru
        newChatBtn.addEventListener('click', function() {
            startNewChat();
            showNotification('Percakapan baru telah dimulai', 'success');
        });
        
        // Event listener untuk tombol Hapus Riwayat
        clearHistoryBtn.addEventListener('click', function() {
            showConfirmDialog('Anda yakin ingin menghapus semua riwayat percakapan?', function() {
                // Hapus dari localStorage
                localStorage.removeItem('healsai_chat_histories');
                
                // Reset riwayat
                chatHistories = [];
                
                // Mulai chat baru
                startNewChat();
                
                // Notifikasi
                showNotification('Riwayat percakapan berhasil dihapus.');
            }, function() {
                // Tidak ada tindakan jika pengguna membatalkan
            });
        });
        
        // Fungsi global untuk memuat chat berdasarkan ID
        window.loadChatById = function(chatId) {
            loadChat(parseInt(chatId));
            // Cari judul percakapan
            const chat = chatHistories.find(c => c.id === parseInt(chatId));
            if (chat) {
                showNotification(`Melanjutkan percakapan: "${chat.title}"`, 'info');
            }
        };
        
        // Fungsi untuk menampilkan notifikasi menarik
        function showNotification(message, type = 'success') {
            // Hapus notifikasi lama jika ada
            const oldNotification = document.getElementById('healsai-notification');
            if (oldNotification) {
                oldNotification.remove();
            }
            
            // Tentukan warna berdasarkan tipe
            let bgColor, iconSvg;
            if (type === 'success') {
                bgColor = 'from-green-500 to-green-600';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                bgColor = 'from-red-500 to-red-600';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else if (type === 'warning') {
                bgColor = 'from-yellow-500 to-yellow-600';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else if (type === 'info') {
                bgColor = 'from-blue-400 to-blue-500';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            } else if (type === 'question') {
                bgColor = 'from-blue-500 to-blue-600';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.id = 'healsai-notification';
            notification.className = 'fixed top-4 right-4 z-50 flex items-center p-4 mb-4 rounded-lg shadow-lg text-white bg-gradient-to-r ' + bgColor + ' transition-all duration-500 transform translate-x-full opacity-0';
            notification.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg bg-white/25">
                    ${iconSvg}
                </div>
                <div class="ml-3 text-sm font-normal">${message}</div>
                <button type="button" class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 bg-white/10 hover:bg-white/20" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            
            // Tambahkan ke DOM
            document.body.appendChild(notification);
            
            // Tampilkan dengan animasi
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Sembunyikan setelah beberapa detik
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 5000);
        }
        
        // Fungsi untuk konfirmasi dengan modal kustom
        function showConfirmDialog(message, onConfirm, onCancel) {
            // Hapus dialog lama jika ada
            const oldDialog = document.getElementById('healsai-confirm-dialog');
            const oldBackdrop = document.getElementById('healsai-confirm-backdrop');
            if (oldDialog) {
                oldDialog.remove();
            }
            if (oldBackdrop) {
                oldBackdrop.remove();
            }
            
            // Buat backdrop
            const backdrop = document.createElement('div');
            backdrop.id = 'healsai-confirm-backdrop';
            backdrop.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 z-40 flex items-center justify-center transition-opacity duration-300 opacity-0';
            
            // Buat dialog
            const dialog = document.createElement('div');
            dialog.id = 'healsai-confirm-dialog';
            dialog.className = 'bg-white rounded-lg shadow-xl p-6 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0';
            dialog.innerHTML = `
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi</h3>
                    <p class="text-sm text-gray-600 mb-6">${message}</p>
                    <div class="flex justify-center space-x-4">
                        <button id="healsai-confirm-cancel" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors">
                            Batal
                        </button>
                        <button id="healsai-confirm-ok" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                            Lanjutkan
                        </button>
                    </div>
                </div>
            `;
            
            // Tambahkan ke DOM
            backdrop.appendChild(dialog);
            document.body.appendChild(backdrop);
            
            // Tampilkan dengan animasi
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                dialog.classList.remove('scale-95', 'opacity-0');
                dialog.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Event listeners
            document.getElementById('healsai-confirm-ok').addEventListener('click', function() {
                closeDialog();
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
            });
            
            document.getElementById('healsai-confirm-cancel').addEventListener('click', function() {
                closeDialog();
                if (typeof onCancel === 'function') {
                    onCancel();
                }
            });
            
            // Tambahkan click handler untuk backdrop (untuk menutup dialog saat klik di luar)
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    closeDialog();
                    if (typeof onCancel === 'function') {
                        onCancel();
                    }
                }
            });
            
            // Fungsi untuk menutup dialog
            function closeDialog() {
                try {
                    backdrop.classList.add('opacity-0');
                    dialog.classList.add('scale-95', 'opacity-0');
                    dialog.classList.remove('scale-100', 'opacity-100');
                    
                    setTimeout(() => {
                        if (backdrop && backdrop.parentNode) {
                            backdrop.parentNode.removeChild(backdrop);
                        }
                    }, 300);
                } catch (error) {
                    console.error('Error menutup dialog konfirmasi:', error);
                    // Fallback: coba hapus dialog langsung jika terjadi error
                    const backdropElement = document.getElementById('healsai-confirm-backdrop');
                    if (backdropElement && backdropElement.parentNode) {
                        backdropElement.parentNode.removeChild(backdropElement);
                    }
                }
            }
        }
        
        // Fungsi untuk mendeteksi rekomendasi telekonsultasi dan menampilkan popup
        function checkForTeleconsultRecommendation(message) {
            // Cek apakah popup sudah ditampilkan dalam percakapan ini
            const popupShownKey = `healsai_teleconsult_popup_shown_${currentChatId}`;
            if (localStorage.getItem(popupShownKey) === 'true') {
                // Popup sudah ditampilkan di percakapan ini, tidak perlu tampilkan lagi
                return;
            }
            
            const telekonsultasiPhrases = [
                'telekonsultasi',
                'konsultasi dengan dokter',
                'konsultasi ke dokter',
                'berkonsultasi dengan dokter',
                'berkonsultasi ke dokter',
                'konsultasi langsung',
                'konsultasi medis',
                'hubungi dokter',
                'terhubung dengan dokter',
                'layanan telekonsultasi',
                'fitur telekonsultasi',
                'fitur konsultasi',
                'dokter yang sesungguhnya',
                'dokter profesional',
                'bantuan dokter',
                'diagnosis yang tepat'
            ];
            
            // Cek jika pesan mengandung frasa rekomendasi telekonsultasi
            const messageLower = message.toLowerCase();
            const containsTelekonsultPhrase = telekonsultasiPhrases.some(phrase => messageLower.includes(phrase.toLowerCase()));
            
            if (containsTelekonsultPhrase) {
                // Tunggu sebentar agar user membaca pesan AI terlebih dahulu
                setTimeout(() => {
                    // Tampilkan popup konfirmasi telekonsultasi
                    showTeleconsultConfirmation();
                    
                    // Tandai bahwa popup telah ditampilkan dalam percakapan ini
                    localStorage.setItem(popupShownKey, 'true');
        }, 1500);
            }
        }
        
        // Fungsi untuk menampilkan popup konfirmasi telekonsultasi
        function showTeleconsultConfirmation() {
            // Hapus dialog lama jika ada
            const oldDialog = document.getElementById('teleconsult-confirm-dialog');
            const oldBackdrop = document.getElementById('teleconsult-confirm-backdrop');
            if (oldDialog) {
                oldDialog.remove();
            }
            if (oldBackdrop) {
                oldBackdrop.remove();
            }
            
            // Buat backdrop
            const backdrop = document.createElement('div');
            backdrop.id = 'teleconsult-confirm-backdrop';
            backdrop.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 z-40 flex items-center justify-center transition-opacity duration-300 opacity-0';
            
            // Buat dialog
            const dialog = document.createElement('div');
            dialog.id = 'teleconsult-confirm-dialog';
            dialog.className = 'bg-white rounded-lg shadow-xl p-6 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0';
            dialog.innerHTML = `
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Rekomendasi Telekonsultasi</h3>
                    <p class="text-sm text-gray-600 mb-6">HealsAI merekomendasikan Anda untuk berkonsultasi dengan dokter. Apakah Anda ingin melakukan telekonsultasi dengan dokter sekarang?</p>
                    <div class="flex justify-center space-x-4">
                        <button id="teleconsult-cancel" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors">
                            Tidak, Lanjutkan Chat
                        </button>
                        <button id="teleconsult-ok" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                            Ya, Konsultasi dengan Dokter
                        </button>
                    </div>
                </div>
            `;
            
            // Tambahkan ke DOM
            backdrop.appendChild(dialog);
            document.body.appendChild(backdrop);
            
            // Tampilkan dengan animasi
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                dialog.classList.remove('scale-95', 'opacity-0');
                dialog.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Event listeners
            document.getElementById('teleconsult-ok').addEventListener('click', function() {
                closeDialog();
                // Arahkan ke halaman telekonsultasi
                window.location.href = '/pasien/konsultasi/create';
            });
            
            document.getElementById('teleconsult-cancel').addEventListener('click', function() {
                closeDialog();
                // Tampilkan notifikasi
                showNotification('Anda dapat melakukan telekonsultasi kapan saja melalui menu Telekonsultasi', 'info');
            });
            
            // Tambahkan click handler untuk backdrop (untuk menutup dialog saat klik di luar)
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    closeDialog();
                }
            });
            
            // Fungsi untuk menutup dialog
            function closeDialog() {
                try {
                    backdrop.classList.add('opacity-0');
                    dialog.classList.add('scale-95', 'opacity-0');
                    dialog.classList.remove('scale-100', 'opacity-100');
                    
                    setTimeout(() => {
                        if (backdrop && backdrop.parentNode) {
                            backdrop.parentNode.removeChild(backdrop);
                        }
                    }, 300);
                } catch (error) {
                    console.error('Error menutup dialog:', error);
                    // Fallback: coba hapus dialog langsung jika terjadi error
                    const backdropElement = document.getElementById('teleconsult-confirm-backdrop');
                    if (backdropElement && backdropElement.parentNode) {
                        backdropElement.parentNode.removeChild(backdropElement);
                    }
                }
            }
        }
        
        // Tambahkan fungsi global untuk menutup semua dialog
        window.closeAllDialogs = function() {
            try {
                // Identifikasi semua dialog dan backdrop yang mungkin ada
                const dialogIds = [
                    'teleconsult-confirm-backdrop',
                    'healsai-confirm-backdrop',
                    'healsai-notification'
                ];
                
                // Hapus semua dialog yang ditemukan
                dialogIds.forEach(id => {
                    const element = document.getElementById(id);
                    if (element && element.parentNode) {
                        element.parentNode.removeChild(element);
                    }
                });
                
                // Hapus semua elemen dengan class yang mengandung kata 'backdrop'
                document.querySelectorAll('[id*="backdrop"]').forEach(el => {
                    if (el && el.parentNode) {
                        el.parentNode.removeChild(el);
                    }
                });
                
                console.log('Semua dialog telah dibersihkan');
                return true;
            } catch (e) {
                console.error('Gagal membersihkan dialog:', e);
                return false;
            }
        };
        
        // Tambahkan tombol escape key untuk menutup semua dialog
        document.addEventListener('keydown', function(e) {
            // Jika tombol Escape ditekan
            if (e.key === 'Escape') {
                window.closeAllDialogs();
            }
        });
        
        // Tambahkan event listener untuk tombol emergency close yang selalu tersedia
        const addEmergencyCloseButton = () => {
            // Hapus jika sudah ada
            const existingButton = document.getElementById('emergency-close-button');
            if (existingButton) {
                existingButton.remove();
            }
            
            // Buat tombol emergency
            const emergencyButton = document.createElement('button');
            emergencyButton.id = 'emergency-close-button';
            emergencyButton.className = 'fixed bottom-4 right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg z-50 opacity-0 transition-opacity duration-300';
            emergencyButton.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            emergencyButton.title = 'Tutup semua dialog';
            emergencyButton.onclick = window.closeAllDialogs;
            
            // Tampilkan tombol saat ada dialog
            document.body.appendChild(emergencyButton);
            
            // Set interval untuk memeriksa apakah ada dialog yang terbuka
            setInterval(() => {
                const hasOpenDialog = document.getElementById('teleconsult-confirm-backdrop') || 
                                     document.getElementById('healsai-confirm-backdrop');
                if (hasOpenDialog) {
                    emergencyButton.classList.remove('opacity-0');
                } else {
                    emergencyButton.classList.add('opacity-0');
                }
            }, 1000);
        };
        
        // Panggil fungsi untuk menambahkan tombol emergency
        addEmergencyCloseButton();
    });
    
    // Fungsi untuk menambahkan pesan yang disarankan
    function addSuggestedMessage(message) {
        const messageInput = document.getElementById('messageInput');
        messageInput.value = message;
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
</script>
@endpush
@endsection 