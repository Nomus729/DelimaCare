<div class="bg-white border border-gray-100 rounded-3xl shadow-sm max-w-4xl mx-auto flex flex-col h-[600px] overflow-hidden dark:bg-[#1E293B] dark:border-gray-800">

    {{-- Chat Header --}}
    <div class="p-5 border-b border-gray-100 flex items-center gap-4 bg-teal-50/50 dark:bg-teal-900/10 dark:border-gray-800">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md" style="background: var(--gradient-main);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 dark:text-white">Asisten Virtual DelimaCare</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tanyakan tentang kesehatan ibu dan anak</p>
        </div>
    </div>

    {{-- Chat Area --}}
    <div id="chat-container" class="flex-grow p-6 overflow-y-auto chat-scroll bg-[#FAFFFE] dark:bg-[#0B1120]">

        {{-- Tip Box --}}
        <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 text-sm text-teal-800 mb-8 flex items-start gap-3 dark:bg-teal-900/30 dark:border-teal-800 dark:text-teal-400">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <span class="font-bold block mb-1">Tips Konsultasi:</span>
                Tanyakan tentang kehamilan, jadwal KB, persiapan persalinan, atau metode kontrasepsi untuk mendapatkan informasi instan.
            </div>
        </div>

        {{-- Messages --}}
        <div class="space-y-6">
            <template x-for="msg in chatMessages" :key="msg.id">
                <div :class="msg.sender === 'bot' ? 'flex flex-col items-start' : 'flex flex-col items-end'">
                    <template x-if="msg.sender === 'bot'">
                        <div class="flex items-center gap-2 mb-1.5 pl-1">
                            <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center dark:bg-teal-900/50 dark:text-teal-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider dark:text-gray-400">Asisten Delima</span>
                        </div>
                    </template>

                    <div :class="msg.sender === 'bot' ? 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm shadow-sm dark:bg-[#1E293B] dark:border-gray-800 dark:text-gray-300' : 'text-white rounded-2xl rounded-tr-sm shadow-md'"
                         :style="msg.sender === 'user' ? 'background: var(--gradient-main);' : ''"
                         class="px-5 py-3.5 max-w-[85%] sm:max-w-[75%] text-[15px] leading-relaxed">
                        <span x-text="msg.text"></span>
                    </div>

                    <span class="text-[11px] text-gray-400 mt-1.5 px-1 font-medium" x-text="msg.time"></span>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex flex-col items-start" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center gap-2 mb-1.5 pl-1">
                    <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center dark:bg-teal-900/50 dark:text-teal-400">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider dark:text-gray-400">Mengetik...</span>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-5 py-4 shadow-sm flex items-center gap-1.5 dark:bg-[#1E293B] dark:border-gray-800">
                    <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                    <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chat Input --}}
    <div class="p-4 bg-white border-t border-gray-100 dark:bg-[#1E293B] dark:border-gray-800">
        <form @submit.prevent="sendMessage" class="relative flex items-center">
            <input type="text" x-model="newMessage" placeholder="Ketik pertanyaan Anda... (Enter untuk kirim)"
                   class="w-full pl-6 pr-14 py-3.5 bg-gray-50/50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 focus:bg-white transition-all text-sm dark:bg-[#0F172A] dark:border-gray-700 dark:text-white dark:focus:bg-[#0F172A]">
            <button type="submit" :disabled="newMessage.trim() === ''"
                    class="absolute right-2.5 p-2 rounded-full text-white transition-all duration-200 disabled:opacity-50 disabled:grayscale"
                    style="background: var(--gradient-main);">
                <svg class="w-4 h-4 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </form>
        <p class="text-center text-xs text-gray-400 mt-4">
            Untuk konsultasi medis yang lebih spesifik, silakan <a href="#" @click.prevent="switchTab('reservasi')" class="text-teal-600 hover:text-teal-700 hover:underline font-semibold dark:text-teal-400 dark:hover:text-teal-300">buat reservasi</a>.
        </p>
    </div>
</div>
