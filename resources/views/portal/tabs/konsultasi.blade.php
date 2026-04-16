<div class="bg-white border border-gray-200 rounded-2xl shadow-sm max-w-4xl mx-auto flex flex-col h-[600px] overflow-hidden">

    <div class="p-5 border-b border-gray-200 flex items-center gap-4 bg-gray-50/50">
        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-md">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900">Asisten Virtual DelimaCare</h3>
            <p class="text-sm text-gray-500">Tanyakan tentang kesehatan ibu dan anak</p>
        </div>
    </div>

    <div id="chat-container" class="flex-grow p-6 overflow-y-auto chat-scroll bg-white">

        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-800 mb-8 flex items-start gap-2">
            <span class="font-bold">Tips:</span>
            Tanyakan tentang kehamilan, KB, pemeriksaan, atau metode kontrasepsi untuk mendapatkan informasi yang Anda butuhkan.
        </div>

        <div class="space-y-6">
            <template x-for="msg in chatMessages" :key="msg.id">

                <div :class="msg.sender === 'bot' ? 'flex flex-col items-start' : 'flex flex-col items-end'">

                    <template x-if="msg.sender === 'bot'">
                        <div class="flex items-center gap-2 mb-1 pl-1">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-xs font-semibold text-gray-600">Asisten DelimaCare</span>
                        </div>
                    </template>

                    <div :class="msg.sender === 'bot' ? 'bg-gray-100 text-gray-800 rounded-2xl rounded-tl-sm' : 'bg-blue-600 text-white rounded-2xl rounded-tr-sm'"
                         class="px-5 py-3 max-w-[85%] sm:max-w-[75%] shadow-sm text-[15px] leading-relaxed">
                        <span x-text="msg.text"></span>
                    </div>

                    <span class="text-[11px] text-gray-400 mt-1 px-1" x-text="msg.time"></span>
                </div>

            </template>

            <div x-show="isTyping" class="flex flex-col items-start" x-transition>
                <div class="flex items-center gap-2 mb-1 pl-1">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-xs font-semibold text-gray-600">Asisten DelimaCare sedang mengetik...</span>
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm flex items-center gap-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white border-t border-gray-200">
        <form @submit.prevent="sendMessage" class="relative flex items-center">
            <input type="text" x-model="newMessage" placeholder="Ketik pertanyaan Anda... (Enter untuk kirim)"
                   class="w-full pl-5 pr-14 py-3.5 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
            <button type="submit" :disabled="newMessage.trim() === ''"
                    class="absolute right-2 p-2 bg-gray-500 text-white rounded-full hover:bg-blue-600 disabled:opacity-50 disabled:hover:bg-gray-500 transition-colors">
                <svg class="w-4 h-4 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
        <p class="text-center text-xs text-gray-500 mt-3">
            Untuk konsultasi medis lebih detail, silakan <a href="#" @click.prevent="switchTab('reservasi')" class="text-blue-600 hover:underline">buat reservasi</a> dengan dokter kami.
        </p>
    </div>
</div>
