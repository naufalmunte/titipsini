@extends('layouts.main')

@section('title', 'Laporan Keuangan | Finance - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-2 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Laporan <span class="text-indigo-600">Keuangan</span></h2>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pantau seluruh arus kas aplikasi Titipsini</p>
            </div>

            <button class="px-4 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase rounded-lg transition-all shadow-md tracking-widest flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-file-download text-xs"></i> Ekspor Laporan
            </button>
        </div>

        {{-- STATISTIK UTAMA (4 KOLOM STANDAR) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- 1. Total Omzet --}}
            <div class="bg-white border border-slate-100 rounded-xl p-4 md:p-5 shadow-sm flex flex-col justify-between group">
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest mb-1">Total Omzet (100%)</p>
                    <h2 class="text-lg font-black text-slate-800 italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2.5 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-money-bill-wave mr-1 text-slate-300"></i> Seluruh Transaksi
                    </span>
                    <i class="fas fa-wallet text-slate-100 text-sm group-hover:text-indigo-50 transition-colors"></i>
                </div>
            </div>

            {{-- 2. Profit Titipsini --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 md:p-5 shadow-md relative overflow-hidden flex flex-col justify-between group">
                <div class="relative z-10 text-left">
                    <p class="text-[8px] font-black text-indigo-300 uppercase italic tracking-widest mb-1">Profit Titipsini (35%)</p>
                    <h2 class="text-lg font-black text-white italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($profitSistem, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2.5 border-t border-slate-800 relative z-10 flex items-center justify-between">
                    <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest">
                        <i class="fas fa-chart-line mr-1"></i> Laba Bersih Sistem
                    </span>
                </div>
                <i class="fas fa-coins absolute -right-2 -bottom-2 text-white/5 text-5xl transform -rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            {{-- 3. Hak Mitra --}}
            <div class="bg-white border border-slate-100 rounded-xl p-4 md:p-5 shadow-sm flex flex-col justify-between group">
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest mb-1">Hak Mitra (65%)</p>
                    <h2 class="text-lg font-black text-emerald-600 italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($totalHakMitra, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2.5 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-users mr-1 text-emerald-400"></i> Kewajiban Mitra
                    </span>
                    <i class="fas fa-hand-holding-usd text-slate-100 text-sm group-hover:text-emerald-50 transition-colors"></i>
                </div>
            </div>

            {{-- 4. Sudah Dicairkan --}}
            <div class="bg-white border border-slate-100 rounded-xl p-4 md:p-5 shadow-sm flex flex-col justify-between group">
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest mb-1">Telah Dicairkan (WD)</p>
                    <h2 class="text-lg font-black text-rose-500 italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($totalDitarik, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2.5 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-check-double mr-1 text-rose-400"></i> Dana Sukses WD
                    </span>
                    <i class="fas fa-arrow-circle-up text-slate-100 text-sm group-hover:text-rose-50 transition-colors"></i>
                </div>
            </div>

        </div>

        {{-- TABEL RIWAYAT TRANSAKSI SELURUH MITRA --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-4 md:p-5 border-b border-slate-100 text-left">
                <h3 class="text-xs font-black italic uppercase text-slate-800 tracking-tight">Riwayat <span class="text-indigo-600">Bagi Hasil</span></h3>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Rekaman distribusi profit transaksi ke seluruh mitra</p>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 w-44">Waktu & Transaksi</th>
                            <th class="px-6 py-4">Mitra & Layanan</th>
                            <th class="px-6 py-4 text-center w-48">Profit Mitra (65%)</th>
                            <th class="px-6 py-4 text-right w-48">Profit Sistem (35%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pendapatans as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- Waktu & Transaksi --}}
                                <td class="px-6 py-4">
                                    <p class="text-[10px] font-black text-slate-800 uppercase italic">
                                        {{ $item->created_at->format('d M Y') }}
                                    </p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded text-[8px] font-black uppercase tracking-widest">
                                        #{{ $item->order->invoice_number ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- Mitra & Layanan --}}
                                <td class="px-6 py-4">
                                    <h4 class="text-xs font-black text-slate-800 uppercase italic tracking-tight">
                                        {{ $item->mitra->name ?? 'Mitra Terhapus' }}
                                    </h4>
                                    <p class="text-[8px] font-black text-indigo-500 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded tracking-widest uppercase mt-1 inline-block">
                                        {{ str_replace('_', ' ', $item->kategori_layanan) }}
                                    </p>
                                </td>

                                {{-- Profit Mitra --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    <span class="text-xs font-black text-slate-700 italic tracking-tighter">
                                        Rp {{ number_format($item->nominal_mitra, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Profit Sistem --}}
                                <td class="px-6 py-4 text-right align-middle">
                                    <span class="text-xs font-black text-emerald-600 italic tracking-tighter">
                                        + Rp {{ number_format($item->nominal_titipsini, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-lg flex items-center justify-center mb-3">
                                            <i class="fas fa-folder-open text-xl"></i>
                                        </div>
                                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight italic mb-0.5">Belum Ada Rekaman</h3>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Belum ada data rekaman distribusi bagi hasil saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if (method_exists($pendapatans, 'hasPages') && $pendapatans->hasPages())
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                        Menampilkan {{ $pendapatans->firstItem() }}-{{ $pendapatans->lastItem() }} dari {{ $pendapatans->total() }} Data
                    </p>
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        @if ($pendapatans->onFirstPage())
                            <span class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-300 cursor-not-allowed shadow-sm">Sebelumnya</span>
                        @else
                            <a href="{{ $pendapatans->previousPageUrl() }}"
                                class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-600 hover:text-indigo-600 hover:border-indigo-300 transition-colors shadow-sm active:scale-95">Sebelumnya</a>
                        @endif

                        @if ($pendapatans->hasMorePages())
                            <a href="{{ $pendapatans->nextPageUrl() }}"
                                class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-600 hover:text-indigo-600 hover:border-indigo-300 transition-colors shadow-sm active:scale-95">Selanjutnya</a>
                        @else
                            <span class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-300 cursor-not-allowed shadow-sm">Selanjutnya</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection