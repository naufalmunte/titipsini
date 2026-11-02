@extends('layouts.main')

@section('title', 'Manajemen Order Angkutan | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-truck-moving text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Jasa <span
                            class="text-indigo-600">Angkutan</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Konfirmasi pesanan masuk
                        untuk memicu tagihan QRIS pelanggan</p>
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


        {{-- TABEL MANAJEMEN ORDER ANGKUTAN --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Permohonan</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pengecekan rute dan muatan
                        jasa angkutan</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-4 py-4 w-28 whitespace-nowrap">Detail Order</th>
                            <th class="px-4 py-4">Data Pelanggan</th>
                            <th class="px-4 py-4">Jadwal & Rute</th>
                            <th class="px-4 py-4 w-32 whitespace-nowrap">Total Bayar</th>
                            <th class="px-4 py-4 text-center w-36">Status</th>
                            <th class="px-4 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                            @php
                                $layanan = $order->layanan;

                                // JEDER! Tambahkan ?-> untuk mencegah error jika $layanan kosong
                                $alamatJemput = $alamats[$layanan?->alamat_jemput_id ?? 0] ?? null;
                                $alamatTujuan = $alamats[$layanan?->alamat_tujuan_id ?? 0] ?? null;

                                // Data Logistik
                                $noHp = $alamatJemput
                                    ? $alamatJemput->telepon
                                    : $order->user->phone ?? 'Tidak ada kontak';
                                $namaPelanggan = $alamatJemput
                                    ? $alamatJemput->nama_penerima
                                    : $order->user->name ?? 'User N/A';
                                $jarakKm = $layanan?->jarak_km ?? '0';
                                $catatanMuatan = $layanan?->catatan_muatan ?? 'Tidak ada catatan muatan';

                                // JEDER! Tameng null-safe (?->) dipasang di sini
                                $tanggalAngkut = $layanan?->tanggal_angkut
                                    ? \Carbon\Carbon::parse($layanan->tanggal_angkut)->format('d M Y')
                                    : '-';
                                $jamAngkut = $layanan?->jam_angkut
                                    ? \Carbon\Carbon::parse($layanan->jam_angkut)->format('H:i')
                                    : '-';
                            @endphp

                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-block px-2 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded text-[9px] font-black tracking-widest uppercase mb-1">
                                        #{{ $order->invoice_number }}
                                    </span>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                        Order: {{ $order->created_at->format('d/m/Y') }}
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
                                    <div class="flex flex-col gap-0.5 overflow-hidden min-w-0">
                                        {{-- Menampilkan Jadwal Angkut --}}
                                        <h4
                                            class="text-[10px] font-black text-slate-800 uppercase italic leading-tight mb-0.5 truncate">
                                            <i class="fas fa-calendar-check text-indigo-500 mr-1"></i> {{ $tanggalAngkut }}
                                            <span class="text-slate-400 font-bold mx-1">•</span> {{ $jamAngkut }} WIB
                                        </h4>
                                        {{-- Menampilkan Jarak & Armada --}}
                                        <p
                                            class="text-[8px] font-bold text-slate-500 uppercase tracking-widest leading-tight truncate max-w-[150px] sm:max-w-full">
                                            {{ $jarakKm }} KM <span
                                                class="text-indigo-400">({{ $layanan?->jenisArmada?->nama_jenis ?? 'Armada' }})</span>
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
                                        {{-- Tombol Detail --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-detail-{{ $order->id }}')"
                                            class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-blue-100 hover:border-blue-600 shrink-0"
                                            title="Detail Pesanan">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </button>

                                        @if ($order->status == 'menunggu_konfirmasi_admin')
                                            {{-- Tombol Terima --}}
                                            <form action="{{ route('admin.order_angkutan.konfirmasi', $order->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menyetujui pesanan angkutan ini?');">
                                                @csrf
                                                <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600 shrink-0"
                                                    title="Terima Pesanan">
                                                    <i class="fas fa-check text-[10px]"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.order_angkutan.tolak', $order->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menolak pesanan angkutan ini?');">
                                                @csrf
                                                <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600 shrink-0"
                                                    title="Tolak Pesanan">
                                                    <i class="fas fa-times text-[10px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- ================= MODAL DETAIL ANGKUTAN ================= --}}
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
                                                        <i class="fas fa-user text-indigo-400"></i> Kontak Logistik
                                                    </h3>
                                                    <p class="text-xs font-black text-slate-800 uppercase italic">
                                                        {{ $namaPelanggan }}</p>
                                                    <p class="text-[10px] font-bold text-slate-500 break-all">
                                                        {{ $order->user->email ?? '-' }}</p>
                                                    <p class="text-[10px] font-bold text-slate-500 mt-1"><i
                                                            class="fas fa-phone-alt mr-1"></i> {{ $noHp }}</p>
                                                </div>

                                                {{-- Detail Muatan --}}
                                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                    <h3
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                                        <i class="fas fa-box-open text-indigo-400"></i> Detail Muatan
                                                    </h3>
                                                    <p class="text-xs font-black text-slate-800 uppercase italic">
                                                        {{ $layanan?->jenisArmada?->nama_jenis ?? 'Armada N/A' }}</p>
                                                    <p
                                                        class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mt-0.5">
                                                        Estimasi Jarak: {{ $jarakKm }} KM</p>
                                                    <p class="text-[9px] font-bold text-slate-500 mt-1 line-clamp-2"
                                                        title="{{ $catatanMuatan }}">{{ $catatanMuatan }}</p>
                                                </div>

                                                {{-- Rute Perjalanan Lengkap --}}
                                                <div
                                                    class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                                                    <h3
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                        <i class="fas fa-route text-indigo-400"></i> Rute Perjalanan
                                                    </h3>
                                                    <div
                                                        class="flex flex-col gap-3 relative before:absolute before:inset-y-0 before:left-[11px] before:w-0.5 before:bg-slate-200">
                                                        <div class="relative pl-8">
                                                            <div
                                                                class="absolute left-0 top-1 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center border-4 border-slate-50 shadow-sm">
                                                                <i class="fas fa-map-marker-alt text-[10px]"></i>
                                                            </div>
                                                            <p
                                                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                                                                Titik Jemput</p>
                                                            <p class="text-[10px] font-black text-slate-700 leading-snug">
                                                                {{ $alamatJemput ? $alamatJemput->alamat_lengkap . ', ' . $alamatJemput->kecamatan . ', ' . $alamatJemput->kab_kota : 'Alamat jemput tidak ditemukan' }}
                                                            </p>
                                                        </div>
                                                        <div class="relative pl-8">
                                                            <div
                                                                class="absolute left-0 top-1 w-6 h-6 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center border-4 border-slate-50 shadow-sm">
                                                                <i class="fas fa-flag-checkered text-[10px]"></i>
                                                            </div>
                                                            <p
                                                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                                                                Titik Tujuan</p>
                                                            <p class="text-[10px] font-black text-slate-700 leading-snug">
                                                                {{ $alamatTujuan ? $alamatTujuan->alamat_lengkap . ', ' . $alamatTujuan->kecamatan . ', ' . $alamatTujuan->kab_kota : 'Alamat tujuan tidak ditemukan' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Jadwal & Biaya --}}
                                                <div
                                                    class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2 flex flex-col md:flex-row justify-between items-center gap-4">
                                                    <div class="text-center md:text-left w-full md:w-auto">
                                                        <h3
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-center md:justify-start gap-2">
                                                            <i class="fas fa-calendar-day text-indigo-400"></i> Jadwal
                                                            Angkut
                                                        </h3>
                                                        <p
                                                            class="text-[10px] font-bold text-slate-700 uppercase tracking-widest">
                                                            {{ $tanggalAngkut }} <span
                                                                class="text-indigo-400 mx-1 font-black italic">•</span>
                                                            {{ $jamAngkut }} WIB
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
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-4 py-16 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-truck-moving text-3xl mb-3 text-slate-300 block"></i>
                                    Belum ada pesanan angkutan masuk.
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
