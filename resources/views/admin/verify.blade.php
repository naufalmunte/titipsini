@extends('layouts.main')

@section('title', 'Verifikasi Pelanggan | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-id-card text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Verifikasi <span
                            class="text-indigo-600">Pelanggan</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Panel Validasi Data KTP
                        Pengguna Sistem</p>
                </div>
            </div>
            <div class="shrink-0">
                <span
                    class="px-5 py-3 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    {{ $verifications->where('status', 'pending')->count() }} Perlu Dicek
                </span>
            </div>
        </div>

        {{-- TABEL VERIFIKASI KTP --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Antrean</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pengecekan identitas dan
                        dokumen KTP</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4">Data Pengguna</th>
                            <th class="px-6 py-4">Informasi KTP</th>
                            <th class="px-6 py-4 text-center">Dokumen</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($verifications as $v)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-md text-sm uppercase italic shrink-0">
                                            {{ substr($v->user->name, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-xs font-black text-slate-800 uppercase italic truncate">
                                                {{ $v->nama_lengkap }}</h4>
                                            <p class="text-[9px] font-bold text-slate-500 lowercase tracking-widest mt-0.5">
                                                {{ $v->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-mono text-[11px] font-black text-slate-800 tracking-wider mb-1">
                                        {{ $v->nik }}</p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $v->jenis_kelamin }} <i
                                            class="fas fa-circle text-[4px] mx-1 text-slate-300 align-middle"></i>
                                        {{ \Carbon\Carbon::parse($v->tgl_lahir)->format('d M Y') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ $v->link_identitas }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white text-[9px] font-black uppercase tracking-widest rounded-lg transition-colors border border-indigo-100 hover:border-indigo-600 shadow-sm">
                                        <i class="fas fa-external-link-alt"></i> Buka KTP
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusStyle =
                                            [
                                                'pending' =>
                                                    'bg-amber-50 text-amber-600 border-amber-200 animate-pulse',
                                                'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                'rejected' => 'bg-rose-50 text-rose-600 border-rose-200',
                                            ][$v->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                    @endphp
                                    <span
                                        class="inline-block px-2.5 py-1 {{ $statusStyle }} text-[8px] font-black uppercase rounded border tracking-widest">
                                        {{ $v->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($v->status == 'pending')
                                        <div class="flex justify-center gap-2 items-center">
                                            {{-- Tombol Approve --}}
                                            <form action="{{ route('admin.verify.update', $v->id) }}" method="POST"
                                                class="m-0">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-emerald-100 hover:border-emerald-600"
                                                    title="Setujui">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Reject (Buka Modal Alpine) --}}
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'modal-reject-{{ $v->id }}')"
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600"
                                                title="Tolak">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>

                                        {{-- MODAL REJECT (Pengganti Prompt JS Bawaan) --}}
                                        <x-modal name="modal-reject-{{ $v->id }}" maxWidth="sm" focusable>
                                            <div class="p-6 text-left bg-white rounded-2xl w-full">
                                                <form action="{{ route('admin.verify.update', $v->id) }}" method="POST"
                                                    class="space-y-4">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">

                                                    <div
                                                        class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                                        <div>
                                                            <h2
                                                                class="text-sm font-black text-slate-900 uppercase tracking-tight italic">
                                                                Tolak <span class="text-rose-600">Verifikasi</span></h2>
                                                            <p
                                                                class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                                Penolakan KTP: {{ $v->nama_lengkap }}</p>
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
                                                            <textarea name="catatan" rows="3" placeholder="Sebutkan alasan penolakan yang jelas untuk pengguna..." required
                                                                class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-[10px] font-bold text-slate-700 focus:border-rose-500 focus:ring-0 transition-all outline-none resize-none"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')"
                                                            class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                                        <button type="submit"
                                                            class="px-8 py-3 bg-rose-600 hover:bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95 italic">Kirim
                                                            Penolakan 🚀</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>
                                    @else
                                        <div class="group relative inline-block">
                                            <span
                                                class="text-[8px] font-black text-slate-300 uppercase tracking-widest cursor-help"><i
                                                    class="fas fa-lock mr-1"></i> Tervalidasi</span>
                                            @if ($v->status == 'rejected')
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-48 p-3 bg-slate-900 text-white text-[9px] font-bold tracking-widest uppercase rounded-xl shadow-xl z-50 text-center">
                                                    <span class="text-rose-400 block mb-1">Alasan Penolakan:</span>
                                                    {{ $v->catatan }}
                                                    <div
                                                        class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-900">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-12 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-folder-open text-2xl mb-2 text-slate-300 block"></i>
                                    Belum ada antrean verifikasi KTP.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
