<div x-data="chatbotApp()" class="bg-white border border-gray-100 rounded-2xl shadow-sm w-full flex flex-col h-[calc(100vh-160px)] overflow-hidden dark:bg-[#1E293B] dark:border-gray-800">

    {{-- Chat Header --}}
    <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white dark:bg-[#1E293B] dark:border-gray-800 flex-shrink-0 shadow-sm z-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md transition-all duration-300"
                 :class="step >= 2 ? 'bg-emerald-500' : 'bg-teal-500'"
                 style="background: var(--gradient-main);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <div>
                <h3 class="font-black text-gray-900 dark:text-white text-base" x-text="step >= 2 ? 'Dokter Spesialis DelimaCare' : 'Asisten Medis DelimaCare'"></h3>
                <div class="mt-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-extrabold text-emerald-500 uppercase tracking-wider" x-text="step >= 2 ? 'Terhubung dengan Dokter' : 'Sistem Bantuan Aktif'"></span>
                </div>
            </div>
        </div>
        
        <!-- Info Badge -->
        <span class="hidden sm:inline-block text-xs bg-teal-500/10 text-teal-600 dark:text-teal-400 font-extrabold px-3 py-1.5 rounded-full">
            Konsultasi Online Gratis
        </span>
    </div>

    {{-- Chat Area --}}
    <div id="chat-container" class="flex-grow p-6 md:p-8 overflow-y-auto chat-scroll bg-gray-50/30 dark:bg-[#0B1120]">

        {{-- Tip Box --}}
        <div class="bg-teal-50 border border-teal-100/50 rounded-xl p-5 text-sm text-teal-800 mb-8 flex items-start gap-3.5 dark:bg-teal-900/20 dark:border-teal-800 dark:text-teal-400 max-w-3xl mx-auto shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <span class="font-extrabold block mb-1">Tips Konsultasi:</span>
                Tanyakan seputar keluhan kehamilan, kesehatan ibu anak, kontrasepsi, atau gejala fisik untuk memperoleh arahan medis langsung.
            </div>
        </div>

        {{-- Messages container --}}
        <div class="space-y-6 max-w-3xl mx-auto">
            <template x-for="msg in chatMessages" :key="msg.id">
                <div :class="msg.sender === 'bot' || msg.sender === 'admin' ? 'flex flex-col items-start' : 'flex flex-col items-end'">

                    {{-- Sender Tag --}}
                    <template x-if="msg.sender === 'bot' || msg.sender === 'admin'">
                        <div class="flex items-center gap-1.5 mb-1.5 pl-1.5">
                            <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center dark:bg-teal-900/50 dark:text-teal-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider dark:text-gray-400" x-text="msg.sender === 'bot' ? 'Asisten Medis' : 'Dokter Medis'"></span>
                        </div>
                    </template>

                    {{-- Chat Bubble --}}
                    <div :class="msg.sender === 'bot' || msg.sender === 'admin'
                         ? 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm shadow-sm dark:bg-[#1E293B] dark:border-gray-800 dark:text-gray-300'
                         : 'bg-teal-600 text-white rounded-2xl rounded-tr-sm shadow-md'"
                         class="px-5 py-3.5 max-w-[90%] sm:max-w-[85%] w-fit text-[15px] leading-relaxed font-semibold">

                        {{-- Message content --}}
                        <span x-text="msg.text" class="whitespace-pre-wrap"></span>

                        {{-- Form Keluhan --}}
                        <template x-if="msg.type === 'form' && step === 1">
                            <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-5 w-full sm:w-[480px] dark:bg-gray-900 dark:border-gray-700">
                                <p class="text-[12px] font-black text-teal-600 dark:text-teal-400 mb-4 border-b border-gray-200 pb-2 dark:border-gray-700 uppercase tracking-wider">Formulir Gejala & Keluhan</p>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[11px] font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1.5">Sejak kapan dirasakan?</label>
                                        <input type="text" x-model="formKeluhan.durasi" placeholder="Contoh: Sejak 2 hari yang lalu" class="w-full text-xs p-3 bg-white border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1.5">Obat yang diminum & reaksi?</label>
                                        <input type="text" x-model="formKeluhan.reaksi_obat" placeholder="Contoh: Paracetamol, mual mereda" class="w-full text-xs p-3 bg-white border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1.5">Riwayat alergi obat</label>
                                            <input type="text" x-model="formKeluhan.alergi" placeholder="Contoh: Penisilin / tidak ada" class="w-full text-xs p-3 bg-white border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1.5">Gejala penyerta lainnya</label>
                                            <input type="text" x-model="formKeluhan.gejala_lain" placeholder="Contoh: Mual, demam tinggi" class="w-full text-xs p-3 bg-white border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1.5">Riwayat penyakit terdahulu</label>
                                        <input type="text" x-model="formKeluhan.riwayat_penyakit" placeholder="Contoh: Asma, Hipertensi" class="w-full text-xs p-3 bg-white border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <button @click="submitFormKeluhan()" :disabled="!formKeluhan.durasi || !formKeluhan.reaksi_obat" class="w-full py-3 mt-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all disabled:opacity-50 shadow-md">
                                        Kirim Rincian Keluhan ke Dokter
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Meta Timestamp --}}
                    <span class="text-[10px] text-gray-400 mt-1.5 px-1 font-semibold" x-text="msg.time"></span>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex flex-col items-start" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center gap-1.5 mb-1.5 pl-1.5">
                    <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center dark:bg-teal-900/50 dark:text-teal-400">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider dark:text-gray-400">Dokter Menulis...</span>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-5 py-3.5 shadow-sm flex items-center gap-1.5 dark:bg-[#1E293B] dark:border-gray-800 w-fit">
                    <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                    <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chat Input Bar --}}
    <div class="p-6 bg-white border-t border-gray-100 dark:bg-[#1E293B] dark:border-gray-800 flex-shrink-0">
        <div class="max-w-3xl mx-auto">
            <form @submit.prevent="sendMessage" class="relative flex items-center">
                <input type="text" x-model="newMessage" :disabled="step === 1"
                       :placeholder="step === 1 ? 'Silakan lengkapi formulir medis di atas dahulu...' : 'Tulis keluhan atau pertanyaan Anda disini... (Enter untuk kirim)'"
                       class="w-full pl-6 pr-16 py-4 bg-gray-50 dark:bg-gray-800 border-0 focus:ring-2 focus:ring-teal-500 rounded-xl outline-none text-sm font-semibold dark:text-white disabled:opacity-60">
                <button type="submit" :disabled="newMessage.trim() === '' || step === 1"
                        class="absolute right-2.5 p-2 rounded-lg text-white transition-all duration-200 disabled:opacity-50 disabled:grayscale bg-teal-500 hover:bg-teal-600">
                    <svg class="w-4 h-4 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
            <p class="text-center text-xs text-gray-400 mt-4 font-semibold">
                Konsultasi ini bersifat skrining awal. Untuk tindakan lanjutan medis, silakan <a href="#" @click.prevent="switchTab('reservasi')" class="text-teal-600 hover:text-teal-700 hover:underline font-extrabold dark:text-teal-400 dark:hover:text-teal-300">Buat Reservasi Dokter</a>.
            </p>
        </div>
    </div>
</div>

<script src="{{ asset('js/konsultasi.js') }}"></script>
