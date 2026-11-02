@extends('layouts.main')

@section('title', 'Manajemen User | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Manajemen <span
                            class="text-indigo-600">User</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola akses dan otoritas
                        data pengguna sistem</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <span
                    class="px-4 py-3 bg-slate-50 text-slate-500 border border-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-2 italic">
                    Total {{ $users->total() }} Akun
                </span>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                    class="px-5 py-3 bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md active:scale-95 tracking-widest flex items-center justify-center gap-2 italic">
                    <i class="fas fa-user-plus"></i> Tambah User
                </button>
            </div>
        </div>

        {{-- SEARCH BAR --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.user') }}" method="GET" class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau email pengguna..."
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 uppercase tracking-wide focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
            </form>
        </div>


        {{-- TABEL MANAJEMEN USER --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center text-left">
                <div>
                    <h3 class="text-sm font-black italic uppercase text-slate-800 tracking-tight">Daftar <span
                            class="text-indigo-600">Pengguna</span></h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar seluruh akun yang
                        terdaftar di sistem</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Data Pengguna</th>
                            <th class="px-6 py-4">Role Akses</th>
                            <th class="px-6 py-4 text-center">Aksi Konfigurasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400">{{ $users->firstItem() + $index }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-md text-sm uppercase italic shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-xs font-black text-slate-800 uppercase italic truncate">
                                                {{ $user->name }}</h4>
                                            <p class="text-[9px] font-bold text-slate-500 lowercase tracking-widest mt-0.5">
                                                {{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $roleStyle =
                                            [
                                                'admin' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                'finance' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                'mitra' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                                'pelanggan' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            ][$user->role] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                                    @endphp
                                    <span
                                        class="inline-block px-2.5 py-1 {{ $roleStyle }} text-[8px] font-black uppercase rounded border tracking-widest">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 items-center">
                                        {{-- Tombol Edit --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-edit-{{ $user->id }}')"
                                            class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-indigo-100 hover:border-indigo-600"
                                            title="Edit Akun">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'modal-delete-{{ $user->id }}')"
                                            class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors shadow-sm border border-rose-100 hover:border-rose-600"
                                            title="Hapus Akun">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>

                                    {{-- MODAL EDIT --}}
                                    <x-modal name="modal-edit-{{ $user->id }}" focusable maxWidth="sm">
                                        <div class="p-6 text-left bg-white rounded-2xl w-full">
                                            <form method="post" action="{{ route('admin.user.update', $user->id) }}"
                                                class="space-y-4">
                                                @csrf @method('PUT')
                                                <div
                                                    class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                                                    <div>
                                                        <h2
                                                            class="text-sm font-black text-slate-900 uppercase tracking-tight italic">
                                                            Edit <span class="text-indigo-600">User</span></h2>
                                                        <p
                                                            class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                            Perbarui data kredensial</p>
                                                    </div>
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="text-slate-400 hover:text-rose-500 font-black"><i
                                                            class="fas fa-times"></i></button>
                                                </div>

                                                <div class="space-y-3">
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                                                            Lengkap</label>
                                                        <input type="text" name="name" value="{{ $user->name }}"
                                                            required
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat
                                                            Email</label>
                                                        <input type="email" name="email" value="{{ $user->email }}"
                                                            required
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-[11px] font-bold text-slate-600 lowercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Role
                                                            Akses</label>
                                                        <select name="role" required
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none cursor-pointer">
                                                            <option value="pelanggan"
                                                                {{ $user->role == 'pelanggan' ? 'selected' : '' }}>
                                                                Pelanggan</option>
                                                            <option value="mitra"
                                                                {{ $user->role == 'mitra' ? 'selected' : '' }}>Mitra
                                                            </option>
                                                            <option value="finance"
                                                                {{ $user->role == 'finance' ? 'selected' : '' }}>Finance
                                                            </option>
                                                            <option value="admin"
                                                                {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Password
                                                            (Kosongkan jika tetap)</label>
                                                        <input type="password" name="password" placeholder="••••••••"
                                                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
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
                                    <x-modal name="modal-delete-{{ $user->id }}" maxWidth="sm" focusable>
                                        <div class="p-6 text-center bg-white rounded-2xl w-full">
                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div
                                                    class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                                                    <i class="fas fa-user-times text-2xl animate-pulse"></i>
                                                </div>
                                                <h2
                                                    class="text-base font-black text-slate-900 mb-1 uppercase tracking-tight italic">
                                                    Hapus Akun?</h2>
                                                <p
                                                    class="text-[10px] font-bold text-slate-500 mb-6 px-4 uppercase tracking-widest leading-relaxed">
                                                    Yakin menghapus pengguna <span
                                                        class="text-rose-600 font-black">"{{ $user->name }}"</span>?
                                                    Semua data yang terkait mungkin akan ikut terhapus atau kehilangan
                                                    relasi!
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
                                <td colspan="4"
                                    class="px-6 py-12 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-folder-open text-2xl mb-2 text-slate-300 block"></i>
                                    Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if (method_exists($users, 'links') && $users->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL TAMBAH USER BARU --}}
    <x-modal name="modal-create" focusable maxWidth="sm">
        <div class="p-6 text-left bg-white rounded-2xl w-full">
            <form method="post" action="{{ route('admin.user.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Tambah <span
                                class="text-indigo-600">User</span></h2>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Input akun baru ke
                            dalam sistem</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-slate-400 hover:text-rose-500 font-black"><i class="fas fa-times"></i></button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                            Lengkap</label>
                        <input type="text" name="name" placeholder="Cth: Raja..." required
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat
                            Email</label>
                        <input type="email" name="email" placeholder="user@titipsini.com" required
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-[11px] font-bold text-slate-600 lowercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Role
                            Akses</label>
                        <select name="role" required
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none cursor-pointer">
                            <option value="pelanggan">Pelanggan</option>
                            <option value="mitra">Mitra</option>
                            <option value="finance">Finance</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Password
                            Akses</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter..." required
                            class="w-full mt-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
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
