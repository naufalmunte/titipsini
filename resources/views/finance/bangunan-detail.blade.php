@extends('layouts.main')

@section('title', 'Detail Verifikasi Bangunan | Finance - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-2 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('finance.order-bangunan.index') }}"
                    class="w-10 h-10 bg-slate-50 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg flex items-center justify-center transition-all shadow-sm border border-slate-100 shrink-0">
                    <i class="fas fa-arrow-left text-base"></i>
                </a>
                <div>
                    <h2 class="text-base font-black text-slate-800 tracking-tight uppercase italic">Validasi <span class="text-indigo-600">Pembayaran</span></h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Invoice #{{ $order->invoice_number }}</p>
                </div>
            </div>
            <div class="shrink-0">
                <span class="px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[8px] font-black uppercase tracking-widest shadow-sm animate-pulse inline-block">
                    Awaiting Verification
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

            {{-- PANEL KIRI: BUKTI PEMBAYARAN & RINGKASAN BIAYA --}}
            <div class="lg:col-span-4 space-y-4">
                {{-- Bukti Transfer --}}
                <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden md:sticky md:top-6">
                    <div class="bg-slate-900 px-4 py-3 flex justify-between items-center text-[9px] font-black text-white tracking-widest uppercase italic">
                        <span>Bukti Pembayaran</span>
                        <i class="fas fa-receipt text-indigo-400"></i>
                    </div>
                    <div class="p-4 text-center">
                        <div class="bg-slate-50 rounded-lg flex items-center justify-center h-64 overflow-hidden border border-slate-100 group relative">
                            @if ($order->bukti_pembayaran)
                                <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}"
                                    class="max-w-full max-h-full object-contain group-hover:scale-105 transition-all duration-500 cursor-zoom-in rounded-md"
                                    onclick="window.open(this.src)">
                            @else
                                <div class="text-center text-slate-400 flex flex-col items-center gap-2">
                                    <i class="fas fa-image text-2xl"></i>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-300">Belum Upload</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Bayar --}}
                    <div class="px-4 pb-4">
                        <div class="bg-indigo-600 rounded-lg p-4 text-white shadow-md shadow-indigo-200">
                            <p class="text-[8px] font-black uppercase tracking-widest text-indigo-200 mb-1.5 italic text-left">Total Dana Masuk</p>
                            <div class="flex justify-between items-end">
                                <span class="text-lg font-black tracking-tighter italic">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                                <span class="text-[7px] font-black uppercase tracking-widest bg-indigo-500 border border-indigo-400 px-2 py-1 rounded">{{ $order->metode_pembayaran ?? 'Manual' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL KANAN: DATA PELANGGAN, DETAIL SEWA, & PLOTTING MITRA --}}
            <div class="lg:col-span-8 space-y-4 text-left">

                {{-- INFORMASI LENGKAP PELANGGAN & MASA SEWA --}}
                <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden text-left">
                    <div class="p-4 md:p-5 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-black italic uppercase text-slate-800 tracking-tight">Info <span class="text-indigo-600">Pelanggan</span></h3>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Detail Customer & Waktu Sewa Jasa Jaga</p>
                        </div>
                    </div>

                    <div class="p-4 md:p-5 text-left">
                        {{-- 1. Info Pelanggan & Alamat Lokasi Aset --}}
                        @php
                            $kotaPelanggan = strtolower($lokasiAset->kab_kota ?? '-');
                        @endphp

                        <div class="mb-5 pb-5 border-b border-slate-100 flex items-start gap-3 text-left">
                            <div class="w-10 h-10 bg-slate-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-user-tie text-lg"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-black text-slate-800 uppercase italic tracking-tight">{{ $order->user?->name ?? 'User Terhapus' }}</h4>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1 leading-relaxed">
                                    @if ($lokasiAset)
                                        <i class="fas fa-map-marker-alt text-rose-500 mr-1"></i>
                                        {{ $lokasiAset->alamat_lengkap }}, {{ $lokasiAset->kecamatan }}, <span class="text-indigo-600">{{ $lokasiAset->kab_kota }}</span>
                                    @else
                                        <span class="text-rose-400 italic font-bold">Lokasi Aset Belum Dilengkapi</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- 2. Timeline Sewa --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-left mb-5">
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 flex flex-col justify-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Mulai Layanan</p>
                                <p class="text-[10px] font-black text-slate-800 uppercase italic">{{ \Carbon\Carbon::parse($order->layanan->tanggal_mulai)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 flex flex-col justify-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Selesai Layanan</p>
                                <p class="text-[10px] font-black text-slate-800 uppercase italic">{{ \Carbon\Carbon::parse($order->layanan->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-lg border border-indigo-100 flex flex-col justify-center">
                                <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1">Durasi</p>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $order->layanan->total_hari }} Hari Kontrak</p>
                            </div>
                        </div>

                        {{-- 3. Detail Spek Aset --}}
                        <div class="space-y-3 text-left">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 italic">Aset Yang Dijaga</p>
                            <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg flex justify-between items-center shadow-sm">
                                <div class="text-left">
                                    <h5 class="text-[10px] font-black text-slate-800 uppercase italic">{{ $order->layanan->items->first()->nama_aset ?? 'Bangunan' }}</h5>
                                    <p class="text-[8px] font-bold text-slate-500 mt-0.5 uppercase tracking-widest">
                                        Catatan: <span class="italic text-slate-400">"{{ $order->catatan_pelanggan ?? '-' }}"</span>
                                    </p>
                                </div>
                                <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[7px] font-black text-slate-500 uppercase tracking-widest">
                                    {{ $order->layanan->items->first()->kategori->nama_kategori ?? 'Aset' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SELEKSI PENUNJUKAN MITRA PENJAGA --}}
                <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-4 md:p-5 text-left">
                    <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center text-left border-b border-slate-50 pb-3 gap-3">
                        <div>
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight italic">Penunjukan <span class="text-indigo-600">Mitra Lapangan</span></h3>
                            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Saran mitra terdekat berdasarkan wilayah</p>
                        </div>

                        {{-- FITUR PENCARIAN MITRA --}}
                        <div class="relative w-full sm:w-56">
                            <input type="text" id="searchMitra" placeholder="Cari nama mitra / area..." 
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-8 pr-3 py-2 text-[9px] font-bold text-slate-700 uppercase tracking-widest focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left" id="mitraContainer">
                        @php $currentLevel = -1; @endphp
                        @forelse ($mitras as $mitra)
                            @php
                                $kotaMitra = strtolower($mitra->display_kota ?? '');
                                $isSameKab = !empty($kotaPelanggan) && !empty($kotaMitra) &&
                                    (\Illuminate\Support\Str::contains($kotaMitra, $kotaPelanggan) || \Illuminate\Support\Str::contains($kotaPelanggan, $kotaMitra));
                                $isSameKec = $mitra->is_match_kecamatan;

                                if ($isSameKec) {
                                    $level = 0;
                                } elseif ($isSameKab) {
                                    $level = 1;
                                } else {
                                    $level = 2;
                                }
                            @endphp

                            {{-- Group Divider --}}
                            @if ($level !== $currentLevel)
                                <div class="col-span-full mt-2 mb-1 text-left mitra-divider">
                                    @if ($level === 0)
                                        <span class="text-[7px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded uppercase tracking-widest italic"><i class="fas fa-map-marker-alt mr-1"></i> Rekomendasi Terdekat (Kecamatan Sama)</span>
                                    @elseif ($level === 1)
                                        <span class="text-[7px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-1 rounded uppercase tracking-widest italic"><i class="fas fa-city mr-1"></i> Alternatif Wilayah (Kota yang Sama)</span>
                                    @else
                                        <span class="text-[7px] font-black text-slate-400 bg-slate-100 border border-slate-200 px-2 py-1 rounded uppercase tracking-widest italic"><i class="fas fa-globe mr-1"></i> Opsi Lain (Luar Wilayah)</span>
                                    @endif
                                </div>
                                @php $currentLevel = $level; @endphp
                            @endif

                            {{-- Card Mitra --}}
                            <div class="mitra-card relative bg-white border {{ $level === 0 ? 'border-emerald-300 shadow-sm' : ($level === 1 ? 'border-indigo-200' : 'border-slate-200') }} rounded-lg p-4 flex flex-col justify-between h-full text-left transition-all hover:-translate-y-0.5">

                                @if ($level === 0)
                                    <div class="absolute -top-2 -right-2 z-20">
                                        <span class="bg-emerald-500 text-white text-[6px] font-black uppercase tracking-widest px-2 py-0.5 rounded shadow-sm animate-pulse">Kecamatan</span>
                                    </div>
                                @elseif($level === 1)
                                    <div class="absolute -top-2 -right-2 z-20">
                                        <span class="bg-indigo-500 text-white text-[6px] font-black uppercase tracking-widest px-2 py-0.5 rounded shadow-sm">Satu Kota</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-3 mb-4 border-b border-slate-100/50 pb-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-[10px] shrink-0
                                        @if ($level === 0) bg-emerald-600 text-white @elseif($level === 1) bg-indigo-600 text-white @else bg-slate-100 text-slate-400 @endif">
                                        {{ strtoupper(substr($mitra->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-[11px] font-black text-slate-800 uppercase italic truncate" title="{{ $mitra->name }}">{{ $mitra->name }}</h5>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest truncate">{{ $mitra->display_kecamatan }}, {{ $mitra->display_kota }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('finance.order-bangunan.approve', $order->id) }}" method="POST" class="w-full mt-auto">
                                    @csrf
                                    <input type="hidden" name="mitra_id" value="{{ $mitra->id }}">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-indigo-600 text-white rounded-md text-[8px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95 text-center">
                                        Tugaskan Mitra ⚡
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center">
                                <p class="text-[9px] font-bold text-rose-500 uppercase tracking-widest bg-rose-50 border border-rose-100 px-4 py-2 rounded-lg inline-block">
                                    Tidak ada mitra penjaga yang tersedia saat ini.
                                </p>
                            </div>
                        @endforelse

                        {{-- Pesan Kosong Saat Pencarian --}}
                        <div id="noResult" class="hidden col-span-full text-center py-6">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Mitra tidak ditemukan.</p>
                        </div>
                    </div>

                    {{-- TOMBOL TOLAK PEMBAYARAN (Terpisah di Bawah) --}}
                    <div class="mt-5 pt-4 border-t border-slate-100 text-right">
                        <form action="{{ route('finance.order-bangunan.approve', $order->id) }}" method="POST" class="inline-block w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="w-full sm:w-auto px-5 py-2 text-slate-400 hover:text-rose-600 text-[9px] font-black uppercase transition-all tracking-widest bg-slate-50 hover:bg-rose-50 rounded-lg text-center">
                                Tolak Pembayaran
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PENCARIAN MITRA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchMitra');
            const cards = document.querySelectorAll('.mitra-card');
            const dividers = document.querySelectorAll('.mitra-divider');
            const noResult = document.getElementById('noResult');

            searchInput.addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                let visibleCount = 0;

                // Sembunyikan label pembatas (divider) jika sedang melakukan pencarian
                dividers.forEach(div => {
                    div.style.display = filter !== '' ? 'none' : 'block';
                });

                // Cek setiap card mitra
                cards.forEach(card => {
                    let text = card.innerText.toLowerCase();
                    if (text.includes(filter)) {
                        card.style.display = 'flex'; // Tetap gunakan flex agar tingginya selaras
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Tampilkan pesan "Tidak ditemukan" jika tidak ada card yang cocok
                if (visibleCount === 0 && filter !== '') {
                    noResult.classList.remove('hidden');
                } else {
                    noResult.classList.add('hidden');
                }
            });
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection