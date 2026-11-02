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
            @if ($appLogo)
                <img src="{{ $appLogo }}" alt="Logo"
                    class="w-8 h-8 rounded-lg object-cover shadow-md shadow-indigo-500/30 shrink-0">
            @else
                <div
                    class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-500/30 shrink-0">
                    {{ substr($appName, 0, 1) }}
                </div>
            @endif
            <span class="text-white text-base font-black tracking-tight uppercase italic truncate"
                title="{{ $appName }}">
                {{ $appName }}<span class="text-indigo-500">.</span>
            </span>
        </div>
    </div>

    {{-- User Profile Card --}}
    <div class="p-4 border-b border-slate-800 shrink-0">
        <div class="bg-slate-800/50 rounded-xl p-3 border border-slate-700/50 flex items-center gap-3">
            <div
                class="w-8 h-8 bg-emerald-500 rounded-full flex flex-shrink-0 items-center justify-center text-white text-xs font-bold shadow-inner uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-[8px] font-black tracking-widest text-emerald-400 uppercase">Pelanggan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Menus --}}
    <div class="flex-1 overflow-y-auto sidebar-scroll p-3 space-y-5">

        @php
            // Kita cek ke relasi 'verify', lalu ambil kolom 'status' nya
            $statusVerifikasi = auth()->user()->verify ? auth()->user()->verify->status : 'pending';

            // Pintu terbuka HANYA jika statusnya teks 'approved'
            $isVerified = $statusVerifikasi === 'approved';
            $vRoute = route('pelanggan.verify.index');
        @endphp

        {{-- GRUP 1: UTAMA --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.dashboard') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.booking.index') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.booking.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.booking.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Booking Saya {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 2: LAYANAN PENITIPAN --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Layanan Penitipan</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.titip.create') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.titip.create') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.titip.create') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Titip Barang {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.titip-kendaraan.index') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.titip-kendaraan.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.titip-kendaraan.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Titip Kendaraan {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.order-bangunan.index') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.order-bangunan*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.order-bangunan*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                        </svg>
                        Titip Bangunan {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.order_angkutan.index') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.order_angkutan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.order_angkutan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        Jasa Angkutan {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 3: PENGATURAN --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Pengaturan</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $isVerified ? route('pelanggan.alamat') : $vRoute }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ !$isVerified ? 'opacity-40 grayscale cursor-not-allowed' : '' }} {{ request()->routeIs('pelanggan.alamat') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.alamat') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Daftar Alamat {!! !$isVerified ? '<i class="fas fa-lock ml-auto text-[8px] opacity-50"></i>' : '' !!}
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelanggan.profil.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('pelanggan.profil.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('pelanggan.profil.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil Saya
                    </a>
                </li>
            </ul>
        </div>

    </div>

    {{-- Logout --}}
    <div class="p-3 border-t border-slate-800 shrink-0 bg-slate-900">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-3 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-500/10 hover:text-rose-400 rounded-lg transition-all group">
                <svg class="w-[18px] h-[18px] mr-2.5 group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector('.sidebar-scroll');
            const scrollPosition = sessionStorage.getItem('sidebarScrollPosPelanggan');
            if (scrollPosition) {
                sidebar.scrollTop = scrollPosition;
            }
            const menuLinks = sidebar.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    sessionStorage.setItem('sidebarScrollPosPelanggan', sidebar.scrollTop);
                });
            });
        });
    </script>
</aside>
