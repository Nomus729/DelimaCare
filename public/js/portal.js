// public/js/portal.js

document.addEventListener("alpine:init", () => {
    Alpine.data("portalApp", () => ({
        // Tab aktif secara default (ambil dari URL jika ada)
        activeTab: new URLSearchParams(window.location.search).get('tab') || "reservasi",

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

            // Simpan ke URL agar tidak hilang saat refresh/submit (Clean URL)
            const newUrl = window.location.origin + window.location.pathname + '?tab=' + tabName;
            window.history.pushState({}, '', newUrl);
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

    Alpine.data("reservasiApp", (initialDoctors, initialDate) => ({
        isSubmitting: false,
        selectedDoctor: '',      // Sekarang menyimpan ID (integer)
        selectedDoctorName: '',  // Nama dokter untuk display
        doctorMap: {},           // Map ID -> Nama (di-populate dari x-init di blade)
        selectedDate: initialDate,
        doctors: initialDoctors,
        days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
        daysIndo: ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
        warning: "",
        currentStep: 1,
        isMobile: false,

        init() {
            this.checkAvailability();
            this.checkMobile();
            window.addEventListener('resize', () => this.checkMobile());
        },

        checkMobile() {
            this.isMobile = window.innerWidth < 768;
        },

        nextStep() {
            const phoneInput = document.getElementById('phone');
            const layananSelect = document.getElementById('layanan');
            const tanggalInput = document.getElementById('tanggal');

            if (phoneInput && !phoneInput.checkValidity()) {
                phoneInput.reportValidity();
                return;
            }
            if (layananSelect && !layananSelect.checkValidity()) {
                layananSelect.reportValidity();
                return;
            }
            if (tanggalInput && !tanggalInput.checkValidity()) {
                tanggalInput.reportValidity();
                return;
            }

            this.currentStep = 2;
        },

        checkAvailability() {
            this.warning = "";
            if (!this.selectedDoctor || !this.selectedDate) return;

            // Cari dokter by ID (bukan by nama)
            const doc = this.doctors.find(d => d.id == this.selectedDoctor);
            if (!doc) return;

            const dateObj = new Date(this.selectedDate);
            const dayName = this.days[dateObj.getDay()];

            const regex = /^(.+) - (.+) \((..):(..) - (..):(..)\)$/;
            const match = doc.jadwal_praktek ? doc.jadwal_praktek.match(regex) : null;

            if (match) {
                const dayStart = match[1];
                const dayEnd = match[2];
                
                const startIndex = this.daysIndo.indexOf(dayStart);
                const endIndex = this.daysIndo.indexOf(dayEnd);
                const currentIndex = this.daysIndo.indexOf(dayName);

                if (currentIndex < startIndex || currentIndex > endIndex) {
                    this.warning = `Maaf, ${doc.nama} tidak praktek di hari ${dayName}. Jadwal: ${dayStart} - ${dayEnd}`;
                }
            }
        }
    }));
});
