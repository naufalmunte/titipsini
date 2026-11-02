@extends('layouts.main')

@section('title', 'Konfirmasi Order Kendaraan | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-car text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Penitipan <span
                            class="text-indigo-600">Kendaraan</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Manajemen antrean
                        penitipan kendaraan pelanggan</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <span
                    class="px-5 py-3 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Total {{ method_exists($orders, 'total') ? $orders->total() : $orders->count() }} Orderan
                </span>
            </div>
        </div>

        {{-- TABEL MANAJEMEN ORDER KENDARAAN --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Permohonan</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Persetujuan admin sebelum
                        proses pembayaran</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-4 py-4 w-28 whitespace-nowrap">Detail Order</th>
                            <th class="px-4 py-4">Data Pelanggan</th>
                            <th class="px-4 py-4">Unit Kendaraan</th>
                            <th class="px-4 py-4 w-32 whitespace-nowrap">Total Bayar</th>
                            <th class="px-4 py-4 text-center w-36">Status</th>
                            <th class="px-4 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                            @php
                                // JEDER! Ambil Kontak dari Data yang Dilempar Controller
                                $alamatUser = $alamatPelanggans[$order->user_id ?? 0] ?? null;
                                $noHp = $alamatUser ? $alamatUser->telepon : $order->user->phone ?? 'Tidak ada kontak';
                            @endphp

                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-block px-2 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded text-[9px] font-black tracking-widest uppercase mb-1">
                                        #{{ $order->invoice_number }}
                                    </span>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $order->created_at->format('d M Y') }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center font-black shadow-md text-xs uppercase italic shrink-0">
                                            {{ substr($order->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden min-w-0">
                                            <h4 class="text-[10px] font-black text-slate-800 uppercase italic truncate">
                                                {{ $alamatUser->nama_penerima ?? ($order->user->name ?? 'User N/A') }}</h4>
                                            <p
                                                class="text-[8px] font-bold text-slate-500 lowercase tracking-widest mt-0.5 truncate">
                                                {{ $order->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-0.5 overflow-hidden min-w-0">
                                        <span
                                            class="text-[10px] font-black text-slate-800 uppercase italic leading-tight truncate">
                                            {{ $order->layanan?->items?->count() ?? 0 }} <span class="text-indigo-600">Unit
                                                Terdaftar</span>
                                        </span>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-tight truncate max-w-[150px] sm:max-w-full"
                                            title="{{ $order->layanan?->catatan_pelanggan }}">
                                            <i class="fas fa-comment-alt mr-1 text-slate-300"></i>
                                            {{ \Illuminate\Support\Str::limit($order->layanan?->catatan_pelanggan ?? 'Tidak ada catatan', 20) }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    <p class="text-[11px] font-black italic tracking-wide text-emerald-600">Rp
                                        {{ number_format($order->total_bayar, 0, ',', '.') }}</p>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    @php
                                        $statusStyle =
                                            [
                                                'menunggu_konfirmasi_admin' =>
                                                    'bg-amber-50 text-amber-600 border-amber-200 animate-pulse',
                                                'menunggu_pembayaran' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                'menunggu_konfirmasi_pembayaran' =>
                                                    'bg-indigo-50 text-indigo-600 border-indigo-200',
                                                'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                'ditolak' => 'bg-rose-50 text-rose-600 border-rose-200',
                                            ][$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';

                                        $statusText = str_replace('_', ' ', $order->status);
                                    @endphp
                                    <span
                                        class="inline-block px-2 py-1 {{ $statusStyle }} text-[8px] font-black uppercase rounded border tracking-widest text-center whitespace-nowrap">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        {{-- Tombol Detail (Mata Biru) --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-detail-{{ $order->id }}')"
                                            class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-blue-100 hover:border-blue-600 shrink-0"
                                            title="Detail Pesanan">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </button>

                                        @if ($order->status == 'menunggu_konfirmasi_admin')
                                            {{-- Tombol Terima --}}
                                            <form action="{{ route('admin.order_kendaraan.konfirmasi', $order->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menyetujui pesanan ini?');">
                                                @csrf
                                                <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600 shrink-0"
                                                    title="Terima Pesanan">
                                                    <i class="fas fa-check text-[10px]"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.order_kendaraan.tolak', $order->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menolak pesanan ini?');">
                                                @csrf
                                                <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600 shrink-0"
                                                    title="Tolak Pesanan">
                                                    <i class="fas fa-times text-[10px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- ================= MODAL DETAIL KENDARAAN ================= --}}
                                    <x-modal name="modal-detail-{{ $order->id }}" maxWidth="2xl" focusable>
                                        <div class="p-6 text-left bg-white rounded-2xl w-full">
                                            <div
                                                class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4">
                                                <div>
                                                    <h2
                                                        class="text-lg font-black text-slate-900 uppercase tracking-tight italic">
                                                        Detail <span class="text-indigo-600">Pesanan</span></h2>
                                                    <p
                                                        class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                        Invoice: <span
                                                            class="text-indigo-500">#{{ $order->invoice_number }}</span>
                                                    </p>
                                                </div>
                                                <button type="button" x-on:click="$dispatch('close')"
                                                    class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full font-black transition-colors">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 whitespace-normal">
                                                {{-- Data Pelanggan --}}
                                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                    <h3
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                                        <i class="fas fa-user text-indigo-400"></i> Data Pelanggan
                                                    </h3>
                                                    <p class="text-xs font-black text-slate-800 uppercase italic">
                                                        {{ $alamatUser->nama_penerima ?? ($order->user->name ?? 'User N/A') }}
                                                    </p>
                                                    <p class="text-[10px] font-bold text-slate-500 break-all">
                                                        {{ $order->user->email ?? '-' }}</p>
                                                    <p class="text-[10px] font-bold text-slate-500 mt-1"><i
                                                            class="fas fa-phone-alt mr-1"></i> {{ $noHp }}</p>
                                                </div>

                                                {{-- Daftar Kendaraan --}}
                                                <div
                                                    class="bg-slate-50 p-4 rounded-xl border border-slate-100 overflow-y-auto max-h-32">
                                                    <h3
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                                        <i class="fas fa-car text-indigo-400"></i> Daftar Unit
                                                    </h3>
                                                    <ul class="space-y-2">
                                                        @forelse($order->layanan?->items ?? [] as $kendaraan)
                                                            <li
                                                                class="flex justify-between items-center bg-white p-2 rounded-lg border border-slate-100">
                                                                {{-- JEDER! Fallback jika nama kolom di databasemu bukan 'merk' atau 'plat_nomor' --}}
                                                                <span
                                                                    class="text-[10px] font-black text-slate-800 uppercase italic">
                                                                    {{ $kendaraan->merk ?? ($kendaraan->nama_kendaraan ?? ($kendaraan->jenis_kendaraan ?? 'Merk N/A')) }}
                                                                </span>
                                                                <span
                                                                    class="text-[9px] font-bold text-indigo-500 tracking-widest">
                                                                    {{ $kendaraan->nomor_plat ?? ($kendaraan->plat ?? ($kendaraan->nopol ?? 'Plat N/A')) }}
                                                                </span>
                                                            </li>
                                                        @empty
                                                            <li class="text-[10px] font-bold text-slate-500">Tidak ada
                                                                detail kendaraan.</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                {{-- Durasi & Biaya --}}
                                                <div
                                                    class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2 flex flex-col md:flex-row justify-between items-center gap-4">
                                                    <div class="text-center md:text-left w-full md:w-auto">
                                                        <h3
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-center md:justify-start gap-2">
                                                            <i class="fas fa-clock text-indigo-400"></i> Estimasi Durasi
                                                        </h3>
                                                        <p
                                                            class="text-[10px] font-bold text-slate-700 uppercase tracking-widest">
                                                            {{ $order->layanan?->tanggal_mulai ? \Carbon\Carbon::parse($order->layanan->tanggal_mulai)->format('d M Y') : '-' }}
                                                            <span class="text-rose-500 mx-2 font-black italic">s/d</span>
                                                            {{ $order->layanan?->tanggal_selesai ? \Carbon\Carbon::parse($order->layanan->tanggal_selesai)->format('d M Y') : '-' }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="text-center md:text-right w-full md:w-auto p-3 md:p-0 bg-white md:bg-transparent rounded-lg border md:border-none border-slate-200">
                                                        <h3
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                                            Total Tagihan</h3>
                                                        <p class="text-xl font-black text-emerald-600 italic">Rp
                                                            {{ number_format($order->total_bayar, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>

                                                {{-- Catatan --}}
                                                @if ($order->layanan?->catatan_pelanggan)
                                                    <div
                                                        class="bg-amber-50 p-4 rounded-xl border border-amber-100 md:col-span-2">
                                                        <h3
                                                            class="text-[9px] font-black text-amber-500 uppercase tracking-widest mb-1 flex items-center gap-2">
                                                            <i class="fas fa-comment-dots"></i> Catatan Pelanggan
                                                        </h3>
                                                        <p
                                                            class="text-[10px] font-bold text-amber-700 italic leading-relaxed">
                                                            {{ $order->layanan->catatan_pelanggan }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-4 py-16 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-car-side text-3xl mb-3 text-slate-300 block"></i>
                                    Belum ada antrean order kendaraan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if (method_exists($orders, 'links') && $orders->hasPages())
                <div class="px-4 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- ANIMATION STYLE PACK --}}
    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
