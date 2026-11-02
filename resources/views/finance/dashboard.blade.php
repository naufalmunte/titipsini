@extends('layouts.main')

@section('title', 'Finance Dashboard | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- ========================================== --}}
        {{-- 1. BAGIAN ALERT ANTREAN PEMBAYARAN         --}}
        {{-- ========================================== --}}
        @if ($pendingConfirmation > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-sm transition-all duration-500">
                <div class="flex items-center gap-3 w-full">
                    <div class="w-10 h-10 bg-amber-500 text-white rounded-lg flex items-center justify-center shadow-md animate-pulse shrink-0">
                        <i class="fas fa-bell text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase italic tracking-tight">Menunggu Validasi!</h3>
                        <p class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-0.5">Ada <span class="text-rose-600">{{ $pendingConfirmation }} pesanan</span> perlu dicek.</p>
                    </div>
                </div>
                <a href="{{ route('finance.confirm.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-amber-500 hover:bg-slate-900 text-white text-[9px] font-black uppercase rounded-lg transition-all shadow-sm tracking-widest text-center whitespace-nowrap">
                    Cek Sekarang ⚡
                </a>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- 2. WELCOME BANNER MINIMALIS                --}}
        {{-- ========================================== --}}
        <div class="relative overflow-hidden bg-slate-900 rounded-xl p-4 shadow-sm">
            <div class="relative z-10 text-white text-left">
                <h2 class="text-base font-black italic uppercase tracking-tight">Halo, {{ auth()->user()->name }}! 💸</h2>
                <p class="text-slate-300 text-[9px] font-medium opacity-90 italic tracking-wide uppercase mt-1">Divisi Finance. Pantau arus kas, omzet, dan validasi pembayaran Titipsini.</p>
            </div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        {{-- ========================================== --}}
        {{-- 3. KARTU RINGKASAN INFORMASI REALTIME      --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            {{-- Total Omzet --}}
            <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Total Omzet</p>
                <h2 class="text-base font-black text-emerald-600 italic mt-0.5 truncate">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>
            
            {{-- Barang Dititip --}}
            <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Barang Dititip</p>
                <h2 class="text-base font-black text-black italic mt-0.5">
                    {{ $aktifDititip }} <span class="text-[9px] font-bold text-slate-300 uppercase">Unit</span>
                </h2>
            </div>
            
            {{-- Antrean Bayar --}}
            <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm text-left">
                <p class="text-[9px] font-black text-slate-900 uppercase italic tracking-wide">Antrean Bayar</p>
                <h2 class="text-base font-black text-rose-500 italic mt-0.5">
                    {{ $pendingConfirmation }} <span class="text-[9px] font-bold text-slate-300 uppercase">Trx</span>
                </h2>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- 4. GRAFIK ORDERAN PER LAYANAN              --}}
        {{-- ========================================== --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-left mt-4 mb-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                <div>
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Grafik Orderan Bulanan</h3>
                    <p class="text-[9px] text-slate-500 font-medium italic uppercase mt-0.5">Perbandingan Jumlah Transaksi 4 Layanan</p>
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
                <canvas id="orderChart"></canvas>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- 5. FITUR GRID AKSES CEPAT FINANCE          --}}
        {{-- ========================================== --}}
        <div class="space-y-3 pt-2 pb-6">
            <div class="px-1 text-left">
                <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Akses Cepat Modul Finance</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Opsi 1: Validasi Gudang --}}
                <a href="{{ route('finance.confirm.index') }}"
                    class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 text-left">
                    <div>
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-warehouse text-lg"></i>
                        </div>
                        <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Validasi Gudang</h3>
                        <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Cek pembayaran penitipan barang & gudang.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-wider group-hover:text-indigo-800 transition-colors">
                            Buka Modul <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>

                {{-- Opsi 2: Validasi Angkutan --}}
                <a href="#"
                    class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 text-left">
                    <div>
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-truck text-lg"></i>
                        </div>
                        <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Validasi Angkutan</h3>
                        <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Cek pembayaran sewa angkutan/armada.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-emerald-600 uppercase tracking-wider group-hover:text-emerald-800 transition-colors">
                            Buka Modul <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>

                {{-- Opsi 3: Validasi Kendaraan --}}
                <a href="#"
                    class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-amber-300 transition-all duration-300 text-left">
                    <div>
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-car text-lg"></i>
                        </div>
                        <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Validasi Kendaraan</h3>
                        <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Cek pembayaran penitipan & parkir kendaraan.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-amber-600 uppercase tracking-wider group-hover:text-amber-800 transition-colors">
                            Buka Modul <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>

                {{-- Opsi 4: Validasi Bangunan --}}
                <a href="#"
                    class="block bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-rose-300 transition-all duration-300 text-left">
                    <div>
                        <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-building text-lg"></i>
                        </div>
                        <h3 class="font-black text-black uppercase italic tracking-wide text-xs leading-none">Validasi Bangunan</h3>
                        <p class="text-[9px] text-slate-500 mt-2.5 font-medium italic leading-relaxed uppercase">Cek pembayaran layanan penjaga bangunan.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-left">
                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-rose-600 uppercase tracking-wider group-hover:text-rose-800 transition-colors">
                            Buka Modul <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>

            </div>
        </div>

    </div>

    {{-- SCRIPT UNTUK CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('orderChart').getContext('2d');
            
            // Ambil data angka dan nama bulan dari Controller
            const namaBulan = "{!! $namaBulan !!} {!! $tahun !!}"; 
            
            // Kita masukkan angka ke dalam array berisi 1 item saja
            const dataTitipBarang = [{!! $titipBarang !!}];
            const dataTitipKendaraan = [{!! $titipKendaraan !!}];
            const dataTitipBangunan = [{!! $titipBangunan !!}];
            const dataJasaAngkutan = [{!! $jasaAngkutan !!}];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [namaBulan], // Sumbu X cuma menampilkan teks bulan yang dipilih
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

    {{-- ANIMATION STYLE PACK --}}
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection