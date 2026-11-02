@extends('layouts.main')

@section('title', 'Mitra Dashboard - Titipsini')

@section('content')
    @if (auth()->user()->verify && auth()->user()->verify->status == 'approved' && !auth()->user()->profilMitra)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endif

    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-2 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        @php
            $verify = auth()->user()->verify;
            $isApproved = $verify && $verify->status == 'approved';
            $profil = auth()->user()->profilMitra;
        @endphp

        {{-- ======================================================== --}}
        {{-- 1. NOTIFIKASI STATUS VERIFIKASI                          --}}
        {{-- ======================================================== --}}
        @if (!$verify || $verify->status == 'rejected')
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3 w-full text-left">
                    <div class="w-10 h-10 bg-rose-500 text-white rounded-lg flex items-center justify-center shadow-md shrink-0 animate-pulse">
                        <i class="fas fa-id-card text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-black uppercase italic leading-none">
                            {{ !$verify ? 'Akun Belum Terverifikasi!' : 'Verifikasi Ditolak!' }}
                        </h3>
                        <p class="text-[10px] text-slate-500 font-medium italic mt-1 uppercase tracking-wider">
                            {{ !$verify ? 'Selesaikan validasi data (KTP) untuk mengaktifkan dashboard bisnis Anda.' : 'Alasan: ' . ($verify->catatan ?? 'Data tidak sesuai.') }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('mitra.verify.index') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-[9px] font-black uppercase rounded-md transition-all shadow-sm tracking-wider text-center shrink-0">
                    {{ !$verify ? 'Verifikasi Sekarang ⚡' : 'Perbaiki Dokumen ⚡' }}
                </a>
            </div>
        @elseif($verify->status == 'pending')
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3 shadow-sm border-dashed text-left">
                <div class="w-10 h-10 bg-amber-500 text-white rounded-lg flex items-center justify-center shadow-md shrink-0 animate-pulse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-black uppercase italic leading-none">Data Sedang Ditinjau!</h3>
                    <p class="text-[10px] text-slate-500 italic font-medium mt-1 uppercase tracking-wider">Berkas KTP Anda sedang divalidasi oleh admin. Mohon tunggu maksimal 1x24 jam.</p>
                </div>
            </div>
        @endif

        {{-- ======================================================== --}}
        {{-- 2. STATE: APPROVED TAPI BELUM ISI PROFIL LOKASI BISNIS   --}}
        {{-- ======================================================== --}}
        @if ($isApproved && !$profil)
            <div class="bg-indigo-600 rounded-xl p-4 shadow-sm relative overflow-hidden text-white text-left mt-2">
                <div class="relative z-10">
                    <h2 class="text-base font-black italic uppercase tracking-tight">Langkah Terakhir, {{ auth()->user()->name }}! 🎯</h2>
                    <p class="text-indigo-100 text-[9px] font-medium opacity-90 italic tracking-wide uppercase mt-1">Tentukan lokasi operasional bisnis Anda untuk sinkronisasi maps.</p>
                </div>
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm mt-4">
                <form action="{{ route('mitra.profil.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Nama Bisnis / PT / Gudang</label>
                        <input type="text" name="nama_pt" required value="{{ old('nama_pt') }}" class="w-full px-4 py-2.5 mt-1 bg-slate-50 border border-slate-200 rounded-lg focus:border-indigo-600 focus:bg-white text-[10px] font-bold uppercase transition-all" placeholder="CONTOH: TITIPSINI LOGISTICS">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" required readonly placeholder="Pilih di map.." class="w-full px-4 py-2.5 mt-1 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 uppercase">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Kota / Kab</label>
                            <input type="text" name="kab_kota" id="kab_kota" required readonly placeholder="Pilih di map.." class="w-full px-4 py-2.5 mt-1 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 uppercase">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatan" required readonly placeholder="Pilih di map.." class="w-full px-4 py-2.5 mt-1 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Alamat Lengkap Detail</label>
                        <textarea name="alamat" id="alamat_detail" required rows="2" class="w-full px-4 py-2.5 mt-1 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold uppercase resize-none leading-relaxed"></textarea>
                    </div>

                    <div class="space-y-1 pt-2">
                        <label class="text-[9px] font-black text-indigo-600 uppercase italic tracking-wide flex justify-between">
                            Titik Lokasi Kantor <span class="animate-pulse">📍 Geser Pin</span>
                        </label>
                        <div id="map" class="w-full h-48 rounded-xl border border-slate-200 shadow-inner z-0"></div>
                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="lng">
                    </div>

                    <button type="submit" class="w-full py-3 mt-4 bg-slate-900 hover:bg-indigo-600 text-white rounded-lg shadow-md transition-all font-black uppercase tracking-widest text-[10px] italic">
                        Aktifkan Dashboard Bisnis 🚀
                    </button>
                </form>
            </div>
        @endif

        {{-- ======================================================== --}}
        {{-- 3. STATE: FULLY APPROVED & ACTIVE DASHBOARD              --}}
        {{-- ======================================================== --}}
        @if ($isApproved && $profil)
            {{-- WELCOME BANNER MINIMALIS --}}
            <div class="relative overflow-hidden bg-indigo-600 rounded-xl p-4 shadow-sm flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                <div class="relative z-10 text-white text-left">
                    <h2 class="text-base font-black italic uppercase tracking-tight">Halo, {{ $profil->nama_pt ?? auth()->user()->name }}! 👋</h2>
                    <p class="text-indigo-100 text-[9px] font-medium opacity-90 italic tracking-wide uppercase mt-1">Pusat kendali operasional bisnis & logistik Anda hari ini.</p>
                </div>
                <div class="relative z-10 shrink-0">
                    <a href="{{ route('mitra.profil.edit') }}" class="inline-flex bg-white/20 hover:bg-white/30 border border-white/30 text-white px-3 py-1.5 rounded text-[8px] font-black uppercase tracking-widest transition-all backdrop-blur-sm shadow-sm">
                        <i class="fas fa-cog mr-1.5 mt-0.5"></i> Edit Profil
                    </a>
                </div>
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            {{-- KARTU RINGKASAN INFORMASI PENITIPAN REAL --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                {{-- KOTAK 1: PESANAN AKTIF --}}
                <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                    <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Pesanan Aktif</p>
                    <h2 class="text-base font-black text-indigo-600 italic mt-0.5">
                        {{ $pesananAktif ?? 0 }} <span class="text-[9px] font-bold text-slate-300 uppercase">Trx</span>
                    </h2>
                </div>

                {{-- KOTAK 2: TOTAL PENDAPATAN --}}
                <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                    <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Total Pendapatan</p>
                    <h2 class="text-base font-black text-emerald-600 italic mt-0.5">
                        Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </h2>
                </div>

                {{-- KOTAK 3: SALDO SIAP DITARIK --}}
                <div class="bg-slate-900 border border-slate-800 rounded-lg p-3 shadow-md text-left relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-indigo-300 uppercase italic tracking-wide">Saldo Ditarik</p>
                        <h2 class="text-base font-black text-white italic mt-0.5">
                            Rp {{ number_format($saldoTersedia ?? 0, 0, ',', '.') }}
                        </h2>
                    </div>
                    <i class="fas fa-wallet absolute -right-2 -bottom-2 text-white/5 text-3xl transform -rotate-12"></i>
                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- 4. GRAFIK ORDERAN KHUSUS MITRA (BARU)                    --}}
            {{-- ======================================================== --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-left mt-4 mb-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                    <div>
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Grafik Pesanan Masuk</h3>
                        <p class="text-[9px] text-slate-500 font-medium italic uppercase mt-0.5">Total transaksi Anda di bulan ini</p>
                    </div>

                    {{-- Filter Bulan & Tahun --}}
                    <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                        <select name="bulan" onchange="this.form.submit()" class="text-xs font-bold italic bg-slate-50 border border-slate-200 text-slate-700 rounded-md py-1.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-sm">
                            @php 
                                $bulanTerpilih = request('bulan', date('m')); 
                                $daftarBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                            @endphp
                            @foreach($daftarBulan as $angka => $nama)
                                <option value="{{ $angka }}" {{ $bulanTerpilih == $angka ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>

                        <select name="tahun" onchange="this.form.submit()" class="text-xs font-bold italic bg-slate-50 border border-slate-200 text-slate-700 rounded-md py-1.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-sm">
                            @php $tahunTerpilih = request('tahun', date('Y')); @endphp
                            <option value="2026" {{ $tahunTerpilih == '2026' ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ $tahunTerpilih == '2025' ? 'selected' : '' }}>2025</option>
                            <option value="2024" {{ $tahunTerpilih == '2024' ? 'selected' : '' }}>2024</option>
                        </select>
                    </form>
                </div>

                {{-- Canvas untuk Chart.js --}}
                <div class="relative h-72 w-full">
                    <canvas id="mitraOrderChart"></canvas>
                </div>
            </div>

            {{-- FITUR GRID AKSES 4 LAYANAN UTAMA --}}
            <div class="space-y-3 pt-2 pb-6">
                <div class="px-1 text-left">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Kategori Manajemen Bisnis</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- 1. Kelola Gudang --}}
                    <a href="{{ url('/mitra/gudang') }}" class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                        <div>
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-boxes text-lg"></i>
                            </div>
                            <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Kelola Gudang</h3>
                            <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Atur kapasitas gudang, ruangan, dan tarif penitipan barang umum.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                                Masuk Panel <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </a>

                    {{-- 2. Lahan Parkir --}}
                    <a href="{{ url('/mitra/parkir') }}" class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                        <div>
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-parking text-lg"></i>
                            </div>
                            <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Lahan Parkir</h3>
                            <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Kelola slot parkir, tarif inap motor/mobil, dan keamanan kendaraan.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                                Masuk Panel <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </a>

                    {{-- 3. Garasi Armada --}}
                    <a href="{{ url('/mitra/armada') }}" class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                        <div>
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-truck-moving text-lg"></i>
                            </div>
                            <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Garasi Armada</h3>
                            <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Daftarkan armada logistik, pickup, atau truk untuk jasa angkut.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                                Masuk Panel <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </a>

                    {{-- 4. Data Penjaga --}}
                    <a href="{{ url('/mitra/penjaga') }}" class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                        <div>
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-shield text-lg"></i>
                            </div>
                            <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Data Penjaga</h3>
                            <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Kelola data staf keamanan atau penjaga aset bangunan Anda.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                                Masuk Panel <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Script untuk Leaflet Map (Mode Edit Profil) --}}
    @if ($isApproved && !$profil)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('map', { zoomControl: false }).setView([-0.947, 100.417], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                const marker = L.marker([-0.947, 100.417], { draggable: true }).addTo(map);

                function updateInfo(lat, lng) {
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`)
                        .then(r => r.json())
                        .then(data => {
                            const a = data.address;
                            document.getElementById('provinsi').value = a.state || '';
                            document.getElementById('kab_kota').value = a.city || a.town || a.county || '';
                            let kecamatanCandidate = a.city_district || a.district || a.municipality || '';
                            if (document.getElementById('kab_kota').value === kecamatanCandidate || !kecamatanCandidate) {
                                kecamatanCandidate = a.town || a.suburb || a.village || '';
                            }
                            document.getElementById('kecamatan').value = kecamatanCandidate;
                            document.getElementById('alamat_detail').value = data.display_name || '';
                        });
                }

                marker.on('dragend', () => updateInfo(marker.getLatLng().lat, marker.getLatLng().lng));
                map.on('click', (e) => {
                    marker.setLatLng(e.latlng);
                    updateInfo(e.latlng.lat, e.latlng.lng);
                });

                setTimeout(() => { map.invalidateSize(); }, 300);
            });
        </script>
    @endif

    {{-- Script untuk Chart.js (Hanya jika disetujui & profil ada) --}}
    @if ($isApproved && $profil)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('mitraOrderChart').getContext('2d');
                
                // Ambil data angka dan nama bulan dari Controller
                const namaBulan = "{!! $namaBulan ?? date('F') !!} {!! $tahun ?? date('Y') !!}"; 
                
                // Masukkan angka layanan ke dalam array
                const dataTitipBarang = [{!! $titipBarang ?? 0 !!}];
                const dataTitipKendaraan = [{!! $titipKendaraan ?? 0 !!}];
                const dataTitipBangunan = [{!! $titipBangunan ?? 0 !!}];
                const dataJasaAngkutan = [{!! $jasaAngkutan ?? 0 !!}];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [namaBulan], 
                        datasets: [
                            { label: 'Titip Barang', data: dataTitipBarang, backgroundColor: '#4f46e5', borderRadius: 6, barPercentage: 0.8 },
                            { label: 'Titip Kendaraan', data: dataTitipKendaraan, backgroundColor: '#10b981', borderRadius: 6, barPercentage: 0.8 },
                            { label: 'Titip Bangunan', data: dataTitipBangunan, backgroundColor: '#f59e0b', borderRadius: 6, barPercentage: 0.8 },
                            { label: 'Jasa Angkutan', data: dataJasaAngkutan, backgroundColor: '#ef4444', borderRadius: 6, barPercentage: 0.8 }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 11, style: 'italic', weight: 'bold' }, usePointStyle: true } }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { borderDash: [4, 4] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            });
        </script>
    @endif

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection