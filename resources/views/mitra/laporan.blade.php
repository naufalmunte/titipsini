@extends('layouts.main')

@section('title', 'Laporan Pendapatan | Mitra - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-2 px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase italic">Laporan <span class="text-indigo-600">Pendapatan</span></h2>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pantau langsung akumulasi bagi hasil Anda</p>
            </div>

            <button class="px-4 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black uppercase rounded-lg transition-all shadow-md tracking-widest flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-file-download text-xs"></i> Ekspor Laporan
            </button>
        </div>

        @if (session('error'))
            <div class="p-3 bg-rose-50 border border-rose-100 rounded-lg flex items-center gap-2 shadow-sm mb-2">
                <i class="fas fa-exclamation-triangle text-rose-500 text-sm shrink-0"></i>
                <p class="text-[9px] font-black text-rose-700 uppercase tracking-widest italic">{{ session('error') }}</p>
            </div>
        @endif

        {{-- STATISTIK UTAMA (3 KOLOM STANDAR) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-2">
            
            {{-- Gross Revenue --}}
            <div class="bg-white border border-slate-100 rounded-xl p-4 md:p-5 shadow-sm flex flex-col justify-between group">
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest mb-1">Total Pendapatan Kotor</p>
                    <h2 class="text-lg font-black text-slate-800 italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2.5 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest flex items-center">
                        <i class="fas fa-circle text-[5px] mr-1.5"></i> Terakumulasi Global
                    </span>
                    <i class="fas fa-chart-bar text-slate-100 text-sm group-hover:text-emerald-50 transition-colors"></i>
                </div>
            </div>

            {{-- Ready To WD --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 md:p-5 shadow-md relative overflow-hidden flex flex-col justify-between group">
                <div class="relative z-10 text-left">
                    <p class="text-[8px] font-black text-indigo-300 uppercase italic tracking-widest mb-1">Saldo Siap Ditarik</p>
                    <h2 class="text-lg font-black text-white italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($saldoTersedia, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2 border-t border-slate-800 relative z-10 text-left flex items-center justify-between">
                    @if ($saldoTersedia >= 100000)
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-wd')"
                            class="bg-indigo-600 hover:bg-white hover:text-indigo-600 text-white px-3 py-1.5 rounded-md text-[8px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                            Tarik Saldo ⚡
                        </button>
                    @else
                        <span class="inline-block px-2.5 py-1 bg-white/10 rounded-md border border-white/10 text-[7px] font-black text-white/60 uppercase tracking-widest">
                            Minimal Rp 100.000
                        </span>
                    @endif
                </div>
                <i class="fas fa-wallet absolute -right-2 -bottom-2 text-white/5 text-5xl transform -rotate-12 group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            {{-- Total Paid Out --}}
            <div class="bg-white border border-slate-100 rounded-xl p-4 md:p-5 shadow-sm flex flex-col justify-between group">
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase italic tracking-widest mb-1">Total Dana Dicairkan</p>
                    <h2 class="text-lg font-black text-rose-500 italic mt-0.5 truncate tracking-tighter">
                        Rp {{ number_format($totalDitarik, 0, ',', '.') }}
                    </h2>
                </div>
                <div class="mt-4 pt-2.5 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-check-double mr-1 text-rose-400"></i> Penarikan Sukses
                    </span>
                    <i class="fas fa-hand-holding-usd text-slate-100 text-sm group-hover:text-rose-50 transition-colors"></i>
                </div>
            </div>

        </div>

        {{-- TABEL RIWAYAT TRANSAKSI --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-4">
            <div class="p-4 md:p-5 border-b border-slate-100 text-left">
                <h3 class="text-xs font-black italic uppercase text-slate-800 tracking-tight">Riwayat <span class="text-indigo-600">Transaksi</span></h3>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Detail transparansi arus kas masuk ke dompet Anda</p>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 w-44">Info Transaksi</th>
                            <th class="px-6 py-4">Kategori Layanan</th>
                            <th class="px-6 py-4 text-center w-40">Rasio Bagi Hasil</th>
                            <th class="px-6 py-4 text-right w-44">Pendapatan Anda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pendapatans as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-[10px] font-black text-slate-800 uppercase italic">
                                        {{ $item->created_at->format('d M Y') }}
                                    </p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded text-[8px] font-black uppercase tracking-widest">
                                        #{{ $item->order->invoice_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[8px] font-black text-indigo-500 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded tracking-widest uppercase inline-block">
                                        {{ str_replace('_', ' ', $item->kategori_layanan) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ $item->persen_mitra }}% <span class="text-slate-300 mx-1">|</span> {{ 100 - $item->persen_mitra }}%
                                </td>
                                <td class="px-6 py-4 text-right font-black italic text-emerald-600 text-xs tracking-tight">
                                    + Rp {{ number_format($item->nominal_mitra, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <i class="fas fa-folder-open text-2xl mb-2 text-slate-300 block"></i>
                                    Belum ada data rekaman bagi hasil pendapatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if (method_exists($pendapatans, 'hasPages') && $pendapatans->hasPages())
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                        Menampilkan {{ $pendapatans->firstItem() }}-{{ $pendapatans->lastItem() }} dari {{ $pendapatans->total() }} Data
                    </p>
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        @if ($pendapatans->onFirstPage())
                            <span class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-300 cursor-not-allowed shadow-sm">Sebelumnya</span>
                        @else
                            <a href="{{ $pendapatans->previousPageUrl() }}"
                                class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-600 hover:text-indigo-600 hover:border-indigo-300 transition-colors shadow-sm active:scale-95">Sebelumnya</a>
                        @endif

                        @if ($pendapatans->hasMorePages())
                            <a href="{{ $pendapatans->nextPageUrl() }}"
                                class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-600 hover:text-indigo-600 hover:border-indigo-300 transition-colors shadow-sm active:scale-95">Selanjutnya</a>
                        @else
                            <span class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-300 cursor-not-allowed shadow-sm">Selanjutnya</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- TABEL STATUS PENARIKAN DANA --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-4 md:p-5 border-b border-slate-100 text-left">
                <h3 class="text-xs font-black italic uppercase text-slate-800 tracking-tight">Pencairan <span class="text-indigo-600">Dana Mitra</span></h3>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Status pengajuan mutasi penarikan saldo (WD)</p>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 w-48">Nominal & Tanggal</th>
                            <th class="px-6 py-4">Info Admin / Catatan</th>
                            <th class="px-6 py-4 text-center w-32">Status</th>
                            <th class="px-6 py-4 text-center w-32">Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php $wd_history = \App\Models\Withdrawal::where('mitra_id', Auth::id())->latest()->get(); @endphp
                        @forelse ($wd_history as $wd)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-left">
                                    <h3 class="text-xs font-black text-slate-800 italic uppercase truncate">
                                        Rp {{ number_format($wd->nominal, 0, ',', '.') }}
                                    </h3>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        {{ $wd->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    @if ($wd->bukti_transfer)
                                        <a href="{{ asset('uploads/bukti_transfer/' . $wd->bukti_transfer) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded text-[7px] font-black uppercase tracking-widest mb-1.5 hover:bg-indigo-100 transition-colors">
                                            <i class="fas fa-image"></i> Lihat Bukti
                                        </a><br>
                                    @endif
                                    <p class="text-[8px] text-slate-500 font-bold uppercase truncate max-w-[200px]">
                                        {{ $wd->catatan_admin ?? 'Menunggu proses admin...' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded text-[7px] font-black uppercase tracking-widest border 
                                        {{ $wd->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse' : '' }}
                                        {{ $wd->status == 'disetujui' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : '' }}
                                        {{ $wd->status == 'selesai' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                        {{ $wd->status == 'ditolak' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}">
                                        {{ $wd->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($wd->status == 'disetujui')
                                        <form action="{{ route('mitra.withdrawal.konfirmasi', $wd->id) }}" method="POST" class="inline-block">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                class="bg-indigo-600 hover:bg-slate-900 text-white px-3 py-1.5 rounded-md text-[8px] font-black uppercase tracking-widest shadow-sm transition-all active:scale-95 flex items-center gap-1 mx-auto">
                                                Terima ⚡
                                            </button>
                                        </form>
                                    @elseif($wd->status == 'selesai')
                                        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">
                                            <i class="fas fa-check-double mr-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="text-[8px] font-black text-slate-300 uppercase">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    📭 Belum ada riwayat pengajuan penarikan dana.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL WD RE-DESIGN (COMPACT) --}}
    <x-modal name="modal-wd" focusable maxWidth="sm">
        <div class="p-5 text-left bg-white rounded-xl w-full">
            <form action="{{ route('mitra.withdrawal.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-tight italic">Tarik Saldo</h2>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pencairan Dana ke Rekening Anda</p>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-rose-500 font-black transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Rekening Tujuan</label>
                    <select name="rekening_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-[9px] font-bold text-slate-700 uppercase cursor-pointer focus:border-indigo-500 focus:ring-0 transition-colors">
                        <option value="">-- Pilih Rekening --</option>
                        @php $rekenings = \App\Models\Rekening::where('user_id', Auth::id())->get(); @endphp
                        @foreach ($rekenings as $rek)
                            <option value="{{ $rek->id }}">{{ $rek->nama_bank }} - {{ $rek->nomor_rekening }} ({{ $rek->atas_nama }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Nominal Penarikan (Rp)</label>
                        <span class="text-[7px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-1.5 py-0.5 rounded">
                            Saldo: {{ number_format($saldoTersedia, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="relative">
                        <input type="number" name="nominal" id="input-nominal-wd" value="100000" min="100000" max="{{ $saldoTersedia }}" oninput="validateWithdrawal(this)" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-[10px] font-black text-slate-800 uppercase focus:border-indigo-500 focus:ring-0 transition-all outline-none">
                        <button type="button" onclick="fillMax()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded-md text-[7px] font-black uppercase tracking-widest shadow-sm transition-colors">
                            MAKS ⚡
                        </button>
                    </div>
                    <p id="wd-error" class="hidden text-[7px] font-black text-rose-500 uppercase tracking-widest mt-1 ml-1"></p>
                </div>

                <div class="pt-3 mt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-4 py-2 bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" id="btn-submit-wd"
                        class="px-5 py-2 bg-slate-900 hover:bg-indigo-600 text-white text-[8px] font-black uppercase tracking-widest rounded-lg transition-all shadow-md active:scale-95 flex items-center gap-1.5">Konfirmasi 🚀</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- SCRIPT VALIDASI KONTROL SALDO --}}
    <script>
        const available = {{ $saldoTersedia }};

        function validateWithdrawal(input) {
            const errorEl = document.getElementById('wd-error');
            const btnSubmit = document.getElementById('btn-submit-wd');
            const val = parseInt(input.value);

            if (val > available || available < 100000 || val < 100000) {
                errorEl.classList.remove('hidden');
                errorEl.innerText = (available < 100000 || val < 100000) ? "Minimal penarikan Rp 100.000!" : "Saldo tidak mencukupi!";
                input.classList.add('border-rose-500');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('bg-slate-300', 'cursor-not-allowed', 'text-slate-500');
                btnSubmit.classList.remove('bg-slate-900', 'hover:bg-indigo-600', 'text-white', 'shadow-md');
            } else {
                errorEl.classList.add('hidden');
                input.classList.remove('border-rose-500');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('bg-slate-300', 'cursor-not-allowed', 'text-slate-500');
                btnSubmit.classList.add('bg-slate-900', 'hover:bg-indigo-600', 'text-white', 'shadow-md');
            }
        }

        function fillMax() {
            const input = document.getElementById('input-nominal-wd');
            input.value = available;
            validateWithdrawal(input);
        }

        window.onload = function() {
            const input = document.getElementById('input-nominal-wd');
            if (input) validateWithdrawal(input);
        };
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection