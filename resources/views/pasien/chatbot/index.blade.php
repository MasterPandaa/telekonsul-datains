@extends('layouts.pasien')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

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

    /* Modern chatbot styles */
    .chatbot-container {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 8px 10px -6px rgba(59, 130, 246, 0.1);
        transition: all 0.3s ease;
    }
    
    .chatbot-header {
        background: linear-gradient(120deg, #4f46e5, #3b82f6);
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    
    .chatbot-header::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }
    
    .chatbot-avatar {
        background: linear-gradient(120deg, #c7d2fe, #a5b4fc);
        border: 3px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .chatbot-avatar:hover {
        transform: scale(1.05);
    }
    
    .chat-message {
        transition: all 0.3s ease;
    }
    
    .chat-message:hover {
        transform: translateY(-2px);
    }
    
    .user-message {
        background: linear-gradient(120deg, #3b82f6, #2563eb);
        border-radius: 1rem 1rem 0 1rem;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
    }
    
    .ai-message-container {
        background: linear-gradient(120deg, #f9fafb, #f3f4f6);
        border-radius: 1rem 1rem 1rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    
    .chat-input {
        border-radius: 9999px;
        padding-left: 1.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .chat-input:focus {
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
    }
    
    .send-button {
        border-radius: 9999px;
        transition: all 0.3s ease;
    }
    
    .send-button:hover {
        transform: scale(1.05);
    }
    
    .topic-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .topic-card:hover {
        border-left-color: #3b82f6;
        transform: translateX(3px);
    }
    
    .topic-icon {
        transition: all 0.3s ease;
    }
    
    .topic-card:hover .topic-icon {
        transform: scale(1.2);
    }
    
    .chat-history-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .chat-history-item:hover, .chat-history-item.active {
        border-left-color: #3b82f6;
        transform: translateX(3px);
    }
    
    .chat-history-item.active {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .pulse-dot {
        position: relative;
    }
    
    .pulse-dot::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        bottom: 0;
        right: 0;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
    
    .typing-indicator {
        display: flex;
        align-items: center;
    }
    
    .typing-dot {
        width: 8px;
        height: 8px;
        margin: 0 2px;
        background-color: #3b82f6;
        border-radius: 50%;
        opacity: 0.6;
    }
    
    .typing-dot:nth-child(1) {
        animation: typing 1.5s infinite 0s;
    }
    
    .typing-dot:nth-child(2) {
        animation: typing 1.5s infinite 0.3s;
    }
    
    .typing-dot:nth-child(3) {
        animation: typing 1.5s infinite 0.6s;
    }
    
    @keyframes typing {
        0% {
            transform: translateY(0px);
            opacity: 0.6;
        }
        50% {
            transform: translateY(-5px);
            opacity: 1;
        }
        100% {
            transform: translateY(0px);
            opacity: 0.6;
        }
    }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <span class="bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">HealsAI</span>
        <span class="mx-2">-</span>
        <span>Asisten Kesehatan</span>
        <span class="ml-3 px-3 py-1 bg-gradient-to-r from-indigo-100 to-blue-100 text-indigo-800 text-xs rounded-full font-medium">AI Powered</span>
    </h1>
    <p class="text-sm text-gray-600 mt-1">Tanyakan informasi kesehatan kepada asisten AI pintar kami untuk mendapatkan jawaban cepat dan akurat</p>
</div>

<!-- Layout Utama -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Chat (Sebelah Kiri) -->
    <div class="lg:col-span-2">
        <div class="chatbot-container bg-white h-full flex flex-col">
            <div class="chatbot-header text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-4 pulse-dot">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">HealsAI</h3>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            <p class="text-xs text-blue-100">Asisten Kesehatan Pintar • Online</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="h-[400px] p-4 overflow-y-auto flex-grow" id="chatContainer">
                <!-- Pesan AI -->
                <div class="flex mb-4 chat-message">
                    <div class="w-8 h-8 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-2 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ai-message-container py-3 px-4 max-w-[85%] shadow-sm">
                        <p class="text-sm text-gray-800">Halo! Saya HealsAI, asisten kesehatan pintar Anda. Apa yang ingin Anda tanyakan tentang kesehatan hari ini?</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t bg-gray-50">
                <form id="chatForm" class="flex items-center">
                    <input type="text" id="messageInput" class="chat-input flex-grow border border-gray-300 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik pertanyaan Anda...">
                    <button type="submit" id="submitBtn" class="send-button ml-2 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-medium py-3 px-6 transition flex items-center">
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
    
    <!-- Kolom Kanan (Trend dan Riwayat) -->
    <div class="space-y-6">
        <!-- Topik Populer -->
        <div class="chatbot-container bg-white">
            <div class="chatbot-header text-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold">Topik Populer</h3>
                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full">Trending</span>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <button class="topic-card w-full text-left p-3 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Apa itu diabetes?')">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3 topic-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Apa itu diabetes?</p>
                                <p class="text-xs text-gray-500 mt-0.5">Informasi dasar tentang diabetes</p>
                            </div>
                        </button>
                    </li>
                    <li>
                        <button class="topic-card w-full text-left p-3 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Bagaimana cara menjaga kesehatan jantung?')">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3 topic-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Kesehatan jantung</p>
                                <p class="text-xs text-gray-500 mt-0.5">Tips menjaga jantung tetap sehat</p>
                            </div>
                        </button>
                    </li>
                    <li>
                        <button class="topic-card w-full text-left p-3 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition flex items-center" onclick="addSuggestedMessage('Berapa banyak air yang harus diminum setiap hari?')">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 topic-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Konsumsi air</p>
                                <p class="text-xs text-gray-500 mt-0.5">Kebutuhan air minum harian</p>
                            </div>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Riwayat Percakapan -->
        <div class="chatbot-container bg-white">
            <div class="chatbot-header text-white flex justify-between items-center">
                <h3 class="font-bold">Riwayat Percakapan</h3>
                <div class="flex space-x-2">
                    <button id="newChatBtn" class="text-xs bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-full transition-colors flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Baru
                    </button>
                    <button id="clearHistoryBtn" class="text-xs bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-full transition-colors flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
            <div class="p-4" id="chatHistoryContainer">
                <p class="text-xs text-gray-500 mb-3">Percakapan terbaru Anda</p>
                <ul class="space-y-2" id="chatHistoryList">
                    <!-- Riwayat percakapan akan ditampilkan di sini -->
                    <li class="text-xs text-gray-400 italic">Belum ada riwayat percakapan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Disclaimer (Bagian Bawah) -->
<div class="mt-4">
    <div class="flex items-center justify-center bg-blue-50 text-xs text-gray-600 py-2 px-3 rounded-md">
        <svg class="h-3.5 w-3.5 text-blue-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p>Chatbot ini hanya memberikan informasi umum dan tidak menggantikan konsultasi dengan dokter.</p>
    </div>
</div>

@push('scripts')
<script>
    /**
     * HealsAI - Chatbot Asisten Kesehatan
     * 
     * Fitur:
     * - Chatbot AI untuk informasi kesehatan
     * - Riwayat percakapan disimpan per user (pasien) di localStorage
     * - Deteksi rekomendasi telekonsultasi
     * - Tampilan responsif dan interaktif
     */
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

        // Dapatkan ID user saat ini untuk menyimpan riwayat spesifik per user
        const currentUserId = {{ Auth::id() }};
        const currentUserName = "{{ Auth::user()->name }}";
        const chatHistoryKey = `healsai_chat_histories_user_${currentUserId}`;
        const popupKeyPrefix = `healsai_teleconsult_popup_shown_user_${currentUserId}_chat_`;

        // Memuat riwayat percakapan dari localStorage
        function loadChatHistories() {
            try {
                const savedHistories = localStorage.getItem(chatHistoryKey);
                if (savedHistories) {
                    chatHistories = JSON.parse(savedHistories);
                    
                    // Filter untuk hanya memuat riwayat milik user saat ini
                    // atau riwayat lama yang belum memiliki userId
                    chatHistories = chatHistories.filter(chat => 
                        !chat.userId || chat.userId === currentUserId
                    );
                    
                    // Tambahkan userId ke chat yang belum memilikinya
                    chatHistories.forEach(chat => {
                        if (!chat.userId) {
                            chat.userId = currentUserId;
                        }
                    });
                    
                    // Simpan kembali setelah filtering dan penambahan userId
                    saveAllChatHistories();
                    
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
                localStorage.setItem(chatHistoryKey, JSON.stringify(chatHistories));
            } catch (e) {
                console.error('Gagal menyimpan riwayat percakapan:', e);
            }
        }
        
        // Load chat histories dan inisialisasi pertama kali
        loadChatHistories();
        
        // Bersihkan riwayat chat yang tidak terkait dengan user saat ini
        cleanupOtherUserHistories();
        
        // Jika belum ada riwayat atau tidak ada chat yang dipilih, mulai chat baru
        if (chatHistories.length === 0 || !currentChatId) {
            startNewChat();
        } else {
            // Jika ada riwayat, muat chat terakhir
            loadChat(chatHistories[0].id);
        }
        
        // Fungsi untuk membersihkan riwayat chat yang tidak terkait dengan user saat ini
        function cleanupOtherUserHistories() {
            // Hanya simpan maksimal 5 riwayat chat per user untuk menghemat ruang localStorage
            const maxHistoriesPerUser = 5;
            
            // Jika riwayat chat user saat ini lebih dari batas maksimal, potong
            if (chatHistories.length > maxHistoriesPerUser) {
                chatHistories = chatHistories.slice(0, maxHistoriesPerUser);
                saveAllChatHistories();
            }
            
            // Opsional: Bersihkan riwayat chat yang sudah terlalu lama (lebih dari 30 hari)
            const thirtyDaysAgo = Date.now() - (30 * 24 * 60 * 60 * 1000);
            chatHistories = chatHistories.filter(chat => chat.timestamp > thirtyDaysAgo);
            saveAllChatHistories();
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
                localStorage.removeItem(`${popupKeyPrefix}${currentChatId}`);
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
            // Ambil informasi foto profil pasien
            @php
                $pasien = Auth::user()->pasien;
                $fotoUrl = $pasien ? $pasien->foto_url : asset('img/pasien/default.jpg');
            @endphp
            
            const userPhotoUrl = "{{ $fotoUrl }}";
            const userPhotoHTML = `<div class="w-10 h-10 rounded-full bg-cover bg-center ml-3 flex-shrink-0 border-2 border-white shadow-sm" style="background-image: url('${userPhotoUrl}')"></div>`;
                
            const userMessageHTML = `
                <div class="flex mb-5 justify-end chat-message">
                    <div class="user-message py-3 px-4 max-w-[85%]">
                        <p class="text-sm text-white">${escapeHtml(message)}</p>
                    </div>
                    ${userPhotoHTML}
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
                <div class="flex mb-5 chat-message">
                    <div class="w-10 h-10 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ai-message-container py-3 px-4 max-w-[85%]">
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
                <div class="flex mb-5 chat-message">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="bg-red-50 rounded-lg py-3 px-4 max-w-[85%] border-l-4 border-red-400">
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
            typingIndicator.classList.add('flex', 'mb-5', 'chat-message');
            typingIndicator.innerHTML = `
                <div class="w-10 h-10 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ai-message-container py-3 px-4 max-w-[85%] flex items-center">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
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
                userId: currentUserId,
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
                // Validasi keamanan: pastikan chat ini milik user yang sedang login
                if (chat.userId && chat.userId !== currentUserId) {
                    console.error('Akses ditolak: Chat ini bukan milik user yang sedang login');
                    showNotification('Terjadi kesalahan saat memuat percakapan', 'error');
                    return;
                }
                
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
                historyList.innerHTML = `
                    <li class="text-center py-6">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400 italic">Belum ada riwayat percakapan</p>
                        <p class="text-xs text-gray-400 mt-1">Mulai percakapan baru dengan HealsAI</p>
                    </li>
                `;
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
                
                // Ambil snippet dari pesan terakhir
                let lastMessage = '';
                const userMessages = chat.messages.filter(m => m.role === 'user');
                if (userMessages.length > 0) {
                    const lastUserMessage = userMessages[userMessages.length - 1].content;
                    lastMessage = lastUserMessage.length > 30 ? lastUserMessage.substring(0, 30) + '...' : lastUserMessage;
                }
                
                const chatItemHTML = `
                    <li>
                        <button class="chat-history-item w-full text-left p-3 rounded-lg ${isActive ? 'active' : 'hover:bg-blue-50'} text-sm text-gray-700 transition flex items-start" 
                               onclick="window.loadChatById(${chat.id})">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center mb-1">
                                    <p class="truncate font-medium">${escapeHtml(chat.title)}</p>
                                    <span class="text-xs text-gray-400 flex-shrink-0 ml-2">${formattedDate}</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate">${lastMessage ? escapeHtml(lastMessage) : 'Percakapan baru'}</p>
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
                localStorage.removeItem(chatHistoryKey);
                
                // Hapus juga semua popup flags yang terkait dengan user ini
                Object.keys(localStorage).forEach(key => {
                    if (key.startsWith(`healsai_teleconsult_popup_shown_user_${currentUserId}`)) {
                        localStorage.removeItem(key);
                    }
                });
                
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
                bgColor = 'from-emerald-500 to-green-500';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                bgColor = 'from-red-500 to-rose-500';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else if (type === 'warning') {
                bgColor = 'from-amber-500 to-yellow-500';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else if (type === 'info') {
                bgColor = 'from-blue-500 to-indigo-500';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            } else if (type === 'question') {
                bgColor = 'from-violet-500 to-purple-500';
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.id = 'healsai-notification';
            notification.className = 'fixed top-4 right-4 z-50 flex items-center p-4 mb-4 rounded-xl shadow-lg text-white bg-gradient-to-r ' + bgColor + ' transition-all duration-500 transform translate-x-full opacity-0';
            notification.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-white/25 mr-3">
                    ${iconSvg}
                </div>
                <div class="text-sm font-medium">${message}</div>
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
            backdrop.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-40 flex items-center justify-center transition-opacity duration-300 opacity-0';
            
            // Buat dialog
            const dialog = document.createElement('div');
            dialog.id = 'healsai-confirm-dialog';
            dialog.className = 'bg-white rounded-xl shadow-xl p-6 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0';
            dialog.innerHTML = `
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi</h3>
                    <p class="text-sm text-gray-600 mb-6">${message}</p>
                    <div class="flex justify-center space-x-4">
                        <button id="healsai-confirm-cancel" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button id="healsai-confirm-ok" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-lg transition-colors">
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
        
        // Fungsi untuk mendeteksi rekomendasi telekonsultasi dan menampilkan popup
        function checkForTeleconsultRecommendation(message) {
            // Cek apakah popup sudah ditampilkan dalam percakapan ini
            const popupShownKey = `${popupKeyPrefix}${currentChatId}`;
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
            backdrop.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-40 flex items-center justify-center transition-opacity duration-300 opacity-0';
            
            // Buat dialog
            const dialog = document.createElement('div');
            dialog.id = 'teleconsult-confirm-dialog';
            dialog.className = 'bg-white rounded-xl shadow-xl p-6 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0';
            dialog.innerHTML = `
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Rekomendasi Telekonsultasi</h3>
                    <p class="text-gray-600 mb-6">HealsAI merekomendasikan Anda untuk berkonsultasi dengan dokter profesional. Apakah Anda ingin melakukan telekonsultasi dengan dokter sekarang?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button id="teleconsult-cancel" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                            </svg>
                            Lanjutkan Chat
                        </button>
                        <button id="teleconsult-ok" class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Konsultasi Dokter
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