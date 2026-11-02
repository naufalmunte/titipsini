<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titipsini - {{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Opsional: Bikin scrollbar di konten utama jadi lebih tipis ala Mac */
        .content-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .content-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .content-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-900 overflow-hidden">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen w-full">

        <div
            class="fixed top-0 w-full z-40 flex items-center justify-between bg-[#0f172a] p-4 md:hidden border-b border-slate-800">
            <h1 class="text-xl font-bold text-white uppercase tracking-tighter">
                Titipsini<span class="text-indigo-500">.</span>
            </h1>
            <button @click="sidebarOpen = !sidebarOpen"
                class="text-slate-300 focus:outline-none bg-slate-800 p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="sidebarOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] text-slate-300 transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:h-screen md:flex-shrink-0 border-r border-slate-800 shadow-xl flex flex-col">

            @if (auth()->user()->role == 'admin')
                @include('layouts.partials.sidebar-admin')
            @elseif(auth()->user()->role == 'mitra')
                @include('layouts.partials.sidebar-mitra')
            @elseif(auth()->user()->role == 'finance')
                @include('layouts.partials.sidebar-finance')
            @else
                @include('layouts.partials.sidebar-pelanggan')
            @endif
        </aside>

        <div x-show="sidebarOpen" x-transition:enter="transition opacity-100 duration-300"
            x-transition:leave="transition opacity-0 duration-300" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 md:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">

            <div class="hidden md:block shrink-0">
                @include('layouts.navigation')
            </div>

            <main class="flex-1 overflow-y-auto content-scroll p-4 md:p-8 pt-20 md:pt-8 relative">
                <div class="max-w-7xl mx-auto pb-10">
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    @yield('content')
                </div>
            </main>

            <div class="shrink-0 border-t border-slate-200 bg-white">
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    <div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if (session('success')) this.showToast('{{ session('success') }}', 'success'); @endif
            @if (session('error')) this.showToast('{{ session('error') }}', 'error'); @endif
        },
        showToast(msg, type) {
            this.message = msg;
            this.type = type;
            this.show = true;
            setTimeout(() => { this.show = false }, 4000);
        }
    }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-8"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-8" class="fixed top-5 right-5 z-[9999] min-w-[300px]"
        x-cloak>

        <div :class="type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'"
            class="text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border border-white/20 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <template x-if="type === 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </template>
                <p class="text-xs font-bold uppercase tracking-widest" x-text="message"></p>
            </div>
            <button @click="show = false" class="ml-4 opacity-70 hover:opacity-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-width="3"></path>
                </svg>
            </button>
        </div>
    </div>

</body>

</html>
