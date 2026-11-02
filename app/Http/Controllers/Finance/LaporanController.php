<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Pendapatan;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // 1. TOTAL OMZET KOTOR (Gabungan Hak Mitra + Hak Titipsini)
        $totalPendapatan = Pendapatan::sum(DB::raw('nominal_mitra + nominal_titipsini'));

        // 2. PROFIT BERSIH SISTEM (35%)
        $profitSistem = Pendapatan::sum('nominal_titipsini');

        // 3. TOTAL HAK MITRA (65%)
        $totalHakMitra = Pendapatan::sum('nominal_mitra');

        // 4. TOTAL DANA DICAIRKAN (Penarikan Mitra yang sudah sukses)
        $totalDitarik = Withdrawal::where('status', 'selesai')->sum('nominal');

        // 5. SALDO MENGENDAP (Opsional jika butuh: Total Hak Mitra - Total WD)
        $danaDalamProses = Withdrawal::whereIn('status', ['pending', 'disetujui', 'selesai'])->sum('nominal');
        $saldoTersedia = $totalHakMitra - $danaDalamProses;

        // 6. RIWAYAT PENDAPATAN GLOBAL
        $pendapatans = Pendapatan::with(['order', 'mitra'])->latest()->paginate(10);

        // Lempar ke view laporan keuangan di folder finance
        return view('finance.laporan-keuangan', compact(
            'totalPendapatan',
            'profitSistem',
            'totalHakMitra',
            'saldoTersedia',
            'totalDitarik',
            'pendapatans'
        ));
    }
}