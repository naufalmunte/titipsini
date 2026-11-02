@extends('layouts.main')

@section('title', 'Garasi Armada Saya - Titipsini')

@section('content')
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Garasi Armada Saya</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola Kendaraan & Status Operasional Jasa Angkut</p>
            </div>

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                class="px-5 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Mobil
            </button>
        </div>

        {{-- FILTER & TOTAL --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('mitra.armada.index') }}" method="GET" class="relative w-full md:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor atau merek..."
                    class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:border-indigo-600 focus:ring-0 transition-all uppercase placeholder:normal-case">
            </form>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-sm">
                Total: <span class="text-indigo-600">{{ method_exists($armadas, 'total') ? $armadas->total() : $armadas->count() }}</span> Unit
            </div>
        </div>

        {{-- TABEL ARMADA --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Mobil & Merek</th>
                            <th class="px-6 py-4 text-center">Plat Nomor</th>
                            <th class="px-6 py-4 w-40">Status Ketersediaan</th>
                            <th class="px-6 py-4 w-32 text-center">Verifikasi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($armadas as $index => $armada)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-[10px] font-black text-slate-400">
                                    {{ (method_exists($armadas, 'firstItem') ? $armadas->firstItem() : 1) + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <h3 class="text-xs font-black text-slate-800 uppercase italic">{{ $armada->merek }}</h3>
                                    <p class="text-[9px] text-slate-400 mt-1 font-bold line-clamp-1 uppercase tracking-widest">{{ $armada->jenisArmada->nama_jenis ?? 'Tidak Diketahui' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-black text-slate-800 uppercase tracking-[0.2em] shadow-inner">
                                        {{ $armada->plat_nomor }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($armada->status_ketersediaan == 'tersedia')
                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest"><i class="fas fa-circle text-[6px] mr-1 align-middle"></i> Tersedia</span>
                                    @elseif($armada->status_ketersediaan == 'sedang_jalan')
                                        <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest"><i class="fas fa-circle text-[6px] mr-1 align-middle animate-pulse"></i> Jalan</span>
                                    @else
                                        <span class="text-[9px] font-black text-rose-600 uppercase tracking-widest"><i class="fas fa-circle text-[6px] mr-1 align-middle"></i> Bengkel</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($armada->status_verifikasi == 'disetujui')
                                        <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @elseif($armada->status_verifikasi == 'ditolak')
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
                                        @if ($armada->link_foto)
                                            <a href="{{ $armada->link_foto }}" target="_blank" class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Lihat Foto">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-edit-armada-{{ $armada->id }}')" class="w-8 h-8 bg-indigo-50 text-indigo-500 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors" title="Edit Data">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-delete-armada-{{ $armada->id }}')" class="w-8 h-8 bg-rose-50 text-rose-500 rounded-lg flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- MODAL HAPUS --}}
                                    <x-modal name="modal-delete-armada-{{ $armada->id }}" maxWidth="sm" focusable>
                                        <div class="py-8 px-6 text-center bg-white rounded-2xl">
                                            <form action="{{ route('mitra.armada.destroy', $armada->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-truck-slash text-3xl"></i>
                                                </div>
                                                <h2 class="text-sm font-black text-slate-900 mb-2 uppercase italic tracking-tight">Hapus Kendaraan?</h2>
                                                <p class="text-[10px] text-slate-500 mb-6 font-bold uppercase leading-relaxed">
                                                    Yakin menghapus mobil Plat <span class="text-rose-600">{{ $armada->plat_nomor }}</span>? Data tidak dapat dikembalikan.
                                                </p>
                                                <div class="flex justify-center gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase rounded-lg hover:bg-slate-200 transition-colors tracking-widest">Batal</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white text-[9px] font-black uppercase rounded-lg hover:bg-rose-700 transition-colors tracking-widest shadow-md">Ya, Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    {{-- MODAL EDIT --}}
                                    <x-modal name="modal-edit-armada-{{ $armada->id }}" focusable maxWidth="md">
                                        <div class="p-6 text-left bg-white rounded-2xl">
                                            <form method="post" action="{{ route('mitra.armada.update', $armada->id) }}" class="space-y-4">
                                                @csrf @method('PUT')
                                                <h2 class="text-sm font-black text-slate-900 mb-4 uppercase tracking-tight italic border-b border-slate-100 pb-2">Edit Data Armada</h2>
                                                
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Armada</label>
                                                        <select name="jenis_armada_id" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase cursor-pointer" required>
                                                            @foreach ($jenisArmadas as $jenis)
                                                                <option value="{{ $jenis->id }}" {{ $armada->jenis_armada_id == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Plat Nomor</label>
                                                        <input type="text" name="plat_nomor" value="{{ $armada->plat_nomor }}" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-black text-slate-800 uppercase tracking-widest" required>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Merek Kendaraan</label>
                                                        <input type="text" name="merek" value="{{ $armada->merek }}" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Ketersediaan</label>
                                                        <select name="status_ketersediaan" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase cursor-pointer" required>
                                                            <option value="tersedia" {{ $armada->status_ketersediaan == 'tersedia' ? 'selected' : '' }}>Tersedia (Standby)</option>
                                                            <option value="sedang_jalan" {{ $armada->status_ketersediaan == 'sedang_jalan' ? 'selected' : '' }}>Sedang Jalan</option>
                                                            <option value="maintenance" {{ $armada->status_ketersediaan == 'maintenance' ? 'selected' : '' }}>Masuk Bengkel</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Drive Foto</label>
                                                    <input type="url" name="link_foto" value="{{ $armada->link_foto }}" class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700">
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
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50/50">Garasi masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if (method_exists($armadas, 'links') && $armadas->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $armadas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH ARMADA (Ukuran Medium karena formnya sedikit) --}}
    <x-modal name="modal-create" focusable maxWidth="md">
        <div class="p-6 text-left bg-white rounded-2xl w-full">
            <form method="post" action="{{ route('mitra.armada.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Tambah Mobil ke Garasi</h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Lengkapi identitas kendaraan</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Armada</label>
                        <select name="jenis_armada_id" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase cursor-pointer" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($jenisArmadas as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-black text-slate-800 uppercase tracking-widest" placeholder="BA 1234 XX" required>
                    </div>
                </div>

                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Merek Kendaraan</label>
                    <input type="text" name="merek" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" placeholder="Cth: Suzuki Carry / L300" required>
                </div>

                <div>
                    <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Foto (G-Drive)</label>
                    <input type="url" name="link_foto" class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700" placeholder="https://drive.google.com/...">
                </div>

                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl">Kirim Pengajuan 🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection