// public/js/portal.js

document.addEventListener("alpine:init", () => {
    Alpine.data("portalApp", () => ({
        // Tab aktif secara default
        activeTab: "reservasi",

        // Dark mode state
        darkMode: document.documentElement.classList.contains('dark'),

        // Toggle Dark Mode
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('delimacare-dark', this.darkMode);
            document.documentElement.classList.add('dark-transition');
            setTimeout(() => document.documentElement.classList.remove('dark-transition'), 350);
            this.darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
        },

        // Mobile menu state
        mobileMenuOpen: false,

        // State untuk fitur Chat Asisten Virtual
        newMessage: "",
        isTyping: false,
        chatMessages: [
            {
                id: 1,
                sender: "bot",
                text: "Halo! Saya asisten virtual DelimaCare. Ada yang bisa saya bantu terkait kesehatan ibu dan anak?",
                time: "09:00",
            },
        ],

        // Fungsi berpindah tab
        switchTab(tabName) {
            this.activeTab = tabName;
            this.mobileMenuOpen = false;
            window.scrollTo({ top: 0, behavior: "smooth" });
        },

        // Fungsi kirim pesan di Konsultasi Online
        sendMessage() {
            if (this.newMessage.trim() === "") return;

            const now = new Date();
            const timeString = `${now.getHours().toString().padStart(2, "0")}:${now.getMinutes().toString().padStart(2, "0")}`;

            // Tambahkan pesan user
            this.chatMessages.push({
                id: Date.now(),
                sender: "user",
                text: this.newMessage,
                time: timeString,
            });

            this.newMessage = "";
            this.isTyping = true;
            this.scrollToBottom();

            // Simulasi balasan bot (delay 1.5 detik)
            setTimeout(() => {
                this.isTyping = false;
                this.chatMessages.push({
                    id: Date.now(),
                    sender: "bot",
                    text: "Terima kasih atas pertanyaan Anda. Untuk informasi medis yang lebih spesifik dan akurat, silakan buat reservasi untuk konsultasi langsung dengan dokter kami.",
                    time: timeString,
                });
                this.scrollToBottom();
            }, 1500);
        },

        scrollToBottom() {
            setTimeout(() => {
                const chatContainer = document.getElementById("chat-container");
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            }, 50);
        },
    }));
});
