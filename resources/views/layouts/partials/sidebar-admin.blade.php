@php
    // JEDER! Tarik data pengaturan global dari database
    $setting = \App\Models\Setting::first();
    $appName = $setting->app_name ?? 'Titipsini';
    $appLogo = $setting->app_logo ? asset('storage/' . $setting->app_logo) : null;
@endphp

<style>
    .sidebar-scroll::-webkit-scrollbar { display: none; }
    .sidebar-scroll { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<aside class="fixed inset-y-0 left-0 bg-slate-900 w-60 flex flex-col z-50 shadow-2xl h-screen text-left">

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

    <div class="p-4 border-b border-slate-800 shrink-0">
        <div class="bg-slate-800/50 rounded-xl p-3 border border-slate-700/50 flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex flex-shrink-0 items-center justify-center text-white text-xs font-bold shadow-inner uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-[8px] font-black tracking-widest text-emerald-400 uppercase">Admin Pusat</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto sidebar-scroll p-3 space-y-5">

        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Master Data</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.jenis-armada.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.jenis-armada*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.jenis-armada*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Jenis Armada
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kategori-barang.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.kategori-barang*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.kategori-barang*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Kategori Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kategori-kendaraan.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.kategori-kendaraan*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.kategori-kendaraan*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        Kategori Kendaraan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kategori-bangunan.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.kategori-bangunan*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.kategori-bangunan*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                        </svg>
                        Kategori Bangunan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mitra.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.mitra.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.mitra.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Daftar Mitra Aktif
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Tugas Verifikasi</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.verify.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.verify.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.verify.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.48V22" />
                        </svg>
                        Verif. Akun (KTP)
                        @php $pendingCount = \App\Models\Verify::where('status', 'pending')->count(); @endphp
                        @if ($pendingCount > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.gudang.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.gudang.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.gudang.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Verifikasi Gudang
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.parkir.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.parkir.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.parkir.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8a2 2 0 012 2v6M8 11h8M4 11l1.5-4h13L20 11m-16 0h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z"></path>
                            <circle cx="8" cy="15" r="2" fill="currentColor"></circle>
                            <circle cx="16" cy="15" r="2" fill="currentColor"></circle>
                        </svg>
                        Verifikasi Parkir
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.armada.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.armada*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.armada*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        Verifikasi Armada
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.verifikasi-penjaga.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.verifikasi-penjaga.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.verifikasi-penjaga.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Verifikasi Penjaga
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Order & Layanan</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.order-gudang') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.order-gudang.*', 'admin.order-gudang') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.order-gudang.*', 'admin.order-gudang') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Penitipan Barang
                        @php $pendingOrderCount = \App\Models\Order::where('status', 'menunggu_konfirmasi_admin')->where('layanan_type', \App\Models\OrderGudang::class)->count(); @endphp
                        @if ($pendingOrderCount > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.order_kendaraan.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.order_kendaraan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.order_kendaraan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Penitipan Kendaraan
                        @php
                            $pendingKendaraanCount = \App\Models\Order::where('status', 'menunggu_konfirmasi_admin')
                                ->where('layanan_type', \App\Models\OrderKendaraan::class)
                                ->count();
                        @endphp
                        @if ($pendingKendaraanCount > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.order-bangunan.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.order-bangunan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.order-bangunan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                        </svg>
                        Penitipan Bangunan
                        @php
                            $pendingBangunanCount = \App\Models\Order::where('status', 'menunggu_konfirmasi_admin')
                                ->where('layanan_type', \App\Models\OrderBangunan::class)
                                ->count();
                        @endphp
                        @if ($pendingBangunanCount > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.order_angkutan.index') }}"
                        class="relative group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.order_angkutan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.order_angkutan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                        </svg>
                        Jasa Angkutan
                        @php
                            $pendingAngkutanCount = \App\Models\Order::where('status', 'menunggu_konfirmasi_admin')
                                ->where('layanan_type', \App\Models\OrderAngkutan::class)
                                ->count();
                        @endphp
                        @if ($pendingAngkutanCount > 0)
                            <span class="absolute right-3 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Sistem</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.user') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.user') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.user') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Manajemen User
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pengaturan') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.pengaturan*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.pengaturan*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan Sistem
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.komisi.index') }}"
                        class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-lg transition-all {{ request()->routeIs('admin.komisi.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] mr-2.5 {{ request()->routeIs('admin.komisi.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pengaturan Komisi
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="p-3 border-t border-slate-800 shrink-0 bg-slate-900">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-500/10 hover:text-rose-400 rounded-lg transition-all group">
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

            const scrollPosition = sessionStorage.getItem('sidebarScrollPos');
            if (scrollPosition) {
                sidebar.scrollTop = scrollPosition;
            }

            const menuLinks = sidebar.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    sessionStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
                });
            });
        });
    </script>
</aside>