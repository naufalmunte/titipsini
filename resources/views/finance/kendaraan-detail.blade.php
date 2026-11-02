@extends('layouts.main')

@section('title', 'Detail Verifikasi Kendaraan | Finance - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-2 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('finance.confirm-kendaraan.index') }}"
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

            {{-- PANEL KANAN: DATA PELANGGAN, DETAIL SEWA, & PLOTTING LAHAN --}}
            <div class="lg:col-span-8 space-y-4 text-left">

                {{-- INFORMASI LENGKAP PELANGGAN & MASA SEWA --}}
                <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden text-left">
                    <div class="p-4 md:p-5 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-black italic uppercase text-slate-800 tracking-tight">Info <span class="text-indigo-600">Pelanggan</span></h3>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Detail Customer & Waktu Sewa</p>
                        </div>
                    </div>

                    <div class="p-4 md:p-5 text-left">
                        {{-- Info Pelanggan & Alamat Domisili --}}
                        @php
                            // PERBAIKAN: Gunakan Null-Safe Operator (?->) agar tidak error jika relasi alamat kosong
                            $alamatPlg = $order->user?->alamats?->where('is_utama', 1)->first() ?? $order->user?->alamats?->first();
                            $kecamatanPelanggan = $alamatPlg->kecamatan ?? '-';
                            $kotaPelanggan = strtolower($alamatPlg->kab_kota ?? '-');
                            $tglMulai = \Carbon\Carbon::parse($order->layanan->tanggal_mulai);
                            $namaHari = $tglMulai->translatedFormat('l');
                        @endphp

                        <div class="mb-5 pb-5 border-b border-slate-100 flex items-start gap-3 text-left">
                            <div class="w-10 h-10 bg-slate-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-user-tie text-lg"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-black text-slate-800 uppercase italic tracking-tight">{{ $order->user?->name ?? 'User Terhapus' }}</h4>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1 leading-relaxed">
                                    @if ($alamatPlg)
                                        <i class="fas fa-map-marker-alt text-rose-500 mr-1"></i>
                                        {{ $alamatPlg->alamat_lengkap }}, {{ $alamatPlg->kecamatan }}, <span class="text-indigo-600">{{ $alamatPlg->kab_kota }}</span>
                                    @else
                                        <span class="text-rose-400 italic">Profil Alamat Belum Dilengkapi</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Timeline Sewa --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-left mb-5">
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 flex flex-col justify-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Mulai Sewa</p>
                                <p class="text-[10px] font-black text-slate-800 uppercase italic">{{ $tglMulai->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 flex flex-col justify-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Selesai Sewa</p>
                                <p class="text-[10px] font-black text-slate-800 uppercase italic">{{ \Carbon\Carbon::parse($order->layanan->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-lg border border-indigo-100 flex flex-col justify-center">
                                <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1">Durasi Sewa</p>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $order->layanan->total_hari }} Hari Layanan</p>
                            </div>
                        </div>

                        {{-- Daftar Unit Kendaraan --}}
                        <div class="space-y-3 text-left">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 italic">Daftar Unit ({{ $order->layanan->items->count() }} Kendaraan)</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($order->layanan->items as $item)
                                    <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg flex justify-between items-center shadow-sm">
                                        <div class="text-left">
                                            <h5 class="text-[10px] font-black text-slate-800 uppercase italic">{{ $item->merk_kendaraan }}</h5>
                                            <p class="text-[8px] font-black text-indigo-600 mt-0.5 tracking-widest">{{ $item->nomor_plat }}</p>
                                        </div>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[7px] font-black text-slate-500 uppercase tracking-widest">
                                            {{ $item->kategori->nama_kategori ?? 'Unit' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SELEKSI LAHAN PENEMPATAN --}}
                <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-4 md:p-5 text-left">
                    <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center text-left border-b border-slate-50 pb-3 gap-3">
                        <div>
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight italic">Alokasi <span class="text-indigo-600">Lahan Parkir</span></h3>
                            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Saran area terdekat berdasarkan wilayah</p>
                        </div>

                        {{-- FITUR PENCARIAN LAHAN --}}
                        <div class="relative w-full sm:w-56">
                            <input type="text" id="searchLahan" placeholder="Cari nama lahan / area..." 
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-8 pr-3 py-2 text-[9px] font-bold text-slate-700 uppercase tracking-widest focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left" id="lahanContainer">
                        @php $currentLevel = -1; @endphp
                        @forelse ($lahans as $lahan)
                            @php
                                if ($lahan->kecamatan == $kecamatanPelanggan && $kecamatanPelanggan !== '-') {
                                    $level = 0; 
                                } elseif (strtolower($lahan->kab_kota) == $kotaPelanggan && $kotaPelanggan !== '-') {
                                    $level = 1; 
                                } else {
                                    $level = 2; 
                                }

                                // PERBAIKAN: Gunakan ?-> untuk bypass relasi jam operasional yang kosong/null
                                $op = $lahan->jamOperasional?->where('hari', $namaHari)->first();
                                
                                // Jika fitur Jam Operasional tidak ada di Lahan, otomatis kita anggap "BUKA" (true)
                                $isBuka = $lahan->jamOperasional ? ($op && $op->is_buka == 1) : true;
                                $canChoose = $isBuka && $lahan->is_aktif == 1;

                                // PERBAIKAN: Gunakan ?-> untuk antisipasi jika items null
                                $kategoriIds = $order->layanan?->items?->pluck('kategori_id')->unique() ?? collect();
                                $isFullAny = false;
                                $kapasitasInfo = [];

                                foreach ($kategoriIds as $katId) {
                                    $sisa = $lahan->hitungSisaKapasitas($katId);
                                    $butuh = $order->layanan?->items?->where('kategori_id', $katId)->count() ?? 0;
                                    $namaKat = $order->layanan?->items?->where('kategori_id', $katId)->first()?->kategori?->nama_kategori ?? 'Unit';

                                    $kapasitasInfo[] = ['nama' => $namaKat, 'sisa' => $sisa, 'butuh' => $butuh];
                                    if ($sisa < $butuh) {
                                        $isFullAny = true;
                                    }
                                }
                            @endphp

                            {{-- Group Divider --}}
                            @if ($level !== $currentLevel)
                                <div class="col-span-full mt-2 mb-1 text-left lahan-divider">
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

                            {{-- Card Lahan Parkir --}}
                            <div class="lahan-card relative bg-white border {{ $level === 0 ? 'border-emerald-300 shadow-sm' : ($level === 1 ? 'border-indigo-200' : 'border-slate-200') }} rounded-lg p-4 flex flex-col justify-between h-full text-left transition-all hover:-translate-y-0.5">

                                @if (!$canChoose || $isFullAny)
                                    <div class="absolute -top-2 -right-2 z-20">
                                        <span class="bg-rose-600 text-white text-[6px] font-black uppercase tracking-widest px-2 py-0.5 rounded shadow-sm">{{ !$canChoose ? 'Tutup' : 'Penuh' }}</span>
                                    </div>
                                @elseif ($level === 0)
                                    <div class="absolute -top-2 -right-2 z-20">
                                        <span class="bg-emerald-500 text-white text-[6px] font-black uppercase tracking-widest px-2 py-0.5 rounded shadow-sm animate-pulse">Terdekat</span>
                                    </div>
                                @elseif($level === 1)
                                    <div class="absolute -top-2 -right-2 z-20">
                                        <span class="bg-indigo-500 text-white text-[6px] font-black uppercase tracking-widest px-2 py-0.5 rounded shadow-sm">Satu Kota</span>
                                    </div>
                                @endif

                                <div class="text-left mb-3">
                                    <h4 class="text-[11px] font-black text-slate-800 uppercase italic leading-tight truncate" title="{{ $lahan->nama_lahan }}">{{ $lahan->nama_lahan }}</h4>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $lahan->kecamatan }}, {{ $lahan->kab_kota }}</p>
                                </div>

                                <div class="space-y-1.5 pt-2.5 border-t border-slate-100 mb-4 text-left">
                                    {{-- Hanya tampilkan status hari jika Lahan memiliki fitur jamOperasional di database --}}
                                    @if($lahan->jamOperasional !== null)
                                    <div class="flex justify-between items-center text-[8px] font-black uppercase tracking-widest">
                                        <span class="text-slate-400">Sts Hari {{ strtoupper($namaHari) }}:</span>
                                        <span class="{{ $isBuka ? 'text-emerald-600' : 'text-rose-500' }}">{{ $isBuka ? 'BUKA' : 'LIBUR' }}</span>
                                    </div>
                                    @endif
                                    
                                    @foreach ($kapasitasInfo as $info)
                                        <div class="flex justify-between items-center text-[8px] font-black uppercase tracking-widest">
                                            <span class="text-slate-400">{{ $info['nama'] }}:</span>
                                            <span class="{{ $info['sisa'] < $info['butuh'] ? 'text-rose-500' : 'text-emerald-600' }}">
                                                {{ $info['sisa'] }} <span class="text-[7px] text-slate-400 font-bold italic">Sisa</span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                <form action="{{ route('finance.confirm-kendaraan.approve', $order->id) }}" method="POST" class="w-full mt-auto">
                                    @csrf
                                    <input type="hidden" name="lahan_id" value="{{ $lahan->id }}">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" {{ (!$canChoose || $isFullAny) ? 'disabled' : '' }}
                                        class="w-full py-2 rounded-md text-[8px] font-black uppercase tracking-widest transition-all text-center
                                        {{ ($canChoose && !$isFullAny) ? 'bg-slate-900 text-white hover:bg-indigo-600 shadow-sm active:scale-95' : 'bg-slate-100 text-slate-300 cursor-not-allowed' }}">
                                        {{ ($canChoose && !$isFullAny) ? 'Pilih Lahan ⚡' : 'Tidak Tersedia' }}
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center">
                                <p class="text-[9px] font-bold text-rose-500 uppercase tracking-widest bg-rose-50 border border-rose-100 px-4 py-2 rounded-lg inline-block">
                                    Tidak ada lahan parkir yang aktif saat ini.
                                </p>
                            </div>
                        @endforelse

                        {{-- Pesan Kosong Saat Pencarian --}}
                        <div id="noResult" class="hidden col-span-full text-center py-6">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lahan tidak ditemukan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PENCARIAN LAHAN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchLahan');
            const cards = document.querySelectorAll('.lahan-card');
            const dividers = document.querySelectorAll('.lahan-divider');
            const noResult = document.getElementById('noResult');

            searchInput.addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                let visibleCount = 0;

                // Sembunyikan label pembatas (divider) jika sedang melakukan pencarian
                dividers.forEach(div => {
                    div.style.display = filter !== '' ? 'none' : 'block';
                });

                // Cek setiap card lahan
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