@php
    // JEDER! Tarik data pengaturan global dari database
    $setting = \App\Models\Setting::first();
    $appName = $setting->app_name ?? 'Titipsini';
    $appLogo = $setting->app_logo ? asset('storage/' . $setting->app_logo) : null;
@endphp

<style>
    /* Sembunyikan scrollbar untuk Chrome, Safari dan Edge */
    .sidebar-scroll::-webkit-scrollbar {
        display: none;
    }

    /* Sembunyikan scrollbar untuk IE, Edge dan Firefox */
    .sidebar-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<aside class="fixed inset-y-0 left-0 bg-slate-900 w-60 flex flex-col z-50 shadow-2xl h-screen text-left">

    {{-- HEADER: Logo Dinamis --}}
    <div class="flex items-center justify-center h-16 border-b border-slate-800 shrink-0 px-4">
        <div class="flex items-center gap-3 overflow-hidden">
            @if($appLogo)
                <img src="{{ $appLogo }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover shadow-md shadow-indigo-500/30 shrink-0">
            @else
                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-500/30 shrink-0">
                    {{ substr($appName, 0, 1) }}
                </div>
            @endif
            <span class="text-white text-base font-black tracking-tight uppercase italic truncate" title="{{ $appName }}">
                {{ $appName }}<span class="text-indigo-500">.</span>
            </span>
        </div>
    </div>

    {{-- USER PROFILE CARD --}}
    <div class="p-4 border-b border-slate-800 shrink-0">
        <div class="bg-slate-800/50 rounded-xl p-3 border border-slate-700/50 flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex flex-shrink-0 items-center justify-center text-white text-xs font-bold shadow-inner uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-[8px] font-black tracking-widest text-emerald-400 uppercase italic">Mitra Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN NAVIGATION: Area Scrollable --}}
    <div class="flex-1 overflow-y-auto sidebar-scroll p-3 space-y-5">

        {{-- GRUP 1: UTAMA --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mitra.dashboard') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.laporan.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.laporan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.laporan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Laporan Pendapatan
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 2: OPERASIONAL --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Operasional</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mitra.barang-masuk.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.barang-masuk.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.barang-masuk.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Barang Masuk
                    </a>
                </li>
                <li>
                    <a href="{{ route('kendaraan.masuk.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('kendaraan.masuk.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('kendaraan.masuk.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10V7a2 2 0 012-2h10a2 2 0 012 2v3m2 4V10a2 2 0 00-2-2h-1.172a2 2 0 01-1.414-.586l-1.414-1.414a2 2 0 00-1.414-.586H8.414a2 2 0 00-1.414.586L5.586 7.414A2 2 0 014.172 8H3a2 2 0 00-2 2v4a2 2 0 002 2h18a2 2 0 002-2zM7 14a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        Kendaraan Masuk
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.bangunan-masuk.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.bangunan-masuk.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.bangunan-masuk.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                        </svg>
                        Bangunan Masuk
                    </a>
                </li>
                                <li>
                    <a href="{{ route('mitra.angkutan-masuk.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.angkutan-masuk.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.angkutan-masuk.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                        </svg>
                        Angkutan Masuk
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 3: MANAJEMEN ASET --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Manajemen Aset</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mitra.gudang.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.gudang*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.gudang*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Kelola Gudang
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.parkir.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.parkir*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.parkir*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        Lahan Parkir
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.armada.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.armada*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.armada*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        Garasi Armada
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.penjaga.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.penjaga*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.penjaga*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Data Penjaga
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 4: PENGATURAN & PROFIL --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Pengaturan</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mitra.profil.edit') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.profil.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.profil.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil Bisnis
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.rekening.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.rekening.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.rekening.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Rekening Bank
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.jam.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('mitra.jam.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('mitra.jam.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Jam Operasional
                    </a>
                </li>
            </ul>
        </div>

    </div>

    {{-- LOGOUT --}}
    <div class="p-3 border-t border-slate-800 shrink-0 bg-slate-900">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-3 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-500/10 hover:text-rose-400 rounded-lg transition-all group">
                <svg class="w-[18px] h-[18px] mr-2.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector('.sidebar-scroll');
            const scrollPosition = sessionStorage.getItem('sidebarScrollPosMitra');
            
            if (scrollPosition) {
                sidebar.scrollTop = scrollPosition;
            }

            const menuLinks = sidebar.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    sessionStorage.setItem('sidebarScrollPosMitra', sidebar.scrollTop);
                });
            });
        });
    </script>
</aside>