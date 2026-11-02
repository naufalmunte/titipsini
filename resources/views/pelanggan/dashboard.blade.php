@extends('layouts.main')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12">

        {{-- 1. NOTIFIKASI STATUS VERIFIKASI --}}
        @if (!$verify)
            <div
                class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3 w-full text-left">
                    <div
                        class="w-10 h-10 bg-rose-500 text-white rounded-lg flex items-center justify-center shadow-md shrink-0 animate-pulse">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-black uppercase italic leading-none">Akun Belum Terverifikasi!
                        </h3>
                        <p class="text-[10px] text-slate-500 font-medium italic mt-1 uppercase tracking-wider">Selesaikan
                            validasi data (KTP & PIN) untuk membuka seluruh fitur Titipsini.</p>
                    </div>
                </div>
                <a href="{{ route('pelanggan.verify.index') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-[9px] font-black uppercase rounded-md transition-all shadow-sm tracking-wider text-center shrink-0">
                    Verifikasi Sekarang ⚡
                </a>
            </div>
        @elseif($verify->status == 'pending')
            <div
                class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3 shadow-sm border-dashed text-left">
                <div
                    class="w-10 h-10 bg-amber-500 text-white rounded-lg flex items-center justify-center shadow-md shrink-0 animate-pulse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-black uppercase italic leading-none">Data Sedang Ditinjau!</h3>
                    <p class="text-[10px] text-slate-500 italic font-medium mt-1 uppercase tracking-wider">Berkas identitas
                        KTP Anda sedang divalidasi oleh tim finance eksekutif. Mohon ditunggu!</p>
                </div>
            </div>
        @endif

        {{-- 2. WELCOME BANNER MINIMALIS --}}
        <div class="relative overflow-hidden bg-indigo-600 rounded-xl p-4 shadow-sm">
            <div class="relative z-10 text-white text-left">
                <h2 class="text-base font-black italic uppercase tracking-tight">Halo, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-indigo-100 text-[9px] font-medium opacity-90 italic tracking-wide uppercase mt-1">Ayo amankan
                    barang berharga dan logistik tim Anda hari ini di ekosistem Titipsini.</p>
            </div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        {{-- 3. KARTU RINGKASAN INFORMASI PENITIPAN REAL --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Titipan Aktif</p>
                <h2 class="text-base font-black text-black italic mt-0.5">
                    {{ $activeOrdersCount ?? 0 }} <span class="text-[9px] font-bold text-slate-300 uppercase">Items</span>
                </h2>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Menunggu Pembayaran</p>
                <h2 class="text-base font-black text-rose-600 italic mt-0.5">
                    {{ isset($needPaymentOrders) ? $needPaymentOrders->count() : 0 }} <span
                        class="text-[9px] font-bold text-slate-300 uppercase">Tagihan</span>
                </h2>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Masa Titip Habis</p>
                <h2 class="text-base font-black text-amber-600 italic mt-0.5">
                    {{ isset($needActionOrders) ? $needActionOrders->count() : 0 }} <span
                        class="text-[9px] font-bold text-slate-300 uppercase">Perlu Ambil</span>
                </h2>
            </div>
        </div>

        {{-- 4. ALERT: BARANG PERLU DIAMBIL --}}
        @if (isset($needActionOrders) && $needActionOrders->count() > 0)
            <div class="space-y-2 pt-1">
                <div class="flex items-center gap-2 px-1 text-left">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <h3 class="text-[9px] font-black text-rose-600 uppercase tracking-wider italic">Tindakan Diperlukan:
                        Masa Penitipan Habis!</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($needActionOrders as $order)
                        <div
                            class="bg-white border border-rose-200 rounded-xl p-3.5 shadow-sm flex flex-col justify-between hover:border-rose-400 transition-all text-left">
                            <div class="flex justify-between items-start mb-2">
                                <div
                                    class="w-7 h-7 bg-rose-50 text-rose-600 rounded-md flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span
                                    class="text-[8px] font-black text-black uppercase">#{{ $order->invoice_number }}</span>
                            </div>

                            <div>
                                <h4 class="text-[11px] font-black text-black uppercase italic leading-tight">
                                    {{ $order->orderGudang->nama_gudang ?? 'Layanan Penitipan Gudang' }}
                                </h4>
                                <p class="text-[8px] text-slate-500 font-bold mt-1 italic uppercase">
                                    Batas Pengambilan:
                                    <span class="text-rose-600 font-black">
                                        {{ $order->orderGudang && $order->orderGudang->tanggal_selesai ? \Carbon\Carbon::parse($order->orderGudang->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </p>
                            </div>

                            <a href="{{ route('pelanggan.booking.index') }}"
                                class="mt-3 w-full py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded text-[8px] font-black uppercase tracking-wider text-center transition-all active:scale-95 shadow-sm">
                                Atur Pengambilan Barang
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 5. MINI TABLE WIDGET: TAGIHAN PERLU DIBAYAR --}}
        @if (isset($needPaymentOrders) && $needPaymentOrders->count() > 0)
            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                <div class="px-3 py-2 flex justify-between items-center bg-rose-50/20 text-left">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-rose-500 text-white rounded-md flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[9px] font-black text-black uppercase italic leading-none">Tagihan Menunggu
                                Pembayaran</h4>
                            <p class="text-[7px] text-rose-500 font-bold uppercase tracking-wider mt-0.5">Segera selesaikan
                                transaksi invoice anda</p>
                        </div>
                    </div>
                    <a href="{{ route('pelanggan.booking.index') }}"
                        class="text-[8px] font-black text-rose-600 uppercase tracking-wide hover:underline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-3 py-2 text-[9px] font-black text-slate-900 uppercase italic tracking-wider">
                                    Nomor Invoice</th>
                                <th
                                    class="px-3 py-2 text-[9px] font-black text-slate-900 uppercase italic tracking-wider text-center">
                                    Total Tagihan</th>
                                <th
                                    class="px-3 py-2 text-[9px] font-black text-slate-900 uppercase italic tracking-wider text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($needPaymentOrders->take(3) as $order)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-3 py-2 text-left">
                                        <span
                                            class="text-[10px] font-black text-black block italic leading-none">#{{ $order->invoice_number }}</span>
                                        <span
                                            class="text-[8px] font-bold text-slate-400 uppercase tracking-tight mt-0.5 inline-block">{{ $order->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="text-[10px] font-black text-rose-600 tracking-tight">
                                            Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="{{ route('pelanggan.booking.index') }}"
                                            class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-2.5 py-1 rounded text-[8px] font-black uppercase tracking-wider shadow-sm hover:bg-indigo-700 transition-all active:scale-95">
                                            Detail & Bayar <svg class="w-2 h-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- 6. FITUR GRID AKSES 4 LAYANAN UTAMA --}}
        <div class="space-y-3 pt-2 pb-6">
            <div class="px-1 text-left">
                <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Pilih Kategori Layanan
                    Penitipan</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php $isApproved = $verify && $verify->status == 'approved'; @endphp

                {{-- Opsi 1: Titip Barang (Gudang) --}}
                @if ($isApproved)
                    <a href="{{ route('pelanggan.titip.create') }}"
                        class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                    @else
                        <div
                            class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between opacity-60 cursor-not-allowed text-left">
                @endif
                <div>
                    <div
                        class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Titip Barang</h3>
                    <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Sewa space
                        gudang logistik aman terstandarisasi untuk barang berharga Anda.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                    @if ($isApproved)
                        <span
                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                            Mulai Layanan <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    @else
                        <p class="text-[7px] font-black text-rose-500 uppercase italic leading-none">* Selesaikan
                            verifikasi identitas</p>
                    @endif
                </div>
                @if ($isApproved)
                    </a>
                @else
            </div>
            @endif


            {{-- Opsi 2: Titip Kendaraan --}}
            @if ($isApproved)
                <a href="{{ url('pelanggan/titip-kendaraan') }}"
                    class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                @else
                    <div
                        class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between opacity-60 cursor-not-allowed text-left">
            @endif
            <div>
                <div
                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h8M5 11h14a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2zM7 15h2M15 15h2M3 11l2-4h14l2 4">
                        </path>
                    </svg>
                </div>
                <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Titip Kendaraan</h3>
                <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Fasilitas
                    penitipan aman untuk kendaraan roda 2 maupun roda 4 pengawasan penuh.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                @if ($isApproved)
                    <span
                        class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                        Mulai Layanan <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </span>
                @else
                    <p class="text-[7px] font-black text-rose-500 uppercase italic leading-none">* Selesaikan verifikasi
                        identitas</p>
                @endif
            </div>
            @if ($isApproved)
                </a>
            @else
        </div>
        @endif


        {{-- Opsi 3: Titip Bangunan --}}
        @if ($isApproved)
            <a href="{{ url('pelanggan/titip-bangunan') }}"
                class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
            @else
                <div
                    class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between opacity-60 cursor-not-allowed text-left">
        @endif
        <div>
            <div
                class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v1H9V7zm5 0h1v1h-1V7zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1z">
                    </path>
                </svg>
            </div>
            <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Titip Bangunan</h3>
            <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Layanan penjagaan aset
                rumah, ruko, atau bangunan kosong Anda oleh tim profesional.</p>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-left">
            @if ($isApproved)
                <span
                    class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                    Mulai Layanan <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            @else
                <p class="text-[7px] font-black text-rose-500 uppercase italic leading-none">* Selesaikan verifikasi
                    identitas</p>
            @endif
        </div>
        @if ($isApproved)
            </a>
        @else
    </div>
    @endif

    {{-- Opsi 4: Jasa Angkutan --}}
    @if ($isApproved)
        <a href="{{ url('pelanggan/jasa-angkutan') }}"
            class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
        @else
            <div
                class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between opacity-60 cursor-not-allowed text-left">
    @endif
    <div>
        <div
            class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h3l4 3v5h-1">
                </path>
            </svg>
        </div>
        <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Jasa Angkutan</h3>
        <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Layanan pickup dan
            pengiriman logistik terpercaya menggunakan armada khusus kami.</p>
    </div>
    <div class="mt-4 pt-3 border-t border-slate-100 text-left">
        @if ($isApproved)
            <span
                class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                Mulai Layanan <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </span>
        @else
            <p class="text-[7px] font-black text-rose-500 uppercase italic leading-none">* Selesaikan verifikasi identitas
            </p>
        @endif
    </div>
    @if ($isApproved)
        </a>
    @else
        </div>
    @endif

    </div>
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
