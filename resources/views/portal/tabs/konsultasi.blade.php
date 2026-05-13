<div x-data="chatbotApp()" class="bg-white border border-gray-100 rounded-3xl shadow-sm max-w-4xl mx-auto flex flex-col h-[600px] overflow-hidden dark:bg-[#1E293B] dark:border-gray-800">

    {{-- Chat Header --}}
    <div class="p-5 border-b border-gray-100 flex items-center gap-4 bg-teal-50/50 dark:bg-teal-900/10 dark:border-gray-800">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md transition-colors"
             :class="step >= 2 ? 'bg-emerald-500' : ''"
             :style="step < 2 ? 'background: var(--gradient-main);' : ''">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 dark:text-white" x-text="step >= 2 ? 'Dokter Spesialis' : 'Asisten Virtual DelimaCare'"></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="step >= 2 ? 'Menunggu balasan dokter...' : 'Tanyakan tentang kesehatan ibu dan anak'"></p>
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
                <div :class="msg.sender === 'bot' || msg.sender === 'admin' ? 'flex flex-col items-start' : 'flex flex-col items-end'">

                    {{-- Avatar --}}
                    <template x-if="msg.sender === 'bot' || msg.sender === 'admin'">
                        <div class="flex items-center gap-2 mb-1.5 pl-1">
                            <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center dark:bg-teal-900/50 dark:text-teal-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider dark:text-gray-400" x-text="msg.sender === 'bot' ? 'Asisten Delima' : 'Dokter'"></span>
                        </div>
                    </template>

                    {{-- Chat Bubble (Diperlebar max-w nya biar formnya bisa melar) --}}
                    <div :class="msg.sender === 'bot' || msg.sender === 'admin' ? 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm shadow-sm dark:bg-[#1E293B] dark:border-gray-800 dark:text-gray-300' : 'text-white rounded-2xl rounded-tr-sm shadow-md'"
                         :style="msg.sender === 'user' ? 'background: var(--gradient-main);' : ''"
                         class="px-5 py-3.5 max-w-[90%] sm:max-w-[85%] w-fit text-[15px] leading-relaxed">

                        {{-- Isi Teks --}}
                        <span x-text="msg.text" class="whitespace-pre-wrap"></span>

                        {{-- Form Keluhan Muncul di Chat (Ukuran diperlebar) --}}
                        <template x-if="msg.type === 'form' && step === 1">
                            <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-5 w-[280px] sm:w-[450px] dark:bg-gray-800 dark:border-gray-700">
                                <p class="text-[12px] font-bold text-teal-600 dark:text-teal-400 mb-4 border-b border-gray-200 pb-2 dark:border-gray-700">Formulir Keluhan Medis</p>
                                <div class="space-y-4">
                                    {{-- Field Durasi --}}
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">Sejak kapan dirasakan?</label>
                                        <input type="text" x-model="formKeluhan.durasi" placeholder="Misal: Sejak 3 hari yang lalu" class="w-full text-sm p-3 bg-white border border-gray-200 rounded-lg outline-none focus:border-teal-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                                    </div>

                                    {{-- Field Reaksi Obat --}}
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">Obat yang diminum & reaksi?</label>
                                        <input type="text" x-model="formKeluhan.reaksi_obat" placeholder="Misal: Paracetamol, tapi tetap pusing" class="w-full text-sm p-3 bg-white border border-gray-200 rounded-lg outline-none focus:border-teal-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {{-- Field Alergi --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">Riwayat alergi (jika ada)</label>
                                            <input type="text" x-model="formKeluhan.alergi" placeholder="Misal: Amoxicillin" class="w-full text-sm p-3 bg-white border border-gray-200 rounded-lg outline-none focus:border-teal-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                                        </div>

                                        {{-- Field Gejala Lain --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">Gejala penyerta lainnya</label>
                                            <input type="text" x-model="formKeluhan.gejala_lain" placeholder="Misal: Mual, demam" class="w-full text-sm p-3 bg-white border border-gray-200 rounded-lg outline-none focus:border-teal-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                                        </div>
                                    </div>

                                    {{-- Field Riwayat Penyakit --}}
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">Riwayat penyakit terdahulu</label>
                                        <input type="text" x-model="formKeluhan.riwayat_penyakit" placeholder="Misal: Asma, Maag" class="w-full text-sm p-3 bg-white border border-gray-200 rounded-lg outline-none focus:border-teal-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <button @click="submitFormKeluhan()" :disabled="!formKeluhan.durasi || !formKeluhan.reaksi_obat" class="w-full py-3 mt-3 bg-teal-500 text-white text-sm font-bold rounded-xl hover:bg-teal-600 transition-all disabled:opacity-50">
                                        Kirim Detail Keluhan
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Timestamp --}}
                    <span class="text-[11px] text-gray-400 mt-1.5 px-1 font-medium" x-text="msg.time"></span>
                </div>
            </template>

            {{-- Typing Indicator (Sesuai kode awal) --}}
            <div x-show="isTyping" class="flex flex-col items-start" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center gap-2 mb-1.5 pl-1">
                    <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center dark:bg-teal-900/50 dark:text-teal-400">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider dark:text-gray-400">Mengetik...</span>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-5 py-4 shadow-sm flex items-center gap-1.5 dark:bg-[#1E293B] dark:border-gray-800 w-fit">
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
            <input type="text" x-model="newMessage" :disabled="step === 1"
                   :placeholder="step === 1 ? 'Silakan isi form di atas terlebih dahulu...' : 'Ketik pertanyaan Anda... (Enter untuk kirim)'"
                   class="w-full pl-6 pr-14 py-3.5 bg-gray-50/50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 focus:bg-white transition-all text-sm dark:bg-[#0F172A] dark:border-gray-700 dark:text-white disabled:opacity-60">
            <button type="submit" :disabled="newMessage.trim() === '' || step === 1"
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

<script src="{{ asset('js/konsultasi.js') }}"></script>
