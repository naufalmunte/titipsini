@extends('layouts.main')

@section('title', 'Manajemen Order Penitipan Barang| Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Penitipan <span
                            class="text-indigo-600">Barang</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Daftar permintaan
                        penitipan barang pelanggan</p>
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


        {{-- TABEL MANAJEMEN ORDER GUDANG --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Permohonan</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Konfirmasi pesanan sebelum
                        pembayaran</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-4 py-4 w-28 whitespace-nowrap">Invoice</th>
                            <th class="px-4 py-4">Data Pemohon</th>
                            <th class="px-4 py-4">Estimasi Barang</th>
                            <th class="px-4 py-4 w-32 whitespace-nowrap">Total Bayar</th>
                            <th class="px-4 py-4 text-center w-36">Status</th>
                            <th class="px-4 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                            @php
                                $layanan = $order->orderGudang ?? $order->layanan;

                                // Bersihkan Underscore
                                $opsiJemputRaw = $layanan?->opsi_penjemputan ?? 'Tidak diketahui';
                                $opsiJemput = str_replace('_', ' ', $opsiJemputRaw);

                                // LOGIKA ANTI-KOSONG
                                $alamatUtama = $alamatPelanggans[$order->user_id ?? 0] ?? null;
                                $alamatJemputObj = null;

                                if (isset($alamatSpesifik) && $layanan?->alamat_id) {
                                    $alamatJemputObj = $alamatSpesifik[$layanan->alamat_id] ?? null;
                                }

                                // Gunakan alamat gudang spesifik, kalau kosong kembali ke alamat utama
                                $alamatAktif = $alamatJemputObj ?? $alamatUtama;

                                $noHp = $alamatAktif->telepon ?? ($order->user->phone ?? 'Tidak ada kontak');
                                $namaPelanggan = $alamatAktif->nama_penerima ?? ($order->user->name ?? 'User N/A');

                                $jumlahBarang = $layanan?->items?->count() ?? 0;
                            @endphp

                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-block px-2 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded text-[9px] font-black tracking-widest uppercase shadow-sm mb-1">
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
                                            {{ substr($namaPelanggan, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden min-w-0">
                                            <h4 class="text-[10px] font-black text-slate-800 uppercase italic truncate">
                                                {{ $namaPelanggan }}</h4>
                                            <p
                                                class="text-[8px] font-bold text-slate-500 lowercase tracking-widest mt-0.5 truncate">
                                                {{ $order->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    @if ($layanan)
                                        <div class="flex flex-col gap-0.5 overflow-hidden min-w-0">
                                            <h4
                                                class="text-[10px] font-black text-slate-800 uppercase italic leading-tight mb-0.5 truncate">
                                                {{ $jumlahBarang }} <span class="text-indigo-600">Kategori Barang</span>
                                            </h4>
                                            <p
                                                class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-tight truncate">
                                                <i class="fas fa-truck-loading mr-1 text-slate-300"></i> Opsi: <span
                                                    class="text-slate-600 font-black">{{ $opsiJemput }}</span>
                                            </p>
                                        </div>
                                    @else
                                        <span
                                            class="text-[9px] font-black text-rose-500 uppercase tracking-widest italic border border-rose-100 bg-rose-50 px-2 py-1 rounded">Data
                                            Layanan Hilang</span>
                                    @endif
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
                                        {{-- Tombol Detail --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-detail-{{ $order->id }}')"
                                            class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-blue-100 hover:border-blue-600 shrink-0"
                                            title="Detail Pesanan">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </button>

                                        @if ($order->status === 'menunggu_konfirmasi_admin')
                                            {{-- Tombol Terima --}}
                                            <form action="{{ route('admin.order-gudang.confirm', $order->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menyetujui pesanan gudang ini?');">
                                                @csrf
                                                <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600 shrink-0"
                                                    title="Setujui Pesanan">
                                                    <i class="fas fa-check text-[10px]"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.order-gudang.tolak', $order->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menolak pesanan gudang ini?');">
                                                @csrf
                                                <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600 shrink-0"
                                                    title="Tolak Pesanan">
                                                    <i class="fas fa-times text-[10px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- ================= MODAL DETAIL GUDANG ================= --}}
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
                                                {{-- Kiri: Data Pelanggan --}}
                                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 h-full">
                                                    <h3
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                                        <i class="fas fa-user text-indigo-400"></i> Data Pemohon
                                                    </h3>
                                                    <p class="text-xs font-black text-slate-800 uppercase italic">
                                                        {{ $namaPelanggan }}</p>
                                                    <p class="text-[10px] font-bold text-slate-500 break-all">
                                                        {{ $order->user->email ?? '-' }}</p>
                                                    <p class="text-[10px] font-bold text-slate-500 mt-1"><i
                                                            class="fas fa-phone-alt mr-1"></i> {{ $noHp }}</p>

                                                    {{-- JEDER! Alamat selalu muncul apapun opsinya --}}
                                                    <div class="mt-3 pt-3 border-t border-slate-200">
                                                        <p
                                                            class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mb-1">
                                                            <i class="fas fa-map-marker-alt mr-1"></i> Detail Alamat:
                                                        </p>
                                                        @if ($alamatAktif)
                                                            <p
                                                                class="text-[10px] font-black text-slate-700 leading-relaxed uppercase italic">
                                                                {{ $alamatAktif->alamat_lengkap ?? 'Alamat Belum Lengkap' }},
                                                                {{ $alamatAktif->kecamatan ?? '-' }},
                                                                {{ $alamatAktif->kab_kota ?? '-' }}
                                                            </p>
                                                        @else
                                                            <p
                                                                class="text-[10px] font-black text-rose-500 leading-relaxed uppercase italic">
                                                                ALAMAT BELUM DIISI / TIDAK DITEMUKAN
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kanan: Detail Barang & Metode --}}
                                                <div class="flex flex-col gap-3">
                                                    {{-- Box Metode --}}
                                                    <div
                                                        class="bg-indigo-50 p-3 rounded-xl border border-indigo-100 flex items-center justify-between shadow-sm">
                                                        <span
                                                            class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest"><i
                                                                class="fas fa-truck-loading mr-1"></i> Metode:</span>
                                                        <span
                                                            class="text-[10px] font-black text-indigo-700 uppercase tracking-widest italic">{{ $opsiJemput }}</span>
                                                    </div>

                                                    {{-- Box Barang Titipan --}}
                                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex-1">
                                                        <h3
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                            <i class="fas fa-boxes text-indigo-400"></i> Daftar Barang
                                                            Titipan
                                                        </h3>
                                                        <ul class="space-y-2">
                                                            @forelse($layanan?->items ?? [] as $barang)
                                                                <li
                                                                    class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-slate-100 shadow-sm">
                                                                    <span
                                                                        class="text-[10px] font-black text-slate-800 uppercase italic">
                                                                        {{ $barang->nama_barang_spesifik ?? 'Barang N/A' }}
                                                                    </span>
                                                                    <span
                                                                        class="text-[10px] font-black text-emerald-600 tracking-widest bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-md">
                                                                        {{ $barang->jumlah ?? 0 }} Unit
                                                                    </span>
                                                                </li>
                                                            @empty
                                                                <li
                                                                    class="text-[10px] font-bold text-slate-500 text-center py-2">
                                                                    Tidak ada detail barang.</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- Bawah: Jadwal Penitipan & Biaya --}}
                                                <div
                                                    class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2 flex flex-col md:flex-row justify-between items-center gap-4">
                                                    <div class="text-center md:text-left w-full md:w-auto">
                                                        <h3
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-center md:justify-start gap-2">
                                                            <i class="fas fa-calendar-alt text-indigo-400"></i> Masa
                                                            Penitipan
                                                        </h3>
                                                        <p
                                                            class="text-[10px] font-bold text-slate-700 uppercase tracking-widest">
                                                            {{ $layanan?->tanggal_mulai ? \Carbon\Carbon::parse($layanan->tanggal_mulai)->format('d M Y') : '-' }}
                                                            <span class="text-rose-500 mx-2 font-black italic">s/d</span>
                                                            {{ $layanan?->tanggal_selesai ? \Carbon\Carbon::parse($layanan->tanggal_selesai)->format('d M Y') : '-' }}
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

                                                {{-- Bawah: Catatan Pelanggan --}}
                                                @if ($layanan?->catatan_pelanggan)
                                                    <div
                                                        class="bg-amber-50 p-4 rounded-xl border border-amber-100 md:col-span-2">
                                                        <h3
                                                            class="text-[9px] font-black text-amber-500 uppercase tracking-widest mb-1 flex items-center gap-2">
                                                            <i class="fas fa-comment-dots"></i> Catatan Pelanggan
                                                        </h3>
                                                        <p
                                                            class="text-[10px] font-bold text-amber-700 italic leading-relaxed">
                                                            {{ $layanan->catatan_pelanggan }}</p>
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
                                    <i class="fas fa-folder-open text-3xl mb-3 text-slate-300 block"></i>
                                    Belum ada pesanan gudang baru yang masuk.
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
