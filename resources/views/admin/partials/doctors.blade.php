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

    {{-- Doctor List - Minimalist --}}
    <div class="bg-white/50 dark:bg-[#0E1A2E]/50 backdrop-blur-xl border border-gray-100 dark:border-gray-800/50 rounded-[2rem] overflow-hidden shadow-sm pb-1 mb-10 anim-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800/50 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest bg-gray-50/50 dark:bg-[#0E1A2E]/30">
                        <th class="px-6 py-4">Dokter</th>
                        <th class="px-6 py-4">Jadwal Praktek</th>
                        <th class="px-6 py-4">WhatsApp</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @foreach($doctors as $doc)
                    @php
                        $statusColor = match($doc->current_status) {
                            'Tersedia' => 'emerald',
                            'Istirahat' => 'amber',
                            'Libur' => 'rose',
                            default => 'gray'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-800/20 transition-all duration-200">
                        {{-- Identity Column --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-cyan-500 p-0.5 shadow-sm flex items-center justify-center">
                                    <div class="w-full h-full rounded-[0.55rem] bg-white dark:bg-[#0E1A2E] flex items-center justify-center overflow-hidden">
                                        @if($doc->image)
                                            <img src="{{ asset('storage/' . $doc->image) }}" class="w-full h-full object-cover rounded-[0.55rem]">
                                        @else
                                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-tight">{{ $doc->nama }}</h4>
                                    <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider mt-0.5">{{ $doc->spesialisasi }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Schedule Column --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-teal-600 dark:text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $doc->jadwal_praktek ?? 'Belum Diatur' }}</span>
                            </div>
                        </td>

                        {{-- WhatsApp / Emergency Contact Column --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $doc->phone ?? '-' }}</span>
                            </div>
                        </td>

                        {{-- Status Column --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900/20 border border-{{ $statusColor }}-100 dark:border-{{ $statusColor }}-900/30 text-[10px] font-black uppercase tracking-widest text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $statusColor }}-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-{{ $statusColor }}-500"></span>
                                </span>
                                {{ $doc->current_status }}
                            </span>
                        </td>

                        {{-- Actions Column --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openEdit({{ $doc->toJson() }})" 
                                        class="p-2 rounded-xl bg-gray-50 hover:bg-teal-50 text-gray-400 hover:text-teal-600 dark:bg-[#1E293B] dark:hover:bg-teal-900/30 dark:hover:text-teal-400 transition-all duration-200"
                                        title="Edit Dokter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button @click="confirmDelete({{ $doc->id }}, {{ json_encode($doc->nama) }})" 
                                        class="p-2 rounded-xl bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-600 dark:bg-[#1E293B] dark:hover:bg-rose-550/20 dark:hover:text-rose-400 transition-all duration-200"
                                        title="Hapus Dokter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL REDESIGN: Structured Schedule --}}
    <template x-teleport="body">
        <div x-show="showModal" x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-md" @click="showModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#0E1A2E] w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden anim-up flex flex-col max-h-[90vh]" @click.stop>
                {{-- Compact Header --}}
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 flex-shrink-0">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="editMode ? 'Edit Dokter' : 'Tambah Dokter'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ url('admin/doctors') }}/' + doctor.id : '{{ route('admin.doctors.store') }}'" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Scrollable Content Area --}}
                    <div class="p-6 space-y-4 overflow-y-auto max-h-[55vh] custom-scrollbar">
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

                        {{-- Photo Preview & Upload --}}
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Foto Dokter</label>
                            
                            {{-- Preview current photo in Edit Mode --}}
                            <template x-if="editMode && doctor.image">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-2xl">
                                    <img :src="'/storage/' + doctor.image" class="w-12 h-12 rounded-xl object-cover border border-teal-500/20">
                                    <div>
                                        <p class="text-[9px] font-black text-teal-600 dark:text-teal-400 uppercase tracking-widest">Foto Saat Ini</p>
                                        <p class="text-[10px] font-bold text-gray-400">Tersimpan di sistem</p>
                                    </div>
                                </div>
                            </template>

                            <input type="file" name="image" accept="image/*"
                                   class="w-full px-4 py-2 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-xl outline-none focus:border-teal-500 transition-all font-bold text-xs text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-teal-50 file:text-teal-700 dark:file:bg-teal-900/30 dark:file:text-teal-400 hover:file:bg-teal-100 dark:hover:file:bg-teal-900/40">
                        </div>

                        {{-- WhatsApp --}}
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">No. WhatsApp</label>
                            <input type="text" name="phone" x-model="doctor.phone" placeholder="0812..."
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#080F1E] border border-gray-100 dark:border-gray-800 rounded-xl outline-none focus:border-teal-500 transition-all font-bold text-sm">
                        </div>
                    </div>

                    {{-- Sticky Actions (Footer) --}}
                    <div class="flex gap-2 p-6 bg-gray-50/50 dark:bg-[#0E1A2E]/50 border-t border-gray-100 dark:border-gray-800 flex-shrink-0">
                        <button type="button" @click="showModal = false" class="flex-1 py-3 bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-400 font-bold rounded-xl text-xs">Batal</button>
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
