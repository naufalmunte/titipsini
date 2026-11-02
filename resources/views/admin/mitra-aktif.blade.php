@extends('layouts.main')

@section('title', 'Daftar Mitra Aktif | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-handshake text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Mitra <span
                            class="text-indigo-600">Aktif</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola data profil partner
                        yang tergabung di Titipsini</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                {{-- JEDER! Beri ID pada total badge agar ikut ter-update --}}
                <span id="total-badge"
                    class="px-5 py-3 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Total {{ method_exists($mitras, 'total') ? $mitras->total() : $mitras->count() }} Profil Mitra
                </span>
            </div>
        </div>

        {{-- TABEL DAFTAR MITRA + INTEGRASI SEARCH BAR --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">

            {{-- HEADER TABEL: JUDUL & SEARCH BAR BERSANDING --}}
            <div
                class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Database <span
                            class="text-indigo-600">Partner</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar entitas bisnis
                        mitra yang beroperasi</p>
                </div>

                {{-- COMPACT LIVE SEARCH BAR DI KANAN --}}
                <div class="w-full sm:w-72 shrink-0">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        {{-- JEDER! Tambahkan id="search-input" dan matikan submit form otomatis --}}
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                            autocomplete="off" placeholder="Ketik langsung untuk mencari..."
                            class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-700 uppercase tracking-wide focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            {{-- KONTEN UTAMA TABEL --}}
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Nama Instansi / PT</th>
                            <th class="px-6 py-4">Pemilik & Kontak</th>
                            <th class="px-6 py-4">Area Operasional</th>
                            <th class="px-6 py-4 text-center">Bergabung</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    {{-- JEDER! Beri ID pada tbody agar JavaScript tahu daerah mana yang harus di-refresh --}}
                    <tbody id="mitra-tbody" class="divide-y divide-slate-50">
                        @forelse($mitras as $index => $mitra)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400">{{ (method_exists($mitras, 'firstItem') ? $mitras->firstItem() : 1) + $index }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-md text-sm uppercase italic shrink-0">
                                            {{ substr($mitra->nama_pt ?? 'M', 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-xs font-black text-slate-800 uppercase italic truncate">
                                                {{ $mitra->nama_pt ?? 'Instansi N/A' }}</h4>
                                            <p
                                                class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mt-0.5">
                                                ID: #PTR-{{ str_pad($mitra->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <p
                                        class="text-[10px] font-black text-slate-800 uppercase italic tracking-wide leading-tight mb-1">
                                        <i class="fas fa-user text-slate-400 mr-1 w-3"></i>
                                        {{ $mitra->user?->name ?? 'User Hilang' }}
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-500 lowercase tracking-widest leading-tight">
                                        <i class="fas fa-envelope text-slate-400 mr-1 w-3"></i>
                                        {{ $mitra->user?->email ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-[10px] font-black text-slate-700 uppercase tracking-widest leading-tight">
                                            {{ $mitra->kab_kota ?? '-' }}
                                        </span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ $mitra->provinsi ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="block text-[10px] font-black text-slate-700 uppercase tracking-widest">
                                        {{ \Carbon\Carbon::parse($mitra->created_at)->format('M Y') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[8px] font-black uppercase rounded tracking-widest">
                                        Aktif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-16 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-database text-3xl mb-3 text-slate-300 block"></i>
                                    Data mitra tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION CONTAINER --}}
            {{-- JEDER! Beri ID pada container pagination agar link ikut menyesuaikan keyword --}}
            <div id="pagination-container" class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                @if (method_exists($mitras, 'links') && $mitras->hasPages())
                    {{ $mitras->appends(request()->query())->links() }}
                @endif
            </div>
        </div>

    </div>

    {{-- JEDER! SCRIPT MAGIC LIVE SEARCH TANPA RELOAD --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');

            // Fungsi Debounce: Menahan eksekusi sebelum user selesai mengetik selama 300ms
            function debounce(func, delay) {
                let debounceTimer;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(context, args), delay);
                }
            }

            // Fungsi penembak AJAX
            const performSearch = debounce(function() {
                const keyword = searchInput.value;
                const currentUrl = new URL(window.location.href);

                // Set parameter search ke URL tujuan
                currentUrl.searchParams.set('search', keyword);
                currentUrl.searchParams.set('page', 1); // Reset otomatis ke page 1 tiap cari baru

                // Tembak request via Fetch API
                fetch(currentUrl)
                    .then(response => response.text())
                    .then(html => {
                        // Ubah string HTML menjadi DOM Object sementara
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Tukar isi Tbody lama dengan hasil pencarian baru
                        document.getElementById('mitra-tbody').innerHTML = doc.getElementById(
                            'mitra-tbody').innerHTML;

                        // Tukar Link Pagination jika ada perubahan jumlah data
                        document.getElementById('pagination-container').innerHTML = doc.getElementById(
                            'pagination-container').innerHTML;

                        // Tukar Angka Total Badge di paling atas
                        document.getElementById('total-badge').innerHTML = doc.getElementById(
                            'total-badge').innerHTML;
                    })
                    .catch(error => console.error('Error Live Search:', error));
            }, 300); // 300 Milidetik jeda mengetik

            // Pasang trigger event saat user mengetik
            searchInput.addEventListener('input', performSearch);
        });
    </script>

    {{-- ANIMATION STYLE PACK --}}
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
