@extends('layouts.main')

@section('title', 'Jadwal Operasional Gudang | Mitra - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER (Dengan Tombol Kembali) --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('mitra.dashboard') }}"
                    class="w-12 h-12 bg-slate-50 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-xl flex items-center justify-center transition-all shadow-sm border border-slate-100 shrink-0">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Jadwal <span
                            class="text-indigo-600">Operasional</span></h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Atur hari dan jam kerja
                        penerimaan barang</p>
                </div>
            </div>
        </div>

        {{-- INFO BOX --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md shrink-0">
                <i class="fas fa-info-circle text-sm"></i>
            </div>
            <p class="text-[10px] font-black text-indigo-900 uppercase italic tracking-wide leading-relaxed">
                Info: <span class="text-indigo-600 font-bold">Pelanggan hanya dapat melakukan penitipan pada hari yang
                    berstatus BUKA.</span>
                Semua perubahan (Buka/Tutup & Jam) akan <span class="underline">tersimpan otomatis</span>. Untuk mengganti
                jam buka dan tutup langsung ganti di jam pada card di hari tersebut.
            </p>
        </div>

        {{-- FORM AUTO-SAVE --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative">
            <form action="{{ route('mitra.jam.update') }}" method="POST" id="form-jadwal" onsubmit="showLoading()">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($jamOperasional as $jam)
                        {{-- KOTAK KARTU PER HARI --}}
                        <div
                            class="p-5 rounded-2xl border-2 transition-all relative flex flex-col justify-between min-h-[140px]
                            {{ $jam->is_buka ? 'bg-white border-indigo-500 shadow-md' : 'bg-slate-50 border-slate-100 opacity-60' }}">

                            {{-- Baris Atas: Hari, Badge & Toggle --}}
                            <div
                                class="flex justify-between items-center mb-4 pb-3 border-b {{ $jam->is_buka ? 'border-indigo-50' : 'border-slate-200' }}">

                                {{-- 🌟 HARI & BADGE BUKA/TUTUP --}}
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-wider">
                                        {{ $jam->hari }}</h3>
                                    @if ($jam->is_buka)
                                        <span
                                            class="px-2 py-0.5 bg-emerald-100 text-emerald-600 text-[8px] font-black uppercase tracking-widest rounded-md shadow-sm">Buka</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 bg-slate-200 text-slate-500 text-[8px] font-black uppercase tracking-widest rounded-md shadow-sm">Tutup</span>
                                    @endif
                                </div>

                                {{-- 🌟 SWITCH TOGGLE AUTO-SAVE --}}
                                <label class="relative inline-flex items-center cursor-pointer">
                                    {{-- Input asli yang akan dikirim ke controller (1 atau 0) --}}
                                    <input type="hidden" name="jam[{{ $jam->id }}][is_buka]"
                                        id="status_{{ $jam->id }}" value="{{ $jam->is_buka ? '1' : '0' }}">

                                    {{-- Checkbox visual, mengubah value input hidden di atas saat diklik --}}
                                    <input type="checkbox" class="sr-only peer" {{ $jam->is_buka ? 'checked' : '' }}
                                        onchange="document.getElementById('status_{{ $jam->id }}').value = this.checked ? '1' : '0'; document.getElementById('form-jadwal').submit()">

                                    <div
                                        class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                                    </div>
                                </label>
                            </div>

                            {{-- Baris Bawah: Input Jam (Disable jika tutup) --}}
                            <div
                                class="flex items-center gap-3 justify-between {{ !$jam->is_buka ? 'pointer-events-none' : '' }}">
                                <div class="flex-1">
                                    <label
                                        class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Buka</label>
                                    <input type="time" name="jam[{{ $jam->id }}][jam_buka]"
                                        value="{{ $jam->jam_buka ? \Carbon\Carbon::parse($jam->jam_buka)->format('H:i') : '' }}"
                                        onchange="document.getElementById('form-jadwal').submit()"
                                        class="w-full px-2 py-2 bg-slate-50 border-transparent rounded-lg text-[11px] font-bold text-slate-700 uppercase focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all text-center">
                                </div>

                                <span class="text-slate-300 font-bold mt-3">-</span>

                                <div class="flex-1">
                                    <label
                                        class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Tutup</label>
                                    <input type="time" name="jam[{{ $jam->id }}][jam_tutup]"
                                        value="{{ $jam->jam_tutup ? \Carbon\Carbon::parse($jam->jam_tutup)->format('H:i') : '' }}"
                                        onchange="document.getElementById('form-jadwal').submit()"
                                        class="w-full px-2 py-2 bg-slate-50 border-transparent rounded-lg text-[11px] font-bold text-slate-700 uppercase focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all text-center">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    {{-- LOADING OVERLAY --}}
    <div id="loader"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[999] hidden flex items-center justify-center transition-all">
        <div
            class="bg-white px-8 py-5 rounded-2xl shadow-2xl flex flex-col items-center gap-3 animate-fade-in border border-slate-100">
            <i class="fas fa-spinner fa-spin text-indigo-600 text-3xl"></i>
            <span class="text-[10px] font-black text-slate-800 uppercase tracking-widest italic mt-2">Menyimpan
                Perubahan...</span>
        </div>
    </div>

    {{-- SCRIPT UNTUK LOADING --}}
    <script>
        function showLoading() {
            document.getElementById('loader').classList.remove('hidden');
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
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
