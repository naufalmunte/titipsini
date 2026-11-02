@extends('layouts.main')

@section('title', 'Profil Pelanggan')

@section('content')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-6xl mx-auto py-8 px-4 text-left mb-32 animate-fade-in">

        {{-- HEADER UTAMA --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase italic">Profil Pelanggan</h2>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">
                    Kelola Identitas Pribadi & Lokasi Alamat Utama Anda
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-slate-100 rounded-xl text-[10px] font-bold text-slate-500 border border-slate-200">
                    ID PELANGGAN: #{{ Auth::id() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- ========================================== --}}
            {{-- KOLOM KIRI: KARTU IDENTITAS (TERKUNCI)     --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl shadow-indigo-100 text-white relative overflow-hidden border border-slate-800">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg border border-white/10">
                                <i class="fas fa-user text-2xl text-white"></i>
                            </div>
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-lg text-[8px] font-black uppercase tracking-widest flex items-center gap-2">
                                <div class="w-1 h-1 bg-emerald-400 rounded-full animate-pulse"></div>
                                Pelanggan
                            </span>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <p class="text-[8px] text-indigo-300 uppercase tracking-widest font-black mb-1">Nama Akun</p>
                                <h3 class="text-lg font-bold tracking-tight uppercase">{{ Auth::user()->name }}</h3>
                                <p class="text-indigo-300/50 text-[10px] font-medium mt-1">{{ Auth::user()->email }}</p>
                            </div>

                            <div>
                                <p class="text-[8px] text-indigo-300 uppercase tracking-widest font-black mb-1">Status Akun</p>
                                <p class="text-sm font-bold tracking-[0.15em] text-emerald-400">AKTIF</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-t border-white/10 pt-4 mt-4">
                                <div>
                                    <p class="text-[8px] text-indigo-300 uppercase tracking-widest font-black mb-1">Bergabung Sejak</p>
                                    <p class="text-xs font-bold uppercase">{{ Auth::user()->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Efek Cahaya Background --}}
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-600 rounded-full blur-[80px] opacity-20"></div>
                </div>

                <div class="p-5 bg-amber-50 rounded-2xl border border-amber-100">
                    <p class="text-[9px] text-amber-700 font-bold leading-relaxed italic text-left">
                        <i class="fas fa-lock mr-2"></i>Data identitas dasar terhubung dengan akun utama dan dikelola oleh sistem keamanan kami.
                    </p>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- KOLOM KANAN: FORM KONTAK & MAPS            --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[3rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-50">
                    
                    {{-- Pastikan route ini sesuai dengan route update profil pelanggan di web.php kamu --}}
                    <form action="{{ route('pelanggan.profil.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                <i class="fas fa-address-book"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-black text-slate-800 uppercase italic tracking-tight">Detail Kontak & Alamat</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase">Informasi utama untuk keperluan layanan & logistik</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- Nomor Telepon / WhatsApp --}}
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp / Telepon</label>
                                <input type="text" name="telepon" value="{{ $profil->telepon ?? '' }}" placeholder="08..." required
                                    class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:border-indigo-600 focus:bg-white transition-all font-bold text-slate-700 text-sm shadow-inner">
                            </div>

                            {{-- MAP KONDISIONAL DUA ARAH --}}
                            <div class="md:col-span-3">
                                {{-- Tampilan Box jika sudah ada latitude --}}
                                <div id="location-locked"
                                    class="{{ !isset($profil->latitude) || !$profil->latitude ? 'hidden' : '' }} p-5 bg-indigo-50 border border-indigo-100 rounded-2xl flex justify-between items-center group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1">Koordinat Terkunci</p>
                                            <p class="text-[11px] font-black text-slate-700 italic">{{ $profil->latitude ?? '' }}, {{ $profil->longitude ?? '' }}</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="showMapEditor()"
                                        class="px-4 py-2 bg-white text-[9px] font-black text-indigo-600 uppercase rounded-xl border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        Atur Ulang Lokasi
                                    </button>
                                </div>

                                {{-- Tampilan Map Editor --}}
                                <div id="map-editor" class="{{ isset($profil->latitude) && $profil->latitude ? 'hidden' : '' }} space-y-3">
                                    <label class="text-[9px] font-black text-indigo-600 uppercase tracking-widest ml-1 italic animate-pulse">
                                        Pilih Titik Lokasi Rumah (Maps)
                                    </label>
                                    <div id="map" class="w-full h-72 rounded-[2rem] border-2 border-slate-100 shadow-sm z-0"></div>
                                </div>
                            </div>

                            {{-- Wilayah Inputs --}}
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                                <input type="text" name="provinsi" id="provinsi" value="{{ $profil->provinsi ?? 'Sumatera Barat' }}" required
                                    class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl text-xs font-bold shadow-inner wilayah-input uppercase">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                                <input type="text" name="kab_kota" id="kab_kota" value="{{ $profil->kab_kota ?? '' }}" required
                                    class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl text-xs font-bold shadow-inner wilayah-input uppercase">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                                <input type="text" name="kecamatan" id="kecamatan" value="{{ $profil->kecamatan ?? '' }}" required
                                    class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl text-xs font-bold shadow-inner wilayah-input uppercase">
                            </div>

                            {{-- Alamat Detail --}}
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" id="alamat_detail" required rows="2" placeholder="NAMA JALAN, RT/RW, NOMOR RUMAH..."
                                    class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl text-xs font-bold shadow-inner leading-relaxed uppercase resize-none">{{ $profil->alamat_lengkap ?? $profil->alamat ?? '' }}</textarea>
                            </div>

                            {{-- Hidden Coordinates --}}
                            <input type="hidden" name="latitude" id="lat" value="{{ $profil->latitude ?? '' }}">
                            <input type="hidden" name="longitude" id="lng" value="{{ $profil->longitude ?? '' }}">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full py-5 bg-slate-900 hover:bg-indigo-600 text-white rounded-[1.5rem] shadow-xl transition-all font-black uppercase tracking-[0.3em] text-[10px] italic">
                                Simpan Perubahan Profil 🚀
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        function showMapEditor() {
            document.getElementById('location-locked').classList.add('hidden');
            document.getElementById('map-editor').classList.remove('hidden');
            setTimeout(() => {
                map.invalidateSize();
            }, 200);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Default ke koordinat Padang jika data belum ada
            const initialLat = {{ $profil->latitude ?? -0.947 }};
            const initialLng = {{ $profil->longitude ?? 100.417 }};

            map = L.map('map', {
                zoomControl: false
            }).setView([initialLat, initialLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            // Geocoding: Klik Peta -> Isi Form (Bahasa Indonesia)
            function reverseGeocode(lat, lng) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`)
                    .then(r => r.json())
                    .then(data => {
                        const addr = data.address;
                        if (addr) {
                            // 1. Provinsi
                            document.getElementById('provinsi').value = addr.state || '';

                            // 2. KABUPATEN/KOTA (Laci Besar)
                            document.getElementById('kab_kota').value = addr.city || addr.county || addr.town || '';

                            // 3. KECAMATAN (Laci Menengah)
                            let kecamatanCandidate = addr.city_district || addr.district || addr.municipality || '';
                            if (document.getElementById('kab_kota').value === kecamatanCandidate || !kecamatanCandidate) {
                                kecamatanCandidate = addr.town || addr.suburb || addr.village || '';
                            }
                            document.getElementById('kecamatan').value = kecamatanCandidate;

                            // 4. ALAMAT LENGKAP
                            if (document.getElementById('alamat_detail')) {
                                document.getElementById('alamat_detail').value = data.display_name || '';
                            }
                        }
                    })
                    .catch(e => {
                        console.error("Gagal sinkronisasi koordinat", e);
                    });
            }

            // Sync Map Drag ke Input Form
            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                document.getElementById('lat').value = pos.lat;
                document.getElementById('lng').value = pos.lng;
                reverseGeocode(pos.lat, pos.lng);
            });

            // Sync Map Click ke Input Form
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('lat').value = e.latlng.lat;
                document.getElementById('lng').value = e.latlng.lng;
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            // Sync: Isi Teks Alamat -> Geser Peta Otomatis
            const wilayahInputs = document.querySelectorAll('.wilayah-input');
            wilayahInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const prov = document.getElementById('provinsi').value;
                    const kota = document.getElementById('kab_kota').value;
                    const kec = document.getElementById('kecamatan').value;
                    const query = `${kec}, ${kota}, ${prov}`;

                    if (query.length > 5) {
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1&accept-language=id`)
                            .then(r => r.json())
                            .then(data => {
                                if (data.length > 0) {
                                    const newLat = data[0].lat;
                                    const newLng = data[0].lon;
                                    map.setView([newLat, newLng], 14);
                                    marker.setLatLng([newLat, newLng]);
                                    document.getElementById('lat').value = newLat;
                                    document.getElementById('lng').value = newLng;
                                }
                            });
                    }
                });
            });
            
            // Invalidate size jika map langsung terlihat saat page load
            @if(!isset($profil->latitude) || !$profil->latitude)
                setTimeout(() => { map.invalidateSize(); }, 300);
            @endif
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .shadow-inner {
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03);
        }
    </style>
@endsection