@extends('layouts.main')

@section('title', 'Lahan Parkir Saya - Titipsini')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Lahan Parkir Saya</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Manajemen Lahan & Kapasitas Kendaraan</p>
            </div>

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                class="px-5 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Lahan Baru
            </button>
        </div>

        {{-- FILTER & TOTAL --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('mitra.parkir.index') }}" method="GET" class="relative w-full md:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lahan..."
                    class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:border-indigo-600 focus:ring-0 transition-all uppercase placeholder:normal-case">
            </form>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-sm">
                Total: <span class="text-indigo-600">{{ method_exists($lahan_parkirs, 'total') ? $lahan_parkirs->total() : $lahan_parkirs->count() }}</span> Lahan
            </div>
        </div>

        {{-- TABEL LAHAN PARKIR --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Nama Lahan</th>
                            <th class="px-6 py-4">Lokasi Wilayah</th>
                            <th class="px-6 py-4 text-center">Kapasitas (Unit)</th>
                            <th class="px-6 py-4 w-40">Status Validasi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($lahan_parkirs as $index => $parkir)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-[10px] font-black text-slate-400">
                                    {{ (method_exists($lahan_parkirs, 'firstItem') ? $lahan_parkirs->firstItem() : 1) + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <h3 class="text-xs font-black text-slate-800 uppercase italic">{{ $parkir->nama_lahan }}</h3>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[10px] font-black text-slate-700 uppercase">{{ $parkir->kab_kota }}</p>
                                    <p class="text-[8px] text-slate-400 mt-0.5 font-bold uppercase tracking-widest">{{ $parkir->kecamatan }}, {{ $parkir->provinsi }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="text-center bg-indigo-50 border border-indigo-100 px-2 py-1 rounded-lg">
                                            <span class="block text-[7px] font-black text-slate-500 uppercase">Roda 2</span>
                                            <span class="text-[10px] font-black text-indigo-600">{{ $parkir->kapasitas_roda_2 }}</span>
                                        </div>
                                        <div class="text-center bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-lg">
                                            <span class="block text-[7px] font-black text-slate-500 uppercase">Roda 3</span>
                                            <span class="text-[10px] font-black text-emerald-600">{{ $parkir->kapasitas_roda_3 }}</span>
                                        </div>
                                        <div class="text-center bg-rose-50 border border-rose-100 px-2 py-1 rounded-lg">
                                            <span class="block text-[7px] font-black text-slate-500 uppercase">Roda 4</span>
                                            <span class="text-[10px] font-black text-rose-600">{{ $parkir->kapasitas_roda_4 }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 space-y-2">
                                    @if ($parkir->status_verifikasi == 'disetujui')
                                        <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                        <form action="{{ route('mitra.parkir.toggle', $parkir->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="relative inline-flex h-4 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $parkir->is_aktif ? 'bg-indigo-600' : 'bg-slate-300' }}">
                                                <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $parkir->is_aktif ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                            </button>
                                            <span class="text-[9px] font-black uppercase tracking-widest {{ $parkir->is_aktif ? 'text-indigo-600' : 'text-slate-400' }}">
                                                {{ $parkir->is_aktif ? 'Buka' : 'Tutup' }}
                                            </span>
                                        </form>
                                    @elseif($parkir->status_verifikasi == 'ditolak')
                                        <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100 animate-pulse">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @if ($parkir->link_foto)
                                            <a href="{{ $parkir->link_foto }}" target="_blank" class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Lihat Foto">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-edit-parkir-{{ $parkir->id }}')" class="w-8 h-8 bg-indigo-50 text-indigo-500 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors" title="Edit Data">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-delete-parkir-{{ $parkir->id }}')" class="w-8 h-8 bg-rose-50 text-rose-500 rounded-lg flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- MODAL HAPUS --}}
                                    <x-modal name="modal-delete-parkir-{{ $parkir->id }}" maxWidth="sm" focusable>
                                        <div class="py-8 px-6 text-center bg-white rounded-2xl">
                                            <form action="{{ route('mitra.parkir.destroy', $parkir->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                                                </div>
                                                <h2 class="text-sm font-black text-slate-900 mb-2 uppercase italic tracking-tight">Hapus Lahan?</h2>
                                                <p class="text-[10px] text-slate-500 mb-6 font-bold uppercase leading-relaxed">
                                                    Yakin menghapus lahan <span class="text-rose-600">{{ $parkir->nama_lahan }}</span>? Data tidak dapat dikembalikan.
                                                </p>
                                                <div class="flex justify-center gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase rounded-lg hover:bg-slate-200 transition-colors tracking-widest">Batal</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white text-[9px] font-black uppercase rounded-lg hover:bg-rose-700 transition-colors tracking-widest shadow-md">Ya, Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    {{-- MODAL EDIT --}}
                                    <x-modal name="modal-edit-parkir-{{ $parkir->id }}" focusable maxWidth="2xl">
                                        <div class="p-6 text-left bg-white rounded-2xl max-h-[85vh] overflow-y-auto custom-scrollbar">
                                            <form method="post" action="{{ route('mitra.parkir.update', $parkir->id) }}" class="space-y-4">
                                                @csrf @method('PUT')
                                                <h2 class="text-sm font-black text-slate-900 mb-4 uppercase tracking-tight italic border-b border-slate-100 pb-2">Edit Data Lahan Parkir</h2>
                                                
                                                <div>
                                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lahan</label>
                                                    <input type="text" name="nama_lahan" value="{{ $parkir->nama_lahan }}" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-xs font-bold text-slate-800 uppercase" required>
                                                </div>

                                                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                                                    <h4 class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-3 italic">Kapasitas Maksimal (Unit)</h4>
                                                    <div class="grid grid-cols-3 gap-3">
                                                        <div>
                                                            <label class="text-[9px] font-black text-indigo-600 uppercase tracking-widest ml-1">Roda 2</label>
                                                            <input type="number" name="kapasitas_roda_2" value="{{ $parkir->kapasitas_roda_2 }}" class="w-full px-4 py-2 mt-1 bg-white border-transparent rounded-lg text-xs font-bold text-indigo-600" required>
                                                        </div>
                                                        <div>
                                                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest ml-1">Roda 3</label>
                                                            <input type="number" name="kapasitas_roda_3" value="{{ $parkir->kapasitas_roda_3 }}" class="w-full px-4 py-2 mt-1 bg-white border-transparent rounded-lg text-xs font-bold text-emerald-600" required>
                                                        </div>
                                                        <div>
                                                            <label class="text-[9px] font-black text-rose-600 uppercase tracking-widest ml-1">Roda 4</label>
                                                            <input type="number" name="kapasitas_roda_4" value="{{ $parkir->kapasitas_roda_4 }}" class="w-full px-4 py-2 mt-1 bg-white border-transparent rounded-lg text-xs font-bold text-rose-600" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-2">
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                                                        <input type="text" name="provinsi" value="{{ $parkir->provinsi }}" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota/Kab</label>
                                                        <input type="text" name="kab_kota" value="{{ $parkir->kab_kota }}" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                                                        <input type="text" name="kecamatan" value="{{ $parkir->kecamatan }}" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                                                    <textarea name="alamat_lengkap" rows="2" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none" required>{{ $parkir->alamat_lengkap }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Drive Foto</label>
                                                    <input type="url" name="link_foto" value="{{ $parkir->link_foto }}" class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700">
                                                </div>

                                                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-colors">Batal</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-indigo-700 transition-colors shadow-md">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50/50">Belum ada lahan parkir terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if (method_exists($lahan_parkirs, 'links') && $lahan_parkirs->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $lahan_parkirs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH LAHAN PARKIR (Menggunakan maxWidth="2xl" standar Laravel) --}}
    <x-modal name="modal-create" focusable maxWidth="2xl">
        <div class="p-6 text-left bg-white rounded-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar">
            <form method="post" action="{{ route('mitra.parkir.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Tambah Lahan Parkir Baru</h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Lengkapi form & tandai lokasi</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                </div>
                
                {{-- 1. Nama Lahan --}}
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lahan Parkir</label>
                    <input type="text" name="nama_lahan" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" placeholder="Cth: Parkiran Stasiun Utama" required>
                </div>

                {{-- 2. Kapasitas Maksimal --}}
                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                    <h4 class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-3 italic">Kapasitas Maksimal Kendaraan (Unit)</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-[9px] font-black text-indigo-600 uppercase tracking-widest ml-1">Roda 2</label>
                            <input type="number" name="kapasitas_roda_2" class="w-full px-4 py-2 mt-1 bg-white border-transparent rounded-lg text-xs font-bold text-indigo-600 shadow-sm" placeholder="0" required>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest ml-1">Roda 3</label>
                            <input type="number" name="kapasitas_roda_3" class="w-full px-4 py-2 mt-1 bg-white border-transparent rounded-lg text-xs font-bold text-emerald-600 shadow-sm" placeholder="0" required>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-rose-600 uppercase tracking-widest ml-1">Roda 4</label>
                            <input type="number" name="kapasitas_roda_4" class="w-full px-4 py-2 mt-1 bg-white border-transparent rounded-lg text-xs font-bold text-rose-600 shadow-sm" placeholder="0" required>
                        </div>
                    </div>
                </div>

                {{-- 3. Peta Leaflet (Titik Lokasi) --}}
                <div class="space-y-1 pt-2">
                    <label class="text-[9px] font-black text-indigo-600 uppercase tracking-widest ml-1 flex justify-between">
                        Tandai Titik Lokasi Peta <span class="animate-pulse">📍 Geser Pin</span>
                    </label>
                    <div id="mapTambah" class="w-full h-40 rounded-xl border border-slate-200 shadow-inner z-0"></div>
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                </div>

                {{-- 4. Wilayah (Otomatis dari Peta) --}}
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi_add" class="w-full px-3 py-2 mt-1 bg-slate-100 border-transparent rounded-lg text-[9px] font-bold text-slate-500 uppercase cursor-not-allowed" readonly required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota/Kab</label>
                        <input type="text" name="kab_kota" id="kab_kota_add" class="w-full px-3 py-2 mt-1 bg-slate-100 border-transparent rounded-lg text-[9px] font-bold text-slate-500 uppercase cursor-not-allowed" readonly required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan_add" class="w-full px-3 py-2 mt-1 bg-slate-100 border-transparent rounded-lg text-[9px] font-bold text-slate-500 uppercase cursor-not-allowed" readonly required>
                    </div>
                </div>

                {{-- 5. Alamat Lengkap --}}
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap_add" rows="2" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none" placeholder="JL. RAYA KURANJI..." required></textarea>
                </div>

                {{-- 6. Link Foto --}}
                <div>
                    <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Foto (G-Drive)</label>
                    <input type="url" name="link_foto" class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700" placeholder="https://drive.google.com/...">
                </div>

                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl">Simpan Lahan 🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Script Leaflet untuk Modal Tambah --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let mapInitialized = false;
            let map, marker;

            window.addEventListener('open-modal', event => {
                if (event.detail === 'modal-create') {
                    setTimeout(() => {
                        if (!mapInitialized) {
                            map = L.map('mapTambah', { zoomControl: false }).setView([-0.947, 100.417], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                            marker = L.marker([-0.947, 100.417], { draggable: true }).addTo(map);

                            marker.on('dragend', () => updateLoc(marker.getLatLng().lat, marker.getLatLng().lng));
                            map.on('click', (e) => {
                                marker.setLatLng(e.latlng);
                                updateLoc(e.latlng.lat, e.latlng.lng);
                            });

                            mapInitialized = true;
                        } else {
                            map.invalidateSize();
                        }
                    }, 300);
                }
            });

            function updateLoc(lat, lng) {
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`)
                    .then(r => r.json())
                    .then(data => {
                        const a = data.address;
                        if(a) {
                            document.getElementById('provinsi_add').value = a.state || '';
                            document.getElementById('kab_kota_add').value = a.city || a.town || a.county || '';
                            
                            let kec = a.city_district || a.district || a.municipality || a.suburb || a.village || '';
                            document.getElementById('kecamatan_add').value = kec;
                            
                            document.getElementById('alamat_lengkap_add').value = data.display_name || '';
                        }
                    });
            }
        });
    </script>
    
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
@endsection