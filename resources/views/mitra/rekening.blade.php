@extends('layouts.main')

@section('title', 'Rekening Bank | Mitra - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Rekening <span
                        class="text-indigo-600">Pencairan</span></h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola endpoint tujuan bagi
                    hasil Anda</p>
            </div>

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                class="px-5 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Daftarkan Bank Baru
            </button>
        </div>


        {{-- INFO BOX --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md shrink-0">
                <i class="fas fa-info-circle text-sm"></i>
            </div>
            <p class="text-[10px] font-black text-indigo-900 uppercase italic tracking-wide leading-relaxed">
                Penting: <span class="text-indigo-600 font-bold">Batas minimal penarikan dana adalah Rp 100.000 per
                    pengajuan. Pastikan nomor rekening valid.</span>
            </p>
        </div>

        {{-- REKENING GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($rekenings as $rek)
                {{-- KOTAK CARD REKENING --}}
                <div
                    class="bg-white border-2 {{ $rek->is_utama ? 'border-indigo-500 shadow-md' : 'border-slate-100 shadow-sm' }} rounded-2xl p-6 transition-all hover:-translate-y-1 relative group flex flex-col justify-between min-h-[220px]">

                    {{-- BADGE UTAMA --}}
                    @if ($rek->is_utama)
                        <div
                            class="absolute top-4 right-4 flex items-center gap-1.5 bg-indigo-600 px-2.5 py-1 rounded-md shadow-sm">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            <span class="text-[8px] font-black text-white uppercase tracking-widest">Utama</span>
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center gap-4 mb-5">
                            <div
                                class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-white font-black italic text-xs uppercase shadow-md shrink-0">
                                {{ substr($rek->nama_bank, 0, 3) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 italic uppercase leading-tight tracking-tight">
                                    {{ $rek->nama_bank }}</h3>
                                <p class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest mt-1"><i
                                        class="fas fa-check-circle mr-0.5"></i> Status Aktif</p>
                            </div>
                        </div>

                        <div class="space-y-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor
                                    Rekening</p>
                                <p class="text-base font-black text-slate-900 tracking-widest font-mono leading-none">
                                    {{ $rek->nomor_rekening }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Atas Nama</p>
                                <p
                                    class="text-[10px] font-black text-slate-900 uppercase tracking-widest leading-none truncate">
                                    {{ $rek->atas_nama }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="mt-5 flex items-center gap-2 pt-4 border-t border-slate-100">
                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-edit-rekening-{{ $rek->id }}')"
                            class="flex-1 py-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                            Edit Detail
                        </button>

                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-delete-rekening-{{ $rek->id }}')"
                            class="w-10 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-500 hover:text-white rounded-lg transition-all flex items-center justify-center shrink-0">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                        </button>
                    </div>
                </div> {{-- Akhir dari Div Kotak Card --}}

                {{-- MODAL HAPUS (Sudah dikeluarkan dari dalam card) --}}
                <x-modal name="modal-delete-rekening-{{ $rek->id }}" maxWidth="sm" focusable>
                    <div class="py-8 px-6 text-center bg-white rounded-2xl shadow-xl">
                        <form action="{{ route('mitra.rekening.destroy', $rek->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <div
                                class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-credit-card text-3xl"></i>
                            </div>
                            <h2 class="text-sm font-black text-slate-900 mb-2 uppercase italic tracking-tight">Hapus
                                Rekening?</h2>
                            <p class="text-[10px] text-slate-500 mb-6 font-bold uppercase leading-relaxed">
                                Yakin menghapus rekening bank <span class="text-rose-600">{{ $rek->nama_bank }}</span>?
                                Pencairan tidak dapat dilakukan ke rekening ini lagi.
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

                {{-- MODAL EDIT (Sudah dikeluarkan dari dalam card) --}}
                <x-modal name="modal-edit-rekening-{{ $rek->id }}" focusable maxWidth="md">
                    <div class="p-6 text-left bg-white rounded-2xl w-full">
                        <form action="{{ route('mitra.rekening.update', $rek->id) }}" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                <div>
                                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Edit
                                        Rekening</h2>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Perbarui
                                        data pencairan dana</p>
                                </div>
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="text-slate-400 hover:text-rose-500 font-black"><i
                                        class="fas fa-times"></i></button>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Bank
                                    / E-Wallet</label>
                                <input type="text" name="nama_bank" value="{{ $rek->nama_bank }}"
                                    placeholder="Cth: BCA / MANDIRI / DANA" required
                                    class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase focus:border-indigo-500 focus:ring-0">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor
                                    Rekening</label>
                                <input type="number" name="nomor_rekening" value="{{ $rek->nomor_rekening }}"
                                    placeholder="Masukkan angka saja" required
                                    class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 focus:border-indigo-500 focus:ring-0">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Atas Nama
                                    (Sesuai Buku Tabungan)</label>
                                <input type="text" name="atas_nama" value="{{ $rek->atas_nama }}"
                                    placeholder="Nama Lengkap" required
                                    class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase focus:border-indigo-500 focus:ring-0">
                            </div>

                            <div class="flex items-center gap-2 pt-2 ml-1">
                                <input type="checkbox" name="is_utama" id="is_utama_{{ $rek->id }}" value="1"
                                    {{ $rek->is_utama ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                <label for="is_utama_{{ $rek->id }}"
                                    class="text-[9px] font-black text-slate-600 uppercase tracking-widest cursor-pointer mt-0.5">
                                    Set Sebagai Rekening Utama
                                </label>
                            </div>

                            <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-8 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl">Simpan
                                    🚀</button>
                            </div>
                        </form>
                    </div>
                </x-modal>

            @empty
                <div
                    class="col-span-full py-16 text-center bg-white border-2 border-dashed border-slate-100 rounded-3xl shadow-sm">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 text-2xl mx-auto mb-4">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada data rekening
                        terdaftar</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL TAMBAH REKENING BARU --}}
    <x-modal name="modal-create" focusable maxWidth="md">
        <div class="p-6 text-left bg-white rounded-2xl w-full">
            <form action="{{ route('mitra.rekening.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Tambah Rekening Baru
                        </h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Daftarkan akun bank
                            Anda</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Bank /
                        E-Wallet</label>
                    <input type="text" name="nama_bank" placeholder="Cth: BCA / MANDIRI / DANA" required
                        class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase focus:border-indigo-500 focus:ring-0">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor
                        Rekening</label>
                    <input type="number" name="nomor_rekening" placeholder="Masukkan angka saja" required
                        class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 focus:border-indigo-500 focus:ring-0">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Atas Nama (Sesuai
                        Buku Tabungan)</label>
                    <input type="text" name="atas_nama" placeholder="Nama Lengkap Pemilik" required
                        class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase focus:border-indigo-500 focus:ring-0">
                </div>

                <div class="flex items-center gap-2 pt-2 ml-1">
                    <input type="checkbox" name="is_utama" id="is_utama_add" value="1"
                        class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                    <label for="is_utama_add"
                        class="text-[9px] font-black text-slate-600 uppercase tracking-widest cursor-pointer mt-0.5">
                        Set Sebagai Rekening Utama
                    </label>
                </div>

                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-8 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl">Simpan
                        🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

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
