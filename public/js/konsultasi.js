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
            this.tarikPesan();
            // Polling tiap 5 detik buat ngecek balasan dokter
            this.pollingInterval = setInterval(() => {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get("tab") === "konsultasi") {
                    this.tarikPesan(false);
                }
            }, 5000);
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
                    // Cek kalau ada pesan baru
                    if (this.chatMessages.length !== data.messages.length) {
                        this.chatMessages = data.messages;
                        this.scrollToBottom();

                        // Cek otomatis posisi step
                        const userMsgs = this.chatMessages.filter(
                            (m) => m.sender === "user",
                        );
                        if (userMsgs.length > 0) this.step = 2; // Kalau pasien udah pernah chat, lewatin bot
                    }
                } else if (this.chatMessages.length === 0) {
                    // Kalau database kosong, trigger sapaan bot pertama kali
                    this.kirimKeDatabase(
                        "Halo! 👋 Saya Asisten Virtual DelimaCare. Untuk memulai konsultasi hari ini, silakan ceritakan secara singkat keluhan yang Anda rasakan.",
                        "bot",
                        "text",
                    );
                }
            } catch (err) {
                console.error("Gagal memuat pesan", err);
            }
            if (showLoading) this.isLoading = false;
        },

        async kirimKeDatabase(text, sender = "user", type = "text") {
            try {
                // Update UI langsung biar kerasa cepet
                this.chatMessages.push({
                    id: Date.now(),
                    sender: sender,
                    type: type,
                    text: text,
                    time: this.getTime(),
                });
                this.scrollToBottom();

                // Simpan ke Database
                await fetch("/portal/chat/send", {
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
            } catch (err) {
                console.error("Gagal kirim", err);
            }
        },

        sendMessage() {
            if (this.newMessage.trim() === "") return;

            const pesan = this.newMessage;
            this.newMessage = "";

            // Simpan chat user
            this.kirimKeDatabase(pesan, "user", "text");

            // Trigger Bot Response kalau masih awal
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
            setTimeout(() => {
                const container = document.getElementById("chat-container");
                if (container) container.scrollTop = container.scrollHeight;
            }, 50);
        },
    };
}
