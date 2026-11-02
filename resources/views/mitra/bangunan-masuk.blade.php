@extends('layouts.main')

@section('title', 'Manajemen Bangunan | Mitra')

@section('content')
    {{-- X-DATA DI-DECLARE DI LEVEL PALING ATAS AGAR MODAL & CONTAINER BISA DIAKSES --}}
    <div x-data="{ openAssign: false, openDetail: false, selectedOrder: {} }">

        {{-- CONTAINER UTAMA --}}
        <div class="max-w-6xl mx-auto py-8 px-6 space-y-8 animate-fade-in pb-12">

            {{-- 1. HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm text-left">
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Penitipan <span class="text-indigo-600">Bangunan</span></h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Monitoring penugasan staff dan pengawasan aset secara real-time</p>
                </div>
            </div>

            {{-- KONTEN UTAMA --}}
            <div class="grid grid-cols-1 gap-10 text-left">

                {{-- SECTION 1: ANTRIAN PENUGASAN (Status: menunggu_penjaga) --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest text-left italic">Antrian Penugasan ({{ $orders->where('layanan.status', 'menunggu_penjaga')->count() }})</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-left">
                        @forelse($orders->where('layanan.status', 'menunggu_penjaga') as $order)
                            <div class="bg-white border border-slate-100 rounded-xl p-5 hover:shadow-md hover:border-indigo-200 transition-all group flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-4 text-left">
                                        <span class="text-[8px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md italic uppercase tracking-widest">#{{ $order->invoice_number }}</span>
                                        <p class="text-[8px] font-bold text-slate-400 italic mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 leading-none mb-3 italic uppercase text-left truncate" title="{{ $order->layanan->items->first()->nama_aset ?? 'Aset Bangunan' }}">
                                        {{ $order->layanan->items->first()->nama_aset ?? 'Aset Bangunan' }}
                                    </h4>

                                    <div class="space-y-2 mb-6 text-left">
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <i class="fas fa-user-circle text-[9px] w-3"></i>
                                            <p class="text-[9px] font-black truncate italic uppercase text-left tracking-widest">{{ $order->user->name }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500 bg-slate-50 p-1.5 rounded border border-slate-100">
                                            <i class="fas fa-map-marker-alt text-[9px] w-3 text-rose-400"></i>
                                            <p class="text-[9px] font-black truncate italic uppercase text-left tracking-widest">{{ $order->layanan->alamatPelanggan->kecamatan ?? 'Lokasi N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <button @click="selectedOrder = {{ json_encode($order->load(['user', 'layanan.alamatPelanggan', 'layanan.items'])) }}; openAssign = true"
                                    class="w-full py-2.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all italic text-center shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                                    Pilih Staff Penjaga <i class="fas fa-user-shield"></i>
                                </button>
                            </div>
                        @empty
                            <div class="col-span-full py-12 border border-dashed border-slate-200 bg-slate-50 rounded-xl text-center flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Tidak ada antrian tugas baru</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <hr class="border-slate-100">

                {{-- SECTION 2: DALAM PENGAWASAN (Status: dalam_pengawasan) --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest text-left italic">Dalam Pengawasan ({{ $orders->where('layanan.status', 'dalam_pengawasan')->count() }})</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-4 text-left">
                        @forelse($orders->where('layanan.status', 'dalam_pengawasan') as $order)
                            <div class="bg-white border border-slate-100 rounded-xl p-5 md:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all text-left">
                                <div class="flex-1 space-y-1 text-left w-full">
                                    <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest italic text-left bg-indigo-50 px-2 py-0.5 rounded inline-block">
                                        #{{ $order->invoice_number }}
                                    </p>
                                    <h4 class="text-base font-black text-slate-800 italic uppercase text-left mt-1.5">
                                        {{ $order->layanan->items->first()->nama_aset ?? 'Aset Bangunan' }}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-3 mt-2 text-left">
                                        <p class="text-[9px] font-black text-slate-500 italic text-left uppercase tracking-widest">
                                            <i class="fas fa-user-shield mr-1 text-emerald-500"></i> Staff: {{ $order->layanan->penjaga->nama_penjaga ?? 'Aktif' }}
                                        </p>
                                        <p class="text-[9px] font-black text-slate-500 italic text-left uppercase tracking-widest">
                                            <i class="fas fa-user-tie mr-1 text-indigo-400"></i> Owner: {{ $order->user->name }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="w-full md:w-px h-[1px] md:h-12 bg-slate-100"></div>
                                
                                <div class="flex-1 text-left w-full">
                                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest leading-none mb-1.5 text-left">Titik Lokasi Pengawasan</p>
                                    <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                        <i class="fas fa-map-marker-alt text-rose-500"></i>
                                        <p class="text-[10px] font-black text-slate-700 truncate uppercase italic text-left">
                                            {{ $order->layanan->alamatPelanggan->kecamatan }} <span class="text-slate-300 mx-1">/</span> {{ $order->layanan->alamatPelanggan->kab_kota }}
                                        </p>
                                    </div>
                                </div>
                                
                                <button @click="selectedOrder = {{ json_encode($order->load(['user', 'layanan.alamatPelanggan', 'layanan.items', 'layanan.penjaga'])) }}; openDetail = true"
                                    class="w-full md:w-auto px-6 py-2.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all italic text-center shadow-md active:scale-95 shrink-0 whitespace-nowrap flex items-center justify-center gap-1.5">
                                    Detail & Selesai <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        @empty
                            <div class="py-12 border border-dashed border-slate-200 bg-slate-50 rounded-xl text-center flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-shield-alt text-2xl text-slate-300"></i>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Belum ada aset dalam pengawasan aktif</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL AREA (DIKELUARKAN DARI CONTAINER UTAMA AGAR OVERLAY TIDAK TERTUTUP) --}}
        {{-- ========================================================================= --}}

        {{-- MODAL 1: TUGASKAN PERSONEL (Assign) --}}
        <div x-show="openAssign" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl relative overflow-hidden text-left border-t-4 border-indigo-600" @click.away="openAssign = false">
                
                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-start bg-slate-50">
                    <div class="text-left">
                        <h2 class="text-sm font-black text-slate-900 uppercase italic text-left tracking-tight">Tugaskan Personel</h2>
                        <p class="text-[8px] font-bold text-indigo-500 uppercase italic mt-0.5 text-left tracking-widest" x-text="selectedOrder.layanan?.items?.[0]?.nama_aset"></p>
                    </div>
                    <button @click="openAssign = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 w-7 h-7 rounded-md flex items-center justify-center transition-colors font-black text-xs shrink-0"><i class="fas fa-times"></i></button>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    <form :action="'/mitra/bangunan-masuk/' + selectedOrder.id + '/assign'" method="POST" class="space-y-4 m-0">
                        @csrf
                        <div class="space-y-1.5 text-left">
                            <label class="text-[8px] font-black text-slate-400 uppercase italic ml-1 tracking-widest text-left">Pilih Staff Penjaga Bangunan:</label>
                            <select name="penjaga_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic focus:border-indigo-600 focus:bg-white outline-none transition-all cursor-pointer text-slate-700">
                                <option value="">-- PILIH PENJAGA --</option>
                                @foreach ($penjagas as $pj)
                                    <option value="{{ $pj->id }}">{{ strtoupper($pj->nama_penjaga) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-md transition-all hover:bg-indigo-700 italic text-center flex items-center justify-center gap-1.5 active:scale-95">
                                Kirim Penjaga <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL 2: DETAIL & SELESAIKAN (Surat Tugas) --}}
        <div x-show="openDetail" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh] text-left border-t-4 border-emerald-500" @click.away="openDetail = false">
                
                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-start bg-slate-50">
                    <div class="text-left">
                        <span class="text-[8px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase italic tracking-widest" x-text="'SURAT TUGAS #' + (selectedOrder.invoice_number || '')"></span>
                        <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight mt-1 text-left">Detail Pengawasan</h2>
                    </div>
                    <button @click="openDetail = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 w-7 h-7 rounded-md flex items-center justify-center transition-colors font-black text-xs shrink-0"><i class="fas fa-times"></i></button>
                </div>

                {{-- Body --}}
                <div class="p-5 overflow-y-auto custom-scrollbar text-left bg-white">
                    <div class="grid grid-cols-2 gap-4 mb-5 text-left">
                        <div class="text-left bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5 italic text-left">Pemilik Aset</p>
                            <p class="text-[10px] font-black text-slate-800 uppercase italic text-left truncate" x-text="selectedOrder.user?.name || ''"></p>
                            <p class="text-[9px] font-bold text-slate-500 mt-0.5 italic text-left" x-text="selectedOrder.user?.no_hp || selectedOrder.user?.phone || '-'"></p>
                        </div>
                        <div class="text-left bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                            <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-0.5 italic text-left">Staff Lapangan</p>
                            <p class="text-[10px] font-black text-slate-800 uppercase italic text-left truncate" x-text="selectedOrder.layanan?.penjaga?.nama_penjaga || 'AKTIF'"></p>
                            <p class="text-[9px] font-bold text-slate-500 mt-0.5 italic text-left" x-text="selectedOrder.layanan?.penjaga?.kontak || '-'"></p>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 mb-2 text-left">
                        <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-1.5 italic text-left">Titik Lokasi Penjagaan:</p>
                        
                        {{-- PERBAIKAN: Diubah jadi alamat_pelanggan (snake_case) --}}
                        <p class="text-[11px] font-black text-slate-700 uppercase italic text-left leading-snug" x-text="selectedOrder.layanan?.alamat_pelanggan?.alamat_lengkap || 'Alamat tidak tersedia'"></p>
                        <p class="text-[9px] font-bold text-slate-500 uppercase italic text-left mt-1.5" x-text="(selectedOrder.layanan?.alamat_pelanggan?.kecamatan || 'N/A') + ' - ' + (selectedOrder.layanan?.alamat_pelanggan?.kab_kota || 'N/A')"></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-white">
                    <form :action="'/mitra/bangunan-masuk/' + selectedOrder.id + '/selesai'" method="POST" class="m-0 text-left">
                        @csrf
                        <button type="submit" onclick="return confirm('Jeder! Apakah tugas penjagaan ini benar-benar telah selesai?')"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all italic shadow-md active:scale-95 text-center flex items-center justify-center gap-1.5">
                            Selesaikan Tugas <i class="fas fa-flag-checkered"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div> {{-- AKHIR DARI WRAPPER ALPINE JS --}}

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