@extends('layouts.main')

@section('title', 'Data Penjaga Saya - Titipsini')

@section('content')
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Kelola Penjaga</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Daftar Karyawan Jaga Bangunan</p>
            </div>

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                class="px-5 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Penjaga
            </button>
        </div>

        {{-- FILTER & TOTAL --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('mitra.penjaga.index') }}" method="GET" class="relative w-full md:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penjaga..."
                    class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:border-indigo-600 focus:ring-0 transition-all uppercase placeholder:normal-case">
            </form>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-sm">
                Total: <span class="text-indigo-600">{{ method_exists($penjagas, 'total') ? $penjagas->total() : $penjagas->count() }}</span> Orang
            </div>
        </div>

        {{-- TABEL PENJAGA --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Profil Penjaga</th>
                            <th class="px-6 py-4">Area Domisili</th>
                            <th class="px-6 py-4 w-40 text-center">Status</th>
                            <th class="px-6 py-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($penjagas as $index => $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-[10px] font-black text-slate-400">
                                    {{ (method_exists($penjagas, 'firstItem') ? $penjagas->firstItem() : 1) + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-lg uppercase shrink-0">
                                            {{ substr($p->nama_penjaga, 0, 1) }}
                                        </div>
                                        <div>
                                            <h3 class="text-xs font-black text-slate-800 uppercase italic">{{ $p->nama_penjaga }}</h3>
                                            <p class="text-[9px] text-slate-400 mt-0.5 font-bold tracking-widest"><i class="fab fa-whatsapp text-emerald-500 mr-1"></i> {{ $p->no_telp }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[10px] font-black text-slate-700 uppercase">{{ $p->kota }}</p>
                                    <p class="text-[8px] text-slate-400 mt-0.5 font-bold uppercase tracking-widest line-clamp-1">{{ $p->kecamatan }}, {{ $p->provinsi }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @if($p->status_verifikasi == 'approved' || $p->status_verifikasi == 'disetujui')
                                            <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                <i class="fas fa-check-circle mr-1"></i> Disetujui
                                            </span>
                                        @elseif($p->status_verifikasi == 'rejected' || $p->status_verifikasi == 'ditolak')
                                            <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100 animate-pulse">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif
                                        
                                        @if(isset($p->status_ketersediaan))
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                                Sts: <span class="text-indigo-600">{{ str_replace('_', ' ', $p->status_ketersediaan) }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @if ($p->link_berkas)
                                            <a href="{{ $p->link_berkas }}" target="_blank" class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Lihat Berkas KTP/SKCK">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @endif
                                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-edit-penjaga-{{ $p->id }}')" class="w-8 h-8 bg-indigo-50 text-indigo-500 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors" title="Edit Data">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button x-on:click.prevent="$dispatch('open-modal', 'modal-delete-penjaga-{{ $p->id }}')" class="w-8 h-8 bg-rose-50 text-rose-500 rounded-lg flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- MODAL HAPUS --}}
                                    <x-modal name="modal-delete-penjaga-{{ $p->id }}" maxWidth="sm" focusable>
                                        <div class="py-8 px-6 text-center bg-white rounded-2xl">
                                            <form action="{{ route('mitra.penjaga.destroy', $p->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-user-times text-3xl"></i>
                                                </div>
                                                <h2 class="text-sm font-black text-slate-900 mb-2 uppercase italic tracking-tight">Hapus Penjaga?</h2>
                                                <p class="text-[10px] text-slate-500 mb-6 font-bold uppercase leading-relaxed">
                                                    Yakin menghapus penjaga <span class="text-rose-600">{{ $p->nama_penjaga }}</span>? Data tidak dapat dikembalikan.
                                                </p>
                                                <div class="flex justify-center gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase rounded-lg hover:bg-slate-200 transition-colors tracking-widest">Batal</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white text-[9px] font-black uppercase rounded-lg hover:bg-rose-700 transition-colors tracking-widest shadow-md">Ya, Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    {{-- MODAL EDIT --}}
                                    <x-modal name="modal-edit-penjaga-{{ $p->id }}" focusable maxWidth="md">
                                        <div class="p-6 text-left bg-white rounded-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
                                            <form method="post" action="{{ route('mitra.penjaga.update', $p->id) }}" class="space-y-4">
                                                @csrf @method('PUT')
                                                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                                    <div>
                                                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Edit Data Penjaga</h2>
                                                    </div>
                                                    <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                                        <input type="text" name="nama_penjaga" value="{{ $p->nama_penjaga }}" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">No. WhatsApp</label>
                                                        <input type="text" name="no_telp" value="{{ $p->no_telp }}" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800" required>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                                                        <input type="date" name="tgl_lahir" value="{{ $p->tgl_lahir }}" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Berkas (KTP)</label>
                                                        <input type="url" name="link_berkas" value="{{ $p->link_berkas }}" class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700" required>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-2">
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                                                        <input type="text" name="provinsi" value="{{ $p->provinsi }}" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota/Kab</label>
                                                        <input type="text" name="kota" value="{{ $p->kota }}" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                                                        <input type="text" name="kecamatan" value="{{ $p->kecamatan }}" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Detail Alamat Lengkap</label>
                                                    <textarea name="detail_alamat" rows="2" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none" required>{{ $p->detail_alamat }}</textarea>
                                                </div>

                                                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
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
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50/50">Belum ada penjaga terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if (method_exists($penjagas, 'links') && $penjagas->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $penjagas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH PENJAGA (Ukuran Medium karena formnya sedikit) --}}
    <x-modal name="modal-create" focusable maxWidth="md">
        <div class="p-6 text-left bg-white rounded-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar">
            <form method="post" action="{{ route('mitra.penjaga.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Pendaftaran Penjaga Baru</h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pastikan sesuai dengan KTP</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="nama_penjaga" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" placeholder="Cth: Budi Santoso" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">No. WhatsApp</label>
                        <input type="text" name="no_telp" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800" placeholder="0812..." required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1">Link Berkas (KTP/SKCK)</label>
                        <input type="url" name="link_berkas" class="w-full px-4 py-2 mt-1 bg-indigo-50 border-transparent rounded-lg text-[10px] font-bold text-indigo-700 placeholder-indigo-300" placeholder="https://drive.google.com/..." required>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                        <input type="text" name="provinsi" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota/Kab</label>
                        <input type="text" name="kota" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                        <input type="text" name="kecamatan" class="w-full px-3 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[9px] font-bold text-slate-800 uppercase" required>
                    </div>
                </div>

                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Detail Alamat Lengkap</label>
                    <textarea name="detail_alamat" rows="2" class="w-full px-4 py-2 mt-1 bg-slate-50 border-transparent rounded-lg text-[10px] font-bold text-slate-800 uppercase resize-none" placeholder="JL. RAYA KURANJI..." required></textarea>
                </div>

                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-xl">Simpan Penjaga 🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
@endsection