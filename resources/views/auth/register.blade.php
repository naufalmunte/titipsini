<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autentikasi | Titipsini</title>
    
    {{-- Memanggil Tailwind & FontAwesome --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased overflow-hidden bg-white selection:bg-indigo-500 selection:text-white text-sm">

    <div x-data="{ isLogin: {{ request()->routeIs('register') || $errors->has('name') || $errors->has('role') ? 'false' : 'true' }} }" 
         class="relative w-screen h-screen flex flex-col md:flex-row overflow-hidden">

        {{-- ======================================= --}}
        {{-- TOGGLE MOBILE (Hanya tampil di HP) --}}
        {{-- ======================================= --}}
        <div class="md:hidden flex p-2 bg-slate-100 rounded-2xl m-4 z-30 shrink-0">
            <button @click="isLogin = true" :class="isLogin ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="flex-1 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all italic">Masuk</button>
            <button @click="isLogin = false" :class="!isLogin ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-500'" class="flex-1 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all italic">Daftar</button>
        </div>

        {{-- ======================================= --}}
        {{-- SISI KIRI: FORM LOGIN (MASUK) --}}
        {{-- ======================================= --}}
        <div class="w-full md:w-1/2 h-full flex flex-col justify-center px-8 lg:px-24 z-10 bg-white"
             :class="isLogin ? 'block' : 'hidden md:flex'">
            
            <div class="text-center md:text-left mb-6">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic mb-1">Masuk <span class="text-indigo-600">Akun</span></h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Akses ekosistem logistik Titipsini</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                @csrf
                <div class="space-y-1 text-left">
                    <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="fas fa-envelope text-[10px]"></i></div>
                        <input type="email" name="email" :value="old('email')" required autofocus class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300" placeholder="nama@email.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-[9px]" />
                </div>

                <div class="space-y-1 text-left">
                    <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="fas fa-key text-[10px]"></i></div>
                        <input type="password" name="password" required class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-black text-slate-900 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300" placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-[9px]" />
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3 h-3 cursor-pointer">
                        <span class="ms-2 text-[9px] font-bold text-slate-500 uppercase tracking-widest group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[9px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest italic transition-colors">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="w-full mt-4 py-3 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-md active:scale-[0.98] italic">
                    Masuk Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>


        {{-- ======================================= --}}
        {{-- SISI KANAN: FORM REGISTER (DAFTAR) --}}
        {{-- ======================================= --}}
        <div class="w-full md:w-1/2 h-full flex flex-col justify-center px-8 lg:px-24 z-10 bg-white"
             :class="!isLogin ? 'block' : 'hidden md:flex'">
            
            <div class="text-center md:text-left mb-5">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic mb-1">Buat <span class="text-emerald-600">Akun</span></h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Daftar sebagai pelanggan atau mitra</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="fas fa-user text-[10px]"></i></div>
                            <input type="text" name="name" :value="old('name')" required class="w-full pl-8 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 text-xs focus:ring-2 focus:ring-emerald-500 outline-none placeholder:text-slate-300" placeholder="Nama Lengkap">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-[9px]" />
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="fas fa-envelope text-[10px]"></i></div>
                            <input type="email" name="email" :value="old('email')" required class="w-full pl-8 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 text-xs focus:ring-2 focus:ring-emerald-500 outline-none placeholder:text-slate-300" placeholder="nama@email.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-[9px]" />
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Jenis Akun</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="pelanggan" class="peer sr-only" checked>
                            <div class="rounded-xl border border-slate-200 bg-white p-2.5 text-center hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all shadow-sm">
                                <span class="block text-xs font-black text-slate-600 uppercase peer-checked:text-emerald-700 italic">Pelanggan</span>
                                <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pesan Layanan</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="mitra" class="peer sr-only">
                            <div class="rounded-xl border border-slate-200 bg-white p-2.5 text-center hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all shadow-sm">
                                <span class="block text-xs font-black text-slate-600 uppercase peer-checked:text-emerald-700 italic">Mitra</span>
                                <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest">Sedia Lahan</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="fas fa-key text-[10px]"></i></div>
                            <input type="password" name="password" required class="w-full pl-8 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-black text-slate-900 text-xs focus:ring-2 focus:ring-emerald-500 outline-none placeholder:text-slate-300" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-800 uppercase tracking-widest ml-1">Ulangi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="fas fa-check text-[10px]"></i></div>
                            <input type="password" name="password_confirmation" required class="w-full pl-8 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-black text-slate-900 text-xs focus:ring-2 focus:ring-emerald-500 outline-none placeholder:text-slate-300" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all shadow-md active:scale-[0.98] italic">
                    Daftar Sekarang <i class="fas fa-rocket ml-2"></i>
                </button>
            </form>
        </div>


        {{-- ======================================= --}}
        {{-- PINTU GESER (OVERLAY) - DESKTOP ONLY --}}
        {{-- ======================================= --}}
        <div class="hidden md:block absolute top-0 w-1/2 h-full bg-slate-900 transition-transform duration-700 ease-in-out z-50 overflow-hidden shadow-2xl"
             :class="isLogin ? 'translate-x-full rounded-l-[2rem]' : 'translate-x-0 rounded-r-[2rem]'">
             
             <div class="relative w-full h-full flex items-center justify-center text-center px-12">
                  
                  {{-- Konten Ajakan Daftar (Tampil saat form Login aktif) --}}
                  <div class="absolute inset-0 flex flex-col justify-center items-center px-12 transition-all duration-700 ease-in-out"
                       :class="isLogin ? 'opacity-100 translate-x-0 delay-200' : 'opacity-0 translate-x-16 pointer-events-none'">
                       <div class="w-16 h-16 bg-indigo-500/20 rounded-full flex items-center justify-center mb-6"><i class="fas fa-user-astronaut text-3xl text-indigo-300"></i></div>
                       <h2 class="text-3xl lg:text-4xl text-white font-black italic uppercase leading-tight">Pengguna<br><span class="text-indigo-400">Baru?</span></h2>
                       <p class="text-slate-300 text-[10px] uppercase tracking-widest mt-4 mb-8 font-bold px-4 leading-relaxed">Daftar sekarang untuk mulai menitipkan aset Anda atau bergabung menjadi mitra di ekosistem Titipsini.</p>
                       <button @click="isLogin = false" class="px-8 py-3 border-2 border-slate-600 hover:border-white text-white rounded-xl uppercase font-black text-[10px] tracking-[0.2em] hover:bg-white hover:text-slate-900 transition-all italic active:scale-95">Buat Akun Baru</button>
                  </div>

                  {{-- Konten Ajakan Login (Tampil saat form Register aktif) --}}
                  <div class="absolute inset-0 flex flex-col justify-center items-center px-12 transition-all duration-700 ease-in-out"
                       :class="!isLogin ? 'opacity-100 translate-x-0 delay-200' : 'opacity-0 -translate-x-16 pointer-events-none'">
                       <div class="w-16 h-16 bg-emerald-500/20 rounded-full flex items-center justify-center mb-6"><i class="fas fa-fingerprint text-3xl text-emerald-300"></i></div>
                       <h2 class="text-3xl lg:text-4xl text-white font-black italic uppercase leading-tight">Sudah Punya<br><span class="text-emerald-400">Akun?</span></h2>
                       <p class="text-slate-300 text-[10px] uppercase tracking-widest mt-4 mb-8 font-bold px-4 leading-relaxed">Masuk ke dashboard untuk melacak status titipan, tagihan, dan mengelola aset Anda.</p>
                       <button @click="isLogin = true" class="px-8 py-3 border-2 border-slate-600 hover:border-white text-white rounded-xl uppercase font-black text-[10px] tracking-[0.2em] hover:bg-white hover:text-slate-900 transition-all italic active:scale-95">Masuk Di Sini</button>
                  </div>

             </div>
        </div>

    </div>
</body>
</html>