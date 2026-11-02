@extends('layouts.main')

@section('content')
    {{-- CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-7xl mx-auto py-6 px-4 mb-32" x-data="orderForm()">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-5">

                <div class="text-left border-b-2 border-slate-900 pb-2 flex justify-between items-end">
                    <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Form Penitipan Kendaraan
                    </h2>
                    <span
                        class="text-[8px] font-bold text-slate-900 uppercase tracking-widest italic mb-1 uppercase text-left">Security
                        Verified by Titipsini</span>
                </div>

                {{-- Form Utama --}}
                <form id="mainOrderForm" action="{{ route('pelanggan.order-kendaraan.store') }}" method="POST"
                    class="space-y-5 text-left">
                    @csrf

                    {{-- Error Handling --}}
                    @if ($errors->any())
                        <div class="bg-red-500 text-white p-3 rounded mb-4 text-[10px] font-bold uppercase italic">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
                                :min="tanggalMulai || today" @change="calculate()"
                                class="w-full border border-slate-300 rounded text-[10px] font-bold py-1.5 px-3 focus:border-slate-900 bg-white outline-none text-slate-900"
                                required>
                        </div>
                    </div>

                    {{-- 02. Inventaris Kendaraan --}}
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-slate-900 px-4 py-2 flex justify-between items-center">
                            <h3 class="text-[9px] font-black text-white uppercase italic tracking-widest">02. Inventaris
                                Unit</h3>
                            <button type="button" @click="addItem()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-[8px] font-black px-3 py-1 rounded-md transition-all uppercase italic tracking-widest">
                                + Tambah Kendaraan
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead
                                    class="bg-slate-50 text-[8px] font-black text-slate-900 uppercase italic border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-black italic">Kategori</th>
                                        <th class="px-4 py-2 text-left font-black italic">Merk/Tipe</th>
                                        <th class="px-4 py-2 text-left font-black italic">No. Plat</th>
                                        <th class="px-4 py-2 text-left font-black italic">Warna</th>
                                        <th class="px-2 py-2 w-8"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-slate-50 transition-all">
                                            <td class="px-4 py-1.5 text-left">
                                                <select x-model="item.kategori_id"
                                                    :name="'items[' + index + '][kategori_id]'" @change="calculate()"
                                                    class="w-full border-none bg-transparent p-0 text-[10px] font-bold focus:ring-0 cursor-pointer text-slate-900"
                                                    required>
                                                    <option value="">Pilih...</option>
                                                    @foreach ($kategoris as $k)
                                                        <option value="{{ $k->id }}"
                                                            data-harga="{{ $k->harga_per_hari }}">{{ $k->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-1.5 text-left">
                                                <input type="text" x-model="item.merk_kendaraan"
                                                    :name="'items[' + index + '][merk_kendaraan]'"
                                                    placeholder="CONTOH: VARIO 160..."
                                                    class="w-full border-none bg-transparent p-0 text-[10px] font-bold focus:ring-0 placeholder:text-slate-300 uppercase tracking-tight text-slate-900"
                                                    required>
                                            </td>
                                            <td class="px-4 py-1.5 text-left">
                                                <input type="text" x-model="item.nomor_plat"
                                                    :name="'items[' + index + '][nomor_plat]'" placeholder="BA 1234 ABC..."
                                                    class="w-full border-none bg-transparent p-0 text-[10px] font-bold focus:ring-0 placeholder:text-slate-300 uppercase tracking-tight text-slate-900"
                                                    required>
                                            </td>
                                            <td class="px-4 py-1.5 text-left">
                                                <input type="text" x-model="item.warna"
                                                    :name="'items[' + index + '][warna]'" placeholder="HITAM..."
                                                    class="w-full border-none bg-transparent p-0 text-[10px] font-bold focus:ring-0 placeholder:text-slate-300 uppercase tracking-tight text-slate-900">
                                            </td>
                                            <td class="px-2 py-1.5 text-center">
                                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                    class="text-slate-400 hover:text-rose-500 font-black">✕</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        {{-- Catatan Kondisi --}}
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50 text-left">
                            <label
                                class="text-[8px] font-black text-slate-900 uppercase mb-1 block italic text-left">Catatan
                                Kondisi & Perlengkapan (Lecet/Helm)</label>
                            {{-- PERBAIKAN: Menambahkan old('catatan_pelanggan') di dalam tag textarea --}}
                            <textarea name="catatan_pelanggan" rows="3" placeholder="CATATAN KONDISI KENDARAAN SAAT DITITIP..."
                                class="w-full border border-slate-200 rounded text-[10px] font-bold py-2 px-3 outline-none focus:border-slate-900 bg-white placeholder:text-slate-200 text-slate-900 uppercase italic">{{ old('catatan_pelanggan') }}</textarea>
                        </div>
                    </div>

                    {{-- 03. Lokasi Penitipan --}}
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-slate-900 px-4 py-2 flex items-center justify-between">
                            <h3 class="text-[9px] font-black text-white uppercase italic tracking-widest">03. Lokasi
                                Penitipan</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <input type="hidden" name="alamat_id" :value="selectedAlamatId">
                            <template x-for="almt in alamats.filter(a => a.id == selectedAlamatId)" :key="almt.id">
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
                    </div>
                </form>
            </div>

            {{-- Kolom Kanan: Billing Summary --}}
            <div class="lg:col-span-4 lg:sticky lg:top-10">
                <div
                    class="bg-white border-t-4 border-slate-900 rounded-lg p-6 shadow-xl text-left border border-slate-100">
                    <p
                        class="text-[8px] font-black text-slate-900 uppercase tracking-widest mb-5 italic border-b pb-1 text-left uppercase">
                        Billing Summary</p>
                    <div class="space-y-4 text-left">
                        <div
                            class="flex justify-between items-center text-[9px] font-bold uppercase italic tracking-widest text-left text-slate-900">
                            <span>Sewa (<span x-text="totalHari"></span> Hari)</span>
                            <span x-text="'Rp ' + formatNumber(subtotalSewa)"></span>
                        </div>
                        <div class="pt-5 border-t border-slate-900 flex flex-col items-start text-left">
                            <p class="text-[8px] font-black text-indigo-600 uppercase italic mb-0.5 text-left">Grand Total:
                            </p>
                            <h3 class="text-3xl font-black italic tracking-tighter text-slate-900 leading-none text-left">
                                Rp <span x-text="formatNumber(subtotalSewa)"></span></h3>
                        </div>
                        <div class="pt-6">
                            <button type="button" @click="submitForm()"
                                class="w-full bg-slate-900 text-white py-4 rounded font-black text-[10px] uppercase tracking-widest hover:bg-slate-700 transition-all active:scale-95 italic shadow-lg">
                                KONFIRMASI TITIP 🚀
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Logic AlpineJS --}}
    <script>
        function orderForm() {
            return {
                selectedAlamatId: '{!! old('alamat_id', $alamats->where('is_utama', 1)->first()->id ?? ($alamats->first()->id ?? '')) !!}',
                alamats: {!! json_encode($alamats) !!},
                today: new Date().toISOString().split('T')[0],
                tanggalMulai: '{!! old('tanggal_mulai', '') !!}',
                tanggalSelesai: '{!! old('tanggal_selesai', '') !!}',

                // Mempertahankan baris kendaraan yang sudah ditambah menggunakan old('items')
                items: {!! json_encode(
                    old('items', [['kategori_id' => '', 'merk_kendaraan' => '', 'nomor_plat' => '', 'warna' => '']]),
                ) !!},

                subtotalSewa: 0,
                totalHari: 0,

                init() {
                    // PERBAIKAN: Begitu form diload, langsung kalkulasi agar total bayar tidak Rp 0 jika ada error
                    this.$nextTick(() => {
                        this.calculate();
                    });
                },

                addItem() {
                    this.items.push({
                        kategori_id: '',
                        merk_kendaraan: '',
                        nomor_plat: '',
                        warna: ''
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculate();
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
                    this.items.forEach(item => {
                        if (item.kategori_id) {
                            // Mengubah backticks (`) menjadi string concat biasa agar bebas parser error
                            const opt = document.querySelector('option[value="' + item.kategori_id + '"]');
                            if (opt) {
                                const harga = parseFloat(opt.dataset.harga || 0);
                                currentSewa += (harga * this.totalHari);
                            }
                        }
                    });
                    this.subtotalSewa = currentSewa;
                },
                formatNumber(n) {
                    return new Intl.NumberFormat('id-ID').format(n);
                },
                submitForm() {
                    if (!this.selectedAlamatId) {
                        alert('Harap isi alamat terlebih dahulu, Raja!');
                        return;
                    }
                    document.getElementById('mainOrderForm').submit();
                }
            }
        }
    </script>
@endsection
