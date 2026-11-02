@extends('layouts.main')

@section('title', 'Inventaris Kendaraan - Mitra')

@section('content')

    {{-- WRAPPER LUAR UNTUK ALPINE JS (TANPA ANIMASI) --}}
    <div x-data="{
        openDetail: false,
        selectedOrder: {},
        activeFilter: 'semua'
    }">

        {{-- CONTAINER UTAMA (ANIMASI HANYA DI SINI) --}}
        <div class="max-w-7xl mx-auto py-2 px-3 space-y-4 animate-fade-in pb-12 mt-2">

            {{-- 1. HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm text-left">
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Penitipan <span class="text-indigo-600">Kendaraan</span></h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kendali Unit Lahan Parkir</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shadow-sm border border-indigo-100">
                        <i class="fas fa-parking text-sm"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-[8px] font-black text-slate-400 uppercase leading-none tracking-widest">Total Lahan</p>
                        <p class="text-lg font-black text-slate-800 leading-none mt-1 italic tracking-tighter">{{ $myLahans->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- 2. HORIZONTAL SCROLL: ANTREAN KEDATANGAN --}}
            <section class="space-y-3">
                <div class="flex items-center justify-between px-1 text-left">
                    <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest italic flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        🏎️ Unit Menuju Lokasi ({{ $incomingOrders->count() }})
                    </h3>
                </div>

                <div class="flex gap-3 overflow-x-auto pb-3 custom-scrollbar snap-x">
                    @forelse($incomingOrders as $order)
                        <div class="min-w-[260px] bg-white border border-slate-100 p-4 md:p-5 rounded-xl shadow-sm hover:shadow-md hover:border-indigo-200 transition-all snap-start group relative overflow-hidden text-left flex flex-col justify-between">
                            <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
                                <i class="fas fa-car-side text-4xl"></i>
                            </div>
                            <div class="relative z-10">
                                <span class="text-[8px] font-black text-indigo-600 italic tracking-widest uppercase bg-indigo-50 px-1.5 py-0.5 rounded">#{{ $order->invoice_number }}</span>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight mt-1.5 truncate italic">
                                    {{ $order->user->name }}
                                </h4>
                                <div class="mt-2 mb-4 space-y-1">
                                    <p class="text-[9px] font-black text-slate-600 uppercase italic">
                                        {{ $order->layanan->items->count() }} Unit Kendaraan
                                    </p>
                                    <span class="px-2 py-1 bg-slate-50 border border-slate-100 rounded-md text-[8px] font-black text-slate-500 uppercase italic tracking-widest inline-block">
                                        📍 Lahan: {{ $order->layanan->lahan->nama_lahan ?? 'Belum Ditentukan' }}
                                    </span>
                                </div>
                                <form action="{{ route('kendaraan.masuk.konfirmasi', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-slate-900 text-white rounded-lg text-[8px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5">
                                        Konfirmasi Unit Sampai ✅
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="w-full py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200 flex flex-col items-center justify-center gap-2">
                            <i class="fas fa-car text-xl text-slate-300"></i>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Tidak Ada Antrean</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- 3. GRID INVENTARIS --}}
            <section class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-3 text-left">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-tight">Unit Parkir Terdata</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Monitoring unit di setiap lahan</p>
                    </div>
                    <div class="flex gap-2 overflow-x-auto pb-1 custom-scrollbar">
                        <button @click="activeFilter = 'semua'"
                            :class="activeFilter === 'semua' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-500 border-slate-200 hover:text-indigo-600 hover:border-indigo-600'"
                            class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest transition-all border whitespace-nowrap">Semua Lahan</button>
                        @foreach ($myLahans as $l)
                            <button @click="activeFilter = '{{ $l->id }}'"
                                :class="activeFilter === '{{ $l->id }}' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-500 border-slate-200 hover:text-indigo-600 hover:border-indigo-600'"
                                class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest transition-all border whitespace-nowrap">{{ $l->nama_lahan }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($myLahans as $lahan)
                        @foreach ($lahan->orderKendaraans as $ok)
                            <div x-show="activeFilter === 'semua' || activeFilter === '{{ $lahan->id }}'" x-transition.opacity
                                class="bg-white border border-slate-100 p-4 md:p-5 rounded-xl shadow-sm hover:shadow-md hover:border-indigo-100 transition-all group text-left flex flex-col justify-between">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="space-y-0.5">
                                            <span class="text-[8px] font-black text-indigo-500 uppercase tracking-widest italic bg-indigo-50 px-1.5 py-0.5 rounded">{{ $lahan->nama_lahan }}</span>
                                            <h4 class="text-sm font-black text-slate-800 uppercase italic leading-none tracking-tight truncate w-36 md:w-44 mt-1">
                                                {{ $ok->order->user->name }}
                                            </h4>
                                        </div>
                                        @php
                                            $tglSelesai = \Carbon\Carbon::parse($ok->tanggal_selesai);
                                            $isOverdue = now()->startOfDay()->gt($tglSelesai);
                                        @endphp
                                        <div class="px-2.5 py-1 {{ $ok->status == 'masa_titip_habis' || $isOverdue ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }} rounded text-[7px] font-black uppercase tracking-widest shadow-sm">
                                            {{ $ok->status == 'masa_titip_habis' || $isOverdue ? 'Overdue ⚠️' : 'Parkir ✅' }}
                                        </div>
                                    </div>

                                    <div class="space-y-2 mb-5 text-left">
                                        @foreach ($ok->items->take(2) as $unit)
                                            <div class="flex justify-between items-center border-b border-slate-50 pb-1.5">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-black text-slate-900 uppercase italic">{{ $unit->nomor_plat }}</span>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $unit->merk_kendaraan }}</span>
                                                </div>
                                                <i class="fas fa-motorcycle text-slate-200 text-xs"></i>
                                            </div>
                                        @endforeach
                                        @if ($ok->items->count() > 2)
                                            <p class="text-[8px] font-black text-indigo-400 uppercase italic tracking-widest mt-1">+ {{ $ok->items->count() - 2 }} Unit Lainnya</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-slate-50 relative z-10">
                                    <div class="flex flex-col text-left">
                                        <span class="text-[8px] font-black text-slate-400 uppercase italic leading-none">Selesai Parkir:</span>
                                        <span class="text-[10px] font-black {{ $ok->status == 'masa_titip_habis' || $isOverdue ? 'text-rose-600 animate-pulse' : 'text-slate-800' }} uppercase mt-1 italic tracking-tighter">
                                            {{ $tglSelesai->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                    <button @click="selectedOrder = {{ json_encode($ok->order->load(['user', 'layanan.items.kategori', 'layanan.lahan'])) }}; openDetail = true"
                                        class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center hover:bg-indigo-600 transition-all shadow-md active:scale-90 group/btn">
                                        <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </section>
        </div> {{-- AKHIR DARI CONTAINER UTAMA (ANIMASI) --}}

        {{-- ========================================== --}}
        {{-- MODAL DETAIL KENDARAAN (DILUAR ANIMASI)    --}}
        {{-- ========================================== --}}
        <div x-show="openDetail" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-transition.opacity x-cloak>
            
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden flex flex-col" @click.away="openDetail = false">
                
                {{-- Header Modal --}}
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-start">
                    <div class="text-left">
                        <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-[8px] font-black uppercase tracking-widest mb-1" x-text="'# ' + selectedOrder.invoice_number"></span>
                        <h2 class="text-lg font-black text-slate-800 uppercase italic tracking-tight leading-none" x-text="selectedOrder.user?.name"></h2>
                    </div>
                    <button @click="openDetail = false" class="w-8 h-8 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all font-black text-sm flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="px-5 py-4 max-h-[60vh] overflow-y-auto custom-scrollbar bg-white text-left">
                    
                    {{-- Alert Denda --}}
                    <div class="mb-4 p-3 bg-rose-50 border border-rose-100 rounded-xl flex justify-between items-center shadow-sm" x-show="selectedOrder.total_denda > 0">
                        <div class="text-left">
                            <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest italic leading-none">Tagihan Denda Telat</p>
                            <h5 class="text-base font-black text-rose-600 italic tracking-tighter mt-1">
                                Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedOrder.total_denda)"></span>
                            </h5>
                        </div>
                        <div class="w-8 h-8 bg-rose-600 text-white rounded-lg flex items-center justify-center animate-pulse shadow-md">
                            <i class="fas fa-exclamation text-[10px]"></i>
                        </div>
                    </div>

                    {{-- Rincian Kendaraan --}}
                    <div class="space-y-2">
                        <h4 class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest pb-1 border-b border-slate-50">Rincian Unit:</h4>
                        <div class="grid grid-cols-1 gap-2">
                            <template x-for="item in selectedOrder.layanan?.items" :key="item.id">
                                <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-100 rounded-xl shadow-sm">
                                    <div class="text-left">
                                        <p class="text-[11px] font-black text-slate-800 uppercase tracking-tight truncate w-36" x-text="item.nomor_plat"></p>
                                        <p class="text-[8px] font-bold text-indigo-500 italic mt-0.5 uppercase tracking-widest" x-text="item.merk_kendaraan"></p>
                                    </div>
                                    <div class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-[9px] font-black italic shadow-sm tracking-widest shrink-0">
                                        UNIT
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
                    <template x-if="selectedOrder.layanan?.status === 'dititip' || selectedOrder.layanan?.status === 'masa_titip_habis'">
                        {{-- RAJA: Gunakan .replace agar Laravel tidak error Missing Parameter --}}
                        <form :action="'{{ route('kendaraan.checkout', ['id' => ':id']) }}'.replace(':id', selectedOrder.id)" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-md hover:bg-emerald-600 transition-all active:scale-95 flex items-center justify-center gap-1.5">
                                Checkout Unit 🏎️
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </div>

    </div> {{-- AKHIR DARI WRAPPER ALPINE JS --}}


    {{-- MODAL QRIS PEMBAYARAN DENDA (DILUAR KEMASAN ANIMASI DAN ALPINE) --}}
    @if (session('show_qris') && session('order_id'))
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in text-center">
            <div class="bg-white w-full max-w-sm rounded-xl shadow-2xl overflow-hidden flex flex-col border-t-4 border-indigo-600">
                
                {{-- Header QRIS --}}
                <div class="p-4 border-b border-slate-100 text-left flex justify-between items-center bg-slate-50">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-tight">Selesaikan Denda</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Scan QRIS untuk pelunasan</p>
                    </div>
                    <button onclick="window.location.reload()" class="w-8 h-8 bg-white border border-slate-200 hover:border-rose-200 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all font-black text-xs flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Body QRIS --}}
                <div class="p-5 bg-white flex flex-col items-center">
                    <div class="p-3 bg-slate-50 rounded-xl shadow-sm border border-slate-200">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ session('qris_payload') }}"
                            class="w-40 h-40 rounded-lg" alt="QRIS Denda Dinamis">
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-[8px] font-black text-slate-400 uppercase italic leading-none tracking-widest">Nominal Denda Overdue:</p>
                        <h4 class="text-xl font-black text-rose-600 italic tracking-tighter mt-1">
                            Rp {{ number_format(session('denda_amount'), 0, ',', '.') }}
                        </h4>
                    </div>
                </div>

                {{-- Footer QRIS --}}
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    <form action="{{ route('kendaraan.checkout', session('order_id')) }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="pembayaran_denda_lunas" value="1">
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-md hover:bg-emerald-600 transition-all active:scale-95 flex items-center justify-center gap-1.5">
                            Konfirmasi Lunas ✅
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 4px; width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection