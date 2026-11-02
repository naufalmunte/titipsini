@php
    // JEDER! Tarik data pengaturan global dari database
    $setting = \App\Models\Setting::first();
    $appName = $setting->app_name ?? 'Titipsini';
    $appLogo = $setting->app_logo ? asset('storage/' . $setting->app_logo) : null;
@endphp

<style>
    .sidebar-scroll::-webkit-scrollbar {
        display: none;
    }

    .sidebar-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<aside class="fixed inset-y-0 left-0 bg-slate-900 w-60 flex flex-col z-50 shadow-2xl h-screen">

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

    {{-- User Profile Card --}}
    <div class="p-4 border-b border-slate-800 shrink-0">
        <div class="bg-slate-800/50 rounded-xl p-3 border border-slate-700/50 flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-500 rounded-full flex flex-shrink-0 items-center justify-center text-white text-xs font-bold shadow-inner uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></div>
                    <span class="text-[8px] font-black tracking-widest text-amber-400 uppercase">Divisi Finance</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Menus --}}
    <div class="flex-1 overflow-y-auto sidebar-scroll p-3 space-y-5 text-left">

        {{-- GRUP 1: UTAMA --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('finance.dashboard') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('finance.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('finance.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 2: TUGAS VALIDASI (Pembayaran) --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Validasi Pembayaran</p>
            <ul class="space-y-1">

                {{-- Validasi Gudang --}}
                <li>
                    @php
                        $isGudangActive = request()->routeIs('finance.confirm.*') && !request()->routeIs('finance.confirm-angkutan.*') && !request()->routeIs('finance.confirm-kendaraan.*');
                    @endphp
                    <a href="{{ route('finance.confirm.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ $isGudangActive ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ $isGudangActive ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.48V22" />
                        </svg>
                        Validasi Penitipan Barang
                        @php $pendingPay = \App\Models\Order::where('status', 'pending_confirmation')->count(); @endphp
                        @if ($pendingPay > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>
                </li>

                {{-- Validasi Kendaraan --}}
                <li>
                    <a href="{{ route('finance.confirm-kendaraan.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('finance.confirm-kendaraan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('finance.confirm-kendaraan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10V7a2 2 0 012-2h10a2 2 0 012 2v3m2 4V10a2 2 0 00-2-2h-1.172a2 2 0 01-1.414-.586l-1.414-1.414a2 2 0 00-1.414-.586H8.414a2 2 0 00-1.414.586L5.586 7.414A2 2 0 014.172 8H3a2 2 0 00-2 2v4a2 2 0 002 2h18a2 2 0 002-2zM7 14a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        Validasi Penitipan Kendaraan
                        @php $pendingKendaraan = \App\Models\Order::where('status', 'waiting_payment')->where('layanan_type', \App\Models\OrderKendaraan::class)->whereNotNull('bukti_pembayaran')->count(); @endphp
                        @if ($pendingKendaraan > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
                {{-- Validasi Bangunan --}}
                <li>
                    <a href="{{ route('finance.order-bangunan.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('finance.order-bangunan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('finance.order-bangunan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                        </svg>
                        Validasi Penitipan Bangunan
                        @php $pendingBangunan = \App\Models\Order::where('status', 'menunggu_verifikasi_pembayaran')->where('layanan_type', \App\Models\OrderBangunan::class)->whereNotNull('bukti_pembayaran')->count(); @endphp
                        @if ($pendingBangunan > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                        @endif
                    </a>
                </li>

                 {{-- Validasi Angkutan --}}
                <li>
                    <a href="{{ route('finance.confirm-angkutan.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('finance.confirm-angkutan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('finance.confirm-angkutan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                        </svg>
                        Validasi Jasa Angkutan
                        @php $pendingAngkutan = \App\Models\Order::where('status', 'waiting_payment')->where('layanan_type', \App\Models\OrderAngkutan::class)->whereNotNull('bukti_pembayaran')->count(); @endphp
                        @if ($pendingAngkutan > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- GRUP 3: LAPORAN & RIWAYAT --}}
        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Laporan</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('finance.laporan.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('finance.laporan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('finance.laporan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Laporan Pendapatan
                    </a>
                </li>
                <li>
                    <a href="{{ route('finance.withdrawal.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('finance.withdrawal.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('finance.withdrawal.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Laporan Penarikan Mitra
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
                <svg class="w-[18px] h-[18px] mr-2.5 group-hover:translate-x-1 transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            const scrollPosition = sessionStorage.getItem('sidebarScrollPosFinance');
            if (scrollPosition) {
                sidebar.scrollTop = scrollPosition;
            }

            const menuLinks = sidebar.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    sessionStorage.setItem('sidebarScrollPosFinance', sidebar.scrollTop);
                });
            });
        });
    </script>
</aside>