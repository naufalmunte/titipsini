@extends('layouts.main')

@section('title', 'Verifikasi Armada Mitra | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Verifikasi <span
                            class="text-indigo-600">Armada</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Tinjau dan setujui
                        kendaraan operasional mitra</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <span
                    class="px-5 py-3 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Total {{ method_exists($armadas, 'total') ? $armadas->total() : $armadas->count() }} Pengajuan
                </span>
            </div>
        </div>

        {{-- TABEL VERIFIKASI ARMADA --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Antrean</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pengecekan kelayakan
                        kendaraan angkut</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Data Mitra</th>
                            <th class="px-6 py-4">Informasi Kendaraan</th>
                            <th class="px-6 py-4 text-center">Foto Kendaraan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($armadas as $index => $armada)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400">{{ (method_exists($armadas, 'firstItem') ? $armadas->firstItem() : 1) + $index }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-md text-sm uppercase italic shrink-0">
                                            {{ substr($armada->user->name ?? 'M', 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-xs font-black text-slate-800 uppercase italic truncate">
                                                {{ $armada->user->name ?? 'Mitra N/A' }}</h4>
                                            <p class="text-[9px] font-bold text-slate-500 lowercase tracking-widest mt-0.5">
                                                {{ $armada->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <h4 class="text-[11px] font-black text-slate-800 uppercase italic leading-tight mb-1.5">
                                        {{ $armada->jenisArmada?->nama_jenis ?? 'Tipe Tidak Diketahui' }}
                                        <span class="text-indigo-600">({{ $armada->merek }})</span>
                                    </h4>
                                    <span
                                        class="inline-block px-3 py-1 bg-slate-100 text-slate-800 border border-slate-300 rounded text-[10px] font-black uppercase tracking-widest shadow-inner">
                                        {{ $armada->plat_nomor }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($armada->link_foto)
                                        <a href="{{ $armada->link_foto }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-[8px] font-black uppercase tracking-widest rounded-lg transition-colors border border-blue-100 hover:border-blue-600 shadow-sm">
                                            <i class="fas fa-external-link-alt"></i> Foto GDrive
                                        </a>
                                    @else
                                        <span
                                            class="text-[8px] font-black text-slate-300 uppercase tracking-widest italic">Tidak
                                            Ada Foto</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusStyle =
                                            [
                                                'pending' =>
                                                    'bg-amber-50 text-amber-600 border-amber-200 animate-pulse',
                                                'disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                'ditolak' => 'bg-rose-50 text-rose-600 border-rose-200',
                                            ][$armada->status_verifikasi] ??
                                            'bg-slate-50 text-slate-600 border-slate-200';
                                    @endphp
                                    <span
                                        class="inline-block px-2.5 py-1 {{ $statusStyle }} text-[8px] font-black uppercase rounded border tracking-widest">
                                        {{ $armada->status_verifikasi }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($armada->status_verifikasi == 'pending')
                                        <div class="flex justify-center gap-2 items-center">
                                            {{-- Tombol Approve --}}
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'modal-approve-{{ $armada->id }}')"
                                                class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600"
                                                title="Setujui Armada">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>

                                            {{-- Tombol Reject --}}
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'modal-reject-{{ $armada->id }}')"
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600"
                                                title="Tolak Armada">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>

                                        {{-- MODAL APPROVE --}}
                                        <x-modal name="modal-approve-{{ $armada->id }}" maxWidth="sm" focusable>
                                            <div class="p-6 text-center bg-white rounded-2xl w-full">
                                                <form action="{{ route('admin.armada.verifikasi', $armada->id) }}"
                                                    method="POST" class="space-y-4">
                                                    @csrf
                                                    <input type="hidden" name="status" value="disetujui">

                                                    <div
                                                        class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                                                        <i class="fas fa-check-double text-2xl"></i>
                                                    </div>

                                                    <h2
                                                        class="text-base font-black text-slate-900 mb-1 uppercase tracking-tight italic">
                                                        Setujui Kendaraan?</h2>
                                                    <p
                                                        class="text-[10px] font-bold text-slate-500 mb-6 px-4 uppercase tracking-widest leading-relaxed">
                                                        Mobil <span
                                                            class="text-indigo-600 font-black">"{{ $armada->merek }}"</span>
                                                        dengan plat <span
                                                            class="text-slate-800 font-black">[{{ $armada->plat_nomor }}]</span>
                                                        akan diaktifkan untuk operasional Titipsini.
                                                    </p>

                                                    <div class="flex justify-center gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="w-full px-5 py-3 text-[9px] font-black uppercase tracking-widest text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                                                        <button type="submit"
                                                            class="w-full px-5 py-3 bg-emerald-500 hover:bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-md transition-colors italic">Ya,
                                                            Setujui 🚀</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>

                                        {{-- MODAL REJECT --}}
                                        <x-modal name="modal-reject-{{ $armada->id }}" maxWidth="sm" focusable>
                                            <div class="p-6 text-center bg-white rounded-2xl w-full">
                                                <form action="{{ route('admin.armada.verifikasi', $armada->id) }}"
                                                    method="POST" class="space-y-4">
                                                    @csrf
                                                    <input type="hidden" name="status" value="ditolak">

                                                    <div
                                                        class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                                                        <i class="fas fa-ban text-2xl animate-pulse"></i>
                                                    </div>

                                                    <h2
                                                        class="text-base font-black text-slate-900 mb-1 uppercase tracking-tight italic">
                                                        Tolak Kendaraan?</h2>
                                                    <p
                                                        class="text-[10px] font-bold text-slate-500 mb-6 px-4 uppercase tracking-widest leading-relaxed">
                                                        Anda yakin ingin menolak pengajuan armada plat <span
                                                            class="text-rose-600 font-black">[{{ $armada->plat_nomor }}]</span>?
                                                        Pastikan telah memeriksa foto fisiknya.
                                                    </p>

                                                    <div class="flex justify-center gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="w-full px-5 py-3 text-[9px] font-black uppercase tracking-widest text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                                                        <button type="submit"
                                                            class="w-full px-5 py-3 bg-rose-600 hover:bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-md transition-colors italic">Ya,
                                                            Tolak 🗑️</button>
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
                                    Belum ada armada yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if (method_exists($armadas, 'links') && $armadas->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $armadas->appends(request()->query())->links() }}
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
