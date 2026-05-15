<style>
    /* Custom Scrollbar biar makin elegan */
    .chat-scroll::-webkit-scrollbar { width: 5px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 20px; }
    .dark .chat-scroll::-webkit-scrollbar-thumb { background: #334155; }

    /* Radius bubble khusus biar beda antara admin & pasien */
    .chat-bubble-admin { border-radius: 20px 20px 4px 20px; }
    .chat-bubble-user { border-radius: 20px 20px 20px 4px; }

    /* Animasi masuk */
    .anim-fade-up { animation: fadeUp 0.4s ease-out both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div x-data="adminChatApp()" x-init="init()" class="flex h-[calc(100vh-160px)] bg-white/40 dark:bg-gray-900/40 backdrop-blur-xl border border-gray-200 dark:border-gray-800 rounded-[2.5rem] overflow-hidden shadow-2xl anim-fade-up">

    {{-- KIRI: Sidebar Pasien --}}
    <div class="w-80 border-r border-gray-100 dark:border-gray-800 flex flex-col bg-white/50 dark:bg-gray-900/50">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                Pesan Masuk
                <span class="text-[10px] bg-teal-500 text-white px-2 py-0.5 rounded-full" x-text="userList.length"></span>
            </h2>

            <div class="mt-4 relative">
                <input type="text" placeholder="Cari pasien..." class="w-full pl-9 pr-4 py-2.5 bg-white/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl text-xs font-semibold focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all dark:text-white">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto chat-scroll p-3 space-y-2">
            <template x-for="user in userList" :key="user.id">
                <button @click="pilihPasien(user)"
                        :class="activeUser?.id === user.id ? 'bg-white dark:bg-gray-800 shadow-lg ring-1 ring-teal-500/20' : 'hover:bg-white/40 dark:hover:bg-gray-800/40'"
                        class="w-full text-left p-4 rounded-[1.5rem] transition-all flex items-center gap-3 group">

                    <div class="relative">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-400 to-cyan-500 text-white flex items-center justify-center font-black text-lg shadow-md group-hover:rotate-3 transition-transform">
                            <span x-text="user.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-white dark:border-gray-900 rounded-full"></div>
                    </div>

                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-0.5">
                            <h4 class="text-sm font-black text-gray-900 dark:text-white truncate" x-text="user.name"></h4>
                            <span class="text-[9px] font-bold text-gray-400 uppercase" x-text="user.time"></span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate font-medium" x-text="user.last_message || 'Mulai percakapan...'"></p>
                    </div>
                </button>
            </template>

            <template x-if="userList.length === 0 && !isLoadingList">
                <div class="text-center py-12">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Belum ada pesan</p>
                </div>
            </template>
        </div>
    </div>

    {{-- KANAN: Ruang Chat --}}
    <div class="flex-1 flex flex-col bg-gray-50/50 dark:bg-gray-950/20 relative">

        {{-- Empty State --}}
        <template x-if="!activeUser">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-10">
                <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Konsultasi Medis</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-xs mx-auto">Silakan pilih salah satu pasien di daftar sebelah kiri untuk membalas pesan.</p>
            </div>
        </template>

        {{-- Active Chat Area --}}
        <template x-if="activeUser">
            <div class="flex flex-col h-full">
                {{-- Header --}}
                <div class="p-6 px-10 flex items-center justify-between bg-white/60 dark:bg-gray-900/60 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-teal-500 to-cyan-400 text-white flex items-center justify-center font-bold shadow-lg">
                            <span x-text="activeUser.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div>
                            <h3 class="font-black text-lg text-gray-900 dark:text-white leading-none" x-text="activeUser.name"></h3>
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Pasien Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Messages --}}
                <div id="admin-chat-area" class="flex-grow p-10 overflow-y-auto chat-scroll space-y-8 bg-transparent">
                    <template x-for="msg in chatMessages" :key="msg.id">
                        <div :class="msg.sender === 'admin' ? 'flex flex-col items-end' : 'flex flex-col items-start'">

                            {{-- Bubble --}}
                            <div :class="msg.sender === 'admin'
                                 ? 'bg-teal-500 text-white shadow-xl shadow-teal-500/20 chat-bubble-admin'
                                 : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 shadow-sm border border-gray-100 dark:border-gray-700 chat-bubble-user'"
                                 class="px-6 py-4 max-w-[70%] text-[15px] leading-relaxed font-medium">

                                {{-- Rekap Tag for Bot --}}
                                <template x-if="msg.sender === 'bot'">
                                    <div class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-100 dark:border-gray-700 opacity-80">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Rekap Sistem</span>
                                    </div>
                                </template>

                                <span class="whitespace-pre-wrap" x-text="msg.text"></span>
                            </div>

                            {{-- Meta --}}
                            <div class="flex items-center gap-2 mt-2 px-2">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter" x-text="msg.time"></span>
                                <template x-if="msg.sender === 'admin'">
                                    <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Input --}}
                <div class="p-8 px-10 bg-white/60 dark:bg-gray-900/60 border-t border-gray-100 dark:border-gray-800">
                    <form @submit.prevent="kirimBalasan" class="relative">
                        <input type="text" x-model="replyMessage"
                               placeholder="Ketik instruksi atau saran medis..."
                               class="w-full pl-7 pr-32 py-5 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-[1.5rem] focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all text-[15px] font-semibold dark:text-white"
                               :disabled="isSending" autocomplete="off">

                        <button type="submit"
                                :disabled="replyMessage.trim() === '' || isSending"
                                class="absolute right-3 top-3 bottom-3 px-8 bg-teal-500 text-white rounded-2xl font-black text-xs uppercase tracking-[0.15em] hover:bg-teal-600 transition-all disabled:opacity-50 shadow-lg shadow-teal-500/30">
                            Kirim
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function adminChatApp() {
    return {
        userList: [],
        activeUser: null,
        chatMessages: [],
        replyMessage: '',
        isSending: false,
        isLoadingList: true,
        pollingInterval: null,

        init() {
            // 1. Tarik daftar pasien yang pernah chat
            this.tarikUserList();

            // 2. Polling interval buat real-time chat (Update tiap 5 detik)
            this.pollingInterval = setInterval(() => {
                // Kita cek menu aktif lewat Alpine global atau variable local
                // Jika abang pake variable 'activeMenu', pastiin pengecekannya bener
                if (this.activeMenu === 'konsultasi' || document.body.contains(document.getElementById('admin-chat-area'))) {
                    this.tarikUserList(false);
                    if (this.activeUser) {
                        this.tarikPesanPasien(this.activeUser.id, false);
                    }
                }
            }, 5000);
        },

        async tarikUserList(showLoading = true) {
            if (showLoading) this.isLoadingList = true;
            try {
                const res = await fetch('/admin/chat/users');
                if (!res.ok) throw new Error("Gagal fetch user list");
                const data = await res.json();
                this.userList = data.users;
            } catch (e) {
                console.error("Error tarikUserList:", e);
            }
            if (showLoading) this.isLoadingList = false;
        },

        pilihPasien(user) {
            // Pas klik nama pasien, simpan ke activeUser
            this.activeUser = user;
            this.chatMessages = [];
            // Langsung tarik chat si pasien tersebut (user.id di sini berisi username)
            this.tarikPesanPasien(user.id);
        },

        async tarikPesanPasien(userId, scroll = true) {
            if (!userId) return;
            try {
                // userId di sini adalah string username (hasil map di controller)
                const res = await fetch(`/admin/chat/${userId}`);
                if (!res.ok) throw new Error("Gagal fetch pesan pasien");
                const data = await res.json();

                if (data.messages && data.messages.length !== this.chatMessages.length) {
                    this.chatMessages = data.messages;
                    if (scroll) this.scrollToBottom();
                }
            } catch (e) {
                console.error("Error tarikPesanPasien:", e);
            }
        },

        async kirimBalasan() {
            if (this.replyMessage.trim() === '' || !this.activeUser) return;

            const text = this.replyMessage;
            const targetUsername = this.activeUser.id; // Ini isinya username pasien
            this.replyMessage = '';
            this.isSending = true;

            try {
                const response = await fetch('/admin/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        user_id: targetUsername, // Controller abang nerima ini sebagai 'username'
                        message: text
                    })
                });

                if (!response.ok) throw new Error("Server gagal menyimpan pesan");

                // Langsung tarik data terbaru biar sinkron
                await this.tarikPesanPasien(targetUsername);
                this.tarikUserList(false);
            } catch (e) {
                console.error("Gagal kirim balasan:", e);
                alert("Pesan gagal terkirim ke database!");
            }

            this.isSending = false;
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const area = document.getElementById('admin-chat-area');
                if (area) {
                    area.scrollTo({ top: area.scrollHeight, behavior: 'smooth' });
                }
            });
        }
    }
}
</script>
