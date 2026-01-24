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

        .ai-message ul,
        .ai-message ol {
            margin: 1.5em 0 1.8em 0 !important;
            padding-left: 2.5em !important;
            list-style-position: outside !important;
        }

        .ai-message ul li,
        .ai-message ol li {
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

        .ai-message li>ul,
        .ai-message li>ol {
            margin-top: 0.8em !important;
            margin-bottom: 0.8em !important;
        }

        /* Fixing nested lists */
        .ai-message li>ul li,
        .ai-message li>ol li {
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
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
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

        .chat-history-item:hover,
        .chat-history-item.active {
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
            <span
                class="ml-3 px-3 py-1 bg-gradient-to-r from-indigo-100 to-blue-100 text-indigo-800 text-xs rounded-full font-medium">AI
                Powered</span>
        </h1>
        <p class="text-sm text-gray-600 mt-1">Tanyakan informasi kesehatan kepada asisten AI pintar kami untuk mendapatkan
            jawaban cepat dan akurat</p>
    </div>

    <!-- Layout Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Chat (Sebelah Kiri) -->
        <div class="lg:col-span-2">
            <div class="chatbot-container bg-white h-full flex flex-col">
                <div class="chatbot-header text-white">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-4 pulse-dot">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
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
                        <div
                            class="w-8 h-8 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-2 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ai-message-container py-3 px-4 max-w-[85%] shadow-sm">
                            <p class="text-sm text-gray-800">Halo! Saya HealsAI, asisten kesehatan pintar Anda. Apa yang
                                ingin Anda tanyakan tentang kesehatan hari ini?</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t bg-gray-50">
                    <form id="chatForm" class="flex items-center">
                        <input type="text" id="messageInput"
                            class="chat-input flex-grow border border-gray-300 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ketik pertanyaan Anda...">
                        <button type="submit" id="submitBtn"
                            class="send-button ml-2 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-medium py-3 px-6 transition flex items-center">
                            <span id="sendText">Kirim</span>
                            <svg id="sendIcon" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <div id="loadingIcon" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
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
                            <button
                                class="topic-card w-full text-left p-3 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition flex items-center"
                                onclick="addSuggestedMessage('Apa itu diabetes?')">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3 topic-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium">Apa itu diabetes?</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Informasi dasar tentang diabetes</p>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button
                                class="topic-card w-full text-left p-3 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition flex items-center"
                                onclick="addSuggestedMessage('Bagaimana cara menjaga kesehatan jantung?')">
                                <div
                                    class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3 topic-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium">Kesehatan jantung</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Tips menjaga jantung tetap sehat</p>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button
                                class="topic-card w-full text-left p-3 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition flex items-center"
                                onclick="addSuggestedMessage('Berapa banyak air yang harus diminum setiap hari?')">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 topic-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>
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
                        <button id="newChatBtn"
                            class="text-xs bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-full transition-colors flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Baru
                        </button>
                        <button id="clearHistoryBtn"
                            class="text-xs bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-full transition-colors flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
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
            <svg class="h-3.5 w-3.5 text-blue-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p>Chatbot ini hanya memberikan informasi umum dan tidak menggantikan konsultasi dengan dokter.</p>
        </div>
    </div>

    @push('scripts')
        <script>
            /**
             * HealsAI Chatbot Manager
             * Complete Rewrite to strict logic
             */
            document.addEventListener('DOMContentLoaded', function() {
                // Konfigurasi User
                const currentUserId = {{ Auth::id() }};
                const storageKey = `healsai_history_${currentUserId}`;

                /**
                 * CLASS: ChatHistoryManager
                 * Single source of truth untuk manajemen data riwayat (via Server)
                 */
                class ChatHistoryManager {
                    constructor() {
                        this.histories = [];
                    }

                    // Load from SERVER
                    async fetchAll() {
                        try {
                            const response = await fetch("{{ route('pasien.chatbot.history') }}");
                            if (!response.ok) throw new Error('Failed to fetch history');
                            this.histories = await response.json();
                            renderHistoryList();
                        } catch (e) {
                            console.error('HealsAI: Failed to load history.', e);
                        }
                    }

                    // Get messages for a session from SERVER
                    async fetchChat(id) {
                         try {
                            // URL construction: /pasien/chatbot/history/{id}
                            const baseUrl = "{{ route('pasien.chatbot.history') }}";
                            const response = await fetch(`${baseUrl}/${id}`);
                            if (!response.ok) throw new Error('Failed to fetch chat');
                            return await response.json();
                        } catch (e) {
                            console.error('HealsAI: Failed to load chat.', e);
                            return null;
                        }
                    }

                    // Delete from SERVER
                    async delete(id) {
                         try {
                            const baseUrl = "{{ route('pasien.chatbot.history') }}";
                            const response = await fetch(`${baseUrl}/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            if (!response.ok) throw new Error('Failed to delete');
                            
                            // Remove locally
                            this.histories = this.histories.filter(c => c.id !== id);
                            renderHistoryList();
                            return true;
                        } catch (e) {
                            console.error('HealsAI: Failed to delete.', e);
                            alert('Gagal menghapus riwayat.');
                            return false;
                        }
                    }
                    
                    // Helper to get local summary info
                    get(id) {
                        return this.histories.find(c => c.id === id);
                    }

                    getAll() {
                        return this.histories;
                    }
                }

                // Global Managers
                const historyManager = new ChatHistoryManager();
                
                // State Chat Saat Ini
                let activeChatState = {
                    id: null,
                    sessionId: null,
                    conversationId: null,
                    messages: [],
                    title: 'Percakapan Baru'
                };

                // DOM Elements
                const chatContainer = document.getElementById('chatContainer');
                const messageInput = document.getElementById('messageInput');
                const chatForm = document.getElementById('chatForm');
                const submitBtn = document.getElementById('submitBtn');
                const sendIcon = document.getElementById('sendIcon');
                const loadingIcon = document.getElementById('loadingIcon');
                const historyList = document.getElementById('chatHistoryList');
                const newChatBtn = document.getElementById('newChatBtn');
                const clearHistoryBtn = document.getElementById('clearHistoryBtn');

                // --- UTILITY FUNCTIONS ---

                function generateUUID() {
                    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                }

                function escapeHtml(unsafe) {
                    if (typeof unsafe !== 'string') return '';
                    return unsafe
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }

                function formatMessage(message) {
                    message = escapeHtml(message);
                    // Conversion logic sederhana untuk list dan bold
                    message = message.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
                    message = message.replace(/\n\s*-\s+(.*)/g, '<br>• $1');
                    message = message.replace(/\n/g, '<br>');
                    return message;
                }

                function scrollToBottom() {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }

                // --- UI RENDERING ---

                function renderHistoryList() {
                    const chats = historyManager.getAll();
                    historyList.innerHTML = '';

                    if (chats.length === 0) {
                        historyList.innerHTML = `<li class="text-xs text-gray-400 italic text-center py-4">Belum ada riwayat</li>`;
                        return;
                    }

                    chats.forEach(chat => {
                        const isActive = activeChatState.id === chat.id;
                        const dateStr = new Date(chat.timestamp).toLocaleDateString();
                        
                        const li = document.createElement('li');
                        li.className = 'group relative';
                        li.innerHTML = `
                             <button class="chat-history-item w-full text-left p-3 rounded-lg ${isActive ? 'active bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50 border-l-4 border-transparent'} text-sm text-gray-700 transition flex items-center pr-10">
                                <div class="w-8 h-8 rounded-full ${isActive ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500'} flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="truncate font-medium text-gray-800">${escapeHtml(chat.title)}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">${dateStr}</p>
                                </div>
                            </button>
                            <button class="delete-chat-btn absolute right-2 top-1/2 transform -translate-y-1/2 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100 focus:opacity-100" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        `;

                        // Bind Events
                        // Click main button -> Load Chat
                        li.querySelector('.chat-history-item').addEventListener('click', () => loadChat(chat.id));
                        
                        // Click delete -> Delete Chat
                        li.querySelector('.delete-chat-btn').addEventListener('click', (e) => {
                            e.stopPropagation();
                            confirmDelete(chat.id);
                        });

                        historyList.appendChild(li);
                    });
                }

                function renderMessages(messages) {
                    chatContainer.innerHTML = '';
                    messages.forEach(msg => {
                        if (msg.role === 'assistant') appendAIMessage(msg.content);
                        else appendUserMessage(msg.content);
                    });
                    scrollToBottom();
                }

                function appendUserMessage(text) {
                     @php
                        $pasien = Auth::user()->pasien;
                        $fotoUrl = $pasien ? $pasien->foto_url : asset('img/default.jpg');
                    @endphp
                    const userPhotoUrl = "{{ $fotoUrl }}";
                    
                    const html = `
                        <div class="flex mb-5 justify-end chat-message">
                            <div class="user-message py-3 px-4 max-w-[85%]">
                                <p class="text-sm text-white">${escapeHtml(text)}</p>
                            </div>
                           <div class="w-10 h-10 rounded-full bg-cover bg-center ml-3 flex-shrink-0 border-2 border-white shadow-sm" style="background-image: url('${userPhotoUrl}')"></div>
                        </div>
                    `;
                    chatContainer.insertAdjacentHTML('beforeend', html);
                    scrollToBottom();
                }

                function appendAIMessage(text) {
                    const html = `
                        <div class="flex mb-5 chat-message">
                            <div class="w-10 h-10 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ai-message-container py-3 px-4 max-w-[85%]">
                                <div class="text-sm text-gray-800 ai-message">${formatMessage(text)}</div>
                            </div>
                        </div>
                    `;
                    chatContainer.insertAdjacentHTML('beforeend', html);
                    scrollToBottom();
                }

                function setLoading(isLoading) {
                    if (isLoading) {
                        submitBtn.disabled = true;
                        sendIcon.classList.add('hidden');
                        loadingIcon.classList.remove('hidden');
                        
                        // Add typing indicator
                        const typingHtml = `
                        <div id="typingIndicator" class="flex mb-5 chat-message">
                            <div class="w-10 h-10 rounded-full chatbot-avatar flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">AI</div>
                            <div class="ai-message-container py-3 px-4">
                                <div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>
                            </div>
                        </div>`;
                        chatContainer.insertAdjacentHTML('beforeend', typingHtml);
                        scrollToBottom();
                    } else {
                        submitBtn.disabled = false;
                        sendIcon.classList.remove('hidden');
                        loadingIcon.classList.add('hidden');
                        const el = document.getElementById('typingIndicator');
                        if(el) el.remove();
                    }
                }

                // --- ACTIONS ---

                function startNewChat() {
                    const uuid = generateUUID();
                    activeChatState = {
                        id: uuid, 
                        timestamp: Date.now(), 
                        sessionId: generateUUID(), // n8n session
                        conversationId: uuid, // DB session (matches ID)
                        messages: [],
                        title: 'Percakapan Baru'
                    };
                    
                    // Welcome Message
                    const welcome = 'Halo! Saya HealsAI, asisten kesehatan pintar Anda. Apa yang ingin Anda tanyakan?';
                     activeChatState.messages.push({ role: 'assistant', content: welcome });
                    
                    renderMessages(activeChatState.messages);
                    // Tidak simpan ke history sampai user mengetik pesan pertama
                    renderHistoryList(); // Cuma update highlighting
                }

                async function loadChat(id) {
                    setLoading(true); // Opsional: indikator loading seluruh chat
                    try {
                        const chatData = await historyManager.fetchChat(id);
                        if (!chatData) {
                            alert('Gagal memuat percakapan.');
                            startNewChat();
                            return;
                        }

                        // Restore State
                        activeChatState = {
                            id: chatData.id,
                            timestamp: chatData.timestamp,
                            sessionId: chatData.id, // Reuse ID, as we don't store n8n session separately now
                            conversationId: chatData.id,
                            messages: chatData.messages,
                            title: chatData.title
                        };

                        renderMessages(activeChatState.messages);
                        renderHistoryList(); // highlight active
                    } catch (e) {
                         alert('Terjadi kesalahan saat memuat chat.');
                    } finally {
                        setLoading(false);
                    }
                }

                async function confirmDelete(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus percakapan ini secara permanen?')) {
                        const success = await historyManager.delete(id);
                        if (success) {
                            // Jika yang dihapus sedang aktif, reset
                            if (activeChatState.id === id) {
                                startNewChat();
                            }
                        }
                    }
                }

                async function sendMessage(text) {
                    if (!text.trim()) return;
                    messageInput.value = '';

                    // 1. Add User Message to State
                    activeChatState.messages.push({ role: 'user', content: text });
                    appendUserMessage(text);
                    
                    // 2. Set Title if New (Local update, server updates automatically on create)
                    if (activeChatState.messages.filter(m => m.role === 'user').length === 1) {
                         activeChatState.title = text.substring(0, 50) + (text.length > 50 ? '...' : '');
                    }

                    // 3. API Call
                    setLoading(true);
                    
                    try {
                        const response = await fetch('/pasien/chatbot/healsai', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: text,
                                session_id: activeChatState.sessionId,
                                conversation_id: activeChatState.conversationId,
                                is_new_conversation: activeChatState.messages.length <= 2 // heuristic
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success && data.response) {
                            const reply = data.response;
                            activeChatState.messages.push({ role: 'assistant', content: reply });
                            appendAIMessage(reply);
                            
                            // Re-fetch history to get potential title updates or new session creation confirmation
                            historyManager.fetchAll(); 
                        } else {
                            throw new Error(data.message || 'System Error');
                        }

                    } catch (err) {
                        console.error(err);
                        const errText = "Maaf, terjadi kesalahan koneksi.";
                        activeChatState.messages.push({ role: 'assistant', content: errText });
                        appendAIMessage(errText);
                    } finally {
                        setLoading(false);
                    }
                }

                // --- INITIALIZATION ---
                
                chatForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    sendMessage(messageInput.value);
                });

                newChatBtn.addEventListener('click', startNewChat);

                clearHistoryBtn.addEventListener('click', () => {
                     alert('Fitur hapus semua riwayat dinonaktifkan sementara. Silakan hapus satu per satu.');
                });

                // Global exposes for suggested topics
                window.addSuggestedMessage = function(msg) {
                    sendMessage(msg);
                };

                // Boot
                startNewChat();
                historyManager.fetchAll();
            });
        </script>
    @endpush
@endsection