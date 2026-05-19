function chatbotApp() {
    return {
        step: 0,
        isTyping: false,
        newMessage: "",
        isLoading: true,
        formKeluhan: {
            durasi: "",
            reaksi_obat: "",
            alergi: "",
            gejala_lain: "",
            riwayat_penyakit: "",
        },
        chatMessages: [],
        pollingInterval: null,

        init() {
            // 1. Langsung tarik data pas halaman dibuka
            this.tarikPesan();

            // 2. Listen ke channel WebSocket menggunakan Laravel Echo
            if (typeof window.Echo !== 'undefined' && window.currentUsername) {
                window.Echo.channel('chat.' + window.currentUsername)
                    .listen('MessageSent', (e) => {
                        // Jika pengirim bukan user (berarti admin), tambahkan ke chatMessages
                        if (e.message.sender !== 'user') {
                            // Cek agar tidak duplikat
                            const exists = this.chatMessages.find(m => m.id === e.message.id);
                            if (!exists) {
                                this.chatMessages.push({
                                    id: e.message.id,
                                    sender: e.message.sender,
                                    type: e.message.type,
                                    text: e.message.message,
                                    time: this.getTime()
                                });
                                this.scrollToBottom();
                            }
                        }
                    });
            }
        },

        getTime() {
            const now = new Date();
            return (
                now.getHours().toString().padStart(2, "0") +
                ":" +
                now.getMinutes().toString().padStart(2, "0")
            );
        },

        async tarikPesan(showLoading = true) {
            if (showLoading) this.isLoading = true;
            try {
                const res = await fetch("/portal/chat/load");
                const data = await res.json();

                if (data.messages && data.messages.length > 0) {
                    // Hanya update UI kalau ada pesan baru dari DB (biar gak kedip)
                    if (this.chatMessages.length !== data.messages.length) {
                        this.chatMessages = data.messages;
                        this.scrollToBottom();

                        // Cek apakah user sudah pernah kirim detail keluhan
                        const userHasSentForm = this.chatMessages.some((m) =>
                            m.text.includes("DETAIL KELUHAN MEDIS"),
                        );
                        const userHasChatted = this.chatMessages.some(
                            (m) => m.sender === "user",
                        );

                        if (userHasSentForm) {
                            this.step = 2; // Langsung ke mode nunggu dokter
                        } else if (userHasChatted) {
                            this.step = 1; // Kembali ke mode isi form jika baru chat awal
                        }
                    }
                } else if (this.chatMessages.length === 0 && !showLoading) {
                    // Cek lagi, kalau beneran kosong di DB, kasih sapaan (HANYA DI UI, jangan simpan ke DB dulu biar gak banjir)
                    this.chatMessages.push({
                        id: "welcome",
                        sender: "bot",
                        type: "text",
                        text: "Halo! 👋 Saya Asisten Virtual DelimaCare. Untuk memulai konsultasi hari ini, silakan ceritakan secara singkat keluhan yang Anda rasakan.",
                        time: this.getTime(),
                    });
                }
            } catch (err) {
                console.error("Gagal memuat pesan", err);
            }
            if (showLoading) this.isLoading = false;
        },

        async kirimKeDatabase(text, sender = "user", type = "text") {
            // Update UI lokal dulu biar responsif
            const tempId = Date.now();
            this.chatMessages.push({
                id: tempId,
                sender: sender,
                type: type,
                text: text,
                time: this.getTime(),
            });
            this.scrollToBottom();

            try {
                const response = await fetch("/portal/chat/send", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                    },
                    body: JSON.stringify({
                        message: text,
                        sender: sender,
                        type: type,
                    }),
                });

                if (!response.ok) throw new Error("Gagal kirim ke server");

                const responseData = await response.json();
                
                // Update ID sementara dengan ID asli dari DB
                const msgIndex = this.chatMessages.findIndex(m => m.id === tempId);
                if (msgIndex !== -1 && responseData.message) {
                    this.chatMessages[msgIndex].id = responseData.message.id;
                }
                
                // Hapus tarikPesan() karena kita pakai WebSocket
            } catch (err) {
                console.error("Gagal kirim", err);
                alert("Pesan gagal terkirim, cek koneksi internet!");
            }
        },

        sendMessage() {
            if (this.newMessage.trim() === "") return;

            const pesan = this.newMessage;
            this.newMessage = "";

            this.kirimKeDatabase(pesan, "user", "text");

            // Logic trigger form otomatis
            if (this.step === 0) {
                this.step = 1;
                this.isTyping = true;
                setTimeout(() => {
                    this.isTyping = false;
                    this.kirimKeDatabase(
                        "Baik, mohon lengkapi formulir detail keluhan berikut agar dokter dapat memberikan diagnosa awal yang lebih akurat.",
                        "bot",
                        "form",
                    );
                }, 1000);
            }
        },

        submitFormKeluhan() {
            let rekap = `📌 DETAIL KELUHAN MEDIS:\n- Durasi Keluhan: ${this.formKeluhan.durasi}\n- Obat & Reaksi: ${this.formKeluhan.reaksi_obat}\n- Riwayat Alergi: ${this.formKeluhan.alergi || "-"}\n- Gejala Tambahan: ${this.formKeluhan.gejala_lain || "-"}\n- Riwayat Penyakit: ${this.formKeluhan.riwayat_penyakit || "-"}`;

            this.kirimKeDatabase(rekap, "user", "text");
            this.step = 2;
            this.isTyping = true;

            setTimeout(() => {
                this.isTyping = false;
                this.kirimKeDatabase(
                    "Terima kasih atas informasinya. 🙏 Data Anda sudah kami rekap dan teruskan ke dokter spesialis DelimaCare.\n\nMohon ditunggu sebentar ya, dokter akan segera membalas pesan Anda di ruangan ini.",
                    "bot",
                    "text",
                );
            }, 1500);
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById("chat-container");
                if (container) {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: "smooth",
                    });
                }
            });
        },
    };
}
