<style>
.q-stat { background: white; border-radius: 1.5rem; padding: 1.5rem; border: 1px solid #f1f5f9; transition: all 0.3s; }
.dark .q-stat { background: #1E293B; border-color: #334155; }
.q-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 40px -10px rgba(13,148,136,.15); }
.q-input { width:100%; padding:.75rem 1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:1rem; font-size:.875rem; font-weight: 600; outline:none; transition:all .2s; }
.dark .q-input { background:#0f172a; border-color:#334155; color:#f1f5f9; }
.q-input:focus { border-color:#0d9488; background:#fff; box-shadow:0 0 0 4px rgba(13,148,136,.1); }
.dark .q-input:focus { background:#0f172a; box-shadow:0 0 0 4px rgba(45,212,191,.1); }
.q-select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 1rem center; background-size:1rem; padding-right:2.5rem !important; }
@keyframes slideUp { from{opacity:0;transform:translateY(15px)} to{opacity:1;transform:translateY(0)} }
.q-anim { animation: slideUp .4s cubic-bezier(0.16, 1, 0.3, 1) both; }
</style>
<div x-data="{
    showAddModal: false,
    showDetailModal: false,
    detailItem: null,
    isPolling: false,
    lastHash: '',
    openDetail(item) { this.detailItem = item; this.showDetailModal = true; },

    init() {
        // Initialize lastHash signature from the existing DOM to prevent any redundant update on the first poll
        const currentList = document.getElementById('reservasi-list-container');
        if (currentList) {
            this.lastHash = Array.from(currentList.querySelectorAll('[data-res-id]'))
                .map(el => el.getAttribute('data-res-id') + ':' + el.getAttribute('data-res-status'))
                .join(',');
        }

        // Poll every 10 seconds
        setInterval(() => {
            // Access activeMenu from global adminPanel scope
            const currentTab = this.$data.activeMenu || (this.$root && Alpine.find(this.$root).activeMenu);
            if (!this.showAddModal && !this.showDetailModal) {
                this.pollData();
            }
        }, 10000);

        // Listen for global refresh signal from admin.js
        window.addEventListener('refresh-reservasi', () => {
            this.pollData();
        });
    },

    async pollData() {
        if (this.isPolling) return;
        this.isPolling = true;
        try {
            const url = new URL('{{ route('admin.reservasi.partial') }}', window.location.origin);
            const currentParams = new URLSearchParams(window.location.search);
            // Append current filters to the polling request
            currentParams.forEach((value, key) => url.searchParams.append(key, value));

            const response = await fetch(url);
            const html = await response.text();

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Update stats
            const newStats = doc.getElementById('reservasi-stats-container');
            const oldStats = document.getElementById('reservasi-stats-container');
            if (newStats && oldStats) oldStats.innerHTML = newStats.innerHTML;

            // Update list with a smart signature check (IDs & statuses) to avoid scroll flicker
            const newList = doc.getElementById('reservasi-list-container');
            const oldList = document.getElementById('reservasi-list-container');
            if (newList && oldList) {
                const getItemsSignature = (container) => {
                    return Array.from(container.querySelectorAll('[data-res-id]'))
                        .map(el => el.getAttribute('data-res-id') + ':' + el.getAttribute('data-res-status'))
                        .join(',');
                };

                const newSignature = getItemsSignature(newList);
                const oldSignature = getItemsSignature(oldList);

                if (newSignature !== oldSignature) {
                    oldList.innerHTML = newList.innerHTML;
                    this.lastHash = newSignature;
                }
            }
        } catch (e) { console.error('Polling error:', e); }
        this.isPolling = false;
    },

    showDeleteModal: false,
    itemToDelete: { id: '', nama: '' },
    confirmDelete(id, nama) {
        this.itemToDelete = { id, nama };
        this.showDeleteModal = true;
    },
    executeDelete() {
        const f = document.createElement('form'); f.method='POST';
        f.action=`{{ url('admin/reservasi') }}/${this.itemToDelete.id}/batal`;
        const c=document.createElement('input'); c.type='hidden'; c.name='_token'; c.value='{{ csrf_token() }}';
        const m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE';
        f.appendChild(c); f.appendChild(m); document.body.appendChild(f); f.submit();
    }
}">

{{-- ─── Header ─── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Antrean Pasien</h2>
        <p class="text-sm text-gray-400 font-medium mt-0.5">Kelola jadwal & antrean klinik secara real-time</p>
    </div>
    <button @click="showAddModal = true"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-500/20 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Antrean
    </button>
</div>


{{-- ─── Stats Row ─── --}}
<div id="reservasi-stats-container">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
    $statItems = [
        ['label'=>'Hari Ini',    'value'=>$reservasiHariIni,        'color'=>'teal',    'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label'=>'Mendatang',   'value'=>$reservasiMendatang,      'color'=>'cyan',    'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Menunggu',    'value'=>$pendingReservasiCount,   'color'=>'amber',   'icon'=>'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Dikonfirmasi','value'=>$reservasiDikonfirmasi,   'color'=>'emerald', 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    @endphp
    @foreach($statItems as $s)
    <div class="q-stat flex items-center gap-5 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-{{ $s['color'] }}-500/10 rounded-full blur-xl group-hover:bg-{{ $s['color'] }}-500/20 transition-all duration-500"></div>
        <div class="w-14 h-14 rounded-2xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-900/20 flex items-center justify-center flex-shrink-0 group-hover:rotate-6 transition-all duration-300">
            <svg class="w-7 h-7 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ $s['label'] }}</p>
            <p class="text-3xl font-black text-gray-900 dark:text-white tabular-nums leading-none">{{ $s['value'] }}</p>
        </div>
    </div>
    @endforeach
    </div>
</div>

{{-- ─── Filter & Search Bar ─── --}}
<div class="bg-white/50 dark:bg-[#1E293B]/50 backdrop-blur-md border border-gray-100 dark:border-gray-800 rounded-3xl p-4 mb-6 shadow-sm">
    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col xl:flex-row gap-4 items-center justify-between w-full">
        <input type="hidden" name="tab" value="reservasi">

        {{-- Date Filter Tabs --}}
        <div class="flex bg-white dark:bg-[#0f172a] rounded-2xl p-1.5 gap-1 shadow-sm border border-gray-100 dark:border-gray-700/50 w-full xl:w-auto shrink-0 overflow-x-auto hide-scrollbar">
            @foreach(['today'=>'Hari Ini','upcoming'=>'Mendatang','all'=>'Semua'] as $key=>$label)
            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['tab'=>'reservasi','res_filter'=>$key,'res_status'=>$resStatus,'res_search'=>$resSearch])) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $resFilter===$key ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Search Container --}}
        <div class="relative flex-1 w-full min-w-0">
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="res_search" value="{{ $resSearch }}" placeholder="Cari nama pasien..."
                   class="q-input w-full !pl-11 h-[46px]">
        </div>

        {{-- Status Filter & Button --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto shrink-0">
            <select name="res_status" onchange="this.form.requestSubmit()" class="q-input q-select w-full sm:w-[160px] h-[46px]">
                <option value="" {{ $resStatus==='' ? 'selected':'' }}>Semua Status</option>
                @foreach(['Menunggu','Dikonfirmasi','Datang','Tidak Datang'] as $st)
                <option value="{{ $st }}" {{ $resStatus===$st ? 'selected':'' }}>{{ $st }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="w-full sm:w-auto h-[46px] px-6 bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white text-sm font-black rounded-2xl shadow-lg shadow-teal-500/20 transition-all hover:-translate-y-0.5 shrink-0 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            
            {{-- Reset Button if active filters --}}
            @if($resSearch || $resStatus || $resFilter !== 'today')
                <a href="{{ route('admin.dashboard', ['tab' => 'reservasi']) }}" title="Reset Filter"
                   class="w-full sm:w-12 h-[46px] flex items-center justify-center bg-rose-50 dark:bg-rose-900/20 text-rose-500 dark:text-rose-400 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ─── Queue Cards ─── --}}
<div id="reservasi-list-container" class="space-y-2.5">
@if(isset($semuaReservasi) && $semuaReservasi->count() > 0)
    @foreach($semuaReservasi as $idx => $item)
    @php
        $isToday = \Carbon\Carbon::parse($item->tanggal)->isToday();
        $status  = $item->status ?? 'Menunggu';

        // 🔥 LOGIKA BARU: HITUNG SISA WAKTU KONFIRMASI 🔥
        $createdAt = \Carbon\Carbon::parse($item->created_at);
        $deadline = $createdAt->copy()->addHours(24);
        $isPending = $status === 'Menunggu';
        $isAutoCancelled = ($status === 'Tidak Datang' && $createdAt->diffInHours(now()) >= 24);
        
        // Warna & Tema
        $statusTheme = match($status) {
            'Menunggu'     => ['pill'=>'bg-amber-50 text-amber-600 ring-1 ring-amber-200 dark:bg-amber-900/30 dark:ring-amber-500/30 dark:text-amber-400',   'dot'=>'bg-amber-400',  'color'=>'amber'],
            'Dikonfirmasi' => ['pill'=>'bg-teal-50 text-teal-600 ring-1 ring-teal-200 dark:bg-teal-900/30 dark:ring-teal-500/30 dark:text-teal-400',      'dot'=>'bg-teal-400',   'color'=>'teal'],
            'Datang'       => ['pill'=>'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200 dark:bg-emerald-900/30 dark:ring-emerald-500/30 dark:text-emerald-400','dot'=>'bg-emerald-400','color'=>'emerald'],
            'Tidak Datang' => ['pill'=>'bg-rose-50 text-rose-500 ring-1 ring-rose-200 dark:bg-rose-900/30 dark:ring-rose-500/30 dark:text-rose-400',      'dot'=>'bg-rose-400',   'color'=>'rose'],
            default        => ['pill'=>'bg-slate-50 text-slate-500 ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700 dark:text-slate-400',   'dot'=>'bg-slate-400',  'color'=>'slate'],
        };
        $numGrads = ['from-teal-500 to-cyan-400','from-emerald-500 to-teal-400','from-cyan-500 to-teal-500','from-teal-400 to-emerald-500'];
        $numGrad  = $numGrads[$idx % count($numGrads)];
    @endphp
    
    <div data-res-id="{{ $item->id }}" data-res-status="{{ $status }}" class="group relative bg-white dark:bg-[#1E293B] rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-2xl hover:shadow-{{ $statusTheme['color'] }}-500/10 transition-all duration-300 overflow-hidden flex flex-col md:flex-row items-start md:items-center gap-5"
         style="animation: slideUp .35s ease both; animation-delay:{{ $idx*40 }}ms">
        
        {{-- Decorative Glow --}}
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-{{ $statusTheme['color'] }}-500/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="relative w-16 h-16 shrink-0 group-hover:scale-105 transition-transform duration-300">
                <div class="w-full h-full bg-white dark:bg-[#1E293B] border-2 border-gray-100 dark:border-gray-800 rounded-2xl flex flex-col items-center justify-center shadow-sm group-hover:border-teal-200 dark:group-hover:border-teal-900/50 transition-colors duration-300">
                    <span class="text-[8px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] leading-none mb-1">Antrean</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white leading-none">{{ $item->queue_number ?? ($idx+1) }}</span>
                </div>
            </div>
            
            <div class="md:hidden flex-1 min-w-0">
                <h4 class="font-black text-gray-900 dark:text-white text-lg truncate">{{ $item->nama }}</h4>
                <div class="flex items-center gap-2 mt-1">
                     <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2.5 py-1 rounded-full {{ $statusTheme['pill'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusTheme['dot'] }} {{ $status==='Datang'?'animate-pulse':'' }}"></span>
                        {{ $status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Patient Info --}}
        <div class="flex-1 min-w-0 hidden md:block">
            <div class="flex items-center gap-3 mb-1.5">
                <h4 class="font-black text-gray-900 dark:text-white text-xl truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">{{ $item->nama }}</h4>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2.5 py-1 rounded-full {{ $statusTheme['pill'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusTheme['dot'] }} {{ $status==='Datang'?'animate-pulse':'' }}"></span>
                    {{ $status }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 px-2.5 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700/50">
                    <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    {{ $item->layanan }}
                </span>
                
                @if($isToday)
                    <span class="flex items-center gap-1.5 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 px-2.5 py-1.5 rounded-lg border border-teal-100 dark:border-teal-900/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Hari Ini
                    </span>
                @else
                    <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 px-2.5 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700/50">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                    </span>
                @endif
                
                @if($item->dokter_nama)
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 px-2.5 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700/50">
                    <svg class="w-3.5 h-3.5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $item->dokter_nama }}
                </span>
                @endif
                
                @if($item->keluhan)
                <span class="flex items-center gap-1.5 text-rose-500 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 px-2.5 py-1.5 rounded-lg max-w-xs truncate border border-rose-100 dark:border-rose-900/30" title="{{ $item->keluhan }}">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <span class="truncate">{{ $item->keluhan }}</span>
                </span>
                @endif
            </div>
        </div>

        {{-- Info Mobile (only show tags/badges) --}}
        <div class="md:hidden flex flex-wrap gap-2 text-[10px] font-medium w-full">
            <span class="flex items-center gap-1 bg-gray-50 dark:bg-gray-800 px-2 py-1.5 rounded-lg text-gray-600 dark:text-gray-300">{{ $item->layanan }}</span>
            @if($isToday)
                <span class="flex items-center gap-1 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 px-2 py-1.5 rounded-lg">Hari Ini</span>
            @endif
        </div>

        {{-- Time & Warnings --}}
        <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto gap-3">
             <div class="flex flex-col items-start md:items-end gap-1">
                @if($item->estimated_time)
                <span class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-1.5 px-1 py-0.5">
                    <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ \Carbon\Carbon::parse($item->estimated_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->estimated_time)->addMinutes(30)->format('H:i') }}
                </span>
                @endif
                
                {{-- Countdown Warning --}}
                @if($isPending)
                    @php
                        $hoursLeft = round(now()->diffInMinutes($deadline) / 60, 1);
                        $warningColor = $hoursLeft < 4 ? 'text-rose-500' : ($hoursLeft < 12 ? 'text-amber-500' : 'text-gray-400');
                    @endphp
                    <span class="text-[9px] font-bold {{ $warningColor }} flex items-center gap-1">
                        <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Konfirmasi dlm {{ $hoursLeft }}j
                    </span>
                @elseif($isAutoCancelled)
                    <span class="text-[9px] font-black text-rose-500 uppercase tracking-wider flex items-center gap-1 bg-rose-50 dark:bg-rose-900/20 px-1.5 py-0.5 rounded border border-rose-100 dark:border-rose-900/30">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        Auto-Cancel (24j)
                    </span>
                @endif
             </div>

             {{-- Actions --}}
             <div class="flex items-center gap-2">
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $item->phone) }}" target="_blank"
                   class="w-10 h-10 rounded-2xl flex items-center justify-center text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-500 hover:text-white dark:hover:bg-emerald-500 hover:scale-110 transition-all shadow-sm group/btn" title="Hubungi Pasien">
                    <svg class="w-5 h-5 group-hover/btn:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                </a>
                
                @if($status === 'Menunggu')
                <form action="{{ route('admin.reservasi.konfirmasi', $item->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" title="Konfirmasi Antrean"
                        class="w-10 h-10 rounded-2xl flex items-center justify-center text-violet-600 bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-600 hover:text-white hover:scale-110 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
                @elseif($status === 'Dikonfirmasi')
                <button @click="switchMenu('rekam_medis').then(() => { setTimeout(() => { $dispatch('open-rm-modal', { reservasi_id: {{ $item->id }}, nama_pasien: '{{ addslashes($item->nama) }}', phone: '{{ $item->phone }}', layanan: '{{ $item->layanan }}', dokter_id: '{{ addslashes($item->dokter_nama) }}' }) }, 100) })"
                    title="Buat Rekam Medis & Selesaikan"
                    class="w-10 h-10 rounded-2xl flex items-center justify-center text-teal-600 bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-600 hover:text-white hover:scale-110 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </button>
                @endif
                
                <button @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                    title="Batalkan / Hapus"
                    class="w-10 h-10 rounded-2xl flex items-center justify-center text-slate-400 bg-slate-50 dark:bg-slate-800 dark:text-slate-500 hover:bg-rose-500 hover:text-white dark:hover:bg-rose-500 dark:hover:text-white hover:scale-110 transition-all shadow-sm group-hover:text-rose-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
             </div>
        </div>
    </div>
    @endforeach
@else
    <div class="bg-white/60 border border-dashed border-indigo-100 rounded-3xl py-16 text-center">
        <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-base font-black text-gray-700 mb-1">Tidak Ada Antrean</h3>
        <p class="text-sm text-gray-400">
            @if($resFilter === 'today') Belum ada antrean untuk hari ini.
            @elseif($resFilter === 'upcoming') Belum ada jadwal mendatang.
            @else Tidak ditemukan data antrean. @endif
        </p>
    </div>
@endif
</div>

@if(isset($semuaReservasi) && $semuaReservasi->hasPages())
    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
        {{ $semuaReservasi->links() }}
    </div>
@endif

{{-- ─── MODAL TAMBAH ANTREAN ─── --}}
<template x-teleport="body">
    <div x-show="showAddModal" x-cloak
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[999] flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>

        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden q-anim" @click.stop>
            {{-- Header --}}
            <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-black text-gray-900">Tambah Antrean Manual</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Input data pasien untuk membuat jadwal baru</p>
                </div>
                <button @click="showAddModal = false"
                    class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-400 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.reservasi.store_admin') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Nama Pasien *</label>
                    <input type="text" name="nama" required placeholder="Contoh: Siti Rahayu"
                           class="q-input font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">No. HP / WhatsApp *</label>
                        <input type="text" name="phone" required placeholder="08xxxxxxxxxx"
                               class="q-input font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Tanggal *</label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                               class="q-input font-semibold">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Layanan *</label>
                        <select name="layanan" required class="q-input q-select font-semibold">
                            <option value="Kontrol Kehamilan">Kontrol Kehamilan</option>
                            <option value="Keluarga Berencana">Keluarga Berencana</option>
                            <option value="Pemeriksaan Umum">Pemeriksaan Umum</option>
                            <option value="Imunisasi">Imunisasi</option>
                            <option value="Konsultasi">Konsultasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Dokter</label>
                        <select name="dokter_id" class="q-input q-select font-semibold">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Keluhan (Opsional)</label>
                    <textarea name="keluhan" rows="3" placeholder="Tulis keluhan jika ada..."
                              class="q-input font-semibold resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                        class="flex-1 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-2xl transition-all text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] py-3 bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white font-black rounded-2xl shadow-lg shadow-teal-500/20 transition-all text-sm">
                        Simpan Antrean
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

{{-- ─── MODAL HAPUS ANTREAN ─── --}}
<template x-teleport="body">
    <div x-show="showDeleteModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[999] flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-gray-900/65 backdrop-blur-sm" @click="showDeleteModal = false"></div>

        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md rounded-2xl shadow-2xl overflow-hidden q-anim" @click.stop>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Hapus Antrean?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                    Yakin ingin menghapus antrean untuk pasien <span class="font-bold text-gray-900 dark:text-white" x-text="itemToDelete.nama"></span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button @click="showDeleteModal = false"
                        class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                               font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-sm">
                        Batal
                    </button>
                    <button @click="executeDelete()"
                        class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-bold rounded-xl
                               hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all text-sm">
                        Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

</div>
