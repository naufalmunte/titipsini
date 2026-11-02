@extends('layouts.main')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-4xl mx-auto py-4 px-3 mb-20 animate-fade-in" x-data="bookingModal()">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div class="flex items-center gap-4 mb-6 text-left">
            <a href="{{ route('pelanggan.dashboard') }}"
                class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-300 transition-all shadow-sm active:scale-95 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic leading-none">Pesanan <span
                        class="text-indigo-600">Saya</span></h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Histori dan manajemen layanan
                </p>
            </div>
        </div>

        {{-- TABS NAVIGATION STICKY PREMIUM --}}
        <div
            class="flex border border-slate-100 rounded-2xl p-1.5 mb-6 overflow-x-auto no-scrollbar bg-white sticky top-2 z-20 shadow-sm gap-1 text-left">
            <button @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'"
                class="px-5 py-2.5 class-b-tabs text-[10px] font-black rounded-xl uppercase tracking-widest whitespace-nowrap transition-all italic flex-1 text-center focus:outline-none">
                Semua
            </button>
            <button @click="activeTab = 'OrderGudang'"
                :class="activeTab === 'OrderGudang' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' :
                    'text-slate-500 hover:bg-slate-50'"
                class="px-5 py-2.5 class-b-tabs text-[10px] font-black rounded-xl uppercase tracking-widest whitespace-nowrap transition-all italic flex-1 text-center focus:outline-none">
                📦 Barang
            </button>
            <button @click="activeTab = 'OrderAngkutan'"
                :class="activeTab === 'OrderAngkutan' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' :
                    'text-slate-500 hover:bg-slate-50'"
                class="px-5 py-2.5 class-b-tabs text-[10px] font-black rounded-xl uppercase tracking-widest whitespace-nowrap transition-all italic flex-1 text-center focus:outline-none">
                🚚 Angkutan
            </button>
            <button @click="activeTab = 'OrderKendaraan'"
                :class="activeTab === 'OrderKendaraan' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' :
                    'text-slate-500 hover:bg-slate-50'"
                class="px-5 py-2.5 class-b-tabs text-[10px] font-black rounded-xl uppercase tracking-widest whitespace-nowrap transition-all italic flex-1 text-center focus:outline-none">
                🛵 Kendaraan
            </button>
            <button @click="activeTab = 'OrderBangunan'"
                :class="activeTab === 'OrderBangunan' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' :
                    'text-slate-500 hover:bg-slate-50'"
                class="px-5 py-2.5 class-b-tabs text-[10px] font-black rounded-xl uppercase tracking-widest whitespace-nowrap transition-all italic flex-1 text-center focus:outline-none">
                🏛️ Bangunan
            </button>
        </div>
        {{-- List Pesanan --}}
        <div class="space-y-3">
            @forelse($bookings as $b)
                @php
                    $type = class_basename($b->layanan_type);
                    $isExpired = false;
                    $hariTelat = 0;

                    // Ambil tanggal batas
                    $tglBatas =
                        $b->layanan->tanggal_selesai ??
                        ($b->layanan->tanggal_mulai ?? ($b->layanan->tanggal_angkut ?? null));

                    // STATUS YANG AMAN (TIDAK BOLEH KENA NOTIF TELAT)
                    $statusAman = ['selesai', 'dibatalkan', 'dibatalkan_otomatis', 'pembayaran_ditolak'];

                    // Cek jika ada tanggal batas DAN statusnya BUKAN dibatalkan/selesai
                    if ($tglBatas && !in_array(strtolower(trim($b->status)), $statusAman)) {
                        $tgl = \Carbon\Carbon::parse($tglBatas)->startOfDay();
                        $sekarang = now()->startOfDay();

                        // Hanya dihitung telat jika hari ini sudah benar-benar melewati tanggal batas (minimal telat 1 hari)
                        if ($sekarang->gt($tgl)) {
                            $isExpired = true;
                            $hariTelat = $tgl->diffInDays($sekarang);
                        }
                    }
                @endphp

                <div x-show="activeTab === 'semua' || activeTab === '{{ $type }}'" x-transition.opacity
                    class="bg-white border {{ $isExpired ? 'border-rose-200' : 'border-slate-200' }} overflow-hidden transition-all hover:shadow-sm rounded-xl">

                    {{-- Card Header --}}
                    <div
                        class="px-4 py-2 border-b border-slate-100 flex justify-between items-center {{ $isExpired ? 'bg-rose-50/50' : 'bg-slate-50/50' }}">
                        <span class="text-[10px] font-black text-black uppercase italic">#{{ $b->invoice_number }}</span>

                        <div class="text-[10px] font-black uppercase tracking-widest italic text-right">
                            @if ($b->status == 'menunggu_pembayaran')
                                <span class="text-rose-600">Belum Bayar</span>
                            @elseif($b->status == 'menunggu_konfirmasi_pembayaran')
                                <span class="text-amber-500 animate-pulse">Verifikasi Finance</span>
                            @elseif($b->status == 'menunggu_konfirmasi_admin')
                                <span class="text-indigo-600 animate-pulse">Menunggu Admin</span>
                            @elseif($b->status == 'diproses')
                                <span class="text-indigo-600">Sedang Diproses</span>
                            @elseif($b->status == 'selesai')
                                <span class="text-emerald-500">Selesai</span>
                            @elseif(in_array(strtolower(trim($b->status)), ['dibatalkan', 'dibatalkan_otomatis']))
                                <span class="text-rose-600">Dibatalkan</span>
                            @else
                                <span class="text-black">{{ str_replace('_', ' ', $b->status) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-3.5 flex gap-4 cursor-pointer text-left"
                        @click="openModalWithData({{ $b->toJson() }}, {{ $hariTelat }})">
                        <div
                            class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-lg flex-shrink-0 flex items-center justify-center text-black text-lg">
                            @if ($type == 'OrderAngkutan')
                                🚚
                            @elseif($type == 'OrderKendaraan')
                                🛵
                            @elseif($type == 'OrderBangunan')
                                🏛️
                            @else
                                📦
                            @endif
                        </div>

                        <div class="flex-1 min-w-0 flex justify-between items-center">
                            <div>
                                <h3 class="text-xs font-black text-black uppercase tracking-tight">
                                    @if ($type == 'OrderAngkutan')
                                        JASA ANGKUTAN LOGISTIK
                                    @elseif($type == 'OrderKendaraan')
                                        PENITIPAN KENDARAAN
                                    @elseif($type == 'OrderBangunan')
                                        PENJAGAAN ASET BANGUNAN
                                    @else
                                        PENITIPAN BARANG GUDANG
                                    @endif
                                </h3>
                                <p class="text-[9px] text-slate-500 uppercase font-black italic">
                                    @if ($type == 'OrderAngkutan')
                                        Jarak: {{ $b->layanan?->jarak_km ?? 0 }} KM
                                    @elseif($type == 'OrderKendaraan')
                                        Total:
                                        {{ collect($b->layanan?->kendaraans ?? ($b->layanan?->items ?? [1]))->count() }}
                                        Unit
                                    @elseif($type == 'OrderBangunan')
                                        Lokasi: {{ $b->layanan?->kecamatan ?? 'Kecamatan' }}
                                    @else
                                        Total: {{ collect($b->layanan?->items ?? [])->count() }} Kategori Barang
                                    @endif
                                </p>
                                @if ($isExpired)
                                    <p class="text-[9px] text-rose-600 font-black uppercase italic animate-pulse">TELAT
                                        {{ $hariTelat }} HARI</p>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-black text-black italic block">Rp
                                    {{ number_format($b->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white py-16 text-center rounded-xl border border-dashed border-slate-200">
                    <p class="text-xs font-black text-slate-400 uppercase italic">Belum ada data riwayat pesanan</p>
                </div>
            @endforelse
        </div>

        {{-- MODAL DETAIL LANDSCAPE (PADAT & TANPA SCROLL BERLEBIH) --}}
        <div x-show="isOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
            x-transition.opacity x-cloak>

            <div class="bg-white w-full max-w-4xl rounded-[1.5rem] shadow-2xl border border-slate-100 text-left flex flex-col max-h-[90vh]"
                @click.away="isOpen = false">

                {{-- Modal Header --}}
                <div
                    class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0 rounded-t-[1.5rem]">
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-black uppercase text-sm tracking-widest italic leading-none">DETAIL
                            PESANAN</h3>
                        <span
                            class="px-2 py-1 rounded bg-black text-white text-[9px] font-black uppercase italic leading-none"
                            x-text="'#' + detail.invoice_number"></span>
                    </div>
                    <button @click="isOpen = false"
                        class="text-slate-400 hover:text-rose-500 transition-colors text-xl font-black leading-none">✕</button>
                </div>

                {{-- Modal Contents (Grid 2 Kolom Landscape) --}}
                <div class="p-5 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- ================= KOLOM KIRI: Pembayaran & Status ================= --}}
                        <div class="space-y-4">
                            <div
                                class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex justify-between items-center text-black">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic leading-none">Status
                                        Sistem</p>
                                    <p class="text-sm font-black uppercase mt-1 text-indigo-600"
                                        x-text="detail.status ? detail.status.replace(/_/g, ' ') : '-'"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic leading-none">Total
                                        Tagihan</p>
                                    <span class="text-rose-600 block text-lg font-black mt-1"
                                        x-text="'Rp ' + formatRupiah(detail.total_bayar)"></span>
                                </div>
                            </div>

                            {{-- AREA TRANSAKSI PEMBAYARAN QRIS (HANYA MUNCUL JIKA MENUNGGU PEMBAYARAN) --}}
                            <template x-if="detail.status === 'menunggu_pembayaran'">
                                <div class="bg-white border-2 border-dashed border-indigo-100 p-4 rounded-xl text-center">
                                    <p class="text-[10px] font-black text-black uppercase italic mb-2">Scan QRIS untuk
                                        Membayar</p>
                                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + (detail
                                        .qris_payload || detail.invoice_number)"
                                        class="w-32 h-32 mx-auto mb-3 border border-slate-200 shadow-sm rounded-lg">

                                    {{-- Form Upload --}}
                                    <form action="{{ route('pelanggan.konfirmasi.pembayaran') }}" method="POST"
                                        enctype="multipart/form-data"
                                        class="space-y-2 mt-3 pt-3 border-t border-slate-100 text-left">
                                        @csrf
                                        <input type="hidden" name="order_id" :value="detail.id">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-black uppercase italic ml-1">Lampirkan
                                                Bukti TF Cash:</label>
                                            <input type="file" name="bukti_pembayaran" required
                                                class="w-full text-[10px] font-black uppercase bg-slate-50 p-2.5 rounded border border-slate-200 text-black cursor-pointer">
                                        </div>
                                        <button type="submit"
                                            class="w-full bg-black hover:bg-indigo-600 text-white py-2.5 rounded font-black text-[10px] uppercase tracking-wider transition-all shadow-md">
                                            KIRIM BUKTI 🚀
                                        </button>
                                    </form>
                                </div>
                            </template>
                        </div>

                        {{-- ================= KOLOM KANAN: Detail Layanan Berdasarkan Tipe ================= --}}
                        <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4 text-black h-fit">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                <div
                                    class="w-10 h-10 bg-slate-50 border border-slate-100 text-indigo-600 rounded-lg flex items-center justify-center text-lg">
                                    <span x-show="detail.layanan_type?.includes('OrderAngkutan')">🚚</span>
                                    <span x-show="detail.layanan_type?.includes('OrderKendaraan')">🛵</span>
                                    <span x-show="detail.layanan_type?.includes('OrderBangunan')">🏛️</span>
                                    <span x-show="detail.layanan_type?.includes('OrderGudang')">📦</span>
                                </div>
                                <div>
                                    <h4
                                        class="text-[11px] font-black text-black uppercase tracking-widest italic leading-none">
                                        Spesifikasi Layanan</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1"
                                        x-text="detail.layanan_type?.includes('OrderGudang') ? 'Penitipan Barang' : (detail.layanan_type?.includes('OrderKendaraan') ? 'Penitipan Kendaraan' : (detail.layanan_type?.includes('OrderBangunan') ? 'Penjagaan Aset' : 'Jasa Angkutan Logistik'))">
                                    </p>
                                </div>
                            </div>

                            {{-- 1. TEMPLATE ORDER GUDANG (BARANG) --}}
                            <template x-if="detail.layanan_type?.includes('OrderGudang')">
                                <div class="space-y-3">
                                    {{-- Lokasi Mitra --}}
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100 space-y-1">
                                        <p class="text-[8px] text-slate-500 uppercase italic font-bold">Tempat Penitipan /
                                            Mitra:</p>
                                        <p class="text-[10px] font-black text-indigo-600 uppercase"
                                            x-text="detail.mitra?.name || detail.mitra?.nama || detail.layanan?.gudang?.nama_gudang || 'MITRA TITIPSINI'">
                                        </p>
                                        <p class="text-[8px] font-bold text-slate-600 uppercase tracking-tight"
                                            x-text="detail.layanan?.gudang?.alamat_lengkap || detail.layanan?.gudang?.alamat || detail.mitra?.alamat_lengkap || detail.mitra?.alamat || '-'">
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold leading-none">
                                                Tanggal Mulai</p>
                                            <p class="text-[11px] font-black mt-1"
                                                x-text="detail.layanan?.tanggal_mulai || '-'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold leading-none">
                                                Tanggal Selesai</p>
                                            <p class="text-[11px] font-black mt-1 text-rose-600"
                                                x-text="detail.layanan?.tanggal_selesai || '-'"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-black uppercase italic mb-2 px-1">Daftar
                                            Barang Titipan:</p>
                                        <div class="space-y-1.5 max-h-32 overflow-y-auto pr-1">
                                            <template x-for="item in detail.layanan?.items">
                                                <div
                                                    class="flex justify-between items-center text-[10px] font-black bg-slate-50 px-3 py-2 rounded border border-slate-100">
                                                    <span class="uppercase"
                                                        x-text="item.nama_barang_spesifik || item.nama_barang || 'Barang'"></span>
                                                    <span class="text-indigo-600 px-2 py-0.5 bg-indigo-100 rounded"
                                                        x-text="item.jumlah + ' Unit'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- 2. TEMPLATE ORDER KENDARAAN --}}
                            {{-- 2. TEMPLATE ORDER KENDARAAN --}}
                            <template x-if="detail.layanan_type?.includes('OrderKendaraan')">
                                <div class="space-y-3">
                                    {{-- Lokasi Lahan / Mitra --}}
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100 space-y-1">
                                        <p class="text-[8px] text-slate-500 uppercase italic font-bold">Tempat Penitipan /
                                            Lahan:</p>
                                        {{-- Kita panggil detail.layanan.lahan (karena lahan_id ada di order_kendaraans) --}}
                                        <p class="text-[10px] font-black text-indigo-600 uppercase"
                                            x-text="detail.layanan?.lahan?.nama_lahan || detail.layanan?.lahan?.nama || 'MITRA TITIPSINI'">
                                        </p>
                                        <p class="text-[8px] font-bold text-slate-600 uppercase tracking-tight"
                                            x-text="detail.layanan?.lahan?.alamat_lengkap || detail.layanan?.lahan?.alamat || '-'">
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold leading-none">
                                                Tanggal Mulai</p>
                                            <p class="text-[11px] font-black mt-1"
                                                x-text="detail.layanan?.tanggal_mulai || '-'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold leading-none">
                                                Tanggal Selesai</p>
                                            <p class="text-[11px] font-black mt-1 text-rose-600"
                                                x-text="detail.layanan?.tanggal_selesai || '-'"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-black uppercase italic mb-2 px-1">Daftar
                                            Kendaraan Titipan:</p>
                                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                            <template
                                                x-for="(k, index) in (detail.layanan?.kendaraans || detail.layanan?.kendaraan || detail.layanan?.items || [detail.layanan])">
                                                <div
                                                    class="bg-slate-50 p-3 rounded-lg border border-slate-100 space-y-1.5">
                                                    <div
                                                        class="flex justify-between items-center text-[11px] font-bold border-b border-slate-200 pb-1.5">
                                                        <span class="text-slate-500 uppercase italic">Kendaraan <span
                                                                x-text="index + 1"></span>:</span>
                                                        <span class="font-black uppercase"
                                                            x-text="k.nama_kendaraan || k.merk_kendaraan || k.jenis_kendaraan || 'KENDARAAN'"></span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center text-[11px] font-bold pt-0.5">
                                                        <span class="text-slate-500 uppercase italic">No Plat /
                                                            STNK:</span>
                                                        <span
                                                            class="font-black uppercase text-indigo-600 px-2 py-0.5 bg-indigo-100 rounded"
                                                            x-text="k.no_plat || k.plat_nomor || k.nomor_plat || '-'"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            {{-- 3. TEMPLATE ORDER BANGUNAN --}}
                            <template x-if="detail.layanan_type?.includes('OrderBangunan')">
                                <div class="space-y-3">
                                    <div class="bg-slate-50 p-2 rounded border border-slate-100 space-y-1.5">
                                        <div
                                            class="flex justify-between text-[10px] font-bold border-b border-slate-200 pb-1">
                                            <span class="text-slate-500 uppercase italic">Nama Aset Bangunan:</span>
                                            <span class="font-black uppercase"
                                                x-text="detail.layanan?.nama_gudang || detail.layanan?.nama_aset || detail.layanan?.items?.[0]?.nama_aset || 'Aset'"></span>
                                        </div>
                                        <div class="flex justify-between text-[10px] font-bold">
                                            <span class="text-slate-500 uppercase italic">Kecamatan:</span>
                                            <span class="font-black uppercase"
                                                x-text="detail.layanan?.kecamatan || detail.layanan?.alamat?.kecamatan || detail.layanan?.alamatPelanggan?.kecamatan || detail.layanan?.items?.[0]?.kecamatan || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 bg-slate-50 p-2 rounded border border-slate-100">
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold leading-none">
                                                Tanggal Mulai</p>
                                            <p class="text-[10px] font-black mt-1"
                                                x-text="detail.layanan?.tanggal_mulai || '-'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold leading-none">
                                                Tanggal Selesai</p>
                                            <p class="text-[10px] font-black mt-1 text-rose-600"
                                                x-text="detail.layanan?.tanggal_selesai || '-'"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-black uppercase italic mb-1 px-1">Alamat
                                            Lengkap Lokasi:</p>
                                        <p class="text-[9px] font-bold bg-slate-50 p-2 rounded border border-slate-100 uppercase tracking-tight"
                                            x-text="detail.layanan?.alamat_lengkap || detail.layanan?.alamat?.alamat_lengkap || detail.layanan?.alamat?.alamat || detail.layanan?.alamatPelanggan?.alamat_lengkap || detail.layanan?.items?.[0]?.alamat_lengkap || detail.layanan?.items?.[0]?.alamat || '-'">
                                        </p>
                                    </div>
                                </div>
                            </template>

                            {{-- 4. TEMPLATE JASA ANGKUTAN --}}
                            <template x-if="detail.layanan_type?.includes('OrderAngkutan')">
                                <div class="space-y-3">
                                    <div class="bg-slate-50 p-2 rounded border border-slate-100 space-y-1.5">
                                        <div
                                            class="flex justify-between text-[10px] font-bold border-b border-slate-200 pb-1">
                                            <span class="text-slate-500 uppercase italic">Jenis Armada Mobil:</span>
                                            <span
                                                class="font-black uppercase text-indigo-600 px-2 py-0.5 bg-indigo-100 rounded"
                                                x-text="detail.layanan?.jenis_armada?.nama_jenis || detail.layanan?.jenisArmada?.nama_jenis || 'Truk/Pickup'"></span>
                                        </div>
                                        <div class="flex justify-between text-[10px] font-bold">
                                            <span class="text-slate-500 uppercase italic">Estimasi Jarak:</span>
                                            <span class="font-black"
                                                x-text="(detail.layanan?.jarak_km ?? detail.layanan?.jarak ?? 0) + ' KM'"></span>
                                        </div>
                                        <div
                                            class="flex justify-between text-[10px] font-bold border-t border-slate-200 pt-1">
                                            <span class="text-slate-500 uppercase italic">Waktu Angkut:</span>
                                            <span class="font-black"
                                                x-text="(detail.layanan?.tanggal_angkut || '-') + ' ' + (detail.layanan?.jam_angkut || '')"></span>
                                        </div>
                                    </div>

                                    {{-- Titik Jemput & Tujuan --}}
                                    <div class="bg-slate-50 p-2.5 rounded border border-slate-100 space-y-2">
                                        <div>
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold">📍 Titik
                                                Jemput:</p>
                                            <p class="text-[9px] font-black mt-0.5 uppercase tracking-tight"
                                                x-text="detail.layanan?.alamat_jemput?.alamat_lengkap || detail.layanan?.alamatJemput?.alamat_lengkap || detail.layanan?.alamatJemput?.alamat || '-'">
                                            </p>
                                        </div>
                                        <div class="border-t border-slate-200 pt-1.5">
                                            <p class="text-[8px] text-slate-500 uppercase italic font-bold">🏁 Titik
                                                Tujuan:</p>
                                            <p class="text-[9px] font-black mt-0.5 uppercase tracking-tight text-indigo-600"
                                                x-text="detail.layanan?.alamat_tujuan?.alamat_lengkap || detail.layanan?.alamatTujuan?.alamat_lengkap || detail.layanan?.alamatTujuan?.alamat || '-'">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SYSTEM MODAL CORE ALPINE OBJECTS --}}
    <script>
        function bookingModal() {
            return {
                isOpen: false,
                activeTab: 'semua',
                detail: {},
                telat: 0,
                openModalWithData(data, hariTelat) {
                    this.detail = data;
                    this.telat = hariTelat || 0;
                    this.isOpen = true;
                },
                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                }
            }
        }
    </script>

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

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .class-b-tabs:focus {
            outline: none !important;
        }
    </style>
@endsection
