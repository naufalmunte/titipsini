@extends('layouts.main')

@section('title', 'Pengaturan Global | Titipsini')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 animate-fade-in text-left">
        
        {{-- Header Section --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100 shadow-sm">
                <i class="fas fa-cog text-xl animate-[spin_4s_linear_infinite]"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight uppercase italic leading-none">Pengaturan <span class="text-indigo-600">Sistem</span></h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Sesuaikan identitas dan branding platform Titipsini</p>
            </div>
        </div>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-emerald-500 text-base shrink-0"></i>
                <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest italic">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Form Pengaturan Identitas Aplikasi --}}
        {{-- JEDER! Jangan lupa enctype="multipart/form-data" agar gambar bisa terupload --}}
        <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl shadow-indigo-900/5 border border-slate-100 overflow-hidden">
            @csrf
            @method('PUT') {{-- Sesuaikan method (POST/PUT) dengan Controller kamu --}}

            <div class="p-6 md:p-8 space-y-8">
                
                {{-- KOTAK NAMA APLIKASI --}}
                <div class="space-y-2">
                    <label for="app_name" class="flex items-center gap-2 text-[10px] font-black text-slate-800 uppercase tracking-widest">
                        <i class="fas fa-signature text-indigo-500"></i> Nama Aplikasi (Sidebar)
                    </label>
                    <input type="text" id="app_name" name="app_name" value="{{ $setting->app_name ?? 'Titipsini' }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-black text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300"
                        placeholder="Contoh: TITIPSINI LOGISTIK">
                    <p class="text-[9px] font-bold text-slate-400 italic">Nama ini akan muncul di samping logo pada Sidebar navigasi utama.</p>
                </div>

                {{-- KOTAK UPLOAD LOGO DENGAN PREVIEW ALPINE.JS --}}
                <div x-data="{ photoName: null, photoPreview: null }" class="space-y-3 pt-4 border-t border-slate-100">
                    <label class="flex items-center gap-2 text-[10px] font-black text-slate-800 uppercase tracking-widest">
                        <i class="fas fa-image text-indigo-500"></i> Logo Sidebar Platform
                    </label>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        {{-- Avatar Preview Container --}}
                        <div class="shrink-0 flex items-center justify-center w-24 h-24 rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 overflow-hidden relative group">
                            
                            {{-- Jika ada logo lama dari database --}}
                            <div x-show="!photoPreview" class="w-full h-full flex items-center justify-center">
                                @if(isset($setting) && $setting->app_logo)
                                    <img src="{{ asset('storage/' . $setting->app_logo) }}" alt="Current Logo" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-cube text-3xl text-indigo-300 group-hover:scale-110 transition-transform"></i>
                                @endif
                            </div>

                            {{-- Jika Admin memilih gambar baru (Preview Langsung) --}}
                            <div x-show="photoPreview" class="w-full h-full" style="display: none;">
                                <span class="block w-full h-full bg-cover bg-center bg-no-repeat"
                                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Pilih File --}}
                        <div class="flex-1 w-full sm:w-auto text-center sm:text-left">
                            <input type="file" id="app_logo" name="app_logo" class="hidden" accept="image/*"
                                x-ref="logo"
                                x-on:change="
                                    photoName = $refs.logo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL($refs.logo.files[0]);
                                ">
                            
                            <button type="button" x-on:click.prevent="$refs.logo.click()" 
                                class="px-6 py-2.5 bg-white border border-slate-300 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-700 hover:bg-slate-50 hover:border-indigo-400 hover:text-indigo-600 transition-all shadow-sm active:scale-95 italic">
                                📁 Pilih Gambar Baru
                            </button>
                            
                            <p class="text-[9px] font-bold text-slate-400 mt-2 italic" x-show="!photoName">Format yang didukung: PNG, JPG, JPEG. (Rekomendasi ukuran: 150x150 px).</p>
                            <p class="text-[9px] font-black text-emerald-500 mt-2" x-show="photoName" style="display: none;">
                                Terpilih: <span x-text="photoName"></span> <i class="fas fa-check-circle ml-1"></i>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer / Submit Button --}}
            <div class="px-6 md:px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-md active:scale-95 italic flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>

    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection