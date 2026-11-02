@extends('layouts.main')

@section('content')
    {{-- CSS Leaflet untuk Peta --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-7xl mx-auto py-6 px-4 mb-32" x-data="orderForm()">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-5">

                <div class="text-left border-b-2 border-slate-900 pb-2 flex justify-between items-end">
                    <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Form Penitipan Bangunan
                    </h2>
                    <span
                        class="text-[8px] font-bold text-slate-900 uppercase tracking-widest italic mb-1 uppercase text-left">
                        Security Verified by Titipsini
                    </span>
                </div>

                {{-- FORM UTAMA --}}
                <form id="mainOrderForm" action="{{ route('pelanggan.order-bangunan.store') }}" method="POST"
                    class="space-y-5 text-left">
                    @csrf

                    {{-- 01. DURASI --}}
                    {{-- 01. DURASI --}}
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div class="text-left">
                            <label
                                class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Tanggal
                                Mulai</label>
                            {{-- JEDER! Lock hari ini --}}
                            <input type="date" name="tanggal_mulai" x-model="tanggalMulai" :min="today"
                                @change="calculate()"
                                class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900"
                                required>
                        </div>
                        <div class="text-left">
                            <label
                                class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Estimasi
                                Selesai</label>
                            {{-- JEDER! Lock minimal sama dengan tanggal mulai --}}
                            <input type="date" name="tanggal_selesai" x-model="tanggalSelesai"
                                :min="tanggalMulai || today" @change="calculate()"
                                class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900"
                                required>
                        </div>
                    </div>

                    {{-- 02. DETAIL ASET (1 UNIT) --}}
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-slate-900 px-4 py-2">
                            <h3 class="text-[9px] font-black text-white uppercase italic tracking-widest">02. Detail Aset
                                Bangunan</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                                <div>
                                    <label
                                        class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Kategori
                                        Bangunan</label>
                                    <select name="kategori_id" x-model="selectedKategoriId" @change="calculate()"
                                        class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900 cursor-pointer"
                                        required>
                                        <option value="">PILIH KATEGORI...</option>
                                        @foreach ($kategoris as $k)
                                            <option value="{{ $k->id }}" data-harga="{{ $k->harga_per_hari }}">
                                                {{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Nama
                                        Bangunan / Aset</label>
                                    <input type="text" name="nama_aset"
                                        placeholder="CONTOH: RUMAH TIPE 36, RUKO PUSAT..."
                                        class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900 uppercase tracking-tight"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 03. LOKASI PENITIPAN --}}
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm"
                        x-data="{ showAllAlamat: false }">
                        <div class="bg-slate-900 px-4 py-2 flex items-center justify-between">
                            <h3 class="text-[9px] font-black text-white uppercase italic tracking-widest">03. Lokasi
                                Bangunan</h3>
                            <button type="button" @click="showAddAlamat = true"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-[8px] font-black px-3 py-1 rounded-md transition-all uppercase italic tracking-widest">
                                + Lokasi Baru
                            </button>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-[9px] font-black text-slate-900 uppercase italic">Pilih Lokasi Aset:</p>
                                <button type="button" @click="showAllAlamat = !showAllAlamat"
                                    class="text-[8px] font-black text-indigo-600 uppercase italic hover:underline">
                                    <span x-text="showAllAlamat ? 'Tutup' : 'Ganti Lokasi'"></span>
                                </button>
                            </div>

                            <input type="hidden" name="alamat_id" :value="selectedAlamatId">

                            {{-- Alamat Terpilih --}}
                            <div x-show="!showAllAlamat" class="animate-fade-in">
                                <template x-for="almt in alamats.filter(a => a.id == selectedAlamatId)"
                                    :key="almt.id">
                                    <div
                                        class="p-3 border-2 border-slate-900 rounded bg-slate-50 flex justify-between items-center">
                                        <div class="text-left">
                                            <p class="text-[9px] font-black text-slate-900 uppercase italic"
                                                x-text="almt.nama_penerima"></p>
                                            <p class="text-[8px] text-slate-900 font-bold uppercase mt-0.5 italic opacity-70"
                                                x-text="almt.alamat_lengkap"></p>
                                        </div>
                                        <span
                                            class="text-[6px] bg-slate-900 text-white px-1.5 py-0.5 rounded uppercase font-black italic">Terpilih</span>
                                    </div>
                                </template>
                            </div>

                            {{-- Daftar Alamat --}}
                            <div x-show="showAllAlamat" class="grid grid-cols-1 md:grid-cols-2 gap-2 animate-fade-in">
                                <template x-for="almt in alamats" :key="almt.id">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" :value="almt.id" class="hidden peer"
                                            x-model="selectedAlamatId" @change="showAllAlamat = false">
                                        <div
                                            class="p-3 border border-slate-200 rounded bg-white peer-checked:border-slate-900 peer-checked:bg-slate-50 transition-all text-left">
                                            <p class="text-[9px] font-black text-slate-900 uppercase italic"
                                                x-text="almt.nama_penerima"></p>
                                            <p class="text-[8px] text-slate-900 font-bold uppercase mt-0.5 line-clamp-1 italic opacity-70"
                                                x-text="almt.alamat_lengkap"></p>
                                        </div>
                                    </label>
                                </template>
                            </div>

                            <div class="text-left pt-2 border-t border-slate-100">
                                <label
                                    class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Catatan
                                    Tambahan (Opsional)</label>
                                <textarea name="catatan_pelanggan" rows="2" placeholder="CONTOH: TITIP KUNCI DI TETANGGA..."
                                    class="w-full border border-slate-200 rounded text-[10px] font-bold py-2 px-3 outline-none focus:border-slate-900 bg-white text-slate-900 uppercase italic"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- BILLING SUMMARY (STICKY) --}}
            <div class="lg:col-span-4 lg:sticky lg:top-10">
                <div
                    class="bg-white border-t-4 border-slate-900 rounded-lg p-6 shadow-xl text-left border border-slate-100">
                    <p
                        class="text-[8px] font-black text-slate-900 uppercase tracking-widest mb-5 italic border-b pb-1 uppercase">
                        Billing Summary</p>
                    <div class="space-y-4 text-left">
                        <div
                            class="flex justify-between items-center text-[9px] font-bold uppercase italic tracking-widest text-slate-900">
                            <span>Estimasi (<span x-text="totalHari"></span> Hari)</span>
                            <span x-text="'Rp ' + formatNumber(totalPrice)"></span>
                        </div>
                        <div class="pt-5 border-t border-slate-900 flex flex-col items-start text-left">
                            <p class="text-[8px] font-black text-indigo-600 uppercase italic mb-0.5">Grand Total Estimasi:
                            </p>
                            <h3 class="text-3xl font-black italic tracking-tighter text-slate-900 leading-none">
                                Rp <span x-text="formatNumber(totalPrice)"></span>
                            </h3>
                        </div>
                        <div class="pt-6">
                            <button type="button" @click="submitForm()"
                                class="w-full bg-slate-900 text-white py-4 rounded font-black text-[10px] uppercase tracking-widest hover:bg-slate-700 transition-all active:scale-95 italic shadow-lg">
                                KONFIRMASI TITIP BANGUNAN 🚀
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL LOKASI BARU --}}
        <div x-show="showAddAlamat"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak
            x-transition>
            <div class="bg-white w-full max-w-5xl max-h-[600px] flex flex-col rounded-xl shadow-2xl overflow-hidden text-left"
                @click.away="showAddAlamat = false">
                <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-[11px] font-black text-slate-900 uppercase italic tracking-tighter">Daftarkan Lokasi
                        Bangunan Baru</h3>
                    <button type="button" @click="showAddAlamat = false"
                        class="text-slate-900 hover:text-rose-500 font-black text-sm">✕</button>
                </div>

                <div class="flex flex-col lg:flex-row flex-1 overflow-hidden">
                    {{-- Form Panel --}}
                    <div class="w-full lg:w-1/2 p-6 overflow-y-auto custom-scrollbar bg-white">
                        <form id="formAlamatAjax" class="space-y-3">
                            @csrf
                            <input type="hidden" name="latitude" id="lat_input">
                            <input type="hidden" name="longitude" id="lng_input">

                            <div class="grid grid-cols-2 gap-3 text-left">
                                <div class="space-y-0.5 text-left">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">Label
                                        Lokasi</label>
                                    <input type="text" name="nama_penerima" placeholder="CONTOH: RUKO SUDIRMAN"
                                        required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                                <div class="space-y-0.5 text-left">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">Kontak
                                        (WA)</label>
                                    <input type="text" name="telepon" placeholder="08..." required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold text-slate-900">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-left">
                                <div class="space-y-0.5 text-left"><label
                                        class="text-[7px] font-black text-slate-900 uppercase italic">Provinsi</label>
                                    <input type="text" name="provinsi" id="in_provinsi" value="Sumatera Barat"
                                        required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                                <div class="space-y-0.5 text-left"><label
                                        class="text-[7px] font-black text-slate-900 uppercase italic">Kota</label>
                                    <input type="text" name="kab_kota" id="in_kota" required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                                <div class="space-y-0.5 text-left"><label
                                        class="text-[7px] font-black text-slate-900 uppercase italic">Kecamatan</label>
                                    <input type="text" name="kecamatan" id="in_kecamatan" required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                            </div>

                            <div class="space-y-0.5 text-left">
                                <label class="text-[7px] font-black text-slate-900 uppercase italic">Alamat Lengkap
                                    Aset</label>
                                <textarea name="alamat_lengkap" id="in_alamat" rows="2" required
                                    class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900 resize-none"></textarea>
                            </div>

                            <div class="pt-4 flex gap-2">
                                <button type="button" @click="showAddAlamat = false"
                                    class="flex-1 py-2 text-[8px] font-black uppercase text-slate-400 italic">Batal</button>
                                <button type="button" @click="simpanAlamatBaru()"
                                    class="flex-2 bg-slate-900 text-white px-5 py-2 rounded text-[8px] font-black uppercase italic hover:bg-indigo-600 transition-all">Simpan
                                    Lokasi</button>
                            </div>
                        </form>
                    </div>

                    {{-- Map Panel --}}
                    <div class="w-full lg:w-1/2 h-[250px] lg:h-auto relative bg-slate-100 border-l border-slate-200">
                        <div id="map" class="absolute inset-0 z-0"></div>
                        <div class="absolute bottom-3 left-3 right-3 z-10 text-left">
                            <div id="geoStatus"
                                class="bg-white/90 backdrop-blur px-2 py-1.5 rounded shadow text-[7px] font-black text-slate-900 uppercase italic">
                                📍 KLIK ATAU GESER PIN KE LOKASI BANGUNAN
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Peta & Logic --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        function orderForm() {
            return {
                showAddAlamat: false,
                selectedAlamatId: '{{ $alamats->where('is_utama', 1)->first()->id ?? ($alamats->first()->id ?? '') }}',
                alamats: @json($alamats),
                today: new Date().toISOString().split('T')[0],
                tanggalMulai: '',
                tanggalSelesai: '',
                selectedKategoriId: '',
                totalHari: 0,
                totalPrice: 0,

                init() {
                    setTimeout(() => {
                        map = L.map('map', {
                            zoomControl: false
                        }).setView([-0.947, 100.417], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                        marker = L.marker([-0.947, 100.417], {
                            draggable: true
                        }).addTo(map);
                        marker.on('dragend', () => {
                            const pos = marker.getLatLng();
                            this.reverseGeocode(pos.lat, pos.lng);
                        });
                        map.on('click', (e) => {
                            marker.setLatLng(e.latlng);
                            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                        });
                    }, 500);

                    this.$watch('showAddAlamat', value => {
                        if (value) setTimeout(() => {
                            map.invalidateSize()
                        }, 400);
                    });
                },

                async simpanAlamatBaru() {
                    const form = document.getElementById('formAlamatAjax');
                    const formData = new FormData(form);
                    try {
                        const response = await fetch("{{ route('pelanggan.alamat.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (response.ok) {
                            this.alamats.push(result.data);
                            this.selectedAlamatId = result.data.id;
                            this.showAddAlamat = false;
                            form.reset();
                        }
                    } catch (e) {
                        alert('Gagal menyimpan alamat.');
                    }
                },

                async reverseGeocode(lat, lng) {
                    document.getElementById('lat_input').value = lat;
                    document.getElementById('lng_input').value = lng;
                    const status = document.getElementById('geoStatus');
                    status.innerHTML = "⚡ MENCARI ALAMAT...";
                    try {
                        const res = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`
                        );
                        const data = await res.json();
                        if (data.address) {
                            const addr = data.address;
                            document.getElementById('in_provinsi').value = addr.state || 'Sumatera Barat';
                            document.getElementById('in_kota').value = addr.city || addr.town || addr.county || '';
                            document.getElementById('in_kecamatan').value = addr.suburb || addr.village || addr
                                .neighbourhood || '';
                            document.getElementById('in_alamat').value = data.display_name || '';
                            status.innerHTML = "✅ LOKASI DITEMUKAN";
                        }
                    } catch (e) {
                        status.innerHTML = "📍 KOORDINAT TERKUNCI";
                    }
                },

                calculate() {
                    if (this.tanggalMulai && this.tanggalSelesai) {
                        const start = new Date(this.tanggalMulai);
                        const end = new Date(this.tanggalSelesai);
                        const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                        this.totalHari = diff > 0 ? diff : 0;
                    }
                    if (this.selectedKategoriId) {
                        const opt = document.querySelector(`option[value="${this.selectedKategoriId}"]`);
                        this.totalPrice = (parseFloat(opt.dataset.harga) || 0) * this.totalHari;
                    }
                },

                formatNumber(n) {
                    return new Intl.NumberFormat('id-ID').format(n);
                },

                submitForm() {
                    if (!this.selectedAlamatId || this.totalPrice <= 0) {
                        alert('Lengkapi data dan pilih lokasi bangunan, Raja!');
                        return;
                    }
                    document.getElementById('mainOrderForm').submit();
                }
            }
        }
    </script>
@endsection
