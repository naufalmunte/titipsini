@extends('layouts.main')

@section('title', 'Verifikasi Penjaga Bangunan | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Verifikasi <span
                            class="text-indigo-600">Penjaga</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Pusat pengecekan dokumen
                        karyawan mitra</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <span
                    class="px-5 py-3 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Total {{ method_exists($penjagas, 'total') ? $penjagas->total() : $penjagas->count() }} Penjaga
                </span>
            </div>
        </div>

        {{-- TABEL VERIFIKASI PENJAGA --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Antrean</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pengecekan kelayakan dan
                        SKCK staff mitra</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Data Penjaga & Mitra</th>
                            <th class="px-6 py-4">Domisili / Area</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($penjagas as $index => $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400">{{ (method_exists($penjagas, 'firstItem') ? $penjagas->firstItem() : 1) + $index }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-md text-sm uppercase italic shrink-0">
                                            {{ substr($p->nama_penjaga ?? 'P', 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-xs font-black text-slate-800 uppercase italic truncate">
                                                {{ $p->nama_penjaga }}</h4>
                                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                                                Mitra: <span
                                                    class="text-indigo-600 font-black">{{ $p->mitra?->name ?? 'Mitra N/A' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-[10px] font-black text-slate-700 uppercase tracking-widest leading-tight">
                                            {{ $p->kota ?? '-' }}
                                        </span>
                                        <span
                                            class="text-[8px] font-bold text-slate-400 uppercase tracking-widest truncate max-w-[150px]">
                                            {{ $p->provinsi ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($p->status_verifikasi == 'approved')
                                        <span
                                            class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[8px] font-black uppercase rounded tracking-widest">
                                            Disetujui
                                        </span>
                                    @elseif($p->status_verifikasi == 'pending')
                                        <span
                                            class="inline-block px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-200 text-[8px] font-black uppercase rounded tracking-widest animate-pulse">
                                            Pending
                                        </span>
                                    @else
                                        <div class="group relative inline-block">
                                            <span
                                                class="inline-block px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-200 text-[8px] font-black uppercase rounded tracking-widest cursor-help">
                                                Ditolak
                                            </span>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-48 p-3 bg-slate-900 text-white text-[9px] font-bold tracking-widest uppercase rounded-xl shadow-xl z-50 text-center">
                                                <span class="text-rose-400 block mb-1">Alasan Penolakan:</span>
                                                {{ $p->catatan_admin ?? '-' }}
                                                <div
                                                    class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-900">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 items-center">
                                        {{-- Tombol Cek KTP --}}
                                        <a href="{{ $p->link_berkas }}" target="_blank"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-blue-100 hover:border-blue-600"
                                            title="Cek Dokumen / KTP">
                                            <i class="fas fa-id-card text-xs"></i>
                                        </a>

                                        @if ($p->status_verifikasi != 'approved')
                                            {{-- Tombol Approve --}}
                                            <form action="{{ route('admin.verifikasi-penjaga.approve', $p->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('Yakin ingin menyetujui Penjaga ini?');">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600"
                                                    title="Setujui Penjaga">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($p->status_verifikasi != 'rejected')
                                            {{-- Tombol Reject (Buka Modal Alpine) --}}
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'modal-reject-{{ $p->id }}')"
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600"
                                                title="Tolak Penjaga">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- MODAL REJECT (STANDAR TITIPSINI) --}}
                                    @if ($p->status_verifikasi != 'rejected')
                                        <x-modal name="modal-reject-{{ $p->id }}" maxWidth="sm" focusable>
                                            <div class="p-6 text-left bg-white rounded-2xl w-full">
                                                <form action="{{ route('admin.verifikasi-penjaga.reject', $p->id) }}"
                                                    method="POST" class="space-y-4">
                                                    @csrf @method('PATCH')

                                                    <div
                                                        class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                                        <div>
                                                            <h2
                                                                class="text-sm font-black text-slate-900 uppercase tracking-tight italic">
                                                                Tolak <span class="text-rose-600">Berkas</span></h2>
                                                            <p
                                                                class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                                Penolakan penjaga: {{ $p->nama_penjaga }}</p>
                                                        </div>
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="text-slate-400 hover:text-rose-500 font-black"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>

                                                    <div class="space-y-3">
                                                        <div>
                                                            <label
                                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alasan
                                                                Penolakan</label>
                                                            <textarea name="catatan_admin" rows="3" placeholder="Contoh: Foto KTP buram, SKCK sudah kadaluarsa..." required
                                                                class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-[10px] font-bold text-slate-700 focus:border-rose-500 focus:ring-0 transition-all outline-none resize-none"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                                        <button type="submit"
                                                            class="px-8 py-3 bg-rose-600 hover:bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95 italic">Tolak
                                                            Berkas 🚀</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-16 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-user-times text-3xl mb-3 text-slate-300 block"></i>
                                    Belum ada data penjaga yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION CONTAINER --}}
            @if (method_exists($penjagas, 'links') && $penjagas->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $penjagas->links() }}
                </div>
            @endif
        </div>

    </div>

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
