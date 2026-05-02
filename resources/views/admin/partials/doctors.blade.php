<div x-data="{ 
    showModal: false, 
    editMode: false,
    doctor: {
        id: '',
        nama: '',
        spesialisasi: '',
        status: 'Tersedia',
        jadwal_praktek: '',
        phone: ''
    },
    // Structured Schedule State
    schedule: {
        dayStart: 'Senin',
        dayEnd: 'Jumat',
        hourStart: '08',
        minStart: '00',
        hourEnd: '16',
        minEnd: '00'
    },
    days: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    hours: Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0')),
    minutes: ['00', '15', '30', '45'],
    
    get combinedSchedule() {
        return `${this.schedule.dayStart} - ${this.schedule.dayEnd} (${this.schedule.hourStart}:${this.schedule.minStart} - ${this.schedule.hourEnd}:${this.schedule.minEnd})`;
    },

    openAdd() {
        this.editMode = false;
        this.doctor = { id: '', nama: '', spesialisasi: '', status: 'Tersedia', jadwal_praktek: '', phone: '' };
        this.schedule = { dayStart: 'Senin', dayEnd: 'Jumat', hourStart: '08', minStart: '00', hourEnd: '16', minEnd: '00' };
        this.showModal = true;
    },
    openEdit(data) {
        this.editMode = true;
        this.doctor = { ...data };
        
        try {
            // Parse format: 'Senin - Jumat (08:00 - 16:00)'
            const regex = /^(.+) - (.+) \((..):(..) - (..):(..)\)$/;
            const match = data.jadwal_praktek.match(regex);
            if (match) {
                this.schedule.dayStart = match[1];
                this.schedule.dayEnd = match[2];
                this.schedule.hourStart = match[3];
                this.schedule.minStart = match[4];
                this.schedule.hourEnd = match[5];
                this.schedule.minEnd = match[6];
            }
        } catch(e) {
            console.log('Using default schedule parsing');
        }
        
        this.showModal = true;
    },
    
    showDeleteModal: false,
    doctorToDelete: { id: '', nama: '' },
    confirmDelete(id, nama) {
        this.doctorToDelete = { id, nama };
        this.showDeleteModal = true;
    },
    executeDelete() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = `{{ url('admin/doctors') }}/${this.doctorToDelete.id}`;
        const c = document.createElement('input'); c.type = 'hidden'; c.name = '_token'; c.value = '{{ csrf_token() }}';
        const m = document.createElement('input'); m.type = 'hidden'; m.name = '_method'; m.value = 'DELETE';
        f.appendChild(c); f.appendChild(m);
        document.body.appendChild(f);
        f.submit();
    }
}">
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
        <div class="anim-up">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-2 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Manajemen Dokter</h2>
            </div>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 max-w-md">
                Optimalkan pelayanan klinik dengan mengatur ketersediaan dan jadwal praktek tenaga medis Anda secara profesional.
            </p>
        </div>
        <button @click="openAdd()"
            class="group relative inline-flex items-center gap-3 px-8 py-4 rounded-[2rem] text-sm font-black text-white
                   bg-gray-900 dark:bg-teal-600 hover:scale-105 active:scale-95 transition-all duration-300 shadow-xl overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
            <span class="relative z-10">Daftarkan Dokter Baru</span>
        </button>
    </div>

    {{-- Doctor Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pb-10">
        @foreach($doctors as $doc)
        <div class="group relative bg-white/70 dark:bg-[#0E1A2E]/70 backdrop-blur-xl border border-white/40 dark:border-gray-800/50 rounded-[3rem] p-1 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            {{-- Inner Card Container --}}
            <div class="bg-white dark:bg-[#0E1A2E] rounded-[2.8rem] p-7 h-full flex flex-col">
                
                {{-- Top Action & Status --}}
                <div class="flex justify-between items-start mb-8">
                    @php
                        $statusColor = match($doc->current_status) {
                            'Tersedia' => 'emerald',
                            'Istirahat' => 'amber',
                            'Libur' => 'rose',
                            default => 'gray'
                        };
                    @endphp
                    <div class="px-4 py-2 rounded-2xl bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900/20 border border-{{ $statusColor }}-100 dark:border-{{ $statusColor }}-900/30 flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $statusColor }}-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-{{ $statusColor }}-500"></span>
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400">{{ $doc->current_status }}</span>
                    </div>

                    <div class="flex gap-2">
                        <button @click="openEdit({{ $doc->toJson() }})" class="p-3 rounded-2xl bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Profile Section --}}
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="relative mb-4">
                        <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-teal-500 to-cyan-500 p-1 rotate-3 group-hover:rotate-6 transition-transform duration-500">
                            <div class="w-full h-full rounded-[1.8rem] bg-white dark:bg-[#0E1A2E] flex items-center justify-center overflow-hidden">
                                <span class="text-3xl font-black bg-gradient-to-br from-teal-600 to-cyan-500 bg-clip-text text-transparent">
                                    {{ strtoupper(substr($doc->nama, 4, 1)) }}
                                </span>
                            </div>
                        </div>
                        {{-- Verified badge --}}
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white dark:bg-[#0E1A2E] rounded-xl flex items-center justify-center shadow-lg border border-gray-100 dark:border-gray-800">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.25.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white leading-tight mb-1">{{ $doc->nama }}</h3>
                    <p class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-[0.2em]">{{ $doc->spesialisasi }}</p>
                </div>

                {{-- Schedule & Info --}}
                <div class="bg-gray-50/50 dark:bg-gray-900/30 rounded-[2rem] p-5 space-y-4 mb-6 flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-teal-600 shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Jadwal Praktek</p>
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $doc->jadwal_praktek ?? 'Belum Diatur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-cyan-600 shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Kontak Darurat</p>
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $doc->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Delete Button (Hidden by default, shown on hover/touch) --}}
                <div class="mt-auto pt-2 border-t border-gray-100 dark:border-gray-800/50 flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button @click="confirmDelete({{ $doc->id }}, {{ json_encode($doc->nama) }})" 
                        class="w-full py-3 rounded-2xl text-rose-500 font-bold text-xs hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Data
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- MODAL REDESIGN: Structured Schedule --}}
    <template x-teleport="body">
        <div x-show="showModal" x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-md" @click="showModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#0E1A2E] w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden anim-up" @click.stop>
                {{-- Compact Header --}}
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="editMode ? 'Edit Dokter' : 'Tambah Dokter'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ url('admin/doctors') }}/' + doctor.id : '{{ route('admin.doctors.store') }}'" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" x-model="doctor.nama" required placeholder="Dr. Siti Aminah, Sp.OG"
                               class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-xl outline-none focus:border-teal-500 transition-all font-bold text-sm">
                    </div>

                    {{-- Spesialisasi & Status --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Bidang</label>
                            <select name="spesialisasi" x-model="doctor.spesialisasi" required
                                    class="w-full px-3 py-2.5 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-xl outline-none focus:border-teal-500 font-bold text-xs appearance-none">
                                <option value="" disabled>Pilih</option>
                                <option value="Kebidanan & Kandungan">Kebidanan</option>
                                <option value="Bidan">Bidan</option>
                                <option value="Dokter Umum">Umum</option>
                                <option value="Spesialis Anak">Anak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Ketersediaan</label>
                            <select name="status" x-model="doctor.status" required
                                    class="w-full px-3 py-2.5 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-xl outline-none focus:border-teal-500 font-bold text-xs appearance-none">
                                <option value="Tersedia">Tersedia</option>
                                <option value="Istirahat">Istirahat</option>
                                <option value="Libur">Libur</option>
                            </select>
                        </div>
                    </div>

                    {{-- Schedule Section: Ultra Compact --}}
                    <div class="p-4 bg-teal-50/30 dark:bg-teal-900/10 rounded-2xl border border-teal-100/50">
                        <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest mb-3">Waktu Praktek (Format 24 Jam)</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <select x-model="schedule.dayStart" class="flex-1 px-2 py-2 bg-white dark:bg-gray-800 border border-gray-100 rounded-lg text-[10px] font-bold outline-none">
                                    <template x-for="day in days"><option :value="day" x-text="day"></option></template>
                                </select>
                                <span class="text-gray-300 text-[10px]">s/d</span>
                                <select x-model="schedule.dayEnd" class="flex-1 px-2 py-2 bg-white dark:bg-gray-800 border border-gray-100 rounded-lg text-[10px] font-bold outline-none">
                                    <template x-for="day in days"><option :value="day" x-text="day"></option></template>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-100 rounded-lg px-2 py-1">
                                    <select x-model="schedule.hourStart" class="w-full bg-transparent text-[10px] font-bold outline-none appearance-none text-center">
                                        <template x-for="h in hours"><option :value="h" x-text="h"></option></template>
                                    </select>
                                    <span class="text-gray-300">:</span>
                                    <select x-model="schedule.minStart" class="w-full bg-transparent text-[10px] font-bold outline-none appearance-none text-center">
                                        <template x-for="m in minutes"><option :value="m" x-text="m"></option></template>
                                    </select>
                                </div>
                                <span class="text-gray-300 text-[10px]">s/d</span>
                                <div class="flex-1 flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-100 rounded-lg px-2 py-1">
                                    <select x-model="schedule.hourEnd" class="w-full bg-transparent text-[10px] font-bold outline-none appearance-none text-center">
                                        <template x-for="h in hours"><option :value="h" x-text="h"></option></template>
                                    </select>
                                    <span class="text-gray-300">:</span>
                                    <select x-model="schedule.minEnd" class="w-full bg-transparent text-[10px] font-bold outline-none appearance-none text-center">
                                        <template x-for="m in minutes"><option :value="m" x-text="m"></option></template>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="jadwal_praktek" :value="combinedSchedule">
                        <div class="mt-3 pt-2 border-t border-teal-100/30">
                            <p class="text-[10px] font-bold text-teal-600/80 italic text-center" x-text="combinedSchedule"></p>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">No. WhatsApp</label>
                        <input type="text" name="phone" x-model="doctor.phone" placeholder="0812..."
                               class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-xl outline-none focus:border-teal-500 transition-all font-bold text-sm">
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="showModal = false" class="flex-1 py-3 bg-gray-50 text-gray-400 font-bold rounded-xl text-xs">Batal</button>
                        <button type="submit" class="flex-[2] py-3 bg-teal-600 text-white font-black rounded-xl shadow-lg shadow-teal-600/20 text-xs">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
    
    {{-- ===== DELETE CONFIRMATION MODAL — teleport to body ===== --}}
    <template x-teleport="body">
        <div x-show="showDeleteModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[1000] flex items-center justify-center p-4">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/65 backdrop-blur-sm" @click="showDeleteModal = false"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden p-8 text-center"
                 @click.stop style="font-family:'Inter',sans-serif;">
                
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/20 rounded-3xl flex items-center justify-center mx-auto mb-6 rotate-3">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 italic">Hapus Dokter?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 leading-relaxed">
                    Anda akan menghapus data <span class="font-bold text-gray-900 dark:text-white" x-text="doctorToDelete.nama"></span>. 
                    Seluruh jadwal dan riwayat reservasi yang terhubung mungkin akan terpengaruh.
                </p>

                <div class="flex gap-3">
                    <button @click="showDeleteModal = false"
                            class="flex-1 py-4 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all text-sm">
                        Batalkan
                    </button>
                    <button @click="executeDelete()"
                            class="flex-[1.5] py-4 bg-rose-600 text-white font-black rounded-2xl shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all text-sm">
                        Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
