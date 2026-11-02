@extends('layouts.main')

@section('title', 'Verifikasi Identitas | Titipsini')

@section('content')
    <div class="max-w-3xl mx-auto py-8 px-4 animate-fade-in text-left mb-20">
        
        {{-- HEADER STANDAR TITIPSINI --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('pelanggan.dashboard') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-300 transition-all shadow-sm active:scale-95 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic leading-none">Lengkapi <span class="text-indigo-600">Verifikasi</span></h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Langkah terakhir sebelum menggunakan layanan</p>
            </div>
        </div>

        {{-- FORM CARD PREMIUM --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-indigo-900/5 border border-slate-100 overflow-hidden">
            
            {{-- Banner Header Card --}}
            <div class="bg-slate-900 p-8 text-white relative overflow-hidden">
                <div class="relative z-10 flex items-center gap-5">
                    <div class="hidden md:flex w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl items-center justify-center border border-white/20 shadow-inner shrink-0">
                        <i class="fas fa-shield-alt text-2xl text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black italic tracking-tight uppercase">Verifikasi Data Diri</h3>
                        <p class="text-indigo-300 text-[9px] uppercase font-bold tracking-[0.2em] mt-1">Sistem Titipsini Menjamin Keamanan Datamu</p>
                    </div>
                </div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/30 rounded-full blur-3xl"></div>
            </div>

            <form action="{{ route('pelanggan.verify.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIK --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Nomor Induk Kependudukan (NIK)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-id-card text-xs"></i>
                            </div>
                            <input type="text" name="nik" maxlength="16" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 text-sm placeholder:text-slate-300 outline-none"
                                placeholder="16 Digit Angka KTP">
                        </div>
                        @error('nik')
                            <p class="text-[9px] text-rose-500 font-bold mt-1 ml-1 uppercase tracking-wider italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NAMA LENGKAP --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Nama Lengkap (Sesuai KTP)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <input type="text" name="nama_lengkap" value="{{ auth()->user()->name }}" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 text-sm outline-none">
                        </div>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-venus-mars text-xs"></i>
                            </div>
                            <select name="jenis_kelamin" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 text-sm outline-none appearance-none cursor-pointer">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    {{-- TANGGAL LAHIR --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-calendar-alt text-xs"></i>
                            </div>
                            <input type="date" name="tgl_lahir" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 text-sm outline-none cursor-pointer">
                        </div>
                    </div>
                </div>

                {{-- LINK FOTO KTP --}}
                <div class="space-y-1.5 pt-2 border-t border-slate-100">
                    <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Link Foto KTP (Google Drive)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fab fa-google-drive text-xs"></i>
                        </div>
                        <input type="url" name="link_identitas" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 text-sm placeholder:text-slate-300 outline-none"
                            placeholder="https://drive.google.com/...">
                    </div>
                    <div class="p-3.5 bg-indigo-50 rounded-xl flex items-start gap-3 border border-indigo-100 mt-3">
                        <i class="fas fa-info-circle text-indigo-500 mt-0.5"></i>
                        <p class="text-[9px] text-indigo-800 font-bold leading-relaxed uppercase tracking-wide">Penting: Pastikan akses link Drive diubah menjadi <span class="font-black text-indigo-900">"Anyone with the link / Siapa saja yang memiliki link"</span> agar tim pusat bisa melakukan validasi.</p>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-md active:scale-95 italic flex items-center justify-center gap-2">
                        Kirim Data Verifikasi <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Reset Autofill Styles */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px #f8fafc inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
@endsection