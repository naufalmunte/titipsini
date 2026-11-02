@extends('layouts.main')

@section('title', 'Pengaturan Komisi | Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-4 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER STANDAR TITIPSINI --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="fas fa-percentage text-base"></i>
                </div>
                <div>
                    <h2 class="text-base font-black text-slate-800 tracking-tight uppercase italic">Pengaturan <span
                            class="text-indigo-600">Komisi</span></h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Otoritas penuh kontrol
                        bagi hasil platform</p>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

            {{-- KIRI: FORM KOMISI UTAMA --}}
            <div
                class="lg:col-span-8 bg-white border border-slate-100 rounded-xl p-4 md:p-5 shadow-sm flex flex-col justify-between">
                <form action="{{ route('admin.komisi.update') }}" method="POST" class="flex flex-col h-full">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center mb-5">
                        {{-- Deskripsi Fitur --}}
                        <div class="flex-1 text-left">
                            <h3 class="text-xs font-black italic uppercase text-slate-800 tracking-tight mb-1">
                                Persentase <span class="text-indigo-600">Pendapatan</span>
                            </h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                                Tentukan persentase bagian yang diterima oleh mitra lapangan. Perubahan ini akan otomatis
                                mengunci (snapshot) pada setiap transaksi baru yang masuk ke sistem.
                            </p>
                        </div>

                        {{-- Input Nilai Komisi --}}
                        <div class="w-full md:w-36 relative shrink-0">
                            <input type="number" name="persen_mitra" value="{{ $setting->persen_mitra }}" min="0"
                                max="100" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-lg font-black italic text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-50 transition-all text-center">
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-black text-slate-300 italic select-none">%</span>
                        </div>
                    </div>

                    {{-- FOOTER KONTROL ACTION --}}
                    <div
                        class="mt-auto flex flex-col sm:flex-row items-center justify-between gap-3 p-3 bg-slate-900 rounded-lg shadow-inner">
                        <div class="flex items-center gap-3 w-full sm:w-auto text-left">
                            <div
                                class="w-8 h-8 bg-white/10 rounded-md flex items-center justify-center text-white shrink-0 border border-white/20">
                                <i class="fas fa-sliders-h text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-white uppercase italic tracking-widest leading-none">
                                    Global Rate Control</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Snapshot
                                    aktif pada transaksi baru</p>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-white hover:text-indigo-600 text-white rounded-lg text-[9px] font-black uppercase italic tracking-widest transition-all shadow-sm active:scale-95 text-center shrink-0 border border-transparent hover:border-indigo-200">
                            Update Komisi ⚡
                        </button>
                    </div>
                </form>
            </div>

            {{-- KANAN: VISUAL PREVIEW & NOTES --}}
            <div class="lg:col-span-4 space-y-3">

                {{-- CARD VISUAL PREVIEW --}}
                <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm flex flex-col gap-2.5">
                    <h3
                        class="text-[9px] font-black italic uppercase text-slate-800 tracking-widest border-b border-slate-100 pb-2 mb-1">
                        Persentase <span class="text-indigo-600">Bagi Hasil</span>
                    </h3>

                    <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="text-[9px] font-black text-indigo-400 uppercase italic tracking-widest">Persentase
                                Mitra
                            </p>
                            <p class="text-[8px] font-bold text-indigo-300 uppercase tracking-widest mt-0.5">Pendapatan
                                Mitra</p>
                        </div>
                        <p class="text-lg font-black italic text-indigo-600">{{ $setting->persen_mitra }}%</p>
                    </div>

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="text-[9px] font-black text-slate-500 uppercase italic tracking-widest">Persentase
                                Platform
                            </p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Pendapatan
                                Titipsini</p>
                        </div>
                        <p class="text-lg font-black italic text-slate-900">{{ 100 - $setting->persen_mitra }}%</p>
                    </div>
                </div>

                {{-- FOOTNOTE KETERANGAN --}}
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 md:p-4 shadow-sm">
                    <div class="flex gap-2.5 items-start">
                        <i class="fas fa-exclamation-triangle text-amber-500 text-[10px] mt-0.5 shrink-0"></i>
                        <p class="text-[8px] font-bold text-amber-700 uppercase italic tracking-widest leading-relaxed">
                            Setiap perubahan persentase hanya berlaku untuk transaksi yang dibuat setelah waktu update
                            berhasil dilakukan. Data atau log pendapatan lama pada tabel 'pendapatans' tidak akan mengalami
                            penyesuaian ulang.
                        </p>
                    </div>
                </div>

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
