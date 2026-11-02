@extends('layouts.main')

@section('content')
    {{-- CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-7xl mx-auto py-6 px-4 mb-32" x-data="orderForm()">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-5">

                <div class="text-left border-b-2 border-slate-900 pb-2 flex justify-between items-end">
                    <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Form Penitipan Barang
                    </h2>
                    <span class="text-[8px] font-bold text-slate-900 uppercase tracking-widest italic mb-1 text-left">
                        Security Verified by Titipsini
                    </span>
                </div>

                <form id="mainOrderForm" action="{{ route('pelanggan.titip.store') }}" method="POST"
                    class="space-y-5 text-left">
                    @csrf

                    {{-- 01. Waktu Operasional --}}
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div class="text-left">
                            <label
                                class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Tanggal
                                Masuk</label>
                            <input type="date" name="tanggal_mulai" x-model="tanggalMulai" :min="today"
                                @change="calculate()"
                                class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900"
                                required>
                        </div>
                        <div class="text-left">
                            <label
                                class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Estimasi
                                Selesai</label>
                            <input type="date" name="tanggal_selesai" x-model="tanggalSelesai"
                                :min="tanggalMulai ? tanggalMulai : today" @change="calculate()"
                                class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900"
                                required>
                        </div>
                    </div>

                    {{-- 02. Inventaris Barang --}}
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-slate-900 px-4 py-2 flex justify-between items-center">
                            <h3 class="text-[9px] font-black text-white uppercase italic tracking-widest">02. Inventaris
                            </h3>
                            <button type="button" @click="addItem()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-[8px] font-black px-3 py-1 rounded-md transition-all uppercase italic tracking-widest">
                                + Tambah Unit
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead
                                    class="bg-slate-50 text-[8px] font-black text-slate-900 uppercase italic border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-black italic">Kategori</th>
                                        <th class="px-4 py-2 text-left font-black italic">Merk/Tipe Barang</th>
                                        <th class="px-4 py-2 text-center w-20 font-black italic">Qty</th>
                                        <th class="px-2 py-2 w-8"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-slate-50 transition-all">
                                            <td class="px-4 py-1.5 text-left">
                                                <select x-model="item.kategori_id"
                                                    :name="'items[' + index + '][kategori_barang_id]'" @change="calculate()"
                                                    class="w-full border-none bg-transparent p-0 text-[10px] font-bold focus:ring-0 cursor-pointer text-slate-900"
                                                    required>
                                                    <option value="">Pilih...</option>
                                                    @foreach ($kategoris as $k)
                                                        <option value="{{ $k->id }}"
                                                            data-harga="{{ $k->tarif_per_barang }}"
                                                            data-ongkir="{{ $k->biaya_jemput }}">{{ $k->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-1.5 text-left">
                                                <input type="text" :name="'items[' + index + '][nama_barang_spesifik]'"
                                                    x-model="item.nama_barang" placeholder="DETAIL UNIT..."
                                                    class="w-full border-none bg-transparent p-0 text-[10px] font-bold focus:ring-0 placeholder:text-slate-300 uppercase tracking-tight text-slate-900"
                                                    required>
                                            </td>
                                            <td class="px-4 py-1.5 text-left">
                                                <input type="number" x-model="item.qty"
                                                    :name="'items[' + index + '][jumlah]'" min="1"
                                                    @input="calculate()"
                                                    class="w-14 border border-slate-200 rounded bg-white text-[10px] font-black text-center py-0.5 focus:ring-0 text-slate-900 shadow-inner"
                                                    required>
                                            </td>
                                            <td class="px-2 py-1.5 text-center">
                                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                    class="text-slate-400 hover:text-rose-500">✕</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 border-t border-slate-100 bg-slate-50/50 text-left">
                            <label
                                class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Catatan
                                Khusus (Opsional)</label>
                            <textarea name="catatan_pelanggan" rows="2" placeholder="INSTRUKSI PENANGANAN BARANG..."
                                class="w-full border border-slate-200 rounded text-[10px] font-bold py-2 px-3 outline-none focus:border-slate-900 bg-white placeholder:text-slate-200 text-slate-900">{{ old('catatan_pelanggan') }}</textarea>
                        </div>
                    </div>

                    {{-- 03. Logistik --}}
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm"
                        x-data="{ showAllAlamat: false }">
                        <div class="bg-slate-900 px-4 py-2 flex items-center justify-between">
                            <h3 class="text-[9px] font-black text-white uppercase italic tracking-widest">03. Logistik</h3>
                            <button type="button" @click="showAddAlamat = true"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-[8px] font-black px-3 py-1 rounded-md transition-all uppercase italic tracking-widest">
                                + Lokasi Baru
                            </button>
                        </div>
                        <div class="p-4 space-y-4">
                            {{-- Pilihan Metode --}}
                            <div class="grid grid-cols-2 gap-3 text-left">
                                <label
                                    class="flex items-center justify-center py-2.5 rounded border-2 cursor-pointer transition-all"
                                    :class="metode === 'jemput' ? 'bg-slate-900 border-slate-900 text-white' :
                                        'bg-white border-slate-200 text-slate-900'">
                                    <input type="radio" name="opsi_penjemputan" value="jemput" x-model="metode"
                                        @change="calculate()" class="hidden">
                                    <span class="text-[9px] font-black uppercase italic tracking-widest">Kurir
                                        Pick-Up</span>
                                </label>
                                <label
                                    class="flex items-center justify-center py-2.5 rounded border-2 cursor-pointer transition-all"
                                    :class="metode === 'antar_sendiri' ? 'bg-slate-900 border-slate-900 text-white' :
                                        'bg-white border-slate-200 text-slate-900'">
                                    <input type="radio" name="opsi_penjemputan" value="antar_sendiri" x-model="metode"
                                        @change="calculate()" class="hidden">
                                    <span class="text-[9px] font-black uppercase italic tracking-widest">Antar
                                        Mandiri</span>
                                </label>
                            </div>

                            {{-- Section Alamat Dinamis --}}
                            <div class="border-t border-slate-100 pt-4 text-left">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-[9px] font-black text-slate-900 uppercase italic">
                                        <span
                                            x-text="metode === 'jemput' ? ' Lokasi Penjemputan:' : 'Alamat Pengirim:'"></span>
                                    </p>
                                    <button type="button" @click="showAllAlamat = !showAllAlamat"
                                        class="text-[8px] font-black text-indigo-600 uppercase italic hover:underline">
                                        <span x-text="showAllAlamat ? 'Tutup' : 'Ganti Alamat'"></span>
                                    </button>
                                </div>

                                {{-- Tampilan Alamat terpilih --}}
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
                                                class="text-[6px] bg-slate-900 text-white px-1.5 py-0.5 rounded uppercase font-black">Terpilih</span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Daftar Pilihan Alamat --}}
                                <div x-show="showAllAlamat" class="grid grid-cols-1 md:grid-cols-2 gap-2 animate-fade-in">
                                    <template x-for="almt in alamats" :key="almt.id">
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="alamat_id" :value="almt.id"
                                                class="hidden peer" x-model="selectedAlamatId"
                                                @change="showAllAlamat = false">
                                            <div
                                                class="p-3 border border-slate-200 rounded bg-white peer-checked:border-slate-900 peer-checked:bg-slate-50 transition-all text-left group">
                                                <p class="text-[9px] font-black text-slate-900 uppercase italic"
                                                    x-text="almt.nama_penerima"></p>
                                                <p class="text-[8px] text-slate-900 font-bold uppercase mt-0.5 line-clamp-1 italic opacity-70"
                                                    x-text="almt.alamat_lengkap"></p>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: BILLING SUMMARY (STICKY) --}}
            <div class="lg:col-span-4 lg:sticky lg:top-10">
                <div
                    class="bg-white border-t-4 border-slate-900 rounded-lg p-6 shadow-xl text-left border border-slate-100">
                    <p
                        class="text-[8px] font-black text-slate-900 uppercase tracking-widest mb-5 italic border-b pb-1 text-left">
                        Billing Summary
                    </p>
                    <div class="space-y-4 text-left">
                        <div
                            class="flex justify-between items-center text-[9px] font-bold uppercase italic tracking-widest text-left text-slate-900">
                            <span>Sewa (<span x-text="totalHari"></span> Hari)</span>
                            <span x-text="'Rp ' + formatNumber(subtotalSewa)"></span>
                        </div>
                        <div
                            class="flex justify-between items-center text-[9px] font-bold uppercase italic tracking-widest text-left text-slate-900">
                            <span>Logistik</span>
                            <span x-text="metode === 'jemput' ? 'Rp ' + formatNumber(maxOngkir) : 'Rp 0'"></span>
                        </div>
                        <div class="pt-5 border-t border-slate-900 flex flex-col items-start text-left">
                            <p class="text-[8px] font-black text-indigo-600 uppercase italic mb-0.5 text-left">Grand Total:
                            </p>
                            <h3 class="text-3xl font-black italic tracking-tighter text-slate-900 leading-none text-left">
                                Rp <span x-text="formatNumber(totalAkhir)"></span>
                            </h3>
                        </div>
                        <div class="pt-6">
                            {{-- MODIFIKASI: Menggunakan requestSubmit() agar validasi required HTML5 tidak di-bypass --}}
                            <button type="button" onclick="document.getElementById('mainOrderForm').requestSubmit()"
                                class="w-full bg-slate-900 text-white py-4 rounded font-black text-[10px] uppercase tracking-widest hover:bg-slate-700 transition-all active:scale-95 italic shadow-lg">
                                KONFIRMASI 🚀
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL POPUP ALAMAT (AJAX & PETA) --}}
        <div x-show="showAddAlamat"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak
            x-transition>
            <div class="bg-white w-full max-w-5xl max-h-[600px] flex flex-col rounded-xl shadow-2xl overflow-hidden text-left"
                @click.away="showAddAlamat = false">

                <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-[11px] font-black text-slate-900 uppercase italic tracking-tighter">Daftarkan Lokasi
                        Baru</h3>
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
                                <div class="space-y-0.5">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">Label</label>
                                    <input type="text" name="nama_penerima" placeholder="CONTOH: RUMAH" required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                                <div class="space-y-0.5 text-left">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">WhatsApp</label>
                                    <input type="text" name="telepon" placeholder="08..." required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold text-slate-900">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-left">
                                <div class="space-y-0.5">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">Provinsi</label>
                                    <input type="text" name="provinsi" id="in_provinsi" value="Sumatera Barat"
                                        required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                                <div class="space-y-0.5">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">Kota</label>
                                    <input type="text" name="kab_kota" id="in_kota" required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                                <div class="space-y-0.5">
                                    <label class="text-[7px] font-black text-slate-900 uppercase italic">Kecamatan</label>
                                    <input type="text" name="kecamatan" id="in_kecamatan" required
                                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900">
                                </div>
                            </div>

                            <div class="space-y-0.5 text-left">
                                <label class="text-[7px] font-black text-slate-900 uppercase italic">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" id="in_alamat" rows="2" required
                                    class="w-full p-2 bg-slate-50 border border-slate-200 rounded text-[9px] font-bold uppercase text-slate-900 resize-none"></textarea>
                            </div>

                            <div class="pt-4 flex gap-2">
                                <button type="button" @click="showAddAlamat = false"
                                    class="flex-1 py-2 text-[8px] font-black uppercase text-slate-400 italic">Batal</button>
                                <button type="button" @click="simpanAlamatBaru()"
                                    class="flex-2 bg-slate-900 text-white px-5 py-2 rounded text-[8px] font-black uppercase italic hover:bg-indigo-600 transition-all">
                                    Simpan Lokasi
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Map Panel --}}
                    <div class="w-full lg:w-1/2 h-[200px] lg:h-auto relative bg-slate-100 border-l border-slate-200">
                        <div id="map" class="absolute inset-0 z-0"></div>
                        <div class="absolute bottom-3 left-3 right-3 z-10 text-left">
                            <div id="geoStatus"
                                class="bg-white/90 backdrop-blur px-2 py-1.5 rounded shadow text-[7px] font-black text-slate-900 uppercase italic">
                                📍 GESER PIN KE LOKASI JEMPUT
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        function orderForm() {
            return {
                // Mengambil nilai lama (old) dari Laravel jika terjadi gagal validasi di server
                metode: '{{ old('opsi_penjemputan', 'jemput') }}',
                showAddAlamat: false,
                selectedAlamatId: @json($alamats->where('is_utama', 1)->first()->id ?? ($alamats->first()->id ?? null)),
                alamats: @json($alamats),
                today: new Date().toISOString().split('T')[0],
                tanggalMulai: '{{ old('tanggal_mulai', '') }}',
                tanggalSelesai: '{{ old('tanggal_selesai', '') }}',
                items: [{
                    kategori_id: '',
                    nama_barang: '',
                    qty: 1
                }],
                subtotalSewa: 0,
                maxOngkir: 0,
                totalAkhir: 0,
                totalHari: 0,

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

                        // Hitung ulang jika ada data 'old' dari session laravel
                        this.calculate();
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
                        alert('Error simpan alamat.');
                    }
                },

                addItem() {
                    this.items.push({
                        kategori_id: '',
                        nama_barang: '',
                        qty: 1
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculate();
                },

                async reverseGeocode(lat, lng) {
                    document.getElementById('lat_input').value = lat;
                    document.getElementById('lng_input').value = lng;
                    const status = document.getElementById('geoStatus');
                    status.innerHTML = "⚡ MENYINKRONKAN...";
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
                            status.innerHTML = "✅ LOKASI TERKUNCI";
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
                        this.totalHari = diff > 0 ? diff : 1;
                    } else {
                        this.totalHari = 0;
                    }

                    let currentSewa = 0;
                    let currentMaxOngkir = 0;

                    this.items.forEach((item, index) => {
                        if (item.kategori_id) {
                            // Mencari secara presisi element select berdasarkan index baris perulangan
                            const selectEl = document.querySelector(
                                `select[name="items[${index}][kategori_barang_id]"]`);
                            if (selectEl) {
                                const opt = selectEl.options[selectEl.selectedIndex];
                                if (opt && opt.value !== "") {
                                    const harga = parseFloat(opt.getAttribute('data-harga') || 0);
                                    const ongkir = parseFloat(opt.getAttribute('data-ongkir') || 0);

                                    currentSewa += (harga * item.qty) * this.totalHari;
                                    if (ongkir > currentMaxOngkir) currentMaxOngkir = ongkir;
                                }
                            }
                        }
                    });

                    this.subtotalSewa = currentSewa;
                    this.maxOngkir = currentMaxOngkir;
                    this.totalAkhir = this.subtotalSewa + (this.metode === 'jemput' ? this.maxOngkir : 0);
                },

                formatNumber(n) {
                    return new Intl.NumberFormat('id-ID').format(n);
                }
            }
        }
    </script>
@endsection
