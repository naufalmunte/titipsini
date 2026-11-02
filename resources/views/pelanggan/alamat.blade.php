@extends('layouts.main')

@section('title', 'Manajemen Alamat - Titipsini')

@section('content')
    {{-- CSS & JS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- WRAPPER ALPINE JS (DI LEVEL ROOT AGAR MODAL & CONTAINER TERAKSES) --}}
    <div x-data="alamatManager()">

        {{-- CONTAINER UTAMA (DENGAN ANIMASI FADE-IN) --}}
        <div class="max-w-6xl mx-auto py-8 px-6 space-y-8 animate-fade-in pb-12">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm text-left">
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Daftar <span class="text-indigo-600">Alamat</span></h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kelola titik lokasi pelanggan (Limit: {{ $alamats->count() }}/5)</p>
                </div>

                @if ($alamats->count() < 5)
                    <button @click="openModal('tambah')"
                        class="px-6 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95 shrink-0 flex items-center justify-center gap-1.5 italic">
                        Tambah Lokasi Baru <i class="fas fa-plus-circle"></i>
                    </button>
                @else
                    <button disabled
                        class="px-6 py-2.5 bg-slate-100 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest cursor-not-allowed shrink-0 flex items-center justify-center gap-1.5 italic">
                        Limit Tercapai (Max 5) <i class="fas fa-lock"></i>
                    </button>
                @endif
            </div>

            {{-- GRID DAFTAR ALAMAT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-left">
                @forelse($alamats as $a)
                    <div class="bg-white border {{ $a->is_utama ? 'border-indigo-400 shadow-md ring-1 ring-indigo-400' : 'border-slate-100 shadow-sm hover:border-indigo-200 hover:shadow-md' }} rounded-xl p-5 transition-all group flex flex-col relative overflow-hidden">

                        {{-- Badge Utama --}}
                        @if ($a->is_utama)
                            <div class="absolute top-0 right-0 bg-indigo-500 text-white text-[7px] font-black uppercase px-2 py-1 rounded-bl-lg italic tracking-widest">
                                Utama
                            </div>
                        @endif

                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-lg border border-slate-100 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors shrink-0">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1 mt-0.5">
                                <h4 class="text-sm font-black text-slate-800 uppercase italic truncate">{{ $a->nama_penerima }}</h4>
                                <p class="text-[9px] font-bold text-slate-400 tracking-widest italic mt-0.5"><i class="fas fa-phone-alt mr-1"></i> {{ $a->telepon }}</p>
                            </div>
                        </div>

                        <div class="flex-1 space-y-1 mb-5">
                            <h5 class="text-[10px] font-black text-slate-700 uppercase italic leading-none">{{ $a->kecamatan }}, {{ $a->kab_kota }}</h5>
                            <p class="text-[10px] font-bold text-slate-400 leading-relaxed italic line-clamp-2 uppercase">{{ $a->alamat_lengkap }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2 mt-auto">
                            <button @click="openModal('edit', {{ json_encode($a) }})"
                                class="px-4 py-1.5 bg-slate-100 hover:bg-amber-400 hover:text-white text-slate-500 rounded text-[9px] font-black uppercase transition-colors italic tracking-widest">
                                Edit <i class="fas fa-edit ml-1"></i>
                            </button>
                            <form action="{{ route('pelanggan.alamat.destroy', $a->id) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Jeder! Yakin mau hapus alamat ini, Raja?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-500 rounded text-[9px] font-black uppercase transition-colors italic tracking-widest">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 border border-dashed border-slate-200 bg-slate-50 rounded-xl flex flex-col items-center justify-center gap-2">
                        <i class="fas fa-map-marked-alt text-2xl text-slate-300"></i>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Belum ada alamat tersimpan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL AREA (DIKELUARKAN DARI CONTAINER UTAMA AGAR OVERLAY TIDAK TERTUTUP) --}}
        {{-- ========================================================================= --}}
        
        <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div @click.away="showModal = false" class="bg-white w-full max-w-4xl max-h-[90vh] flex flex-col md:flex-row rounded-2xl shadow-2xl overflow-hidden border-t-4 border-indigo-600 text-left">

                {{-- FORM BAGIAN KIRI --}}
                <div class="w-full md:w-1/2 flex flex-col bg-white">
                    {{-- Header Modal --}}
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-start bg-slate-50">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight" x-text="modalMode === 'tambah' ? 'Tambah Lokasi Baru' : 'Perbarui Lokasi'"></h2>
                            <p class="text-[8px] font-bold text-slate-400 uppercase italic mt-0.5 tracking-widest">Pastikan koordinat peta akurat</p>
                        </div>
                        <button @click="showModal = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 w-7 h-7 rounded-md flex items-center justify-center transition-colors font-black text-xs shrink-0"><i class="fas fa-times"></i></button>
                    </div>

                    {{-- Body Form --}}
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                        <form :action="modalAction" method="POST" id="formAlamat" class="space-y-4 m-0">
                            @csrf
                            <template x-if="modalMode === 'edit'">
                                @method('PUT')
                            </template>

                            <input type="hidden" name="latitude" id="lat_input" x-model="formData.latitude">
                            <input type="hidden" name="longitude" id="lng_input" x-model="formData.longitude">

                            <div class="grid grid-cols-2 gap-3 text-left">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Penerima</label>
                                    <input type="text" name="nama_penerima" x-model="formData.nama_penerima" required placeholder="Cth: Rumah / Kantor"
                                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic focus:border-indigo-600 focus:bg-white outline-none transition-all text-slate-700">
                                </div>
                                <div class="space-y-1 text-left">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Telepon (WA)</label>
                                    <input type="text" name="telepon" x-model="formData.telepon" required placeholder="08..."
                                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic focus:border-indigo-600 focus:bg-white outline-none transition-all text-slate-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-left">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Provinsi</label>
                                    <input type="text" name="provinsi" x-model="formData.provinsi" id="in_provinsi" required readonly
                                        class="w-full p-2.5 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic outline-none text-slate-500 cursor-not-allowed">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Kota/Kab</label>
                                    <input type="text" name="kab_kota" x-model="formData.kab_kota" id="in_kota" required
                                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic focus:border-indigo-600 focus:bg-white outline-none transition-all text-slate-700">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Kecamatan</label>
                                    <input type="text" name="kecamatan" x-model="formData.kecamatan" id="in_kecamatan" required
                                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic focus:border-indigo-600 focus:bg-white outline-none transition-all text-slate-700">
                                </div>
                            </div>

                            <div class="space-y-1 text-left">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic ml-1">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" x-model="formData.alamat_lengkap" id="in_alamat" rows="2" required placeholder="Jalan, RT/RW, Patokan..."
                                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase italic focus:border-indigo-600 focus:bg-white outline-none transition-all text-slate-700 resize-none"></textarea>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <label class="flex items-center gap-2 cursor-pointer w-full sm:w-auto">
                                    <input type="checkbox" name="is_utama" value="1" x-model="formData.is_utama"
                                        class="w-4 h-4 rounded text-indigo-600 border-slate-200 focus:ring-indigo-500">
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest italic">Jadikan Alamat Utama</span>
                                </label>
                                <button type="submit"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all italic shadow-md active:scale-95 flex items-center justify-center gap-1.5">
                                    <span x-text="modalMode === 'tambah' ? 'Simpan Lokasi' : 'Simpan Perubahan'"></span> <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MAP BAGIAN KANAN --}}
                <div class="w-full md:w-1/2 h-[250px] md:h-auto relative bg-slate-200 border-l border-slate-100 z-0">
                    <div id="map" class="absolute inset-0 z-0"></div>
                    <div class="absolute bottom-4 left-4 right-4 z-10">
                        <div id="geoStatus" class="bg-white/90 backdrop-blur px-3 py-2 rounded-lg shadow-md border border-white text-[9px] font-black text-indigo-600 uppercase italic tracking-widest text-center flex items-center justify-center gap-1.5">
                            <i class="fas fa-map-marker-alt"></i> Geser Pin Sesuai Lokasi
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div> {{-- AKHIR WRAPPER ALPINE JS --}}

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 4px; width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function alamatManager() {
            return {
                showModal: false,
                modalMode: 'tambah',
                modalAction: '{{ route('pelanggan.alamat.store') }}',
                formData: {
                    nama_penerima: '',
                    telepon: '',
                    provinsi: 'Sumatera Barat',
                    kab_kota: '',
                    kecamatan: '',
                    alamat_lengkap: '',
                    latitude: -0.947,
                    longitude: 100.417,
                    is_utama: false
                },
                map: null,
                marker: null,

                init() {
                    this.$nextTick(() => {
                        this.setupMap();
                    });
                },

                setupMap() {
                    this.map = L.map('map', { zoomControl: false }).setView([this.formData.latitude, this.formData.longitude], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                    L.control.zoom({ position: 'topright' }).addTo(this.map);

                    this.marker = L.marker([this.formData.latitude, this.formData.longitude], { draggable: true }).addTo(this.map);

                    this.map.on('click', (e) => {
                        this.updateMarker(e.latlng.lat, e.latlng.lng);
                        this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                    });

                    this.marker.on('dragend', () => {
                        const pos = this.marker.getLatLng();
                        this.updateMarker(pos.lat, pos.lng);
                        this.reverseGeocode(pos.lat, pos.lng);
                    });
                },

                openModal(mode, data = null) {
                    this.modalMode = mode;
                    if (mode === 'tambah') {
                        this.modalAction = '{{ route('pelanggan.alamat.store') }}';
                        this.formData = {
                            nama_penerima: '', telepon: '', provinsi: 'Sumatera Barat',
                            kab_kota: '', kecamatan: '', alamat_lengkap: '',
                            latitude: -0.947, longitude: 100.417, is_utama: false
                        };
                    } else {
                        this.modalAction = `/pelanggan/alamat/${data.id}`;
                        this.formData = { ...data, is_utama: !!data.is_utama };
                    }
                    this.showModal = true;
                    setTimeout(() => {
                        this.map.invalidateSize();
                        this.updateMarker(this.formData.latitude, this.formData.longitude, true);
                    }, 200);
                },

                updateMarker(lat, lng, center = false) {
                    this.formData.latitude = lat;
                    this.formData.longitude = lng;
                    this.marker.setLatLng([lat, lng]);
                    if (center) this.map.setView([lat, lng], 17);
                },

                async reverseGeocode(lat, lng) {
                    document.getElementById('geoStatus').innerHTML = "<i class='fas fa-sync fa-spin'></i> Mencari Alamat...";
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
                        const data = await res.json();
                        if (data.address) {
                            const addr = data.address;
                            this.formData.provinsi = addr.state || '';
                            this.formData.kab_kota = addr.city || addr.county || addr.town || '';
                            
                            let kecamatanCandidate = addr.city_district || addr.district || addr.municipality || '';
                            if (this.formData.kab_kota === kecamatanCandidate || !kecamatanCandidate) {
                                kecamatanCandidate = addr.town || addr.suburb || addr.village || '';
                            }
                            
                            this.formData.kecamatan = kecamatanCandidate;
                            this.formData.alamat_lengkap = data.display_name || '';

                            document.getElementById('geoStatus').innerHTML = "<i class='fas fa-check-circle text-emerald-500'></i> Lokasi Terdeteksi";
                        }
                    } catch (e) {
                        document.getElementById('geoStatus').innerHTML = "<i class='fas fa-map-pin text-rose-500'></i> Koordinat Terkunci";
                    }
                }
            }
        }
    </script>
@endsection