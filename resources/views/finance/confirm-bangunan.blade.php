@extends('layouts.main')

@section('title', 'Antrean Verifikasi Bangunan | Finance - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER (Dengan Tombol Kembali) --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 md:p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ url('/finance/dashboard') }}" class="w-12 h-12 bg-slate-50 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-xl flex items-center justify-center transition-all shadow-sm border border-slate-100 shrink-0">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Validasi <span class="text-indigo-600">Bangunan</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Antrean pembayaran jasa penjaga aset</p>
                </div>
            </div>
            <div class="shrink-0">
                <span class="px-4 py-2 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">
                    {{ $orders->count() }} Menunggu
                </span>
            </div>
        </div>

        {{-- TABEL ANTREAN PEMBAYARAN BANGUNAN --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">No. Invoice</th>
                            <th class="px-6 py-4">Pelanggan & Info Aset</th>
                            <th class="px-6 py-4 text-right">Total Bayar</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $index => $order)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- Nomor Urut --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[10px] font-black text-slate-400">{{ $index + 1 }}</span>
                                </td>
                                
                                {{-- Invoice --}}
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded text-[9px] font-black uppercase tracking-widest">
                                        #{{ $order->invoice_number }}
                                    </span>
                                </td>

                                {{-- Pelanggan & Info Aset --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 mt-1">
                                            <i class="fas fa-building text-lg group-hover:scale-110 transition-transform"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-black text-slate-800 text-xs tracking-tight uppercase italic leading-none mb-1.5">
                                                {{ $order->user?->name ?? 'User Terhapus' }}
                                            </h3>
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="bg-slate-50 border border-slate-200 text-slate-500 text-[8px] font-bold px-1.5 py-0.5 rounded tracking-widest uppercase">
                                                    <i class="fas fa-home text-indigo-400 mr-0.5"></i> {{ Str::limit($order->layanan->items->first()->nama_aset ?? 'Bangunan', 15) }}
                                                </span>
                                                <span class="bg-slate-50 border border-slate-200 text-slate-500 text-[8px] font-bold px-1.5 py-0.5 rounded tracking-widest uppercase">
                                                    <i class="fas fa-calendar-alt text-emerald-400 mr-0.5"></i> 
                                                    {{ \Carbon\Carbon::parse($order->layanan->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($order->layanan->tanggal_selesai)->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Total Bayar --}}
                                <td class="px-6 py-4 text-right align-middle">
                                    <p class="text-sm font-black text-emerald-600 italic tracking-tighter">
                                        Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                                    </p>
                                </td>

                                {{-- Tombol Proses --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    <a href="{{ route('finance.bangunan-detail', $order->id) }}"
                                        class="inline-flex w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-md active:scale-95 items-center justify-center gap-2">
                                        Proses <i class="fas fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- STATE KOSONG --}}
                            <tr>
                                <td colspan="5" class="p-0">
                                    <div class="bg-white py-16 text-center flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center mb-4">
                                            <i class="fas fa-check-double text-2xl"></i>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight italic mb-1">Antrean Bersih</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tidak ada antrean pembayaran bangunan saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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