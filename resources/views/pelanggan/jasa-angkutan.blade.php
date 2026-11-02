@extends('layouts.main')

@section('content')
    {{-- CSS & JS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <div class="max-w-7xl mx-auto py-8 px-6 mb-32 animate-fade-in" x-data="angkutanForm()" x-init="initMap()">
        
        {{-- HEADER --}}
        <div class="text-left border-b-2 border-slate-900 pb-3 flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-2">
            <div>
                <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Order Angkutan Barang</h2>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Layanan Pengiriman Cargo & Pindahan</p>
            </div>
            <span class="text-[8px] font-black text-white bg-indigo-600 px-2 py-1 rounded uppercase tracking-widest italic shadow-sm inline-block w-fit">
                <i class="fas fa-check-circle mr-1"></i> Route Sync Verified
            </span>
        </div>

        <form action="{{ route('pelanggan.order_angkutan.store') }}" method="POST" id="mainForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- KOLOM KIRI: FORM --}}
                <div class="lg:col-span-8 space-y-6">
                    
                    {{-- 01. RUTE & ARMADA --}}
                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-900 px-5 py-3">
                            <h3 class="text-[10px] font-black text-white uppercase italic tracking-widest text-left">01. Rute & Jenis Armada</h3>
                        </div>
                        
                        <div class="p-5 space-y-6 text-left">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-900 uppercase italic">Titik Penjemputan</label>
                                    <select name="alamat_jemput_id" id="select_jemput" x-model="alamatJemputId" @change="updateRoute()" required
                                        class="w-full border-slate-200 rounded-lg text-xs font-bold py-2.5 px-3 shadow-sm focus:border-indigo-600 focus:ring-0 outline-none transition-colors cursor-pointer text-slate-700">
                                        <option value="">-- PILIH LOKASI JEMPUT --</option>
                                        @foreach ($alamats as $almt)
                                            <option value="{{ $almt->id }}" data-lat="{{ $almt->latitude }}" data-lng="{{ $almt->longitude }}">
                                                {{ strtoupper($almt->nama_penerima) }} - {{ Str::limit($almt->alamat_lengkap, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-900 uppercase italic">Titik Tujuan Akhir</label>
                                    <select name="alamat_tujuan_id" id="select_tujuan" x-model="alamatTujuanId" @change="updateRoute()" required
                                        class="w-full border-slate-200 rounded-lg text-xs font-bold py-2.5 px-3 shadow-sm focus:border-indigo-600 focus:ring-0 outline-none transition-colors cursor-pointer text-slate-700">
                                        <option value="">-- PILIH LOKASI TUJUAN --</option>
                                        @foreach ($alamats as $almt)
                                            <option value="{{ $almt->id }}" data-lat="{{ $almt->latitude }}" data-lng="{{ $almt->longitude }}">
                                                {{ strtoupper($almt->nama_penerima) }} - {{ Str::limit($almt->alamat_lengkap, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-900 uppercase italic">Pilih Armada Angkut</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($jenisArmadas as $armada)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="jenis_armada_id" value="{{ $armada->id }}" required class="hidden peer" x-model="armadaId"
                                                @change="updateArmada('{{ $armada->nama_jenis }}', {{ $armada->harga_sewa }}, {{ $armada->tarif_per_km }})">
                                            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 transition-all hover:border-slate-300">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <span class="text-[10px] font-black text-slate-900 uppercase italic">{{ $armada->nama_jenis }}</span>
                                                    <i class="fas fa-truck text-slate-400 peer-checked:text-indigo-500"></i>
                                                </div>
                                                <p class="text-[8px] text-slate-500 font-bold uppercase tracking-widest leading-relaxed">
                                                    Dasar: Rp {{ number_format($armada->harga_sewa, 0, ',', '.') }}<br>
                                                    Jarak: Rp {{ number_format($armada->tarif_per_km, 0, ',', '.') }} / KM
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 02. JADWAL & MUATAN --}}
                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-slate-900 px-5 py-3">
                            <h3 class="text-[10px] font-black text-white uppercase italic tracking-widest text-left">02. Jadwal & Muatan</h3>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-900 uppercase italic">Waktu Pengangkutan</label>
                                <div class="flex gap-2">
                                    <input type="date" name="tanggal_angkut" required min="{{ date('Y-m-d') }}"
                                        class="w-full border-slate-200 rounded-lg text-xs font-bold py-2.5 px-3 shadow-sm focus:border-indigo-600 focus:ring-0 outline-none text-slate-700">
                                    <input type="time" name="jam_angkut" required
                                        class="w-32 shrink-0 border-slate-200 rounded-lg text-xs font-bold py-2.5 px-3 shadow-sm focus:border-indigo-600 focus:ring-0 outline-none text-slate-700 text-center">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-900 uppercase italic">Deskripsi Ringkas Barang</label>
                                <input type="text" name="catatan_muatan" required placeholder="Cth: 1 Kulkas, 3 Kardus Pakaian..."
                                    class="w-full border-slate-200 rounded-lg text-[10px] font-bold py-3 px-3 shadow-sm focus:border-indigo-600 focus:ring-0 outline-none text-slate-700 uppercase placeholder:text-slate-300">
                            </div>
                        </div>
                    </div>

                    {{-- 03. MAP ROUTING --}}
                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm relative">
                        <div id="map" class="w-full h-[300px] z-0 bg-slate-100"></div>
                        
                        {{-- Overlay Loading Jarak --}}
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm p-3 rounded-lg shadow-md border border-slate-100 z-[10] text-left">
                            <p class="text-[8px] font-black text-slate-400 uppercase italic leading-none mb-1 tracking-widest">Estimasi Jarak Tempuh:</p>
                            <h4 class="text-xl font-black text-indigo-600 italic tracking-tighter leading-none"><span x-text="jarak"></span> <span class="text-xs text-slate-500">KM</span></h4>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: BILLING SUMMARY (STICKY) --}}
                <div class="lg:col-span-4 lg:sticky lg:top-8">
                    <div class="bg-white border-t-4 border-slate-900 rounded-xl p-5 shadow-xl border border-slate-100 text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4 italic border-b border-slate-100 pb-2">Billing Summary</p>
                        
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between items-center text-[10px] font-bold uppercase italic text-slate-600">
                                <span>Estimasi Jarak</span>
                                <span class="text-slate-900" x-text="jarak + ' KM'"></span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-bold uppercase italic text-slate-600">
                                <span>Biaya Dasar Armada</span>
                                <span class="text-slate-900" x-text="'Rp ' + formatNumber(hargaSewa)"></span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-bold uppercase italic text-slate-600">
                                <span>Tarif Jarak (KM)</span>
                                <span class="text-slate-900" x-text="'Rp ' + formatNumber(Math.round(jarak * tarifKm))"></span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-col items-start mb-5">
                            <p class="text-[8px] font-black text-indigo-600 uppercase italic mb-1 tracking-widest">Estimasi Total Bayar:</p>
                            <h3 class="text-2xl font-black italic tracking-tighter text-slate-900 leading-none">
                                Rp <span x-text="formatNumber(totalAkhir)"></span>
                            </h3>
                        </div>

                        <button type="button" onclick="document.getElementById('mainForm').requestSubmit()" :disabled="!armadaId || jarak == 0"
                            class="w-full bg-slate-900 text-white py-3 rounded-lg font-black text-[10px] uppercase tracking-widest transition-all shadow-md hover:bg-indigo-600 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-slate-900 flex items-center justify-center gap-2">
                            Pesan Armada Sekarang <i class="fas fa-truck-fast"></i>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <style>
        /* Sembunyikan instruksi rute bawaan leaflet yang mengganggu UI */
        .leaflet-routing-container { display: none !important; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    
    <script>
        function angkutanForm() {
            return {
                map: null,
                routingControl: null,
                armadaId: '',
                armadaNama: '',
                hargaSewa: 0,
                tarifKm: 0,
                alamatJemputId: '',
                alamatTujuanId: '',
                jarak: 0,
                totalAkhir: 0,

                initMap() {
                    this.map = L.map('map', { zoomControl: false }).setView([-0.947, 100.417], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                },

                updateArmada(nama, harga, tarif) {
                    this.armadaNama = nama;
                    this.hargaSewa = harga;
                    this.tarifKm = tarif;
                    this.calculateTotal();
                },

                updateRoute() {
                    this.$nextTick(() => {
                        const selJemput = document.getElementById('select_jemput');
                        const selTujuan = document.getElementById('select_tujuan');

                        if(!selJemput.selectedIndex || !selTujuan.selectedIndex) return;

                        const opt1 = selJemput.options[selJemput.selectedIndex];
                        const opt2 = selTujuan.options[selTujuan.selectedIndex];

                        if (this.alamatJemputId && this.alamatTujuanId && opt1.dataset.lat && opt2.dataset.lat) {
                            if (this.routingControl) {
                                this.map.removeControl(this.routingControl);
                            }

                            // Render rute baru
                            this.routingControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(opt1.dataset.lat, opt1.dataset.lng),
                                    L.latLng(opt2.dataset.lat, opt2.dataset.lng)
                                ],
                                routeWhileDragging: false,
                                addWaypoints: false,
                                show: false,
                                createMarker: function() { return null; }, // Matikan marker default routing machine
                                lineOptions: {
                                    styles: [{ color: '#4f46e5', weight: 5, opacity: 0.9 }]
                                }
                            }).on('routesfound', (e) => {
                                const routes = e.routes;
                                this.jarak = (routes[0].summary.totalDistance / 1000).toFixed(2);
                                this.calculateTotal();

                                // Fit bounds agar rute pas di tengah map
                                const bounds = L.latLngBounds([
                                    [opt1.dataset.lat, opt1.dataset.lng],
                                    [opt2.dataset.lat, opt2.dataset.lng]
                                ]);
                                this.map.fitBounds(bounds, { padding: [40, 40] });
                            }).addTo(this.map);
                        }
                    });
                },

                calculateTotal() {
                    const biayaJarak = parseFloat(this.jarak) * parseFloat(this.tarifKm);
                    this.totalAkhir = Math.round(parseFloat(this.hargaSewa) + (isNaN(biayaJarak) ? 0 : biayaJarak));
                },

                formatNumber(n) {
                    if(isNaN(n)) return '0';
                    return new Intl.NumberFormat('id-ID').format(n);
                }
            }
        }
    </script>
@endsection