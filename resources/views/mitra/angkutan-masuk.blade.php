@extends('layouts.main')

@section('title', 'Logistik Dashboard | Mitra')

@section('content')

    {{-- WRAPPER LUAR UNTUK ALPINE JS (TANPA ANIMASI) --}}
    <div x-data="{ openDetail: false, selectedOrder: {} }">

        {{-- CONTAINER UTAMA (ANIMASI HANYA DI SINI) --}}
        <div class="max-w-6xl mx-auto py-8 px-6 space-y-8 animate-fade-in pb-12">

            {{-- HEADER: CLEAN & BOLD --}}
            <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6 text-left">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight italic">Manajemen <span class="text-indigo-600">Angkutan</span></h1>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest italic text-left">Monitoring dan eksekusi pengiriman barang secara real-time.</p>
                </div>
            </div>

            {{-- KONTEN UTAMA --}}
            <div class="grid grid-cols-1 gap-10 text-left">

                {{-- SECTION 1: ANTRIAN TUGAS BARU --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest text-left italic">Antrian Jemputan ({{ $tugas->where('status', 'menunggu_jemputan')->count() }})</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($tugas->where('status', 'menunggu_jemputan') as $t)
                            <div class="bg-white border border-slate-100 rounded-xl p-5 hover:shadow-md hover:border-indigo-200 transition-all group flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="text-[8px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md italic uppercase tracking-widest">#{{ $t->order->invoice_number }}</span>
                                        <p class="text-[8px] font-bold text-slate-400 italic mt-0.5">{{ $t->created_at->diffForHumans() }}</p>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 leading-none mb-3 italic uppercase text-left truncate" title="{{ $t->order->user->name }}">{{ $t->order->user->name }}</h4>

                                    <div class="space-y-2 mb-6">
                                        <div class="flex items-center gap-2 text-slate-500 bg-slate-50 p-1.5 rounded border border-slate-100">
                                            <i class="fas fa-map-pin text-[9px] w-3 text-rose-400"></i>
                                            <p class="text-[9px] font-black truncate italic uppercase text-left tracking-widest">{{ $t->alamatJemput->kecamatan ?? 'Lokasi N/A' }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <i class="fas fa-clock text-[9px] w-3 text-indigo-400"></i>
                                            <p class="text-[9px] font-black truncate italic uppercase text-left tracking-widest">{{ $t->jam_angkut }} WIB</p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('mitra.angkutan-masuk.jemput', $t->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all italic text-center shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                                        Konfirmasi Jemput <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="col-span-full py-12 border border-dashed border-slate-200 bg-slate-50 rounded-xl text-center flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Tidak ada tugas baru</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <hr class="border-slate-100">

                {{-- SECTION 2: TUGAS AKTIF --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest text-left italic">Sedang Diproses ({{ $tugas->where('status', 'proses_angkut')->count() }})</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @forelse($tugas->where('status', 'proses_angkut') as $t)
                            <div class="bg-white border border-slate-100 rounded-xl p-5 md:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 shadow-sm hover:shadow-md transition-shadow">
                                
                                <div class="flex-1 space-y-1 text-left w-full">
                                    <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest italic text-left bg-indigo-50 px-2 py-0.5 rounded inline-block">ID: #{{ $t->order->invoice_number }}</p>
                                    <h4 class="text-base font-black text-slate-800 italic uppercase text-left mt-1.5">{{ $t->order->user->name }}</h4>
                                    <div class="flex flex-wrap items-center gap-3 mt-2 text-left">
                                        <p class="text-[9px] font-black text-slate-500 italic text-left uppercase tracking-widest">
                                            <i class="fas fa-phone-alt mr-1 text-emerald-500"></i> {{ $t->order->user->phone ?? '-' }}
                                        </p>
                                        <p class="text-[9px] font-black text-slate-500 italic text-left uppercase tracking-widest">
                                            <i class="fas fa-truck mr-1 text-indigo-400"></i> {{ $t->jenisArmada->nama_jenis }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="w-full md:w-px h-[1px] md:h-12 bg-slate-100"></div>
                                
                                <div class="flex-1 text-left w-full">
                                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest leading-none mb-1.5 text-left">Rute Pengiriman</p>
                                    <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                        <p class="text-[10px] font-black text-slate-700 truncate uppercase italic text-left flex items-center">
                                            {{ $t->alamatJemput->kecamatan }} 
                                            <i class="fas fa-arrow-right mx-2 text-[8px] text-indigo-400"></i> 
                                            {{ $t->alamatTujuan->kecamatan }}
                                        </p>
                                    </div>
                                </div>
                                
                                <button @click="selectedOrder = {{ json_encode($t->load(['order.user', 'alamatJemput', 'alamatTujuan', 'jenisArmada'])) }}; openDetail = true"
                                    class="w-full md:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-md active:scale-95 shrink-0 whitespace-nowrap flex items-center justify-center gap-1.5">
                                    Detail Tugas <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        @empty
                            <div class="py-12 border border-dashed border-slate-200 bg-slate-50 rounded-xl text-center flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-truck text-2xl text-slate-300"></i>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Belum ada tugas yang diproses</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL AREA (DIKELUARKAN DARI CONTAINER UTAMA AGAR OVERLAY TIDAK TERTUTUP) --}}
        {{-- ========================================================================= --}}

        {{-- MODAL DETAIL: SURAT JALAN --}}
        <div x-show="openDetail" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh] text-left border-t-4 border-indigo-600" @click.away="openDetail = false">
                
                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-start bg-slate-50">
                    <div class="text-left">
                        <span class="text-[8px] font-black text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded uppercase italic tracking-widest" x-text="'SURAT JALAN #' + (selectedOrder.order?.invoice_number || '')"></span>
                        <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight mt-1 text-left">Dokumen Resmi Titipsini</h2>
                    </div>
                    <button @click="openDetail = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 w-7 h-7 rounded-md flex items-center justify-center transition-colors font-black text-xs shrink-0"><i class="fas fa-times"></i></button>
                </div>

                {{-- Body --}}
                <div class="p-5 overflow-y-auto custom-scrollbar text-left bg-white">
                    <div class="grid grid-cols-2 gap-4 mb-5 text-left">
                        <div class="text-left bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5 italic text-left">Pelanggan</p>
                            <p class="text-[10px] font-black text-slate-800 uppercase italic text-left truncate" x-text="selectedOrder.order?.user?.name || ''"></p>
                            <p class="text-[9px] font-bold text-slate-500 mt-0.5 italic text-left" x-text="selectedOrder.order?.user?.phone || selectedOrder.order?.user?.no_hp || '-'"></p>
                        </div>
                        <div class="text-left bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                            <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-0.5 italic text-left">Logistik</p>
                            <p class="text-[10px] font-black text-slate-800 uppercase italic text-left truncate" x-text="selectedOrder.jenis_armada?.nama_jenis || ''"></p>
                            <p class="text-[9px] font-bold text-slate-500 mt-0.5 italic text-left" x-text="(selectedOrder.jarak_km || '0') + ' KM Jarak Tempuh'"></p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-5">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-left">
                            <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest mb-1 italic text-left">Titik Penjemputan:</p>
                            <p class="text-[10px] font-black text-slate-700 uppercase italic text-left leading-snug" x-text="selectedOrder.alamat_jemput?.alamat_lengkap || 'Alamat tidak tersedia'"></p>
                        </div>
                        
                        <div class="flex justify-center -my-3 relative z-10">
                            <div class="bg-white p-1 rounded-full"><i class="fas fa-arrow-down text-indigo-300"></i></div>
                        </div>

                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 text-left">
                            <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mb-1 italic text-left">Titik Tujuan:</p>
                            <p class="text-[10px] font-black text-slate-700 uppercase italic text-left leading-snug" x-text="selectedOrder.alamat_tujuan?.alamat_lengkap || 'Alamat tidak tersedia'"></p>
                        </div>
                    </div>

                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-left" x-show="selectedOrder.catatan_muatan">
                        <p class="text-[8px] font-black text-amber-600 uppercase tracking-widest mb-1 italic text-left">Catatan Khusus:</p>
                        <p class="text-[10px] font-bold text-amber-800 uppercase italic text-left" x-text="selectedOrder.catatan_muatan"></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-white">
                    <form :action="'/mitra/angkutan-masuk/' + selectedOrder.id + '/selesai'" method="POST" class="m-0 text-left">
                        @csrf
                        <button type="submit" onclick="return confirm('Jeder! Apakah tugas pengiriman ini benar-benar telah selesai?')"
                            class="w-full py-2.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all italic shadow-md active:scale-95 text-center flex items-center justify-center gap-1.5">
                            Tandai Tugas Selesai <i class="fas fa-flag-checkered"></i>
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