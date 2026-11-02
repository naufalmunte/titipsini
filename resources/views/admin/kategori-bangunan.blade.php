@extends('layouts.main')

@section('title', 'Master Kategori Bangunan | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Master <span
                            class="text-indigo-600">Kategori Bangunan</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola kategori aset
                        bangunan dan biaya pengawasan</p>
                </div>
            </div>
            <div class="shrink-0">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                    class="w-full md:w-auto px-6 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md active:scale-95 tracking-widest flex items-center justify-center gap-2 italic">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>

        {{-- TABEL MASTER KATEGORI BANGUNAN --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Kategori</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar tipe layanan
                        pengawasan bangunan</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Harga / Hari</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4 text-center">Aksi Konfigurasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($kategoris as $index => $kategori)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[10px] font-black text-slate-400">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <h4 class="text-xs font-black text-slate-800 uppercase italic">
                                        {{ $kategori->nama_kategori }}</h4>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black italic tracking-tighter text-emerald-600">Rp
                                        {{ number_format($kategori->harga_per_hari, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4 max-w-[200px]">
                                    <p class="text-[10px] font-bold text-slate-500 truncate"
                                        title="{{ $kategori->deskripsi ?? '-' }}">{{ $kategori->deskripsi ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 items-center">
                                        {{-- Tombol Edit --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-edit-{{ $kategori->id }}')"
                                            class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-colors shadow-sm">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-delete-{{ $kategori->id }}')"
                                            class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>

                                    {{-- MODAL EDIT --}}
                                    <x-modal name="modal-edit-{{ $kategori->id }}" focusable maxWidth="sm">
                                        <div class="p-6 text-left bg-white rounded-2xl w-full">
                                            <form method="post"
                                                action="{{ route('admin.kategori-bangunan.update', $kategori->id) }}"
                                                class="space-y-4">
                                                @csrf @method('PUT')
                                                <div
                                                    class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                                    <div>
                                                        <h2
                                                            class="text-sm font-black text-slate-900 uppercase tracking-tight italic">
                                                            Edit <span class="text-indigo-600">Kategori</span></h2>
                                                        <p
                                                            class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                            Perbarui data aset bangunan</p>
                                                    </div>
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="text-slate-400 hover:text-rose-500 font-black"><i
                                                            class="fas fa-times"></i></button>
                                                </div>

                                                <div class="space-y-3">
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                                                            Kategori Bangunan</label>
                                                        <input type="text" name="nama_kategori"
                                                            value="{{ $kategori->nama_kategori }}" required
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga
                                                            Pengawasan / Hari (Rp)</label>
                                                        <input type="number" name="harga_per_hari"
                                                            value="{{ (int) $kategori->harga_per_hari }}" required
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-emerald-600 uppercase focus:border-emerald-500 focus:ring-0 transition-all outline-none">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi
                                                            Kategori</label>
                                                        <textarea name="deskripsi" rows="3" placeholder="Detail layanan pengawasan..."
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-[10px] font-bold text-slate-700 focus:border-indigo-500 focus:ring-0 transition-all outline-none resize-none">{{ $kategori->deskripsi }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                                    <button type="submit"
                                                        class="px-8 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95 italic">Simpan
                                                        🚀</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    {{-- MODAL DELETE --}}
                                    <x-modal name="modal-delete-{{ $kategori->id }}" maxWidth="sm" focusable>
                                        <div class="p-6 text-center bg-white rounded-2xl w-full">
                                            <form action="{{ route('admin.kategori-bangunan.destroy', $kategori->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <div
                                                    class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                                                    <i class="fas fa-exclamation-triangle text-2xl animate-pulse"></i>
                                                </div>
                                                <h2
                                                    class="text-base font-black text-slate-900 mb-1 uppercase tracking-tight italic">
                                                    Hapus Kategori?</h2>
                                                <p
                                                    class="text-[10px] font-bold text-slate-500 mb-6 px-4 uppercase tracking-widest leading-relaxed">
                                                    Yakin menghapus <span
                                                        class="text-rose-600 font-black">"{{ $kategori->nama_kategori }}"</span>?
                                                    Ini dapat mempengaruhi aset pelanggan yang sedang diawasi!
                                                </p>
                                                <div class="flex justify-center gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="w-full px-5 py-3 text-[9px] font-black uppercase tracking-widest text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                                                    <button type="submit"
                                                        class="w-full px-5 py-3 bg-rose-600 hover:bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-md transition-colors italic">Ya,
                                                        Hapus 🗑️</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-12 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-folder-open text-2xl mb-2 text-slate-300 block"></i>
                                    Belum ada master data kategori bangunan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- MODAL TAMBAH KATEGORI BARU --}}
    <x-modal name="modal-create" focusable maxWidth="sm">
        <div class="p-6 text-left bg-white rounded-2xl w-full">
            <form method="post" action="{{ route('admin.kategori-bangunan.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Tambah <span
                                class="text-indigo-600">Kategori</span></h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Input kategori
                            pengawasan baru</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kategori
                            Bangunan</label>
                        <input type="text" name="nama_kategori" placeholder="Cth: Rumah Kosong, Ruko..." required
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Pengawasan
                            / Hari (Rp)</label>
                        <input type="number" name="harga_per_hari" placeholder="Cth: 50000" required
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-emerald-600 uppercase focus:border-emerald-500 focus:ring-0 transition-all outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi
                            Layanan</label>
                        <textarea name="deskripsi" rows="3" placeholder="Jelaskan cakupan pengawasan berkala..."
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-[10px] font-bold text-slate-700 focus:border-indigo-500 focus:ring-0 transition-all outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-8 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95 italic">Simpan
                        Data 🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

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
