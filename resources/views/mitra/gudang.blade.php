@extends('layouts.main')

@section('title', 'Kelola Gudang - Titipsini')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Kelola Gudang</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Manajemen Lokasi Penyimpanan
                    Anda</p>
            </div>

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                class="px-5 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Gudang Baru
            </button>
        </div>

        {{-- FILTER & TOTAL --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('mitra.gudang.index') }}" method="GET" class="relative w-full md:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama gudang..."
                    class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:border-indigo-600 focus:ring-0 transition-all uppercase placeholder:normal-case">
            </form>

            <div
                class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-sm">
                Total: <span
                    class="text-indigo-600">{{ method_exists($gudangs, 'total') ? $gudangs->total() : $gudangs->count() }}</span>
                Unit
            </div>
        </div>

        {{-- TABEL GUDANG --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Gudang & Deskripsi</th>
                            <th class="px-6 py-4">Lokasi Wilayah</th>
                            <th class="px-6 py-4 w-40">Status & Spesifikasi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($gudangs as $index => $gudang)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-[10px] font-black text-slate-400">
                                    {{ (method_exists($gudangs, 'firstItem') ? $gudangs->firstItem() : 1) + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <h3 class="text-xs font-black text-slate-800 uppercase italic">
                                        {{ $gudang->nama_gudang }}</h3>
                                    <p class="text-[9px] text-slate-400 mt-1 font-bold line-clamp-1 uppercase">
                                        {{ $gudang->deskripsi ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[10px] font-black text-slate-700 uppercase">{{ $gudang->kab_kota }}</p>
                                    <p class="text-[8px] text-slate-400 mt-0.5 font-bold uppercase tracking-widest">
                                        {{ $gudang->provinsi }}</p>
                                </td>
                                <td class="px-6 py-4 space-y-2">
                                    @if ($gudang->status_verifikasi == 'disetujui')
                                        <span
                                            class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                        <form action="{{ route('mitra.gudang.toggle', $gudang->id) }}" method="POST"
                                            class="flex items-center gap-2">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="relative inline-flex h-4 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $gudang->is_aktif ? 'bg-indigo-600' : 'bg-slate-300' }}">
                                                <span
                                                    class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $gudang->is_aktif ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                            </button>
                                            <span
                                                class="text-[9px] font-black uppercase tracking-widest {{ $gudang->is_aktif ? 'text-indigo-600' : 'text-slate-400' }}">
                                                {{ $gudang->is_aktif ? 'Buka' : 'Tutup' }}
                                            </span>
                                        </form>
                                    @elseif($gudang->status_verifikasi == 'ditolak')
                                        <span
                                            class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100 animate-pulse">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                    <p class="text-[10px] font-bold text-indigo-600"><i
                                            class="fas fa-vector-square mr-1"></i> {{ $gudang->ukuran_gudang ?? '0' }} m²
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @if ($gudang->link_foto)
                                            <a href="{{ $gudang->link_foto }}" target="_blank"
                                                class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"
                                                title="Lihat Foto">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        <button
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-edit-gudang-{{ $gudang->id }}')"
                                            class="w-8 h-8 bg-indigo-50 text-indigo-500 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors"
                                            title="Edit Data">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-delete-gudang-{{ $gudang->id }}')"
                                            class="w-8 h-8 bg-rose-50 text-rose-500 rounded-lg flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors"
                                            title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    {{-- MODAL HAPUS --}}
                                    <x-modal name="modal-delete-gudang-{{ $gudang->id }}" maxWidth="sm" focusable>
                                        <div class="py-8 px-6 text-center bg-white rounded-2xl">
                                            <form action="{{ route('mitra.gudang.destroy', $gudang->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div
                                                    class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                                                </div>
                                                <h2
                                                    class="text-sm font-black text-slate-900 mb-2 uppercase italic tracking-tight">
                                                    Hapus Gudang?</h2>
                                                <p
                                                    class="text-[10px] text-slate-500 mb-6 font-bold uppercase leading-relaxed">
                                                    Yakin menghapus <span
                                                        class="text-rose-600">{{ $gudang->nama_gudang }}</span>? Data tidak
                                                    dapat dikembalikan.
                                                </p>
                                                <div class="flex justify-center gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase rounded-lg hover:bg-slate-200 transition-colors tracking-widest">Batal</button>
                                                    <button type="submit"
                                                        class="px-6 py-2.5 bg-rose-600 text-white text-[9px] font-black uppercase rounded-lg hover:bg-rose-700 transition-colors tracking-widest shadow-md">Ya,
                                                        Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    {{-- MODAL EDIT --}}
                                    <x-modal name="modal-edit-gudang-{{ $gudang->id }}" focusable maxWidth="2xl">
                                        <div
                                            class="p-6 text-left bg-white rounded-2xl max-h-[85vh] overflow-y-auto custom-scrollbar">
                                            <form method="post" action="{{ route('mitra.gudang.update', $gudang->id) }}"
                                                class="space-y-4">
                                                @csrf @method('PUT')
                                                <h2
                                                    class="text-sm font-black text-slate-900 mb-4 uppercase tracking-tight italic border-b border-slate-100 pb-2">
                                                    Edit Data Gudang</h2>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                                                            Gudang</label>
                                                        <input type="text" name="nama_gudang"
                                                            value="{{ $gudang->nama_gudang }}"
                                                            class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-xs font-bold text-slate-800 uppercase"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Ukuran
                                                            (m²)
                                                        </label>
                                                        <input type="number" name="ukuran_gudang"
                                                            value="{{ $gudang->ukuran_gudang }}"
                                                            class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-xs font-bold text-slate-800">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi
                                                        Fasilitas</label>
                                                    <textarea name="deskripsi" rows="2"
                                                        class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none">{{ $gudang->deskripsi }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-3 gap-2">
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                                                        <input type="text" name="provinsi"
                                                            value="{{ $gudang->provinsi }}"
                                                            class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota/Kab</label>
                                                        <input type="text" name="kab_kota"
                                                            value="{{ $gudang->kab_kota }}"
                                                            class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                                                        <input type="text" name="kecamatan"
                                                            value="{{ $gudang->kecamatan }}"
                                                            class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase"
                                                            required>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat
                                                        Lengkap</label>
                                                    <textarea name="alamat_lengkap" rows="2"
                                                        class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none"
                                                        required>{{ $gudang->alamat_lengkap }}</textarea>
                                                </div>

                                                <div>
                                                    <label
                                                        class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link
                                                        Drive Foto</label>
                                                    <input type="url" name="link_foto"
                                                        value="{{ $gudang->link_foto }}"
                                                        class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700">
                                                </div>

                                                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-colors">Batal</button>
                                                    <button type="submit"
                                                        class="px-6 py-2.5 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-indigo-700 transition-colors shadow-md">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50/50">
                                    Belum ada gudang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($gudangs, 'links') && $gudangs->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $gudangs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH GUDANG (Menggunakan maxWidth="2xl" standar Laravel) --}}
    <x-modal name="modal-create" focusable maxWidth="2xl">
        <div class="p-6 text-left bg-white rounded-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar">
            <form method="post" action="{{ route('mitra.gudang.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Tambah Gudang Baru
                        </h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Lengkapi form &
                            tandai lokasi gudang</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-slate-400 hover:text-rose-500 font-black">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- 1. Nama Gudang & Ukuran --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                            Gudang</label>
                        <input type="text" name="nama_gudang"
                            class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase"
                            placeholder="Cth: Gudang Utama" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Ukuran
                            (m²)</label>
                        <input type="number" name="ukuran_gudang"
                            class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800"
                            placeholder="150">
                    </div>
                </div>

                {{-- 2. Peta Leaflet (Titik Lokasi) --}}
                <div class="space-y-1 pt-2">
                    <label
                        class="text-[9px] font-black text-indigo-600 uppercase tracking-widest ml-1 flex justify-between">
                        Tandai Titik Lokasi Peta <span class="animate-pulse">📍 Geser Pin</span>
                    </label>
                    <div id="mapTambah" class="w-full h-40 rounded-xl border border-slate-200 shadow-inner z-0"></div>
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                </div>

                {{-- 3. Wilayah (Otomatis dari Peta) --}}
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi_add"
                            class="w-full px-3 py-2 mt-1 bg-slate-100 border-transparent rounded-lg text-[9px] font-bold text-slate-500 uppercase cursor-not-allowed"
                            readonly required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota/Kab</label>
                        <input type="text" name="kab_kota" id="kab_kota_add"
                            class="w-full px-3 py-2 mt-1 bg-slate-100 border-transparent rounded-lg text-[9px] font-bold text-slate-500 uppercase cursor-not-allowed"
                            readonly required>
                    </div>
                    <div>
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan_add"
                            class="w-full px-3 py-2 mt-1 bg-slate-100 border-transparent rounded-lg text-[9px] font-bold text-slate-500 uppercase cursor-not-allowed"
                            readonly required>
                    </div>
                </div>

                {{-- 4. Alamat Lengkap --}}
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat
                        Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap_add" rows="2"
                        class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none"
                        placeholder="JL. RAYA KURANJI..." required></textarea>
                </div>

                {{-- 5. Foto & Deskripsi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Foto
                            (G-Drive)</label>
                        <input type="url" name="link_foto"
                            class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700"
                            placeholder="https://drive.google.com/...">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi
                            Fasilitas</label>
                        <textarea name="deskripsi" rows="1"
                            class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none"
                            placeholder="CCTV 24 JAM..."></textarea>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-8 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl">Simpan
                        Gudang 🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Script Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let mapInitialized = false;
            let map, marker;

            window.addEventListener('open-modal', event => {
                if (event.detail === 'modal-create') {
                    setTimeout(() => {
                        if (!mapInitialized) {
                            map = L.map('mapTambah', {
                                zoomControl: false
                            }).setView([-0.947, 100.417], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(
                                map);
                            marker = L.marker([-0.947, 100.417], {
                                draggable: true
                            }).addTo(map);

                            marker.on('dragend', () => updateLoc(marker.getLatLng().lat, marker
                                .getLatLng().lng));
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

                fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`
                        )
                    .then(r => r.json())
                    .then(data => {
                        const a = data.address;
                        if (a) {
                            document.getElementById('provinsi_add').value = a.state || '';
                            document.getElementById('kab_kota_add').value = a.city || a.town || a.county || '';

                            let kec = a.city_district || a.district || a.municipality || a.suburb || a
                                .village || '';
                            document.getElementById('kecamatan_add').value = kec;

                            document.getElementById('alamat_lengkap_add').value = data.display_name || '';
                        }
                    });
            }
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
