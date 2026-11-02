@extends('layouts.main')

@section('title', 'Validasi Withdrawal | Finance - Titipsini')

@section('content')
    {{-- CONTAINER UTAMA --}}
    <div class="max-w-7xl mx-auto py-2
     px-3 space-y-4 text-left animate-fade-in pb-12 mt-2">

        {{-- HEADER --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 md:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ url('/finance/dashboard') }}"
                    class="w-10 h-10 bg-slate-50 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg flex items-center justify-center transition-all shadow-sm border border-slate-100 shrink-0">
                    <i class="fas fa-arrow-left text-base"></i>
                </a>
                <div>
                    <h2 class="text-base font-black text-slate-800 tracking-tight uppercase italic">Laporan Penarikan <span
                            class="text-indigo-600">Pendapatan Mitra</span></h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Otoritas pencairan dana
                        bagi hasil mitra</p>
                </div>
            </div>
            <div class="shrink-0">
                <span
                    class="px-3 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg text-[8px] font-black uppercase tracking-widest shadow-sm">
                    {{ $withdrawals->where('status', 'pending')->count() }} Menunggu
                </span>
            </div>
        </div>

        {{-- TABEL WITHDRAWAL --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[9px] font-black tracking-widest italic">
                            <th class="px-6 py-4 w-48">Mitra & Waktu</th>
                            <th class="px-6 py-4">Detail Rekening Tujuan</th>
                            <th class="px-6 py-4 w-40">Nominal WD</th>
                            <th class="px-6 py-4 text-center w-32">Status</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($withdrawals as $wd)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- Mitra & Waktu --}}
                                <td class="px-6 py-4 align-top">
                                    <h4 class="text-xs font-black text-slate-800 uppercase italic truncate"
                                        title="{{ $wd->mitra->name ?? 'Mitra Terhapus' }}">
                                        {{ $wd->mitra->name ?? 'Mitra Terhapus' }}
                                    </h4>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        {{ $wd->created_at->format('d M Y, H:i') }} WIB
                                    </p>
                                </td>

                                {{-- Rekening Tujuan --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="bg-slate-50 rounded-lg p-2.5 border border-slate-100 shadow-sm inline-block min-w-[200px]">
                                        <span
                                            class="text-[8px] font-black text-indigo-600 uppercase tracking-widest block mb-1">
                                            {{ $wd->rekening->nama_bank ?? 'N/A' }}
                                        </span>
                                        <p
                                            class="text-sm font-black text-slate-800 tracking-widest font-mono leading-none mb-1.5">
                                            {{ $wd->rekening->nomor_rekening ?? 'N/A' }}
                                        </p>
                                        <p
                                            class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">
                                            A.N: {{ $wd->rekening->atas_nama ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Nominal --}}
                                <td class="px-6 py-4 align-middle">
                                    <p class="text-sm font-black italic tracking-tighter text-emerald-600">
                                        Rp {{ number_format($wd->nominal, 0, ',', '.') }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded text-[7px] font-black uppercase tracking-widest border 
                                        {{ $wd->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse' : '' }}
                                        {{ $wd->status == 'disetujui' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : '' }}
                                        {{ $wd->status == 'selesai' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                        {{ $wd->status == 'ditolak' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}">
                                        {{ $wd->status }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    @if ($wd->status == 'pending')
                                        <div class="flex flex-col gap-1.5 w-24 mx-auto">
                                            <button onclick="openApproveModal({{ $wd->id }})"
                                                class="w-full bg-slate-900 hover:bg-indigo-600 text-white py-1.5 rounded-md text-[8px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                                                Approve ⚡
                                            </button>
                                            <button onclick="openRejectModal({{ $wd->id }})"
                                                class="w-full bg-white border border-rose-200 text-rose-500 py-1.5 rounded-md text-[8px] font-black uppercase tracking-widest hover:bg-rose-50 transition-all">
                                                Reject
                                            </button>
                                        </div>
                                    @elseif ($wd->status == 'disetujui')
                                        <span class="text-[8px] font-black text-indigo-500 uppercase tracking-widest">
                                            <i class="fas fa-clock mr-1"></i> Proses
                                        </span>
                                    @elseif ($wd->status == 'selesai')
                                        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">
                                            <i class="fas fa-check-double mr-1"></i> Ditransfer
                                        </span>
                                    @else
                                        <span class="text-[8px] font-black text-slate-300 uppercase">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-12 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fas fa-wallet text-2xl text-slate-300"></i>
                                        Tidak ada antrean penarikan dana saat ini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL APPROVE (CLEAN & RESPONSIVE) --}}
    <div id="modal-approve"
        class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white rounded-xl w-full max-w-sm p-5 shadow-xl relative border-t-4 border-indigo-600 animate-fade-in text-left">
            <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-tight italic">Confirm <span
                            class="text-indigo-600">Payment</span></h2>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Unggah Bukti Transfer
                        Saldo</p>
                </div>
                <button type="button" onclick="closeModal('modal-approve')"
                    class="text-slate-400 hover:text-rose-500 font-black transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="form-approve" action="" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="disetujui">

                <div class="space-y-1.5">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Upload Bukti
                        (JPG/PNG)</label>
                    <input type="file" name="bukti_transfer" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-[9px] font-bold text-slate-600 uppercase outline-none focus:border-indigo-500 transition-colors cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-[8px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Admin
                        (Opsional)</label>
                    <textarea name="catatan_admin" placeholder="Masukkan keterangan transfer..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-[10px] font-bold text-slate-700 outline-none h-16 resize-none focus:border-indigo-500 transition-colors"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-approve')"
                        class="px-4 py-2 bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-slate-900 hover:bg-indigo-600 text-white text-[8px] font-black uppercase tracking-widest rounded-lg transition-all shadow-md active:scale-95 flex items-center gap-1.5">Konfirmasi
                        🚀</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL REJECT (CLEAN & RESPONSIVE) --}}
    <div id="modal-reject"
        class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white rounded-xl w-full max-w-sm p-5 shadow-xl relative border-t-4 border-rose-500 animate-fade-in text-left">
            <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-tight italic">Reject <span
                            class="text-rose-600">Withdrawal</span></h2>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Tolak Pengajuan Saldo
                        Mitra</p>
                </div>
                <button type="button" onclick="closeModal('modal-reject')"
                    class="text-slate-400 hover:text-rose-500 font-black transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="form-reject" action="" method="POST" class="space-y-4 text-left">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="ditolak">

                <div class="space-y-1.5">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1">Alasan
                        Penolakan</label>
                    <textarea name="catatan_admin" required placeholder="Sebutkan alasan penolakan dengan jelas..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-[10px] font-bold text-slate-700 outline-none h-20 resize-none focus:border-rose-500 transition-colors"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-reject')"
                        class="px-4 py-2 bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-rose-600 hover:bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest rounded-lg transition-all shadow-md active:scale-95">Tolak
                        WD</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id) {
            const form = document.getElementById('form-approve');
            // JEDER! Sesuaikan dengan prefix /finance/ di route kamu
            form.action = "/finance/withdrawal/" + id;
            document.getElementById('modal-approve').classList.remove('hidden');
        }

        function openRejectModal(id) {
            const form = document.getElementById('form-reject');
            form.action = "/finance/withdrawal/" + id;
            document.getElementById('modal-reject').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Menutup modal jika klik di luar box
        window.onclick = function(event) {
            if (event.target.id === 'modal-approve' || event.target.id === 'modal-reject') {
                event.target.classList.add('hidden');
            }
        }
    </script>

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
