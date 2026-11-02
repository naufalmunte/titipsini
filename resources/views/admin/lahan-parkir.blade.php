@extends('layouts.main')

@section('title', 'Verifikasi Lahan Parkir | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-parking text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Verifikasi <span
                            class="text-indigo-600">Lahan Parkir</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Tinjau dan kelola
                        pengajuan lokasi lahan parkir mitra</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                {{-- ID total-badge untuk auto-update saat live search --}}
                <span id="total-badge"
                    class="px-5 py-3 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Total {{ method_exists($lahan_parkirs, 'total') ? $lahan_parkirs->total() : $lahan_parkirs->count() }}
                    Pengajuan
                </span>
            </div>
        </div>

        {{-- TABEL VERIFIKASI LAHAN PARKIR + LIVE SEARCH BAR --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">

            {{-- HEADER TABEL: JUDUL & SEARCH BAR BERSANDING --}}
            <div
                class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Antrean</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pengecekan kelayakan
                        lokasi penitipan kendaraan</p>
                </div>

                {{-- COMPACT LIVE SEARCH BAR --}}
                <div class="w-full sm:w-72 shrink-0">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                            autocomplete="off" placeholder="Cari nama lahan atau mitra..."
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
                            <th class="px-6 py-4">Data Mitra</th>
                            <th class="px-6 py-4">Informasi Lahan</th>
                            <th class="px-6 py-4">Kapasitas Maksimal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    {{-- ID parkir-tbody untuk target AJAX Refresh --}}
                    <tbody id="parkir-tbody" class="divide-y divide-slate-50">
                        @forelse($lahan_parkirs as $index => $parkir)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400">{{ (method_exists($lahan_parkirs, 'firstItem') ? $lahan_parkirs->firstItem() : 1) + $index }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-md text-sm uppercase italic shrink-0">
                                            {{ substr($parkir->user->name ?? 'M', 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-xs font-black text-slate-800 uppercase italic truncate">
                                                {{ $parkir->user->name ?? 'Mitra N/A' }}
                                            </h4>
                                            <p class="text-[9px] font-bold text-slate-500 lowercase tracking-widest mt-0.5">
                                                {{ $parkir->user->email ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <h4 class="text-[11px] font-black text-slate-800 uppercase italic leading-tight mb-1">
                                        {{ $parkir->nama_lahan }}
                                    </h4>
                                    <p
                                        class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-tight mb-2">
                                        {{ $parkir->kab_kota }} <span class="text-[8px] text-slate-400"> •
                                            {{ $parkir->provinsi }}</span>
                                    </p>
                                    @if ($parkir->link_foto)
                                        <a href="{{ $parkir->link_foto }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-[8px] font-black uppercase tracking-widest rounded-lg transition-colors border border-blue-100 hover:border-blue-600 shadow-sm">
                                            <i class="fas fa-external-link-alt"></i> Foto GDrive
                                        </a>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="text-center bg-slate-50 border border-slate-100 rounded-lg p-1.5 w-10"
                                            title="Roda 2">
                                            <span class="block text-[8px] font-black text-slate-400 uppercase">R2</span>
                                            <span
                                                class="text-[10px] font-black text-indigo-600">{{ $parkir->kapasitas_roda_2 }}</span>
                                        </div>
                                        <div class="text-center bg-slate-50 border border-slate-100 rounded-lg p-1.5 w-10"
                                            title="Roda 3">
                                            <span class="block text-[8px] font-black text-slate-400 uppercase">R3</span>
                                            <span
                                                class="text-[10px] font-black text-emerald-600">{{ $parkir->kapasitas_roda_3 }}</span>
                                        </div>
                                        <div class="text-center bg-slate-50 border border-slate-100 rounded-lg p-1.5 w-10"
                                            title="Roda 4">
                                            <span class="block text-[8px] font-black text-slate-400 uppercase">R4</span>
                                            <span
                                                class="text-[10px] font-black text-rose-600">{{ $parkir->kapasitas_roda_4 }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusStyle =
                                            [
                                                'pending' =>
                                                    'bg-amber-50 text-amber-600 border-amber-200 animate-pulse',
                                                'disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                'ditolak' => 'bg-rose-50 text-rose-600 border-rose-200',
                                            ][$parkir->status_verifikasi] ??
                                            'bg-slate-50 text-slate-600 border-slate-200';
                                    @endphp
                                    <span
                                        class="inline-block px-2.5 py-1 {{ $statusStyle }} text-[8px] font-black uppercase rounded border tracking-widest">
                                        {{ $parkir->status_verifikasi }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($parkir->status_verifikasi == 'pending')
                                        <div class="flex justify-center gap-2 items-center">
                                            {{-- Tombol Approve --}}
                                            <form action="{{ route('admin.parkir.verify', $parkir->id) }}" method="POST"
                                                class="m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="disetujui">
                                                <button type="submit"
                                                    onclick="return confirm('Yakin menyetujui lahan parkir ini?')"
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600"
                                                    title="Setujui">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Reject (Buka Modal Alpine) --}}
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'modal-reject-{{ $parkir->id }}')"
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600"
                                                title="Tolak">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>

                                        {{-- MODAL REJECT --}}
                                        <x-modal name="modal-reject-{{ $parkir->id }}" maxWidth="sm" focusable>
                                            <div class="p-6 text-left bg-white rounded-2xl w-full">
                                                <form action="{{ route('admin.parkir.verify', $parkir->id) }}"
                                                    method="POST" class="space-y-4">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="ditolak">

                                                    <div
                                                        class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                                        <div>
                                                            <h2
                                                                class="text-sm font-black text-slate-900 uppercase tracking-tight italic">
                                                                Tolak <span class="text-rose-600">Lahan Parkir</span></h2>
                                                            <p
                                                                class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                                Penolakan lokasi: {{ $parkir->nama_lahan }}</p>
                                                        </div>
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="text-slate-400 hover:text-rose-500 font-black"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>

                                                    <div class="space-y-3 text-center">
                                                        <div
                                                            class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                                                            <i class="fas fa-ban text-2xl animate-pulse"></i>
                                                        </div>
                                                        <p
                                                            class="text-[10px] font-bold text-slate-500 px-4 uppercase tracking-widest leading-relaxed">
                                                            Anda yakin ingin menolak pengajuan lahan parkir <span
                                                                class="text-rose-600 font-black">"{{ $parkir->nama_lahan }}"</span>?
                                                        </p>
                                                    </div>

                                                    <div
                                                        class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                                        <button type="submit"
                                                            class="px-8 py-3 bg-rose-600 hover:bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95 italic">Tolak
                                                            Parkiran 🚀</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>
                                    @else
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest"><i
                                                class="fas fa-lock mr-1"></i> Tervalidasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-12 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-folder-open text-2xl mb-2 text-slate-300 block"></i>
                                    Belum ada pengajuan lahan parkir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION CONTAINER --}}
            <div id="pagination-container" class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                @if (method_exists($lahan_parkirs, 'links') && $lahan_parkirs->hasPages())
                    {{ $lahan_parkirs->appends(request()->query())->links() }}
                @endif
            </div>
        </div>

    </div>

    {{-- SCRIPT MAGIC LIVE SEARCH TANPA RELOAD --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');

            // Fungsi Debounce untuk meringankan beban server
            function debounce(func, delay) {
                let debounceTimer;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(context, args), delay);
                }
            }

            // Fungsi eksekusi pencarian via AJAX Fetch
            const performSearch = debounce(function() {
                const keyword = searchInput.value;
                const currentUrl = new URL(window.location.href);

                currentUrl.searchParams.set('search', keyword);
                currentUrl.searchParams.set('page', 1); // Reset otomatis ke halaman 1

                fetch(currentUrl)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Replace tbody content
                        document.getElementById('parkir-tbody').innerHTML = doc.getElementById(
                            'parkir-tbody').innerHTML;

                        // Replace pagination content
                        document.getElementById('pagination-container').innerHTML = doc.getElementById(
                            'pagination-container').innerHTML;

                        // Replace badge count
                        document.getElementById('total-badge').innerHTML = doc.getElementById(
                            'total-badge').innerHTML;
                    })
                    .catch(error => console.error('Error Live Search:', error));
            }, 300);

            // Eksekusi tiap kali ada inputan yang diketik
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
