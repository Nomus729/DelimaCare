<style>
.q-card { transition: all .2s cubic-bezier(.4,0,.2,1); }
.q-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(13,148,136,.08); }
</style>

<div x-data="adminChatApp()" class="flex h-[calc(100vh-6.5rem)] bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-[2rem] overflow-hidden shadow-sm">

    {{-- KIRI: Daftar Pasien --}}
    <div class="w-1/3 border-r border-gray-100 dark:border-gray-800 flex flex-col bg-gray-50/30 dark:bg-gray-900/20">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Pesan Masuk</h2>
            <p class="text-xs font-medium text-gray-500 mt-1">Konsultasi Live Pasien</p>

            <div class="mt-4 relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari pasien..." class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:outline-none focus:border-teal-500 dark:text-white transition-all">
            </div>
        </div>

        <div class="flex-grow overflow-y-auto p-3 space-y-2">
            <template x-for="user in userList" :key="user.id">
                <button @click="pilihPasien(user)"
                        :class="activeUser?.id === user.id ? 'bg-teal-50 border-teal-100 dark:bg-teal-900/30 dark:border-teal-800' : 'bg-white border-transparent hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-750'"
                        class="w-full text-left p-4 rounded-2xl border transition-all flex items-center gap-3 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-cyan-500 text-white flex items-center justify-center font-black text-sm shadow-md flex-shrink-0">
                        <span x-text="user.name.charAt(0).toUpperCase()"></span>
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="text-sm font-black text-gray-900 dark:text-white truncate" x-text="user.name"></h4>
                            <span class="text-[9px] font-bold text-gray-400" x-text="user.time"></span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="user.last_message"></p>
                    </div>
                </button>
            </template>
            <template x-if="userList.length === 0 && !isLoadingList">
                <div class="text-center py-10 text-gray-400">
                    <p class="text-xs font-bold uppercase tracking-wider">Belum ada pesan</p>
                </div>
            </template>
        </div>
    </div>

    {{-- KANAN: Ruang Chat --}}
    <div class="w-2/3 flex flex-col relative bg-[#FAFFFE] dark:bg-[#0B1120]">

        <template x-if="!activeUser">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-10">
                <div class="w-20 h-20 bg-teal-50 dark:bg-teal-900/20 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white">Pilih Pesan Pasien</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Pilih nama pasien di sebelah kiri untuk mulai membalas keluhan atau konsultasi medis.</p>
            </div>
        </template>

        <template x-if="activeUser">
            <div class="flex flex-col h-full z-10">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-white/80 backdrop-blur-md flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-cyan-500 text-white flex items-center justify-center font-black shadow-md">
                            <span x-text="activeUser.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 dark:text-white leading-tight" x-text="activeUser.name"></h3>
                            <p class="text-[10px] font-bold text-teal-500 uppercase tracking-widest flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online
                            </p>
                        </div>
                    </div>
                </div>

                <div id="admin-chat-area" class="flex-grow p-6 overflow-y-auto space-y-5">
                    <template x-for="msg in chatMessages" :key="msg.id">
                        <div :class="msg.sender === 'admin' ? 'flex flex-col items-end' : 'flex flex-col items-start'">
                            <template x-if="msg.sender !== 'admin'">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 ml-2" x-text="msg.sender === 'bot' ? 'Sistem Bot' : 'Pasien'"></span>
                            </template>
                            <div :class="msg.sender === 'admin' ? 'text-white rounded-3xl rounded-tr-sm shadow-md' : (msg.sender === 'bot' ? 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 rounded-3xl rounded-tl-sm' : 'bg-white border border-gray-100 text-gray-800 rounded-3xl rounded-tl-sm shadow-[0_4px_20px_rgba(0,0,0,0.03)] dark:bg-[#1E293B] dark:border-gray-800 dark:text-gray-200')"
                                 :style="msg.sender === 'admin' ? 'background: var(--gradient-main);' : ''"
                                 class="px-5 py-3.5 max-w-[80%] text-[14px] leading-relaxed whitespace-pre-wrap">
                                <span x-text="msg.text"></span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 mt-1 px-2" x-text="msg.time"></span>
                        </div>
                    </template>
                </div>

                <div class="p-4 bg-white border-t border-gray-100 dark:bg-[#1E293B] dark:border-gray-800">
                    <form @submit.prevent="kirimBalasan" class="relative flex items-center gap-3">
                        <div class="relative flex-1">
                            <input type="text" x-model="replyMessage" placeholder="Ketik balasan untuk pasien..."
                                   class="w-full pl-6 pr-14 py-4 bg-gray-50/50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 focus:bg-white transition-all text-sm font-semibold dark:bg-[#0F172A] dark:border-gray-700 dark:text-white"
                                   :disabled="isSending" autocomplete="off">
                            <button type="submit" :disabled="replyMessage.trim() === '' || isSending"
                                    class="absolute right-2 top-2 bottom-2 aspect-square rounded-full text-white transition-all duration-300 disabled:opacity-50 disabled:grayscale flex items-center justify-center hover:scale-105"
                                    style="background: var(--gradient-main); box-shadow: 0 4px 10px rgba(13,148,136,0.3);">
                                <svg class="w-4 h-4 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
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
            this.tarikUserList();

            this.pollingInterval = setInterval(() => {
                const currentTab = this.$data.activeMenu || (this.$root && Alpine.find(this.$root).activeMenu);
                if(currentTab === 'konsultasi') {
                    this.tarikUserList(false);
                    if(this.activeUser) {
                        this.tarikPesanPasien(this.activeUser.id, false);
                    }
                }
            }, 5000);
        },

        async tarikUserList(showLoading = true) {
            if(showLoading) this.isLoadingList = true;
            try {
                const res = await fetch('/admin/chat/users');
                if(!res.ok) return;
                const data = await res.json();
                this.userList = data.users;
            } catch (e) {
                console.error("Gagal tarik list user:", e);
            }
            if(showLoading) this.isLoadingList = false;
        },

        pilihPasien(user) {
            this.activeUser = user;
            this.chatMessages = [];
            this.tarikPesanPasien(user.id);
        },

        async tarikPesanPasien(userId, scroll = true) {
            try {
                const res = await fetch(`/admin/chat/${userId}`);
                if(!res.ok) return;
                const data = await res.json();

                if (data.messages && data.messages.length !== this.chatMessages.length) {
                    this.chatMessages = data.messages;
                    if(scroll) this.scrollToBottom();
                }
            } catch (e) {
                console.error("Gagal tarik pesan:", e);
            }
        },

        async kirimBalasan() {
            if (this.replyMessage.trim() === '' || !this.activeUser) return;

            const text = this.replyMessage;
            this.replyMessage = '';
            this.isSending = true;

            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            this.chatMessages.push({ id: Date.now(), sender: 'admin', type: 'text', text: text, time: timeStr });
            this.scrollToBottom();

            try {
                await fetch('/admin/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ user_id: this.activeUser.id, message: text })
                });
                this.tarikUserList(false);
            } catch (e) {
                console.error("Gagal kirim balasan:", e);
                alert("Gagal kirim balasan, periksa koneksi!");
            }

            this.isSending = false;
        },

        scrollToBottom() {
            setTimeout(() => {
                const area = document.getElementById('admin-chat-area');
                if (area) area.scrollTop = area.scrollHeight;
            }, 100);
        }
    }
}
</script>
