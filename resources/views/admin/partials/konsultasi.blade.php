<style>
    /* Custom Scrollbar */
    .chat-scroll::-webkit-scrollbar { width: 5px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 20px; }
    .dark .chat-scroll::-webkit-scrollbar-thumb { background: #334155; }

    /* Custom Chat Bubbles */
    .chat-bubble-admin { border-radius: 18px 18px 4px 18px; }
    .chat-bubble-user { border-radius: 18px 18px 18px 4px; }
</style>

<div x-data="adminChatApp()" x-init="init()" class="flex h-[calc(100vh-72px)] w-full bg-white dark:bg-[#0E1A2E] overflow-hidden">

    {{-- SIDEBAR: Daftar Pasien --}}
    <div class="w-80 md:w-96 flex-shrink-0 border-r border-gray-100 dark:border-gray-800 flex flex-col bg-gray-50/50 dark:bg-[#0F1B30]">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    Konsultasi Aktif
                </h2>
                <span class="text-xs bg-teal-500/10 text-teal-600 dark:text-teal-400 font-extrabold px-2.5 py-1 rounded-full" x-text="userList.length + ' Pasien'"></span>
            </div>

            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Cari percakapan..." class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Chat List -->
        <div class="flex-grow overflow-y-auto chat-scroll p-3 space-y-1.5">
            <template x-for="user in filteredUserList" :key="user.id">
                <button @click="pilihPasien(user)"
                        :class="activeUser?.id === user.id ? 'bg-white dark:bg-gray-800 shadow-md ring-1 ring-teal-500/10' : 'hover:bg-white/50 dark:hover:bg-gray-800/40'"
                        class="w-full text-left p-4 rounded-xl transition-all flex items-center gap-3 group relative">

                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-400 to-cyan-500 text-white flex items-center justify-center font-black text-lg shadow-md group-hover:rotate-3 transition-transform">
                            <span x-text="user.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full"></div>
                    </div>

                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="user.name"></h4>
                            <span class="text-[10px] font-semibold text-gray-400" x-text="user.time"></span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="user.last_message || 'Mulai percakapan...'"></p>
                            
                            <!-- Badge Unread Count -->
                            <template x-if="user.unread_count > 0">
                                <span class="bg-teal-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse" x-text="user.unread_count"></span>
                            </template>
                        </div>
                    </div>
                </button>
            </template>

            <template x-if="filteredUserList.length === 0 && !isLoadingList">
                <div class="text-center py-12">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Tidak ada percakapan</p>
                </div>
            </template>
        </div>
    </div>

    {{-- MAIN CHAT PANEL --}}
    <div class="flex-1 flex flex-col bg-gray-50/30 dark:bg-[#0A1224] relative">

        {{-- Empty State --}}
        <template x-if="!activeUser">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-10">
                <div class="w-20 h-20 bg-white dark:bg-gray-800 rounded-2xl shadow-md flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-700">
                    <svg class="w-10 h-10 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">Ruang Konsultasi</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">Pilih salah satu pasien di daftar sebelah kiri untuk memulai balasan medis.</p>
            </div>
        </template>

        {{-- Active Chat Area --}}
        <template x-if="activeUser">
            <div class="flex flex-col h-full overflow-hidden">
                {{-- Chat Header --}}
                <div class="p-4 px-8 flex items-center justify-between bg-white dark:bg-[#0E1A2E] border-b border-gray-100 dark:border-gray-800 flex-shrink-0 shadow-sm z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-cyan-400 text-white flex items-center justify-center font-bold shadow-md">
                            <span x-text="activeUser.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-gray-900 dark:text-white leading-none" x-text="activeUser.name"></h3>
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[9px] font-extrabold text-emerald-500 uppercase tracking-wider">Pasien Online</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Messages List --}}
                <div id="admin-chat-area" class="flex-grow p-8 overflow-y-auto chat-scroll space-y-6 bg-transparent">
                    <template x-for="msg in chatMessages" :key="msg.id">
                        <div :class="msg.sender === 'admin' ? 'flex flex-col items-end' : 'flex flex-col items-start'">
                            
                            {{-- Bubble --}}
                            <div :class="msg.sender === 'admin'
                                 ? 'bg-teal-600 text-white shadow-md shadow-teal-500/10 chat-bubble-admin'
                                 : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 shadow-sm border border-gray-100 dark:border-gray-700 chat-bubble-user'"
                                 class="px-5 py-3.5 max-w-[70%] text-sm leading-relaxed font-semibold">

                                {{-- Rekap Tag for Bot --}}
                                <template x-if="msg.sender === 'bot'">
                                    <div class="flex items-center gap-1.5 mb-2 pb-1.5 border-b border-gray-100 dark:border-gray-700 opacity-80">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Detail Keluhan Medis</span>
                                    </div>
                                </template>

                                <span class="whitespace-pre-wrap" x-text="msg.text"></span>
                            </div>

                            {{-- Time Meta --}}
                            <div class="flex items-center gap-1.5 mt-1.5 px-1.5 text-gray-400">
                                <span class="text-[10px] font-semibold" x-text="msg.time"></span>
                                <template x-if="msg.sender === 'admin'">
                                    <svg class="w-3 h-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Chat Input Bar --}}
                <div class="p-6 bg-white dark:bg-[#0E1A2E] border-t border-gray-100 dark:border-gray-800 flex-shrink-0">
                    <form method="post" @submit.prevent="kirimBalasan" class="relative">
                        <input type="text" x-model="replyMessage"
                               placeholder="Tulis balasan atau saran medis di sini..."
                               class="w-full pl-6 pr-28 py-4 bg-gray-50 dark:bg-gray-800 border-0 focus:ring-2 focus:ring-teal-500 rounded-xl outline-none text-sm font-semibold dark:text-white"
                               :disabled="isSending" autocomplete="off">

                        <button type="submit"
                                :disabled="replyMessage.trim() === '' || isSending"
                                class="absolute right-2.5 top-2.5 bottom-2.5 px-6 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider transition-all disabled:opacity-50 flex items-center justify-center">
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
        searchQuery: '',

        init() {
            // 1. Tarik daftar pasien pertama kali
            this.tarikUserList();

            // 2. Polling ringan khusus buat list pasien (setiap 15 detik)
            this.pollingInterval = setInterval(() => {
                if (document.body.contains(document.getElementById('admin-chat-area'))) {
                    this.tarikUserList(false);
                }
            }, 15000);

            // 3. Listen WebSocket secara global pada channel admin.chat untuk semua pesan masuk
            if (typeof window.Echo !== 'undefined') {
                // Bersihkan channel/listener sebelumnya agar tidak terjadi duplikasi saat pindah tab
                window.Echo.leave('admin.chat');

                window.Echo.channel('admin.chat')
                    .listen('MessageSent', (e) => {
                        // Hanya proses pesan yang datang dari pasien (sender !== 'admin')
                        if (e.message.sender !== 'admin') {
                            const isCurrentlyActive = this.activeUser && this.activeUser.id === e.message.username;
                            
                            if (isCurrentlyActive) {
                                // Masukkan langsung ke chat window jika sedang aktif
                                const exists = this.chatMessages.find(m => m.id === e.message.id);
                                if (!exists) {
                                    this.chatMessages.push({
                                        id: e.message.id,
                                        sender: e.message.sender,
                                        type: e.message.type,
                                        text: e.message.message,
                                        time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
                                    });
                                    this.scrollToBottom();
                                }
                            }
                            
                            // Auto-Bump Pasien ke urutan paling atas di Sidebar & tingkatkan unread count jika tidak aktif
                            this.bumpUserToTop(e.message.username, e.message.message, !isCurrentlyActive);
                        }
                    });
            }
        },

        get filteredUserList() {
            if (!this.searchQuery.trim()) return this.userList;
            return this.userList.filter(u => u.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },

        async tarikUserList(showLoading = true) {
            if (showLoading) this.isLoadingList = true;
            try {
                const res = await fetch('/admin/chat/users');
                if (!res.ok) throw new Error("Gagal fetch user list");
                const data = await res.json();
                
                // Urutkan ulang secara lokal jika sedang aktif chatting
                this.userList = data.users;
            } catch (e) {
                console.error("Error tarikUserList:", e);
            }
            if (showLoading) this.isLoadingList = false;
        },

        async pilihPasien(user) {
            this.activeUser = user;
            this.chatMessages = [];
            
            // Set unread_count secara lokal langsung ke 0 agar responsif di UI
            user.unread_count = 0;

            await this.tarikPesanPasien(user.id);
        },

        // Helper untuk Auto-Bump list pasien ke paling atas ketika ada pesan baru
        bumpUserToTop(username, lastMessage, incrementUnread = false) {
            const index = this.userList.findIndex(u => u.id === username);
            const currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            if (index !== -1) {
                // Ambil data user yang ada
                const user = this.userList[index];
                user.last_message = lastMessage.length > 30 ? lastMessage.substring(0, 30) + '...' : lastMessage;
                user.time = currentTime;
                if (incrementUnread) {
                    user.unread_count = (user.unread_count || 0) + 1;
                }

                // Keluarkan dari posisi lama, lalu masukkan ke posisi paling atas (index 0)
                this.userList.splice(index, 1);
                this.userList.unshift(user);
            } else {
                // Jika user belum ada di list (kasus baru pertama kali chat)
                this.userList.unshift({
                    id: username,
                    name: username,
                    last_message: lastMessage.length > 30 ? lastMessage.substring(0, 30) + '...' : lastMessage,
                    time: currentTime,
                    unread_count: incrementUnread ? 1 : 0
                });
            }
        },

        async tarikPesanPasien(userId, scroll = true) {
            if (!userId) return;
            try {
                const res = await fetch(`/admin/chat/${userId}`);
                if (!res.ok) throw new Error("Gagal fetch pesan pasien");
                const data = await res.json();

                if (data.messages) {
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
            const targetUsername = this.activeUser.id;
            
            // Tambahkan langsung ke UI lokal agar super responsif
            const tempId = Date.now();
            this.chatMessages.push({
                id: tempId,
                sender: 'admin',
                type: 'text',
                text: text,
                time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
            });
            this.scrollToBottom();

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
                        user_id: targetUsername,
                        message: text
                    })
                });

                if (!response.ok) throw new Error("Server gagal menyimpan pesan");
                
                const responseData = await response.json();
                
                // Ganti ID sementara dengan asli
                const msgIndex = this.chatMessages.findIndex(m => m.id === tempId);
                if (msgIndex !== -1 && responseData.message) {
                    this.chatMessages[msgIndex].id = responseData.message.id;
                }

                // Bump admin chat ke atas list secara lokal
                this.bumpUserToTop(targetUsername, text, false);

            } catch (e) {
                console.error("Gagal kirim balasan:", e);
                alert("Pesan gagal terkirim!");
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
