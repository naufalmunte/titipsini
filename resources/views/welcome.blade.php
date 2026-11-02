<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Titipsini - Solusi Penitipan Terpercaya</title>

    <!-- Fonts & Scripts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .glass-header {
            background: rgba(253, 253, 252, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-[#FDFDFC] text-slate-900 antialiased overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100 glass-header transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2 group">
                <div
                    class="bg-indigo-600 p-2 rounded-lg group-hover:scale-105 transition-transform shadow-md shadow-indigo-200">
                    <i class="fas fa-box-archive text-white text-sm"></i>
                </div>
                <span class="text-lg font-black italic tracking-tighter uppercase">Titip<span
                        class="text-indigo-600">sini</span></span>
            </a>

            <div
                class="hidden md:flex items-center gap-6 text-[10px] font-black uppercase tracking-widest text-slate-500">
                <a href="#tentang" class="hover:text-indigo-600 transition-colors">Tentang</a>
                <a href="#cek-harga" class="hover:text-indigo-600 transition-colors">Cek Harga</a>
                <a href="#ulasan" class="hover:text-indigo-600 transition-colors">Ulasan</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-[9px] font-black uppercase rounded-lg shadow-md transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden sm:block text-[9px] font-black uppercase text-slate-500 hover:text-indigo-600 transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[9px] font-black uppercase rounded-lg shadow-md shadow-indigo-100 transition-all active:scale-95">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- SECTION 1: HERO / INFORMASI TITIP SINI --}}
    <section id="tentang" class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 animate-fade-in-up">
                <span
                    class="inline-block px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-indigo-100">
                    Solusi Logistik Modern
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tighter italic uppercase leading-[1.1]">
                    Titip Apa Saja, <br>
                    <span class="text-indigo-600">Aman Terkendali.</span>
                </h1>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-lg">
                    Titipsini adalah platform penitipan aset paling inovatif di Padang. Mulai dari barang kecil,
                    kendaraan kesayangan, hingga pengawasan bangunan yang sedang ditinggalkan.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-5 pt-2">
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-xl shadow-lg shadow-indigo-200 hover:-translate-y-1 hover:shadow-xl transition-all text-center">
                        Mulai Titip Sekarang
                    </a>

                </div>
            </div>
            <div class="relative hidden lg:block">
                <div class="absolute -inset-4 bg-indigo-500/10 blur-3xl rounded-full"></div>
                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop"
                    class="relative w-full h-[400px] object-cover rounded-[2rem] shadow-xl grayscale hover:grayscale-0 transition-all duration-700"
                    alt="Warehouse">
            </div>
        </div>
    </section>

    {{-- SECTION 2: STATISTIK --}}
    <section class="py-12 md:py-16 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5">
        </div>
        <div class="max-w-7xl mx-auto items-center px-6 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x-0 md:divide-x divide-slate-800">
                <div class="text-center space-y-1.5">
                    <h3 class="text-3xl md:text-4xl font-black text-white italic tracking-tighter">
                        {{ $stats['users'] ?? '1.2k' }}</h3>
                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">User Aktif</p>
                </div>
                <div class="text-center space-y-1.5">
                    <h3 class="text-3xl md:text-4xl font-black text-indigo-500 italic tracking-tighter">
                        {{ $stats['mitra'] ?? '85' }}</h3>
                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Mitra Gudang</p>
                </div>
                <div class="text-center space-y-1.5">
                    <h3 class="text-3xl md:text-4xl font-black text-white italic tracking-tighter">
                        {{ $stats['barang'] ?? '15k' }}</h3>
                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Barang Aman</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 3: SIMULATOR HARGA INTERAKTIF --}}
    <section id="cek-harga" class="min-h-[90vh] flex flex-col justify-center py-16 bg-slate-50" x-data="priceSimulator()">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="text-center md:text-left mb-8 space-y-3">

                {{-- Main Heading --}}
                <h2
                    class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter leading-none text-slate-900">
                    Estimasi Biaya.</span>
                </h2>

                {{-- Deskripsi Tambahan --}}
                <p
                    class="text-slate-500 text-xs md:text-sm font-medium italic max-w-sm mx-auto md:mx-0 leading-relaxed pt-1">
                    Hitung perkiraan harga layanan secara transparan. Sesuaikan jenis layanan, durasi, dan spesifikasi
                    aset untuk melihat total tagihan Anda.
                </p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                {{-- AREA INPUT --}}
                <div class="lg:col-span-8 space-y-5">
                    {{-- 1. PILIH LAYANAN UTAMA --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <template x-for="l in services" :key="l.id">
                            <button @click="selectedService = l.id"
                                :class="selectedService === l.id ?
                                    'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-200 scale-[1.02]' :
                                    'bg-white border-slate-200 text-slate-500 hover:border-indigo-300 hover:bg-indigo-50/50'"
                                class="p-4 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 group">
                                <i :class="l.icon" class="text-lg md:text-xl transition-colors"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest text-center"
                                    x-text="l.name"></span>
                            </button>
                        </template>
                    </div>

                    {{-- 2. DETAIL FORM --}}
                    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-slate-100 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 text-left">

                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-800 uppercase italic ml-1">Pilih Tipe /
                                    Spesifikasi</label>
                                <div class="relative">
                                    <select x-model="selectedSubIndex" @change="calculate()"
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg font-black text-slate-900 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none outline-none transition-all cursor-pointer">
                                        <template x-for="(sub, index) in subCategories[selectedService]"
                                            :key="index">
                                            <option :value="index" x-text="sub.name"></option>
                                        </template>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                </div>
                            </div>

                            <div class="space-y-1.5" x-show="selectedService !== 4">
                                <label class="text-[9px] font-black text-slate-800 uppercase italic ml-1">Jumlah Unit
                                    Aset</label>
                                <input type="number" x-model="jumlah" @input="calculate()" min="1"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg font-black text-slate-900 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            </div>

                            <div class="space-y-1.5" x-show="selectedService !== 4">
                                <label class="text-[9px] font-black text-slate-800 uppercase italic ml-1">Durasi
                                    Penitipan (Hari)</label>
                                <input type="number" x-model="durasi" @input="calculate()" min="1"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg font-black text-slate-900 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            </div>

                            <div class="space-y-1.5" x-show="selectedService === 4">
                                <label class="text-[9px] font-black text-slate-800 uppercase italic ml-1">Estimasi
                                    Jarak Tempuh (KM)</label>
                                <div class="relative">
                                    <input type="number" x-model="jarak" @input="calculate()" min="1"
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg font-black text-slate-900 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="0">
                                    <span
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-indigo-400 uppercase">KM</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                            <i class="fas fa-info-circle text-indigo-500 mt-0.5 text-xs"></i>
                            <p class="text-[9px] text-slate-600 font-bold uppercase italic leading-relaxed">
                                Kalkulasi otomatis sesuai harga terbaru dari database.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BILLING SUMMARY CARD --}}
                <div class="lg:col-span-4">
                    <div
                        class="bg-slate-900 p-6 md:p-7 rounded-2xl text-white space-y-5 shadow-xl overflow-hidden group border border-slate-800 relative">
                        <div
                            class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-600/30 rounded-full blur-2xl group-hover:bg-indigo-500/40 transition-all duration-500">
                        </div>

                        <div class="pb-3 border-b border-white/10 relative text-left">
                            <p class="text-[8px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-1 italic">
                                Summary Report</p>
                            <h4 class="text-xl font-black uppercase italic tracking-tight text-white"
                                x-text="getServiceName()"></h4>
                        </div>

                        <div class="space-y-3 relative text-left">
                            <div class="flex justify-between items-end border-b border-white/5 pb-2.5">
                                <div class="space-y-0.5">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest"
                                        x-text="selectedService === 4 ? 'Harga Sewa Armada' : 'Rate Dasar Harian'"></p>
                                    <p class="text-sm font-black italic text-slate-200"
                                        x-text="formatRupiah(getCurrentRate())"></p>
                                </div>
                                <i class="fas fa-tag text-slate-600 text-sm mb-0.5"></i>
                            </div>

                            <template x-if="selectedService === 4">
                                <div class="flex justify-between items-end border-b border-white/5 pb-2.5">
                                    <div class="space-y-0.5">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tarif
                                            per KM</p>
                                        <p class="text-sm font-black italic text-slate-200"
                                            x-text="formatRupiah(getCurrentKM()) + ' / KM'"></p>
                                    </div>
                                    <i class="fas fa-road text-slate-600 text-sm mb-0.5"></i>
                                </div>
                            </template>
                        </div>

                        <div class="pt-1 relative text-left">
                            <p class="text-indigo-400 text-[9px] font-black uppercase italic mb-1 tracking-[0.2em]">
                                Estimasi Total:</p>
                            <div class="text-3xl font-black italic tracking-tighter text-white"
                                x-text="formatRupiah(total)"></div>
                        </div>

                        <a href="{{ route('register') }}"
                            class="relative flex items-center justify-center gap-2 w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-black text-[9px] uppercase tracking-[0.2em] transition-all shadow-md shadow-indigo-900/50 hover:-translate-y-0.5 mt-2">
                            Booking Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function priceSimulator() {
            return {
                selectedService: 1,
                selectedSubIndex: 0,
                durasi: 1,
                jumlah: 1,
                jarak: 1,
                total: 0,

                services: [{
                        id: 1,
                        name: 'Titip Barang',
                        icon: 'fas fa-boxes-stacked'
                    },
                    {
                        id: 2,
                        name: 'Kendaraan',
                        icon: 'fas fa-motorcycle'
                    },
                    {
                        id: 3,
                        name: 'Bangunan',
                        icon: 'fas fa-building-shield'
                    },
                    {
                        id: 4,
                        name: 'Angkutan',
                        icon: 'fas fa-truck-pickup'
                    }
                ],

                subCategories: {
                    1: @json($kategoriBarang->map(fn($k) => ['name' => $k->nama_kategori, 'rate' => $k->tarif_per_barang]) ?? []),
                    2: @json($kategoriKendaraan->map(fn($k) => ['name' => $k->nama_kategori, 'rate' => $k->harga_per_hari]) ?? []),
                    3: @json($kategoriBangunan->map(fn($k) => ['name' => $k->nama_kategori, 'rate' => $k->harga_per_hari]) ?? []),
                    4: @json($jenisArmada->map(fn($a) => ['name' => $a->nama_jenis, 'rate' => $a->harga_sewa, 'km' => $a->tarif_per_km]) ?? [])
                },

                init() {
                    this.$watch('selectedService', () => {
                        this.selectedSubIndex = 0;
                        this.calculate();
                    });
                    this.calculate();
                },

                getCurrentRate() {
                    const subs = this.subCategories[this.selectedService];
                    if (!subs || subs.length === 0) return 0;
                    return subs[this.selectedSubIndex] ? subs[this.selectedSubIndex].rate : 0;
                },

                getCurrentKM() {
                    const subs = this.subCategories[this.selectedService];
                    if (!subs || subs.length === 0) return 0;
                    return (this.selectedService === 4 && subs[this.selectedSubIndex]) ? subs[this.selectedSubIndex].km : 0;
                },

                calculate() {
                    let rate = parseFloat(this.getCurrentRate()) || 0;
                    if (this.selectedService === 4) {
                        let kmRate = parseFloat(this.getCurrentKM()) || 0;
                        this.total = (rate + (this.jarak * kmRate)) * this.jumlah;
                    } else {
                        this.total = (rate * this.jumlah) * this.durasi;
                    }
                },

                formatRupiah(val) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                },

                getServiceName() {
                    const service = this.services.find(s => s.id === this.selectedService);
                    return service ? service.name : '';
                }
            }
        }
    </script>


    {{-- SECTION 5: FOOTER --}}
    <footer class="bg-slate-900 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 mb-16 text-left">
                <div class="lg:col-span-5 space-y-5">
                    <div class="flex items-center gap-2">
                        <div class="bg-indigo-600 p-2 rounded-lg">
                            <i class="fas fa-box-archive text-white text-sm"></i>
                        </div>
                        <span class="text-lg font-black italic tracking-tighter uppercase text-white">Titip<span
                                class="text-indigo-500">sini</span></span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed italic pr-4">
                        Platform penitipan barang dan aset digital pertama di Sumatera Barat yang mengutamakan keamanan
                        dan kemudahan akses.
                    </p>
                </div>

                <div class="lg:col-span-2 space-y-4">
                    <h5 class="text-white text-[9px] font-black uppercase tracking-widest italic">Layanan</h5>
                    <ul class="text-slate-400 text-[9px] font-bold uppercase tracking-widest space-y-3 italic">
                        <li><a href="#" class="hover:text-indigo-400 transition-colors">Penitipan Barang</a>
                        </li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors">Titip Kendaraan</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors">Jaga Bangunan</a></li>
                        <li><a href="#cek-harga" class="hover:text-indigo-400 transition-colors">Estimasi Biaya</a>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-2 space-y-4">
                    <h5 class="text-white text-[9px] font-black uppercase tracking-widest italic">Kontak</h5>
                    <ul class="text-slate-400 text-[9px] font-bold uppercase tracking-widest space-y-3 italic">
                        <li class="flex items-start gap-2.5"><i
                                class="fas fa-location-dot mt-0.5 text-indigo-500 text-xs"></i> Padang, Sumbar</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-phone text-indigo-500 text-xs"></i>
                            0821-2345-6789</li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-envelope text-indigo-500 text-xs"></i>
                            help@titipsini.com</li>
                    </ul>
                </div>

                <div class="lg:col-span-3 space-y-4">
                    <h5 class="text-white text-[9px] font-black uppercase tracking-widest italic">Newsletter</h5>
                    <p class="text-slate-400 text-[10px] italic">Dapatkan info promo penitipan terbaru.</p>
                    <form class="relative">
                        <input type="email" placeholder="Email Anda" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2.5 text-white text-[10px] outline-none focus:border-indigo-500 transition-colors">
                        <button type="submit"
                            class="absolute right-1 top-1 bottom-1 bg-indigo-600 hover:bg-indigo-500 transition-colors text-white px-3 rounded flex items-center justify-center">
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-[8px] font-black uppercase tracking-widest italic">
                    © {{ date('Y') }} Titipsini.com. All Rights Reserved.
                </p>
                <div class="flex gap-5 text-slate-400 text-sm">
                    <a href="#" class="hover:text-indigo-500 transition-colors"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-indigo-500 transition-colors"><i
                            class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-indigo-500 transition-colors"><i
                            class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-indigo-500 transition-colors"><i
                            class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
