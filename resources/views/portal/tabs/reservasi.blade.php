@php
    $hasActiveReservation = isset($jadwalPasien) ? $jadwalPasien->whereIn('status', ['Menunggu', 'Dikonfirmasi'])->isNotEmpty() : false;
@endphp

<form action="{{ route('reservasi.store') }}" method="POST" @submit="isSubmitting = true"
      class="w-full"
      x-data="reservasiApp(@js($doctors), '{{ date('Y-m-d') }}')"
      x-init="doctorMap = { @foreach($doctors as $doc) {{ $doc->id }}: '{{ addslashes($doc->nama) }}', @endforeach }">
    @csrf

    <!-- Step Indicator for Mobile Only -->
    <div x-show="isMobile" x-cloak class="md:hidden w-full mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-extrabold uppercase tracking-wider text-teal-600 dark:text-teal-400"
                  x-text="currentStep === 1 ? 'Langkah 1: Detail Reservasi' : 'Langkah 2: Pilih Dokter'"></span>
            <span class="text-xs font-bold text-gray-500 dark:text-gray-400" x-text="currentStep + ' / 2'"></span>
        </div>
        <div class="h-2 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-teal-500 to-cyan-400 transition-all duration-300"
                 :style="'width: ' + (currentStep === 1 ? '50%' : '100%')"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        {{-- Left Column: Doctor Availability --}}
        <section aria-labelledby="doctor-list-heading"
                 x-show="!isMobile || currentStep === 2"
                 x-cloak
                 class="lg:col-span-5 bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm dark:bg-[#1E293B] dark:border-gray-800 w-full">
            <header class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 id="doctor-list-heading" class="font-bold text-gray-900 text-lg dark:text-white">Pilih Dokter</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400"
                       x-text="isMobile ? 'Klik pada nama dokter di bawah untuk memilih' : 'Klik pada nama dokter untuk memilih'">Klik pada nama dokter untuk memilih</p>
                </div>
            </header>

            <ul class="space-y-4">
                @forelse($doctors as $doc)
                <li @click="selectedDoctor = '{{ $doc->id }}'; selectedDoctorName = '{{ addslashes($doc->nama) }}'; checkAvailability()"
                     :class="selectedDoctor === '{{ $doc->id }}' ? 'border-teal-500 bg-teal-50/80 ring-2 ring-teal-500/20 dark:bg-teal-900/30' : 'border-gray-100 dark:border-gray-700'"
                     class="flex items-center justify-between p-4 border rounded-2xl hover:border-teal-200 hover:bg-teal-50/50 transition-all cursor-pointer group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center font-bold dark:bg-teal-900/50 dark:text-teal-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-teal-700 transition-colors dark:text-white dark:group-hover:text-teal-400">{{ $doc->nama }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doc->spesialisasi }}</p>
                            <p class="text-[10px] text-teal-500 font-bold mt-1">{{ $doc->jadwal_praktek }}</p>
                        </div>
                    </div>
                    @php
                        $statusColor = match($doc->current_status) {
                            'Tersedia' => 'teal',
                            'Istirahat' => 'amber',
                            'Libur' => 'rose',
                            default => 'gray'
                        };
                    @endphp
                    <span class="px-3 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 text-[10px] font-black uppercase rounded-full dark:bg-{{ $statusColor }}-900/50 dark:text-{{ $statusColor }}-400">
                        {{ $doc->current_status }}
                    </span>
                </li>
                @empty
                <li class="p-8 text-center bg-gray-50 dark:bg-gray-900/50 rounded-3xl border border-dashed border-gray-200 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">Maaf, saat ini tidak ada dokter yang tersedia untuk dipilih.</p>
                </li>
                @endforelse
            </ul>
        </section>

        {{-- Right Column: Reservation Form --}}
        <section aria-labelledby="form-reservasi-heading" class="lg:col-span-7 bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm dark:bg-[#1E293B] dark:border-gray-800 w-full">
            <header class="mb-8" x-show="!isMobile || currentStep === 1">
                <h3 id="form-reservasi-heading" class="font-bold text-gray-900 mb-2 text-xl dark:text-white">Form Reservasi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Isi formulir dengan lengkap untuk membuat janji temu baru. Data Anda dienkripsi.</p>
            </header>

            @if($hasActiveReservation)
                <div class="flex flex-col items-center justify-center text-center py-12">
                    <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/20 rounded-full flex items-center justify-center mb-5 text-rose-500 dark:text-rose-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Reservasi Aktif Ditemukan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-8 leading-relaxed">Anda masih memiliki reservasi yang berstatus <strong class="text-rose-500">Menunggu</strong> atau <strong class="text-rose-500">Dikonfirmasi</strong>. Silakan selesaikan atau batalkan reservasi tersebut terlebih dahulu untuk membuat reservasi baru.</p>
                    <button type="button" @click.prevent="activeTab = 'jadwal'; window.scrollTo({top: 0, behavior: 'smooth'});" class="px-8 py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-teal-500/30 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Lihat Jadwal Saya
                    </button>
                </div>
            @else

            {{-- Error/Warning Alert --}}
            <div x-show="warning" x-cloak x-transition aria-live="polite"
                 class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 text-rose-700 dark:bg-rose-900/20 dark:border-rose-900/30 dark:text-rose-400">
                <svg aria-hidden="true" class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest mb-1">Peringatan Jadwal</p>
                    <p class="text-sm font-bold" x-text="warning"></p>
                </div>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-bold dark:bg-rose-900/20 dark:border-rose-900/30 dark:text-rose-400 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-bold dark:bg-rose-900/20 dark:border-rose-900/30 dark:text-rose-400">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div x-show="!isMobile || currentStep === 1">
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Nama Lengkap Pasien</label>
                        <!-- KODE YANG DIUBAH: NGUNCI NAMA PASIEN -->
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ Auth::user()->username }}"
                               readonly
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed focus:ring-0 focus:border-gray-200 outline-none transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                        <p class="text-[10px] text-gray-400 mt-1 italic">*Nama otomatis disesuaikan dengan akun Anda.</p>
                    </div>
                    <div x-show="!isMobile || currentStep === 1">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Nomor Telepon / WA</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xx-xxxx-xxxx" maxlength="15" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div x-show="!isMobile || currentStep === 1">
                        <label for="layanan" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Jenis Layanan</label>
                        <div class="relative">
                            <select id="layanan" name="layanan" required class="w-full px-4 py-3 appearance-none rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none bg-white transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                                <option value="" disabled {{ !old('layanan') ? 'selected' : '' }}>Pilih layanan</option>
                                <option {{ old('layanan') === 'Pemeriksaan Kehamilan' ? 'selected' : '' }}>Pemeriksaan Kehamilan</option>
                                <option {{ old('layanan') === 'Konsultasi KB' ? 'selected' : '' }}>Konsultasi KB</option>
                                <option {{ old('layanan') === 'Imunisasi Anak' ? 'selected' : '' }}>Imunisasi Anak</option>
                                <option {{ old('layanan') === 'Umum' ? 'selected' : '' }}>Umum</option>
                                <option {{ old('layanan') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div x-show="!isMobile || currentStep === 2" x-cloak>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Dokter Terpilih</label>
                        <div class="px-4 py-3 rounded-xl border border-dashed border-teal-200 bg-teal-50/30 flex items-center justify-between dark:border-teal-900/50 dark:bg-teal-900/10 transition-all">
                            <span class="text-sm font-bold text-teal-700 dark:text-teal-400"
                                  x-text="selectedDoctorName || (isMobile ? 'Silakan klik dokter di atas' : 'Silakan klik dokter di sebelah kiri')"></span>
                            <input type="hidden" name="dokter_id" :value="selectedDoctor">
                            <template x-if="selectedDoctor">
                                <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div x-show="!isMobile || currentStep === 1">
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Tanggal Kunjungan</label>
                        <input type="date" id="tanggal" name="tanggal" required x-model="selectedDate" @change="checkAvailability()"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all text-gray-700 dark:bg-[#0F172A] dark:border-gray-700 dark:text-white dark:color-scheme-dark">
                    </div>
                    <div x-show="!isMobile || currentStep === 1">
                        <label for="waktu" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Jam Kedatangan</label>
                        <div class="relative">
                            <select id="waktu" name="waktu" required class="w-full px-4 py-3 appearance-none rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none bg-white transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                                <option value="" disabled {{ !old('waktu') ? 'selected' : '' }}>Pilih Jam</option>
                                @php
                                    $start = strtotime('08:00');
                                    $end = strtotime('20:00');
                                    $currentTimeStr = date('H:i');
                                    $currentDateStr = date('Y-m-d');
                                    while ($start <= $end) {
                                        $t = date('H:i', $start);
                                        $sel = old('waktu') == $t ? 'selected' : '';
                                        // Gunakan x-bind:disabled dan x-text untuk memanipulasi option lewat AlpineJS berdasarkan selectedDate
                                        echo "<option value=\"$t\" $sel 
                                              x-bind:disabled=\"selectedDate === '$currentDateStr' && '$t' <= '$currentTimeStr'\"
                                              x-text=\"selectedDate === '$currentDateStr' && '$t' <= '$currentTimeStr' ? '$t (Sudah Lewat)' : '$t'\">
                                              $t
                                              </option>";
                                        $start = strtotime('+30 minutes', $start);
                                    }
                                @endphp
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="!isMobile || currentStep === 1">
                    <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Keluhan / Catatan (Opsional)</label>
                    <textarea id="keluhan" name="keluhan" rows="3" placeholder="Jelaskan keluhan atau catatan khusus untuk dokter" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all resize-none dark:bg-[#0F172A] dark:border-gray-700 dark:text-white"></textarea>
                </div>

                <!-- Navigation & Submit Buttons -->
                <div class="flex gap-4 mt-2" :class="isMobile ? 'flex-row' : 'flex-col'">
                    <!-- Tombol Kembali (Mobile Only) -->
                    <button type="button" @click="currentStep = 1"
                            x-show="isMobile && currentStep === 2"
                            x-cloak
                            class="w-1/3 py-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm transition-all duration-300 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali</span>
                    </button>

                    <!-- Tombol Lanjut Pilih Dokter (Mobile Only) -->
                    <button type="button" @click="nextStep()"
                            x-show="isMobile && currentStep === 1"
                            x-cloak
                            class="w-full py-4 rounded-xl text-white font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2 hover:-translate-y-0.5" 
                            style="background: var(--gradient-main); box-shadow: 0 4px 16px rgba(13,148,136,0.25);">
                        <span>Lanjut Pilih Dokter</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                    <!-- Tombol Submit -->
                    <button type="submit" :disabled="!!warning || isSubmitting"
                            x-show="!isMobile || currentStep === 2"
                            :class="[(!!warning || isSubmitting) ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'hover:-translate-y-0.5', isMobile && currentStep === 2 ? 'w-2/3' : 'w-full']"
                            class="py-4 rounded-xl text-white font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2" 
                            style="background: var(--gradient-main); box-shadow: 0 4px 16px rgba(13,148,136,0.25);">
                        <template x-if="isSubmitting">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isSubmitting ? 'Mengirim Permintaan...' : 'Selesai / Buat Reservasi'"></span>
                    </button>
                </div>

                <p class="text-xs text-center text-gray-400 mt-3 flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Data medis Anda dilindungi oleh enkripsi end-to-end.
                </p>
            </div>
            @endif
        </section>
    </div>
</form>
